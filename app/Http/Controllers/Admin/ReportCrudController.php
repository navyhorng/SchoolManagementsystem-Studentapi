<?php

namespace App\Http\Controllers\Admin;

use App\Models\Report;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ReportCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Report::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/report');
        CRUD::setEntityNameStrings('report', 'reports');
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

        CRUD::field('attendance_summary')->type('textarea')->label('Attendance Summary');
        CRUD::field('grade_summary')->type('textarea')->label('Grade Summary');
        CRUD::field('fee_summary')->type('textarea')->label('Fee Summary');
        CRUD::field('term');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation(): void
    {
        $this->setupListOperation();
        CRUD::column('attendance_summary')->type('textarea')->label('Attendance Summary');
        CRUD::column('grade_summary')->type('textarea')->label('Grade Summary');
        CRUD::column('fee_summary')->type('textarea')->label('Fee Summary');
        CRUD::column('updated_at');
    }
}
