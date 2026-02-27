<?php
session_start();
include 'config/koneksi.php';
date_default_timezone_set("Asia/Jakarta");

$data = json_decode(file_get_contents("php://input"), true);

mysqli_begin_transaction($koneksi);

try {

$order_code = $data['order_code'];
$customer = $data['customer_name'];
$order_date = $data['order_date'];
$amount = $data['order_amount'];
$change = $data['order_change'];

mysqli_query($koneksi, "
INSERT INTO orders
(order_code, customer_name, order_date, order_amount, order_change)
VALUES
('$order_code', '$customer', '$order_date', '$amount', '$change')
");

$orderId = mysqli_insert_id($koneksi);

foreach ($data['items'] as $item) {

$productId = $item['id'];
$qty = $item['qty'];
$price = $item['price'];
$subtotal = $qty * $price;

mysqli_query($koneksi, "
INSERT INTO order_details
(order_id, product_id, qty, order_price, order_subtotal)
VALUES
('$orderId', '$productId', '$qty', '$price', '$subtotal')
");

mysqli_query($koneksi, "
UPDATE products
SET qty = qty - $qty
WHERE id = $productId AND qty >= $qty
");

if (mysqli_affected_rows($koneksi) === 0) {
throw new Exception("Stok tidak cukup");
}
}

mysqli_commit($koneksi);

echo json_encode(['status' => 'success']);
} catch (Exception $e) {
mysqli_rollback($koneksi);
echo json_encode(['status' => 'error']);
}
?>