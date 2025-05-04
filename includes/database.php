<?php

// Tạo bảng cơ sở dữ liệu khi plugin được kích hoạt
function create_inventory_manager_tables() {
    global $wpdb;

    // Tên bảng
    $books_table = $wpdb->prefix . 'books';
    $inventory_table = $wpdb->prefix . 'inventory';
    $orders_table = $wpdb->prefix . 'orders';
    $order_details_table = $wpdb->prefix . 'order_details';
    $suppliers_table = $wpdb->prefix . 'suppliers';

    // Câu lệnh SQL tạo bảng 'suppliers' trước để có thể tham chiếu
    $sql = "CREATE TABLE $suppliers_table (
        ID INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        contact_name VARCHAR(255),
        contact_email VARCHAR(255),
        phone VARCHAR(20),
        address TEXT,
        PRIMARY KEY (ID)
    );";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // Câu lệnh SQL tạo bảng 'books'
    $sql = "CREATE TABLE $books_table (
        ID INT NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255),
        publisher VARCHAR(255),
        price DECIMAL(10, 2),
        isbn VARCHAR(20),
        supplier_id INT,
        PRIMARY KEY (ID),
        FOREIGN KEY (supplier_id) REFERENCES {$wpdb->prefix}suppliers(ID) ON DELETE SET NULL
    );";

    dbDelta( $sql );

    // Câu lệnh SQL tạo bảng 'inventory' (quản lý kho)
    $sql = "CREATE TABLE $inventory_table (
        book_id INT NOT NULL,
        quantity INT DEFAULT 0,
        PRIMARY KEY (book_id),
        FOREIGN KEY (book_id) REFERENCES $books_table(ID) ON DELETE CASCADE
    );";
    
    dbDelta( $sql );

    // Câu lệnh SQL tạo bảng 'orders'
    $sql = "CREATE TABLE $orders_table (
        ID INT NOT NULL AUTO_INCREMENT,
        order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        customer_name VARCHAR(255),
        customer_email VARCHAR(255),
        status VARCHAR(50) DEFAULT 'pending',
        total DECIMAL(10, 2) DEFAULT 0.00,
        PRIMARY KEY (ID)
    );";
    
    dbDelta( $sql );

    // Câu lệnh SQL tạo bảng 'order_details' (chi tiết đơn hàng)
    $sql = "CREATE TABLE $order_details_table (
        ID INT NOT NULL AUTO_INCREMENT,
        order_id INT NOT NULL,
        book_id INT NOT NULL,
        quantity INT DEFAULT 1,
        price DECIMAL(10, 2),
        PRIMARY KEY (ID),
        FOREIGN KEY (order_id) REFERENCES $orders_table(ID) ON DELETE CASCADE,
        FOREIGN KEY (book_id) REFERENCES $books_table(ID) ON DELETE CASCADE
    );";
    
    dbDelta( $sql );
}