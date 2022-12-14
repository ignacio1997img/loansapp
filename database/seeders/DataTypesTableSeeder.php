<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_types')->delete();
        
        \DB::table('data_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'users',
                'slug' => 'users',
                'display_name_singular' => 'User',
                'display_name_plural' => 'Users',
                'icon' => 'voyager-person',
                'model_name' => 'TCG\\Voyager\\Models\\User',
                'policy_name' => 'TCG\\Voyager\\Policies\\UserPolicy',
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerUserController',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2022-10-26 16:56:24',
                'updated_at' => '2022-10-26 16:56:24',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'menus',
                'slug' => 'menus',
                'display_name_singular' => 'Menu',
                'display_name_plural' => 'Menus',
                'icon' => 'voyager-list',
                'model_name' => 'TCG\\Voyager\\Models\\Menu',
                'policy_name' => NULL,
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2022-10-26 16:56:24',
                'updated_at' => '2022-10-26 16:56:24',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'roles',
                'slug' => 'roles',
                'display_name_singular' => 'Role',
                'display_name_plural' => 'Roles',
                'icon' => 'voyager-lock',
                'model_name' => 'TCG\\Voyager\\Models\\Role',
                'policy_name' => NULL,
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2022-10-26 16:56:24',
                'updated_at' => '2022-10-26 16:56:24',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'categories',
                'slug' => 'categories',
                'display_name_singular' => 'Category',
                'display_name_plural' => 'Categories',
                'icon' => 'voyager-categories',
                'model_name' => 'TCG\\Voyager\\Models\\Category',
                'policy_name' => NULL,
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2022-10-26 17:33:41',
                'updated_at' => '2022-10-26 17:33:41',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'posts',
                'slug' => 'posts',
                'display_name_singular' => 'Post',
                'display_name_plural' => 'Posts',
                'icon' => 'voyager-news',
                'model_name' => 'TCG\\Voyager\\Models\\Post',
                'policy_name' => 'TCG\\Voyager\\Policies\\PostPolicy',
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2022-10-26 17:33:41',
                'updated_at' => '2022-10-26 17:33:41',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'pages',
                'slug' => 'pages',
                'display_name_singular' => 'Page',
                'display_name_plural' => 'Pages',
                'icon' => 'voyager-file-text',
                'model_name' => 'TCG\\Voyager\\Models\\Page',
                'policy_name' => NULL,
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2022-10-26 17:33:41',
                'updated_at' => '2022-10-26 17:33:41',
            ),
            6 => 
            array (
                'id' => 12,
                'name' => 'type_loans',
                'slug' => 'type-loans',
                'display_name_singular' => 'Tipo de Pr??stamo',
                'display_name_plural' => 'Tipos de Pr??stamos',
                'icon' => 'voyager-categories',
                'model_name' => 'App\\Models\\TypeLoan',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-10-26 20:14:00',
                'updated_at' => '2022-10-26 20:19:30',
            ),
            7 => 
            array (
                'id' => 13,
                'name' => 'people',
                'slug' => 'people',
                'display_name_singular' => 'Persona',
                'display_name_plural' => 'Personas',
                'icon' => 'voyager-people',
                'model_name' => 'App\\Models\\People',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-10-26 20:52:01',
                'updated_at' => '2022-11-15 17:51:38',
            ),
            8 => 
            array (
                'id' => 14,
                'name' => 'agent_types',
                'slug' => 'agent-types',
                'display_name_singular' => 'Tipo Agente',
                'display_name_plural' => 'Tipos de Agentes',
                'icon' => 'fa-solid fa-people-line',
                'model_name' => 'App\\Models\\AgentType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-10-28 05:49:27',
                'updated_at' => '2022-10-31 03:17:38',
            ),
            9 => 
            array (
                'id' => 15,
                'name' => 'routes',
                'slug' => 'routes',
                'display_name_singular' => 'Ruta',
                'display_name_plural' => 'Rutas',
                'icon' => 'fa-solid fa-route',
                'model_name' => 'App\\Models\\Route',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-11-07 18:28:19',
                'updated_at' => '2022-11-22 01:24:03',
            ),
            10 => 
            array (
                'id' => 17,
                'name' => 'pruebas',
                'slug' => 'pruebas',
                'display_name_singular' => 'Prueba',
                'display_name_plural' => 'Pruebas',
                'icon' => NULL,
                'model_name' => 'App\\Models\\Prueba',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2022-11-15 19:13:55',
                'updated_at' => '2022-11-15 19:13:55',
            ),
        ));
        
        
    }
}