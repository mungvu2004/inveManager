<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Lấy danh sách nhà cung cấp
$suppliers = im_get_suppliers();

echo '<div class="wrap">';
echo '<h1 class="wp-heading-inline">Danh sách nhà cung cấp</h1>';
echo '<a href="?page=add_supplier" class="page-title-action">Thêm nhà cung cấp</a>';
echo '<hr class="wp-header-end">';

if ( empty( $suppliers ) ) {
    echo '<p>Không có nhà cung cấp nào.</p>';
} else {
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead>
        <tr>
            <th>ID</th>
            <th>Tên nhà cung cấp</th>
            <th>Người liên hệ</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>';
    foreach ( $suppliers as $supplier ) {
        echo '<tr>
            <td>' . esc_html( $supplier->ID ) . '</td>
            <td>' . esc_html( $supplier->name ) . '</td>
            <td>' . esc_html( $supplier->contact_name ) . '</td>
            <td>' . esc_html( $supplier->contact_email ) . '</td>
            <td>' . esc_html( $supplier->phone ) . '</td>
            <td>
                <a href="?page=edit_supplier&id=' . esc_attr( $supplier->ID ) . '">Sửa</a>
            </td>
        </tr>';
    }
    echo '</tbody></table>';
}
echo '</div>';
?>
