<?php

function im_add_admin_menu() {
    add_menu_page(
        'Quản lý kho hàng',
        'Quản lý kho hàng',
        'manage_options',
        'inventory-manager',
        'im_dashboard_page',
        'dashicons-book',
        6
    );

    add_submenu_page(
        'inventory-manager',
        'Quản lý sách',
        'Quản lý sách',
        'manage_options',
        'books',
        'im_books_page'
    );

    add_submenu_page(
        'inventory-manager',
        'Quản lý đơn hàng',
        'Quản lý đơn hàng',
        'manage_options',
        'orders',
        'im_orders_page'
    );

    add_submenu_page(
        'inventory-manager',
        'Quản lý nhà cung cấp',
        'Quản lý nhà cung cấp',
        'manage_options',
        'suppliers',
        'im_suppliers_page'
    );
}

add_action('admin_menu', 'im_add_admin_menu');

function im_dashboard_page() {
    echo '<p>Chào mừng bạn đến với hệ thống quản lý kho hàng!</p>';
    echo '<p>Đây là nơi bạn có thể quản lý sách, đơn hàng và nhà cung cấp.</p>';
}

function im_books_page() {
    include plugin_dir_path(__FILE__) . 'templates/book-list.php';
}
function im_orders_page() {
    include plugin_dir_path(__FILE__) . 'templates/order-list.php';
}
function im_suppliers_page() {
    include plugin_dir_path(__FILE__) . 'templates/supplier-list.php';
}
function im_inventory_page() {
    include plugin_dir_path(__FILE__) . 'templates/inventory.php';
}
