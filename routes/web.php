<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    // Jalankan schema setup jika tabel belum ada
    $tablesExist = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    if (empty($tablesExist)) {
        require_once database_path('demo.php');
    }

    $usersCount = DB::table('users')->count();
    $productsCount = DB::table('products')->count();
    $ordersCount = DB::table('orders')->count();

    $recentOrders = DB::table('orders as o')
        ->join('users as u', 'o.user_id', '=', 'u.id')
        ->leftJoin('user_profiles as up', 'u.id', '=', 'up.user_id')
        ->leftJoin('payments as p', 'o.id', '=', 'p.order_id')
        ->select(
            'o.order_code',
            'u.name as customer_name',
            'u.email as customer_email',
            'up.city',
            'o.total_amount',
            'o.status as order_status',
            'p.payment_method',
            'p.payment_status',
            'o.created_at'
        )
        ->get();

    $orderItems = DB::table('order_items as oi')
        ->join('orders as o', 'oi.order_id', '=', 'o.id')
        ->join('products as pr', 'oi.product_id', '=', 'pr.id')
        ->join('categories as c', 'pr.category_id', '=', 'c.id')
        ->select(
            'o.order_code',
            'c.name as category_name',
            'pr.name as product_name',
            'oi.quantity',
            'oi.unit_price',
            'oi.subtotal'
        )
        ->get();

    $dbmlContent = file_exists(database_path('schema.dbml')) ? file_get_contents(database_path('schema.dbml')) : '';
    $sqlContent = file_exists(database_path('schema.sql')) ? file_get_contents(database_path('schema.sql')) : '';
    $svgContent = file_exists(database_path('dbdiagram.svg')) ? file_get_contents(database_path('dbdiagram.svg')) : '';

    return view('welcome', compact(
        'usersCount',
        'productsCount',
        'ordersCount',
        'recentOrders',
        'orderItems',
        'dbmlContent',
        'sqlContent',
        'svgContent'
    ));
});

