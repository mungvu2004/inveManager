<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Lấy danh sách sách
$books = im_get_books();

echo '<div class="wrap">';
echo '<h1 class="wp-heading-inline">Danh sách sách</h1>';
echo '<a href="?page=add_book" class="page-title-action">Thêm sách</a>';
echo '<hr class="wp-header-end">';

if ( empty( $books ) ) {
    echo '<p>Không có sách nào trong danh sách.</p>';
} else {
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Tác giả</th>
            <th>Nhà xuất bản</th>
            <th>Giá</th>
            <th>ISBN</th>
            <th>Nhà cung cấp</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>';
    foreach ( $books as $book ) {
        echo '<tr>
            <td>' . esc_html( $book->ID ) . '</td>
            <td>' . esc_html( $book->title ) . '</td>
            <td>' . esc_html( $book->author ) . '</td>
            <td>' . esc_html( $book->publisher ) . '</td>
            <td>' . number_format( $book->price, 2 ) . '</td>
            <td>' . esc_html( $book->isbn ) . '</td>
            <td>' . esc_html( $book->supplier_id ) . '</td>
            <td>
                <a href="?page=add_book&book_id=' . esc_attr( $book->ID ) . '">Sửa</a> |
                <a href="?page=delete_book&id=' . esc_attr( $book->ID ) . '" onclick="return confirm(\'Bạn có chắc chắn muốn xóa sách này?\');">Xóa</a>
            </td>
        </tr>';
    }
    echo '</tbody></table>';
}
echo '</div>';
?>
