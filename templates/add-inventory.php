<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$inventory_id = isset( $_GET['book_id'] ) ? intval( $_GET['book_id'] ) : null;
$inventory = $inventory_id ? im_check_inventory( $inventory_id ) : null;

echo '<div class="wrap">';
echo '<h1>' . ( $inventory ? 'Cập nhật kho sách' : 'Thêm sách vào kho' ) . '</h1>';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = intval( $_POST['quantity'] );

    if ( empty( $inventory_id ) ) {
        // Thêm kho mới
        im_add_inventory( $inventory_id, $quantity );
    } else {
        // Cập nhật kho
        im_update_inventory( $inventory_id, $quantity );
    }
}

im_display_inventory_form( $inventory );
echo '</div>';
?>

<?php
// Hàm hiển thị form thêm/sửa kho sách
function im_display_inventory_form( $inventory = null ) {
    $quantity = $inventory ? esc_attr( $inventory->quantity ) : '';

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
?>
