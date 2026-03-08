<?php

namespace App\Http\Controllers\Admin;

use App\Models\FeePayment;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class FeePaymentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(FeePayment::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/fee-payment');
        CRUD::setEntityNameStrings('fee payment', 'fee payments');
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
        CRUD::column('amount')->type('number')->prefix('$');
        CRUD::column('status');
        CRUD::column('due_date')->type('date')->label('Due Date');
        CRUD::column('payment_date')->type('date')->label('Payment Date');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::field('student_id')
            ->type('select')
            ->label('Student')
            ->entity('student')
            ->attribute('email')
            ->model(User::class);

        CRUD::field('amount')->type('number')->attributes(['step' => '0.01']);
        CRUD::field('status')->type('select_from_array')->options([
            'Paid' => 'Paid',
            'Unpaid' => 'Unpaid',
            'Partial' => 'Partial',
        ]);
        CRUD::field('due_date')->type('date')->label('Due Date');
        CRUD::field('payment_date')->type('date')->label('Payment Date');
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
