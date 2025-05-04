<?php 

function im_add_book( $title, $author, $publisher, $price, $isbn, $supplier_id ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';

    // Kiểm tra xem sách đã tồn tại chưa
    $existing_book = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE title = %s", $title ) );
    if ( $existing_book ) {
        return new WP_Error( 'book_exists', __( 'Sách đã tồn tại trong cơ sở dữ liệu.', 'inventory-manager' ) );
    }

    // Thêm sách mới vào cơ sở dữ liệu
    $result = $wpdb->insert( 
        $table_name, 
        array( 
            'title' => $title, 
            'author' => $author, 
            'publisher' => $publisher, 
            'price' => $price, 
            'isbn' => $isbn,
            'supplier_id' => $supplier_id
        ) 
    );

    if ( false === $result ) {
        return new WP_Error( 'db_insert_error', __( 'Có lỗi xảy ra khi thêm sách vào cơ sở dữ liệu.', 'inventory-manager' ) );
    }

    return true;
}

function im_edit_book( $book_id, $title, $author, $publisher, $price, $isbn, $supplier_id ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';

    // Cập nhật thông tin sách trong cơ sở dữ liệu
    $result = $wpdb->update( 
        $table_name, 
        array( 
            'title' => $title, 
            'author' => $author, 
            'publisher' => $publisher, 
            'price' => $price, 
            'isbn' => $isbn,
            'supplier_id' => $supplier_id
        ), 
        array( 'ID' => $book_id ) 
    );

    if ( false === $result ) {
        return new WP_Error( 'db_update_error', __( 'Có lỗi xảy ra khi cập nhật thông tin sách.', 'inventory-manager' ) );
    }

    return true;
}

function im_delete_book( $book_id ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';

    // Xóa sách khỏi cơ sở dữ liệu
    $result = $wpdb->delete( $table_name, array( 'ID' => $book_id ) );

    if ( false === $result ) {
        return new WP_Error( 'db_delete_error', __( 'Có lỗi xảy ra khi xóa sách.', 'inventory-manager' ) );
    }

    return true;
}

function im_get_book( $book_id ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';

    // Lấy thông tin sách từ cơ sở dữ liệu
    $book = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE ID = %d", $book_id ) );

    if ( ! $book ) {
        return new WP_Error( 'book_not_found', __( 'Không tìm thấy sách.', 'inventory-manager' ) );
    }

    return $book;
}

function im_get_books( $limit = 10, $offset = 0 ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';

    // Lấy danh sách sách từ cơ sở dữ liệu
    $books = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name LIMIT %d OFFSET %d", $limit, $offset ) );

    return $books;
}