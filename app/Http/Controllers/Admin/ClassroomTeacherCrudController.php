<?php

namespace App\Http\Controllers\Admin;

use App\Models\Classroom;
use App\Models\ClassroomTeacher;
use App\Models\Teacher;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ClassroomTeacherCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(ClassroomTeacher::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/classroom-teacher');
        CRUD::setEntityNameStrings('classroom teacher', 'classroom teachers');
        CRUD::allowAccess(['list', 'show', 'create', 'update', 'delete']);
    }

    protected function setupListOperation(): void
    {
        CRUD::column('id');
        CRUD::addColumn([
            'name' => 'classroom_id',
            'type' => 'select',
            'label' => 'Classroom',
            'entity' => 'classroom',
            'attribute' => 'name',
            'model' => Classroom::class,
        ]);
        CRUD::addColumn([
            'name' => 'teacher_id',
            'type' => 'select',
            'label' => 'Teacher',
            'entity' => 'teacher',
            'attribute' => 'name',
            'model' => Teacher::class,
        ]);
        CRUD::column('is_active')->type('boolean')->label('Active');
        CRUD::column('created_at');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::field('classroom_id')
            ->type('select')
            ->label('Classroom')
            ->entity('classroom')
            ->attribute('name')
            ->model(Classroom::class);

        CRUD::field('teacher_id')
            ->type('select')
            ->label('Teacher')
            ->entity('teacher')
            ->attribute('name')
            ->model(Teacher::class);

        CRUD::field('is_active')->type('boolean')->label('Active')->default(true);
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation(): void
    {
        $this->setupListOperation();
        CRUD::column('updated_at');
    }
}
