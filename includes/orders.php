<?php

function im_add_order($customer_name, $customer_email, $books) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'orders';

    // Tạo đơn hàng mới
    $order_data = array(
        'customer_name' => $customer_name,
        'customer_email' => $customer_email,
        'status' => 'pending',
        'total' => 0, // Tổng tiền sẽ được tính sau
        'order_date' => current_time('mysql')
    );

    $wpdb->insert($table_name, $order_data);
    $order_id = $wpdb->insert_id;

    // Tính tổng tiền và thêm chi tiết đơn hàng
    foreach ($books as $book_id => $quantity) {
        $book = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}books WHERE ID = %d", $book_id));
        if ($book) {
            $price = $book->price * $quantity;
            $order_detail_data = array(
                'order_id' => $order_id,
                'book_id' => $book_id,
                'quantity' => $quantity,
                'price' => $price
            );
            $wpdb->insert($wpdb->prefix . 'order_details', $order_detail_data);
            // Cập nhật tổng tiền đơn hàng
            $order_data['total'] += $price;
        }
    }
    return true;
}

function im_get_orders() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'orders';
    return $wpdb->get_results("SELECT * FROM $table_name ORDER BY order_date DESC");
}
function im_get_order($order_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'orders';
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE ID = %d", $order_id));
}
function im_edit_order($order_id, $customer_name, $customer_email, $status) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'orders';

    // Cập nhật thông tin đơn hàng trong cơ sở dữ liệu
    $result = $wpdb->update(
        $table_name,
        array(
            'customer_name' => $customer_name,
            'customer_email' => $customer_email,
            'status' => $status
        ),
        array('ID' => $order_id)
    );

    if (false === $result) {
        return new WP_Error('db_update_error', __('Có lỗi xảy ra khi cập nhật thông tin đơn hàng.', 'inventory-manager'));
    }

    return true;
}
function im_get_order_details($order_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'order_details';
    return $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE order_id = %d", $order_id));
}