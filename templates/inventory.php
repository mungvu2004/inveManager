<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Lấy thông tin kho
$inventory = im_get_inventory();

echo '<div class="wrap">';
echo '<h1 class="wp-heading-inline">Quản lý kho sách</h1>';
echo '<hr class="wp-header-end">';

if ( empty( $inventory ) ) {
    echo '<p>Kho trống. Vui lòng thêm sách vào kho.</p>';
} else {
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề sách</th>
            <th>Số lượng</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>';
    foreach ( $inventory as $item ) {
        echo '<tr>
            <td>' . esc_html( $item->book_id ) . '</td>
            <td>' . esc_html( $item->title ) . '</td>
            <td>' . esc_html( $item->quantity ) . '</td>
            <td>
                <a href="?page=update_inventory&book_id=' . esc_attr( $item->book_id ) . '">Cập nhật</a>
            </td>
        </tr>';
    }
    echo '</tbody></table>';
}
echo '</div>';
?>
