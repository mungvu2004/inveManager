<?php

function im_add_supplier($name, $contact_name, $phone, $email) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'suppliers';

    // Kiểm tra xem nhà cung cấp đã tồn tại chưa
    $existing_supplier = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE name = %s", $name));
    if ($existing_supplier) {
        return new WP_Error('supplier_exists', __('Nhà cung cấp đã tồn tại trong cơ sở dữ liệu.', 'inventory-manager'));
    }

    // Thêm nhà cung cấp mới vào cơ sở dữ liệu
    $result = $wpdb->insert(
        $table_name,
        array(
            'name' => $name,
            'contact_name' => $contact_name,
            'contact_email' => $email,
            'phone' => $phone,
        )
    );

    if (false === $result) {
        return new WP_Error('db_insert_error', __('Có lỗi xảy ra khi thêm nhà cung cấp vào cơ sở dữ liệu.', 'inventory-manager'));
    }

    return true;
}

function im_edit_supplier($supplier_id, $name, $contact_name, $phone, $email) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'suppliers';

    // Cập nhật thông tin nhà cung cấp trong cơ sở dữ liệu
    $result = $wpdb->update(
        $table_name,
        array(
            'name' => $name,
            'contact_name' => $contact_name,
            'contact_email' => $email,
            'phone' => $phone,
        ),
        array('ID' => $supplier_id)
    );

    if (false === $result) {
        return new WP_Error('db_update_error', __('Có lỗi xảy ra khi cập nhật thông tin nhà cung cấp.', 'inventory-manager'));
    }

    return true;
}

function im_delete_supplier($supplier_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'suppliers';

    // Xóa nhà cung cấp khỏi cơ sở dữ liệu
    $result = $wpdb->delete($table_name, array('ID' => $supplier_id));

    if (false === $result) {
        return new WP_Error('db_delete_error', __('Có lỗi xảy ra khi xóa nhà cung cấp.', 'inventory-manager'));
    }

    return true;
}
function im_get_supplier($supplier_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'suppliers';
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE ID = %d", $supplier_id));
}
function im_get_suppliers() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'suppliers';
    return $wpdb->get_results("SELECT * FROM $table_name");
}