<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_items')->delete();
        
        \DB::table('menu_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'menu_id' => 1,
                'title' => 'Inicio',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-house',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 1,
                'created_at' => '2022-10-25 16:56:24',
                'updated_at' => '2023-04-13 13:50:25',
                'route' => 'voyager.dashboard',
                'parameters' => 'null',
            ),
            1 => 
            array (
                'id' => 2,
                'menu_id' => 1,
                'title' => 'Media',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-images',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 1,
                'created_at' => '2022-10-25 16:56:24',
                'updated_at' => '2022-10-25 18:47:59',
                'route' => 'voyager.media.index',
                'parameters' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'menu_id' => 1,
                'title' => 'Users',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => NULL,
                'parent_id' => 15,
                'order' => 1,
                'created_at' => '2022-10-25 16:56:24',
                'updated_at' => '2022-10-25 18:45:39',
                'route' => 'voyager.users.index',
                'parameters' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'menu_id' => 1,
                'title' => 'Roles',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => NULL,
                'parent_id' => 15,
                'order' => 2,
                'created_at' => '2022-10-25 16:56:24',
                'updated_at' => '2022-10-25 18:45:46',
                'route' => 'voyager.roles.index',
                'parameters' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'menu_id' => 1,
                'title' => 'Herramientas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 11,
                'created_at' => '2022-10-25 16:56:24',
                'updated_at' => '2023-06-14 12:03:14',
                'route' => NULL,
                'parameters' => '',
            ),
            5 => 
            array (
                'id' => 6,
                'menu_id' => 1,
                'title' => 'Menu Builder',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-list',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 2,
                'created_at' => '2022-10-25 16:56:24',
                'updated_at' => '2022-10-25 18:47:59',
                'route' => 'voyager.menus.index',
                'parameters' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'menu_id' => 1,
                'title' => 'Database',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-data',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 3,
                'created_at' => '2022-10-25 16:56:24',
                'updated_at' => '2022-10-25 18:47:59',
                'route' => 'voyager.database.index',
                'parameters' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'menu_id' => 1,
                'title' => 'Compass',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-compass',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 4,
                'created_at' => '2022-10-25 16:56:24',
                'updated_at' => '2022-10-25 18:47:59',
                'route' => 'voyager.compass.index',
                'parameters' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'menu_id' => 1,
                'title' => 'BREAD',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-bread',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 5,
                'created_at' => '2022-10-25 16:56:24',
                'updated_at' => '2022-10-25 18:47:59',
                'route' => 'voyager.bread.index',
                'parameters' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'menu_id' => 1,
                'title' => 'Configuración',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-settings',
                'color' => '#000000',
                'parent_id' => 5,
                'order' => 6,
                'created_at' => '2022-10-25 16:56:24',
                'updated_at' => '2023-04-13 13:51:54',
                'route' => 'voyager.settings.index',
                'parameters' => 'null',
            ),
            10 => 
            array (
                'id' => 11,
                'menu_id' => 1,
                'title' => 'Parámetros',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-align-left',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 9,
                'created_at' => '2022-10-25 17:33:41',
                'updated_at' => '2023-06-14 12:03:14',
                'route' => NULL,
                'parameters' => '',
            ),
            11 => 
            array (
                'id' => 15,
                'menu_id' => 1,
                'title' => 'Seguridad',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 10,
                'created_at' => '2022-10-25 18:39:59',
                'updated_at' => '2023-06-14 12:03:14',
                'route' => NULL,
                'parameters' => '',
            ),
            12 => 
            array (
                'id' => 18,
                'menu_id' => 1,
                'title' => 'Limpiar cache',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-broom',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 12,
                'created_at' => '2022-10-25 19:14:59',
                'updated_at' => '2023-06-14 12:03:14',
                'route' => 'clear.cache',
                'parameters' => 'null',
            ),
            13 => 
            array (
                'id' => 21,
                'menu_id' => 1,
                'title' => 'Personas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-people',
                'color' => '#000000',
                'parent_id' => 11,
                'order' => 9,
                'created_at' => '2022-10-26 14:20:59',
                'updated_at' => '2023-07-14 10:27:43',
                'route' => 'voyager.people.index',
                'parameters' => 'null',
            ),
            14 => 
            array (
                'id' => 22,
                'menu_id' => 1,
                'title' => 'Préstamos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-hand-holding-dollar',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 4,
                'created_at' => '2022-10-27 05:03:27',
                'updated_at' => '2023-03-01 03:07:09',
                'route' => 'loans.index',
                'parameters' => NULL,
            ),
            15 => 
            array (
                'id' => 26,
                'menu_id' => 1,
                'title' => 'Bóveda',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-vault',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 2,
                'created_at' => '2022-10-28 01:55:52',
                'updated_at' => '2022-10-28 01:56:08',
                'route' => 'vaults.index',
                'parameters' => NULL,
            ),
            16 => 
            array (
                'id' => 27,
                'menu_id' => 1,
                'title' => 'Cajeros',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-cash-register',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 3,
                'created_at' => '2022-10-28 16:36:14',
                'updated_at' => '2022-10-28 16:36:23',
                'route' => 'cashiers.index',
                'parameters' => NULL,
            ),
            17 => 
            array (
                'id' => 28,
                'menu_id' => 1,
                'title' => 'Rutas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-route',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 5,
                'created_at' => '2022-11-06 18:28:19',
                'updated_at' => '2023-05-29 18:01:35',
                'route' => 'voyager.routes.index',
                'parameters' => NULL,
            ),
            18 => 
            array (
                'id' => 30,
                'menu_id' => 1,
                'title' => 'Cobradores',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'class="fa-solid fa-motorcycle',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 7,
                'created_at' => '2022-11-21 01:29:36',
                'updated_at' => '2023-06-14 12:03:14',
                'route' => NULL,
                'parameters' => NULL,
            ),
            19 => 
            array (
                'id' => 31,
                'menu_id' => 1,
                'title' => 'Reportes',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-chart-pie',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 6,
                'created_at' => '2022-12-08 21:07:39',
                'updated_at' => '2023-06-14 12:03:14',
                'route' => NULL,
                'parameters' => '',
            ),
            20 => 
            array (
                'id' => 32,
                'menu_id' => 1,
                'title' => 'Recaudación',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-print',
                'color' => '#000000',
                'parent_id' => 31,
                'order' => 2,
                'created_at' => '2022-12-08 21:09:12',
                'updated_at' => '2023-02-14 22:26:41',
                'route' => 'print.dailyCollection',
                'parameters' => NULL,
            ),
            21 => 
            array (
                'id' => 33,
                'menu_id' => 1,
                'title' => 'Lista Diaria Cobro',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-print',
                'color' => '#000000',
                'parent_id' => 38,
                'order' => 3,
                'created_at' => '2022-12-08 21:10:11',
                'updated_at' => '2023-02-14 22:26:52',
                'route' => 'print.dailyList',
                'parameters' => 'null',
            ),
            22 => 
            array (
                'id' => 34,
                'menu_id' => 1,
                'title' => 'Deudores Atrazados',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-print',
                'color' => '#000000',
                'parent_id' => 31,
                'order' => 1,
                'created_at' => '2022-12-16 14:46:48',
                'updated_at' => '2022-12-18 15:38:27',
                'route' => 'print-loanListLate',
                'parameters' => 'null',
            ),
            23 => 
            array (
                'id' => 35,
                'menu_id' => 1,
                'title' => 'Usuarios',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-user',
                'color' => '#000000',
                'parent_id' => 11,
                'order' => 10,
                'created_at' => '2022-12-15 12:22:33',
                'updated_at' => '2023-07-14 10:27:43',
                'route' => 'user.index',
                'parameters' => NULL,
            ),
            24 => 
            array (
                'id' => 36,
                'menu_id' => 1,
                'title' => 'Recaudacion Diaria',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-print',
                'color' => '#000000',
                'parent_id' => 38,
                'order' => 1,
                'created_at' => '2022-12-26 13:48:24',
                'updated_at' => '2023-02-12 03:16:23',
                'route' => 'print-loanCollection',
                'parameters' => NULL,
            ),
            25 => 
            array (
                'id' => 38,
                'menu_id' => 1,
                'title' => 'Reportes',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-chart-pie',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 8,
                'created_at' => '2023-02-12 03:16:14',
                'updated_at' => '2023-06-14 12:03:14',
                'route' => NULL,
                'parameters' => '',
            ),
            26 => 
            array (
                'id' => 39,
                'menu_id' => 1,
                'title' => 'Prestamos Entregados',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-print',
                'color' => '#000000',
                'parent_id' => 38,
                'order' => 2,
                'created_at' => '2023-02-12 03:22:51',
                'updated_at' => '2023-02-12 03:23:23',
                'route' => 'print-loanDelivered',
                'parameters' => 'null',
            ),
            27 => 
            array (
                'id' => 40,
                'menu_id' => 1,
                'title' => 'Cambios de Rutas Prestamos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-right-left',
                'color' => '#000000',
                'parent_id' => 11,
                'order' => 6,
                'created_at' => '2023-02-15 00:31:27',
                'updated_at' => '2023-05-29 18:01:35',
                'route' => 'routes-loan-exchange.index',
                'parameters' => 'null',
            ),
            28 => 
            array (
                'id' => 41,
                'menu_id' => 1,
                'title' => 'Prestamos Totales',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-print',
                'color' => '#000000',
                'parent_id' => 31,
                'order' => 3,
                'created_at' => '2023-04-28 17:59:28',
                'updated_at' => '2023-04-28 17:59:35',
                'route' => 'print-loanAll',
                'parameters' => NULL,
            ),
            29 => 
            array (
                'id' => 42,
                'menu_id' => 1,
                'title' => 'Categorías',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-categories',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 1,
                'created_at' => '2023-05-29 15:56:07',
                'updated_at' => '2023-05-29 16:07:41',
                'route' => 'voyager.category-garments.index',
                'parameters' => NULL,
            ),
            30 => 
            array (
                'id' => 43,
                'menu_id' => 1,
                'title' => 'Marcas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-code-branch',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 2,
                'created_at' => '2023-05-29 16:40:43',
                'updated_at' => '2023-05-29 16:46:08',
                'route' => 'voyager.brand-garments.index',
                'parameters' => NULL,
            ),
            31 => 
            array (
                'id' => 44,
                'menu_id' => 1,
                'title' => 'Modelos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-sitemap',
                'color' => '#000000',
                'parent_id' => 11,
                'order' => 3,
                'created_at' => '2023-05-29 17:37:55',
                'updated_at' => '2023-05-29 17:39:40',
                'route' => 'voyager.model-garments.index',
                'parameters' => 'null',
            ),
            32 => 
            array (
                'id' => 45,
                'menu_id' => 1,
                'title' => 'Artículos',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-logbook',
                'color' => '#000000',
                'parent_id' => 11,
                'order' => 4,
                'created_at' => '2023-05-29 17:52:42',
                'updated_at' => '2023-05-29 18:01:35',
                'route' => 'voyager.articles.index',
                'parameters' => 'null',
            ),
            33 => 
            array (
                'id' => 46,
                'menu_id' => 1,
                'title' => 'Prendario',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-handshake',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 5,
                'created_at' => '2023-06-14 12:02:56',
                'updated_at' => '2023-06-14 12:08:37',
                'route' => 'garments.index',
                'parameters' => 'null',
            ),
            34 => 
            array (
                'id' => 47,
                'menu_id' => 1,
                'title' => 'Kilates',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-scale-balanced',
                'color' => '#000000',
                'parent_id' => 11,
                'order' => 8,
                'created_at' => '2023-07-13 23:40:38',
                'updated_at' => '2023-07-14 10:27:43',
                'route' => 'voyager.quilates.index',
                'parameters' => 'null',
            ),
            35 => 
            array (
                'id' => 48,
                'menu_id' => 1,
                'title' => 'Joyas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-regular fa-gem',
                'color' => NULL,
                'parent_id' => 11,
                'order' => 7,
                'created_at' => '2023-07-14 10:25:05',
                'updated_at' => '2023-07-14 10:27:43',
                'route' => 'voyager.jewels.index',
                'parameters' => NULL,
            ),
        ));
        
        
    }
}