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
                'display_name_singular' => 'Usuario',
                'display_name_plural' => 'Usuarios',
                'icon' => 'voyager-person',
                'model_name' => 'TCG\\Voyager\\Models\\User',
                'policy_name' => 'TCG\\Voyager\\Policies\\UserPolicy',
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerUserController',
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"desc","default_search_key":null,"scope":null}',
                'created_at' => '2022-10-25 08:56:24',
                'updated_at' => '2023-09-20 22:10:05',
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
                'created_at' => '2022-10-25 08:56:24',
                'updated_at' => '2022-10-25 08:56:24',
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
                'created_at' => '2022-10-25 08:56:24',
                'updated_at' => '2022-10-25 08:56:24',
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
                'created_at' => '2022-10-25 09:33:41',
                'updated_at' => '2022-10-25 09:33:41',
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
                'created_at' => '2022-10-25 09:33:41',
                'updated_at' => '2022-10-25 09:33:41',
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
                'created_at' => '2022-10-25 09:33:41',
                'updated_at' => '2022-10-25 09:33:41',
            ),
            6 => 
            array (
                'id' => 12,
                'name' => 'type_loans',
                'slug' => 'type-loans',
                'display_name_singular' => 'Tipo de Préstamo',
                'display_name_plural' => 'Tipos de Préstamos',
                'icon' => 'voyager-categories',
                'model_name' => 'App\\Models\\TypeLoan',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2022-10-25 12:14:00',
                'updated_at' => '2022-10-25 12:19:30',
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
                'created_at' => '2022-10-25 12:52:01',
                'updated_at' => '2022-11-14 09:51:38',
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
                'created_at' => '2022-10-26 21:49:27',
                'updated_at' => '2022-10-29 19:17:38',
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
                'created_at' => '2022-11-06 10:28:19',
                'updated_at' => '2022-11-20 17:24:03',
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
                'created_at' => '2022-11-14 11:13:55',
                'updated_at' => '2022-11-14 11:13:55',
            ),
            11 => 
            array (
                'id' => 18,
                'name' => 'category_garments',
                'slug' => 'category-garments',
                'display_name_singular' => 'Categoría',
                'display_name_plural' => 'Categorías',
                'icon' => 'voyager-categories',
                'model_name' => 'App\\Models\\CategoryGarment',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-05-29 07:56:07',
                'updated_at' => '2023-05-29 08:40:55',
            ),
            12 => 
            array (
                'id' => 19,
                'name' => 'brand_garments',
                'slug' => 'brand-garments',
                'display_name_singular' => 'Marca',
                'display_name_plural' => 'Marcas',
                'icon' => 'fa-solid fa-code-branch',
                'model_name' => 'App\\Models\\BrandGarment',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-05-29 08:40:43',
                'updated_at' => '2023-05-29 08:42:36',
            ),
            13 => 
            array (
                'id' => 20,
                'name' => 'model_garments',
                'slug' => 'model-garments',
                'display_name_singular' => 'Modelo',
                'display_name_plural' => 'Modelos',
                'icon' => 'fa-solid fa-sitemap',
                'model_name' => 'App\\Models\\ModelGarment',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-05-29 09:37:55',
                'updated_at' => '2023-05-29 09:39:02',
            ),
            14 => 
            array (
                'id' => 22,
                'name' => 'articles',
                'slug' => 'articles',
                'display_name_singular' => 'Artículo',
                'display_name_plural' => 'Artículos',
                'icon' => 'voyager-logbook',
                'model_name' => 'App\\Models\\Article',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-05-29 09:52:42',
                'updated_at' => '2023-07-11 12:21:24',
            ),
            15 => 
            array (
                'id' => 23,
                'name' => 'quilates',
                'slug' => 'quilates',
                'display_name_singular' => 'Kilate',
                'display_name_plural' => 'Kilates',
                'icon' => 'fa-solid fa-scale-balanced',
                'model_name' => 'App\\Models\\Quilate',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-07-13 15:40:38',
                'updated_at' => '2023-07-28 07:49:46',
            ),
            16 => 
            array (
                'id' => 24,
                'name' => 'jewels',
                'slug' => 'jewels',
                'display_name_singular' => 'Joya',
                'display_name_plural' => 'Joyas',
                'icon' => 'fa-regular fa-gem',
                'model_name' => 'App\\Models\\Jewel',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-07-14 02:25:05',
                'updated_at' => '2023-07-14 02:26:55',
            ),
            17 => 
            array (
                'id' => 26,
                'name' => 'item_categories',
                'slug' => 'item-categories',
                'display_name_singular' => 'Categoría de actículo',
                'display_name_plural' => 'Categorías de actículos',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\ItemCategory',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2023-09-21 23:22:49',
                'updated_at' => '2023-09-21 23:22:49',
            ),
            18 => 
            array (
                'id' => 28,
                'name' => 'item_features',
                'slug' => 'item-features',
                'display_name_singular' => 'Dato de actículo',
                'display_name_plural' => 'Datos de actículos',
                'icon' => 'voyager-list',
                'model_name' => 'App\\Models\\ItemFeature',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-09-21 23:24:43',
                'updated_at' => '2023-09-21 23:25:15',
            ),
            19 => 
            array (
                'id' => 32,
                'name' => 'item_types',
                'slug' => 'item-types',
                'display_name_singular' => 'Tipo de artículo',
                'display_name_plural' => 'Tipos de artículo',
                'icon' => 'voyager-tag',
                'model_name' => 'App\\Models\\ItemType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2023-09-26 11:32:14',
                'updated_at' => '2023-09-28 16:14:41',
            ),
        ));
        
        
    }
}