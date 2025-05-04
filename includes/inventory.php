<?php


function im_update_inventory( $book_id, $quantity ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'inventory';

    // Kiểm tra xem sách đã tồn tại trong kho chưa
    $existing_inventory = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE book_id = %d", $book_id ) );
    
    if ( $existing_inventory ) {
        // Cập nhật số lượng sách trong kho
        $result = $wpdb->update( 
            $table_name, 
            array( 'quantity' => $quantity ), 
            array( 'book_id' => $book_id ) 
        );
    } else {
        // Thêm sách mới vào kho
        $result = $wpdb->insert( 
            $table_name, 
            array( 'book_id' => $book_id, 'quantity' => $quantity ) 
        );
    }

    if ( false === $result ) {
        return new WP_Error( 'db_update_error', __( 'Có lỗi xảy ra khi cập nhật thông tin kho.', 'inventory-manager' ) );
    }

    return true;
}
function im_add_inventory( $book_id, $quantity ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'inventory';

    // Kiểm tra xem sách đã tồn tại trong kho chưa
    $existing_inventory = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE book_id = %d", $book_id ) );
    
    if ( $existing_inventory ) {
        return new WP_Error( 'inventory_exists', __( 'Sách đã tồn tại trong kho.', 'inventory-manager' ) );
    }

    // Thêm sách mới vào kho
    $result = $wpdb->insert( 
        $table_name, 
        array( 'book_id' => $book_id, 'quantity' => $quantity ) 
    );

    if ( false === $result ) {
        return new WP_Error( 'db_insert_error', __( 'Có lỗi xảy ra khi thêm sách vào kho.', 'inventory-manager' ) );
    }

    return true;
}

function im_get_inventory() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'inventory';

    // Lấy thông tin kho từ cơ sở dữ liệu
    $inventory = $wpdb->get_results( "SELECT * FROM $table_name" );

    if ( empty( $inventory ) ) {
        return array(); // Nếu không có thông tin kho, trả về mảng rỗng
    }

    return $inventory; // Trả về thông tin kho
}
function im_check_inventory( $book_id ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'inventory';

    // Lấy thông tin kho của sách
    $inventory = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE book_id = %d", $book_id ) );

    if ( ! $inventory ) {
        return 0; // Nếu không có thông tin kho, trả về 0
    }

    return $inventory->quantity; // Trả về số lượng sách trong kho
}
function im_display_inventory_form( $inventory = null ) {
    $quantity = is_object($inventory) ? esc_attr( $inventory->quantity ) : '';

    echo '<form method="POST">
        <table class="form-table">
            <tr>
                <th><label for="quantity">Số lượng trong kho</label></th>
                <td><input type="number" id="quantity" name="quantity" value="' . $quantity . '" required /></td>
            </tr>
        </table>
        <button type="submit" class="button button-primary">Lưu</button>
    </form>';
}