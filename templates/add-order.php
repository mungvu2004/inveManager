<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = sanitize_text_field( $_POST['customer_name'] );
    $customer_email = sanitize_email( $_POST['customer_email'] );
    $status = sanitize_text_field( $_POST['status'] );
    $total = floatval( $_POST['total'] );

    if ( empty( $order_id ) ) {
        // Thêm đơn hàng mới - Cần tham số books dạng array
        $books = array(); // Khởi tạo mảng sách trong đơn hàng
        im_add_order( $customer_name, $customer_email, $books);
    } else {
        // Cập nhật đơn hàng
        im_edit_order( $order_id, $customer_name, $customer_email, $status);
    }
}