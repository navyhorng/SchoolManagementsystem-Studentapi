<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class AttendanceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Attendance::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/attendance');
        CRUD::setEntityNameStrings('attendance', 'attendances');
        CRUD::allowAccess(['list', 'show', 'create', 'update', 'delete']);
    }
//test commit
    protected function setupListOperation(): void
    {
        CRUD::column('id');
        CRUD::addColumn([
            'name' => 'student_id',
            'type' => 'select',
            'label' => 'Student',
            'entity' => 'student',
            'attribute' => 'student_code',
            'model' => Student::class,
        ]);
        CRUD::addColumn([
            'name' => 'classroom_id',
            'type' => 'select',
            'label' => 'Classroom',
            'entity' => 'classroom',
            'attribute' => 'name',
            'model' => Classroom::class,
        ]);
        CRUD::column('date')->type('date');
        CRUD::column('status');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::field('student_id')
            ->type('select')
            ->label('Student')
            ->entity('student')
            ->attribute('student_code')
            ->model(Student::class);

        CRUD::field('classroom_id')
            ->type('select')
            ->label('Classroom')
            ->entity('classroom')
            ->attribute('name')
            ->model(Classroom::class);

        CRUD::field('date')->type('date');
        CRUD::field('status')->type('select_from_array')->options([
            'Present' => 'Present',
            'Absent' => 'Absent',
            'Late' => 'Late',
        ]);
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation(): void
    {
        $this->setupListOperation();
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }
}
