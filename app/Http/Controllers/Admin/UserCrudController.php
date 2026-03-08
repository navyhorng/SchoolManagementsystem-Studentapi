<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Hash;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup(): void
    {
        CRUD::setModel(User::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/user');
        CRUD::setEntityNameStrings('user', 'users');
        CRUD::allowAccess(['list', 'show', 'create', 'update', 'delete']);
    }

    protected function setupListOperation(): void
    {
        CRUD::column('id');
        CRUD::column('name');
        CRUD::column('email');
        CRUD::column('email_verified_at')->type('datetime')->label('Email Verified At');
        CRUD::column('created_at');
    }

    protected function setupCreateOperation(): void
    {
        CRUD::field('name');
        CRUD::field('email')->type('email');
        CRUD::field('password')->type('password');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
        CRUD::field('password')->hint('Leave blank to keep current password.');
    }

    protected function setupShowOperation(): void
    {
        $this->setupListOperation();
        CRUD::column('updated_at');
    }

    public function store()
    {
        $this->hashPasswordOnRequest();

        return $this->traitStore();
    }

    public function update()
    {
        $this->hashPasswordOnRequest(true);

        return $this->traitUpdate();
    }

    protected function hashPasswordOnRequest(bool $allowBlank = false): void
    {
        $request = $this->crud->getRequest();
        $password = (string) $request->input('password');

        if ($allowBlank && $password === '') {
            $request->request->remove('password');
            $this->crud->setRequest($request);

            return;
        }

        if ($password !== '') {
            $request->request->set('password', Hash::make($password));
            $this->crud->setRequest($request);
        }
    }
}
