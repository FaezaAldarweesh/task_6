<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            //start admin permissions =================================================

            'All Users',
            'View User',
            'Add User',
            'Edit User',
            'Delete User',

            'All categories',
            'View category',
            'Add category',
            'Edit category',
            'Delete category',
            
            'All books',
            'View book',
            'Add book',
            'Edit book',
            'Delete book',
            
            'Update status borrow',

            //start user permissions =================================================
            
            'All ratings',
            'View rating',
            'Add rating',
            'Edit rating',
            'Delete rating',

            'All borrow',
            'View borrow',
            'Add borrow',
            'Edit borrow',
            'Delete borrow',

            //end user permissions =================================================
            //end admin permissions =================================================

        ];

        foreach ($permissions as $permission) {

            Permission::create(['name' => $permission]);
        }
    }
}
