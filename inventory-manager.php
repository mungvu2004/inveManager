<?php
// File: inventory-manager.php - File chính của plugin

/**
 * Plugin Name: Hệ thống quản lý kho hàng
 * Description: Quản lý kho hàng cho cửa hàng sách.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: inventory-manager
 */

defined('ABSPATH') or die('No script kiddies please!');

// Include các file chức năng
require_once plugin_dir_path(__FILE__) . 'includes/database.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'includes/books.php';
require_once plugin_dir_path(__FILE__) . 'includes/inventory.php';
require_once plugin_dir_path(__FILE__) . 'includes/orders.php';
require_once plugin_dir_path(__FILE__) . 'includes/supplier.php';
require_once plugin_dir_path(__FILE__) . 'includes/functions.php';

function im_activate_plugin() {
    create_inventory_manager_tables();
}

register_activation_hook(__FILE__, 'im_activate_plugin');

function im_uninstall_plugin() {
    global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}order_details, {$wpdb->prefix}orders, {$wpdb->prefix}inventory, {$wpdb->prefix}books, {$wpdb->prefix}suppliers");
}
register_uninstall_hook(__FILE__, 'im_uninstall_plugin');