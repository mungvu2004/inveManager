<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$book_id = isset( $_GET['book_id'] ) ? intval( $_GET['book_id'] ) : null;
$book = $book_id ? im_get_book( $book_id ) : null;

echo '<div class="wrap">';
echo '<h1>' . ( $book ? 'Cập nhật sách' : 'Thêm sách mới' ) . '</h1>';

im_display_book_form( $book );

echo '</div>';
?>
