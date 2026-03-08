<?php

namespace App\Http\Controllers\Admin;

use App\Models\Teacher;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class TeacherCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Teacher::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/teacher');
        CRUD::setEntityNameStrings('teacher', 'teachers');
        CRUD::allowAccess(['list', 'show', 'create', 'update', 'delete']);
    }

    protected function setupListOperation(): void
    {
        CRUD::column('id');
        CRUD::column('name');
        CRUD::column('gender');
        CRUD::column('email');
        CRUD::column('phone_number')->label('Phone Number');
        CRUD::column('created_at');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::field('name');
        CRUD::field('gender')->type('select_from_array')->options([
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
        ])->allows_null(true);
        CRUD::field('email')->type('email');
        CRUD::field('phone_number')->label('Phone Number');
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
