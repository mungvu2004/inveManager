<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Lấy danh sách đơn hàng
$orders = im_get_orders();

echo '<div class="wrap">';
echo '<h1 class="wp-heading-inline">Danh sách đơn hàng</h1>';
echo '<hr class="wp-header-end">';

if ( empty( $orders ) ) {
    echo '<p>Không có đơn hàng nào.</p>';
} else {
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead>
        <tr>
            <th>ID</th>
            <th>Ngày đặt</th>
            <th>Tên khách hàng</th>
            <th>Email</th>
            <th>Trạng thái</th>
            <th>Tổng tiền</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>';
    foreach ( $orders as $order ) {
        echo '<tr>
            <td>' . esc_html( $order->ID ) . '</td>
            <td>' . esc_html( $order->order_date ) . '</td>
            <td>' . esc_html( $order->customer_name ) . '</td>
            <td>' . esc_html( $order->customer_email ) . '</td>
            <td>' . esc_html( $order->status ) . '</td>
            <td>' . number_format( $order->total, 2 ) . '</td>
            <td>
                <a href="?page=view_order&id=' . esc_attr( $order->ID ) . '">Xem chi tiết</a>
            </td>
        </tr>';
    }
    echo '</tbody></table>';
}
echo '</div>';
?>
