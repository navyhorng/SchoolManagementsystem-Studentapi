<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StudentRequest;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class StudentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StudentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    public function setup(): void
    {
        CRUD::setModel(Student::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/student');
        CRUD::setEntityNameStrings('student', 'students');
        CRUD::allowAccess(['list', 'show', 'create', 'update', 'delete']);
    }

    protected function setupListOperation(): void
    {
        CRUD::column('id');
        CRUD::column('student_code')->label('Student Code');
        CRUD::addColumn([
            'name' => 'user_id',
            'key' => 'user_name',
            'type' => 'select',
            'label' => 'User',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => User::class,
        ]);
        CRUD::addColumn([
            'name' => 'user_id',
            'key' => 'user_email',
            'type' => 'select',
            'label' => 'Email',
            'entity' => 'user',
            'attribute' => 'email',
            'model' => User::class,
        ]);
        CRUD::column('gender');
        CRUD::column('phone_number')->label('Phone Number');
        CRUD::column('dob')->type('date')->label('Date of Birth');
        CRUD::addColumn([
            'name' => 'classroom_id',
            'type' => 'select',
            'label' => 'Classroom',
            'entity' => 'classroom',
            'attribute' => 'name',
            'model' => Classroom::class,
        ]);
        CRUD::column('is_active')->type('boolean')->label('Active');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(StudentRequest::class);
        CRUD::field('user_id')->type('hidden');
        CRUD::field('student_code')->type('hidden');
        CRUD::field('name')->label('Name');
        CRUD::field('email')->type('email')->label('Email');

        CRUD::field('gender')
            ->type('select_from_array')
            ->options([
                'male' => 'Male',
                'female' => 'Female',
                'other' => 'Other',
            ])
            ->allows_null(true);
        CRUD::field('phone_number')->label('Phone Number');
        CRUD::field('dob')->type('date')->label('Date of Birth');
        CRUD::field('address')->type('textarea');
        CRUD::field('classroom_id')
            ->type('select')
            ->label('Classroom')
            ->entity('classroom')
            ->attribute('name')
            ->model(Classroom::class);
        CRUD::field('is_active')
            ->type('boolean')
            ->default(true)
            ->label('Active');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();

        $entry = $this->crud->getCurrentEntry();
        if ($entry && $entry->user) {
            $this->crud->modifyField('name', [
                'value' => $entry->user->name,
            ]);
            $this->crud->modifyField('email', [
                'value' => $entry->user->email,
            ]);
        }
    }

    protected function setupShowOperation(): void
    {
        $this->setupListOperation();
        CRUD::column('address')->type('textarea');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }

    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        $this->ensureStudentCodeOnRequest();
        $this->crud->validateRequest();
        $this->syncUserFromEmail();
        $request = $this->crud->getRequest();

        $this->crud->registerFieldEvents();
        $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
        $this->data['entry'] = $this->crud->entry = $item;

        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        $this->ensureStudentCodeOnRequest();
        $this->crud->validateRequest();
        $this->syncUserFromEmail();
        $request = $this->crud->getRequest();
        $entryId = $request->get($this->crud->model->getKeyName()) ?? $this->crud->getCurrentEntryId();

        $this->crud->registerFieldEvents();
        $item = $this->crud->update(
            $entryId,
            $this->crud->getStrippedSaveRequest($request)
        );
        $this->data['entry'] = $this->crud->entry = $item;

        \Alert::success(trans('backpack::crud.update_success'))->flash();
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    protected function syncUserFromEmail(): void
    {
        $request = $this->crud->getRequest();
        $email = trim((string) $request->input('email'));

        if ($email === '') {
            return;
        }

        $studentId = $request->route('id');
        $student = $studentId ? Student::find($studentId) : null;
        $user = $student?->user;

        if ($user) {
            $user->name = (string) ($request->input('name') ?: $user->name);
            $user->email = $email;

            if (blank($user->name)) {
                $user->name = (string) ($request->input('student_code') ?: Str::before($email, '@'));
            }

            if (blank($user->password)) {
                $user->password = Hash::make(Str::random(24));
            }

            $user->save();
        } else {
            $user = User::create([
                'name' => (string) ($request->input('name') ?: $request->input('student_code') ?: Str::before($email, '@')),
                'email' => $email,
                'password' => Hash::make(Str::random(24)),
            ]);
        }

        $request->request->set('user_id', $user->id);
        $request->request->remove('name');
        $request->request->remove('email');
        $this->crud->setRequest($request);
    }

    protected function ensureStudentCodeOnRequest(): void
    {
        $request = $this->crud->getRequest();
        $studentCode = trim((string) $request->input('student_code'));

        if ($studentCode !== '') {
            return;
        }

        $studentId = $request->route('id');
        if ($studentId) {
            $existingCode = Student::query()->whereKey($studentId)->value('student_code');
            if (! empty($existingCode)) {
                $request->request->set('student_code', $existingCode);
                $this->crud->setRequest($request);

                return;
            }
        }

        $lastCode = Student::query()
            ->where('student_code', 'like', 'STD-%')
            ->orderByDesc('id')
            ->value('student_code');

        $lastNumber = 0;
        if (is_string($lastCode) && preg_match('/^STD-(\d+)$/', $lastCode, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        $nextNumber = $lastNumber + 1;
        $generatedCode = 'STD-'.str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);

        $request->request->set('student_code', $generatedCode);
        $this->crud->setRequest($request);
    }
}
