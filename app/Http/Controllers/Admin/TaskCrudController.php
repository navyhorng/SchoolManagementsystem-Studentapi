<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class TaskCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(Task::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/task');
        CRUD::setEntityNameStrings('task', 'tasks');
        CRUD::allowAccess(['list', 'show', 'create', 'update', 'delete']);
    }

    protected function setupListOperation(): void
    {
        CRUD::column('id');
        CRUD::addColumn([
            'name' => 'user_id',
            'type' => 'select',
            'label' => 'User',
            'entity' => 'user',
            'attribute' => 'email',
            'model' => User::class,
        ]);
        CRUD::column('title');
        CRUD::column('category');
        CRUD::column('priority');
        CRUD::column('due_date')->type('date')->label('Due Date');
        CRUD::column('is_completed')->type('boolean')->label('Completed');
        CRUD::column('completed_at')->type('datetime')->label('Completed At');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::field('user_id')
            ->type('select')
            ->label('User')
            ->entity('user')
            ->attribute('email')
            ->model(User::class);

        CRUD::field('title');
        CRUD::field('description')->type('textarea');
        CRUD::field('category')->type('select_from_array')->options([
            'Homework' => 'Homework',
            'Exam' => 'Exam',
            'Personal' => 'Personal',
        ]);
        CRUD::field('due_date')->type('date')->label('Due Date');
        CRUD::field('priority')->type('select_from_array')->options([
            'Low' => 'Low',
            'Medium' => 'Medium',
            'High' => 'High',
        ]);
        CRUD::field('is_completed')->type('boolean')->label('Completed')->default(false);
        CRUD::field('completed_at')->type('datetime')->label('Completed At');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation(): void
    {
        $this->setupListOperation();
        CRUD::column('description')->type('textarea');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }
}
