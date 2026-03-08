<?php

namespace App\Http\Controllers\Admin;

use App\Models\Grade;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class GradeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Grade::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/grade');
        CRUD::setEntityNameStrings('grade', 'grades');
        CRUD::allowAccess(['list', 'show', 'create', 'update', 'delete']);
    }

    protected function setupListOperation(): void
    {
        CRUD::column('id');
        CRUD::addColumn([
            'name' => 'student_id',
            'type' => 'select',
            'label' => 'Student',
            'entity' => 'student',
            'attribute' => 'email',
            'model' => User::class,
        ]);
        CRUD::column('score');
        CRUD::column('letter_grade')->label('Letter Grade');
        CRUD::column('term');
        CRUD::column('created_at');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::field('student_id')
            ->type('select')
            ->label('Student')
            ->entity('student')
            ->attribute('email')
            ->model(User::class);

        CRUD::field('score')->type('number')->attributes(['step' => '0.01']);
        CRUD::field('letter_grade')->label('Letter Grade');
        CRUD::field('term');
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
