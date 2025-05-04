<?php

// Đảm bảo file được truy cập từ WordPress
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include các file chức năng
require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/books.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/inventory.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/orders.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/suppliers.php';

// Tạo bảng cơ sở dữ liệu khi plugin được kích hoạt
function im_plugin_activate() {
    create_inventory_manager_tables();
}
register_activation_hook( __FILE__, 'im_plugin_activate' );

// Hiển thị danh sách sách
function im_display_books_table() {
    $books = im_get_books();

    if ( empty( $books ) ) {
        echo '<p>Không có sách nào trong danh sách.</p>';
        return;
    }

    echo '<table class="widefat fixed" style="width:100%; margin-top:10px;">
        <thead>
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
            <td>' . esc_html( number_format( $book->price, 2 ) ) . '</td>
            <td>' . esc_html( $book->isbn ) . '</td>
            <td>' . esc_html( $book->supplier_id ) . '</td>
            <td>
                <a href="?page=edit_book&id=' . esc_attr( $book->ID ) . '">Sửa</a> |
                <a href="?page=delete_book&id=' . esc_attr( $book->ID ) . '">Xóa</a>
            </td>
        </tr>';
    }

    echo '</tbody></table>';
}

// Xử lý form thêm hoặc cập nhật sách
function im_handle_book_form_submission() {
    if ( isset( $_POST['im_book_nonce'] ) && wp_verify_nonce( $_POST['im_book_nonce'], 'im_book_action' ) ) {
        $title = sanitize_text_field( $_POST['title'] );
        $author = sanitize_text_field( $_POST['author'] );
        $publisher = sanitize_text_field( $_POST['publisher'] );
        $price = floatval( $_POST['price'] );
        $isbn = sanitize_text_field( $_POST['isbn'] );
        $supplier_id = intval( $_POST['supplier_id'] );

        if ( empty( $_POST['book_id'] ) ) {
            // Thêm sách mới
            im_add_book( $title, $author, $publisher, $price, $isbn, $supplier_id );
            im_add_admin_notice( 'Đã thêm sách mới thành công!', 'success' );
        } else {
            // Cập nhật sách
            $book_id = intval( $_POST['book_id'] );
            im_edit_book( $book_id, $title, $author, $publisher, $price, $isbn, $supplier_id );
            im_add_admin_notice( 'Đã cập nhật thông tin sách thành công!', 'success' );
        }
    }
}

// Hiển thị form thêm/cập nhật sách
function im_display_book_form( $book = null ) {
    $title = $book ? esc_attr( $book->title ) : '';
    $author = $book ? esc_attr( $book->author ) : '';
    $publisher = $book ? esc_attr( $book->publisher ) : '';
    $price = $book ? esc_attr( $book->price ) : '';
    $isbn = $book ? esc_attr( $book->isbn ) : '';
    $supplier_id = $book ? esc_attr( $book->supplier_id ) : '';

    echo '<form method="POST">
        ' . wp_nonce_field( 'im_book_action', 'im_book_nonce', true, false ) . '
        <table class="form-table">
            <tr>
                <th><label for="title">Tiêu đề</label></th>
                <td><input type="text" id="title" name="title" value="' . $title . '" required /></td>
            </tr>
            <tr>
                <th><label for="author">Tác giả</label></th>
                <td><input type="text" id="author" name="author" value="' . $author . '" /></td>
            </tr>
            <tr>
                <th><label for="publisher">Nhà xuất bản</label></th>
                <td><input type="text" id="publisher" name="publisher" value="' . $publisher . '" /></td>
            </tr>
            <tr>
                <th><label for="price">Giá</label></th>
                <td><input type="number" id="price" name="price" value="' . $price . '" step="0.01" required /></td>
            </tr>
            <tr>
                <th><label for="isbn">ISBN</label></th>
                <td><input type="text" id="isbn" name="isbn" value="' . $isbn . '" /></td>
            </tr>
            <tr>
                <th><label for="supplier_id">Nhà cung cấp</label></th>
                <td><input type="number" id="supplier_id" name="supplier_id" value="' . $supplier_id . '" /></td>
            </tr>
        </table>
        <input type="hidden" name="book_id" value="' . ( $book ? $book->ID : '' ) . '" />
        <button type="submit" class="button button-primary">Lưu</button>
    </form>';
}

// Hàm hiển thị thông báo
function im_add_admin_notice( $message, $type = 'success' ) {
    echo '<div class="' . esc_attr( $type === 'success' ? 'updated' : 'error' ) . ' notice">
        <p>' . esc_html( $message ) . '</p>
    </div>';
}

// Hàm xóa sách
function im_delete_book( $book_id ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';
    $wpdb->delete( $table_name, [ 'ID' => $book_id ], [ '%d' ] );
}

// Hàm tìm kiếm sách
function im_search_books( $keyword ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';

    return $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE title LIKE %s OR author LIKE %s",
            '%' . $wpdb->esc_like( $keyword ) . '%',
            '%' . $wpdb->esc_like( $keyword ) . '%'
        )
    );
}

