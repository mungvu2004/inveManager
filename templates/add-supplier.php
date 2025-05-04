<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_text_field( $_POST['name'] );
    $contact_name = sanitize_text_field( $_POST['contact_name'] );
    $contact_email = sanitize_email( $_POST['contact_email'] );
    $phone = sanitize_text_field( $_POST['phone'] );
    $address = sanitize_textarea_field( $_POST['address'] );

    if ( empty( $supplier_id ) ) {
        // Thêm nhà cung cấp mới - Sửa thứ tự tham số
        im_add_supplier( $name, $contact_name, $phone, $contact_email );
    } else {
        // Cập nhật nhà cung cấp - Sửa thứ tự tham số
        im_edit_supplier( $supplier_id, $name, $contact_name, $phone, $contact_email );
    }
}