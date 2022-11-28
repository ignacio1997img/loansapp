<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        
        $role = Role::where('name', 'admin')->firstOrFail();

        $permissions = Permission::all();

        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );


        //############## Gerente ####################
        $role = Role::where('name', 'gerente')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "vaults" or
                                            table_name = "people" or
                                            table_name = "cashiers" or
                                            table_name = "routes" or

                                            `key` = "browse_loans" or 

                                            `key` = "successLoan_loans" or
                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());



        //############## Administrador ####################
        $role = Role::where('name', 'administrador')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "people" or
                                            table_name = "cashiers" or
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        

        //############## Cajero ####################
        $role = Role::where('name', 'cajeros')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            `key` = "browse_loans" or
                                            `key` = "add_loans" or

                                            `key` = "deliverMoney_loans" or
                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());
    }
}
