<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {

        \DB::table('permissions')->delete();

        Permission::firstOrCreate([
            'key'        => 'browse_admin',
            'table_name' => 'admin',
        ]);

        $keys = [
            // 'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
            'browse_clear-cache'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('roles');

        Permission::generateFor('users');

        Permission::generateFor('settings');


        Permission::generateFor('type_loans');
        Permission::generateFor('agent_types');


        //  Rutas

        $keys = [
            'browse_routes',
            'add_routes',
            'edit_routes',
            // 'delete_routes',
            'read_routes',
            'collector_routes',
            // 'open_routes',
            // 'movements_routes',
            // 'close_routes',
            // 'print_routes',
            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'routes',
            ]);
        }


// boveda

        $keys = [
            'browse_vaults',
            'add_vaults',
            'open_vaults',
            'movements_vaults',
            'close_vaults',
            'print_vaults',
            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'vaults',
            ]);
        }

        // cajeros
        $keys = [
            'browse_cashiers',
            'add_cashiers',
            // 'open_cashiers',
            // 'movements_cashiers',
            // 'close_vaults',
            // 'print_vaults',
            
        ];
        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'cashiers',
            ]);
        }


        // poople
        $keys = [
            'browse_people',
            'add_people',
            'edit_people',
            'read_people',
            'sponsor_people',            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'people',
            ]);
        }
    }
}
