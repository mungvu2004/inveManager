<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$order_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : null;
$order = $order_id ? im_get_order( $order_id ) : null;

echo '<div class="wrap">';
echo '<h1>' . ( $order ? 'Cập nhật đơn hàng' : 'Thêm đơn hàng mới' ) . '</h1>';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = sanitize_text_field( $_POST['customer_name'] );
    $customer_email = sanitize_email( $_POST['customer_email'] );
    $status = sanitize_text_field( $_POST['status'] );
    $total = floatval( $_POST['total'] );

    if ( empty( $order_id ) ) {
        // Thêm đơn hàng mới
        im_add_order( $customer_name, $customer_email, $status);
    } else {
        // Cập nhật đơn hàng
        im_edit_order( $order_id, $customer_name, $customer_email, $status);
    }
}

im_display_order_form( $order );
echo '</div>';
?>

<?php
// Hàm hiển thị form thêm/sửa đơn hàng
function im_display_order_form( $order = null ) {
    $customer_name = $order ? esc_attr( $order->customer_name ) : '';
    $customer_email = $order ? esc_attr( $order->customer_email ) : '';
    $status = $order ? esc_attr( $order->status ) : 'pending';
    $total = $order ? esc_attr( $order->total ) : '';

    echo '<form method="POST">
        <table class="form-table">
            <tr>
                <th><label for="customer_name">Tên khách hàng</label></th>
                <td><input type="text" id="customer_name" name="customer_name" value="' . $customer_name . '" required /></td>
            </tr>
            <tr>
                <th><label for="customer_email">Email khách hàng</label></th>
                <td><input type="email" id="customer_email" name="customer_email" value="' . $customer_email . '" /></td>
            </tr>
            <tr>
                <th><label for="status">Trạng thái</label></th>
                <td><input type="text" id="status" name="status" value="' . $status . '" /></td>
            </tr>
            <tr>
                <th><label for="total">Tổng tiền</label></th>
                <td><input type="number" id="total" name="total" value="' . $total . '" step="0.01" required /></td>
            </tr>
        </table>
        <button type="submit" class="button button-primary">Lưu</button>
    </form>';
}
?>
