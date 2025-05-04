<?php

/**
 * Plugin Name: Hệ thống quản lý kho hàng
 * Description: Quản lý kho hàng cho cửa hàng sách.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: inventory-manager
 */

defined('ABSPATH') or die('No script kiddies please!');

require_once plugin_dir_path(__FILE__) . 'includes/database.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'includes/books.php';
require_once plugin_dir_path(__FILE__) . 'includes/functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/inventory.php';
require_once plugin_dir_path(__FILE__) . 'includes/orders.php';
require_once plugin_dir_path(__FILE__) . 'includes/supplier.php';

function im_activate_plugin() {
    create_inventory_manager_tables();
}

register_activation_hook(__FILE__, 'im_activate_plugin');

function im_uninstall_plugin() {
    global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}books, {$wpdb->prefix}inventory, {$wpdb->prefix}orders, {$wpdb->prefix}order_details, {$wpdb->prefix}suppliers");
}
register_uninstall_hook(__FILE__, 'im_uninstall_plugin');

function im_add_admin_menu() {
    add_menu_page(
        'Quản lý kho hàng',
        'Quản lý kho hàng',
        'manage_options',
        'inventory-manager',
        'im_inventory_manager_page',
        'dashicons-store',
        6
    );

    add_submenu_page(
        'inventory-manager',
        'Sách',
        'Sách',
        'manage_options',
        'inventory-manager-books',
        'im_books_page'
    );
}

add_action('admin_menu', 'im_add_admin_menu');

function im_books_page() {
    include plugin_dir_path(__FILE__) . 'templates/book-list.php';
}