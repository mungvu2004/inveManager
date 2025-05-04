<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$supplier_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : null;
$supplier = $supplier_id ? im_get_supplier( $supplier_id ) : null;

echo '<div class="wrap">';
echo '<h1>' . ( $supplier ? 'Cập nhật nhà cung cấp' : 'Thêm nhà cung cấp mới' ) . '</h1>';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_text_field( $_POST['name'] );
    $contact_name = sanitize_text_field( $_POST['contact_name'] );
    $contact_email = sanitize_email( $_POST['contact_email'] );
    $phone = sanitize_text_field( $_POST['phone'] );
    $address = sanitize_textarea_field( $_POST['address'] );

    if ( empty( $supplier_id ) ) {
        // Thêm nhà cung cấp mới
        im_add_supplier( $name, $contact_name, $contact_email, $phone );
    } else {
        // Cập nhật nhà cung cấp
        im_edit_supplier( $supplier_id, $name, $contact_name, $contact_email, $phone);
    }
}

im_display_supplier_form( $supplier );
echo '</div>';
?>

<?php
// Hàm hiển thị form thêm/sửa nhà cung cấp
function im_display_supplier_form( $supplier = null ) {
    $name = $supplier ? esc_attr( $supplier->name ) : '';
    $contact_name = $supplier ? esc_attr( $supplier->contact_name ) : '';
    $contact_email = $supplier ? esc_attr( $supplier->contact_email ) : '';
    $phone = $supplier ? esc_attr( $supplier->phone ) : '';
    $address = $supplier ? esc_textarea( $supplier->address ) : '';

    echo '<form method="POST">
        <table class="form-table">
            <tr>
                <th><label for="name">Tên nhà cung cấp</label></th>
                <td><input type="text" id="name" name="name" value="' . $name . '" required /></td>
            </tr>
            <tr>
                <th><label for="contact_name">Người liên hệ</label></th>
                <td><input type="text" id="contact_name" name="contact_name" value="' . $contact_name . '" /></td>
            </tr>
            <tr>
                <th><label for="contact_email">Email liên hệ</label></th>
                <td><input type="email" id="contact_email" name="contact_email" value="' . $contact_email . '" /></td>
            </tr>
            <tr>
                <th><label for="phone">Số điện thoại</label></th>
                <td><input type="text" id="phone" name="phone" value="' . $phone . '" /></td>
            </tr>
            <tr>
                <th><label for="address">Địa chỉ</label></th>
                <td><textarea id="address" name="address">' . $address . '</textarea></td>
            </tr>
        </table>
        <button type="submit" class="button button-primary">Lưu</button>
    </form>';
}
?>
