<?php

namespace App\Http\Controllers\Admin;

use App\Models\Classroom;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ClassroomCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Classroom::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/classroom');
        CRUD::setEntityNameStrings('classroom', 'classrooms');
        CRUD::allowAccess(['list', 'show', 'create', 'update', 'delete']);
    }

    protected function setupListOperation(): void
    {
        CRUD::column('id');
        CRUD::column('name');
        CRUD::column('location');
        CRUD::column('created_at');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::field('name');
        CRUD::field('location');
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
