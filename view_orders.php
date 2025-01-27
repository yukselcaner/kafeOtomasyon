<?php
require 'vendor/autoload.php';

// MongoDB bağlantısını ayarla
$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->kafedb;
$collection = $db->menu;

// Tüm siparişleri çek
$orders = $collection->find();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Listesi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Sipariş Listesi</h1>
<table>
    <tr>
        <th>Masa Numarası</th>
        <th>Ürün Adı</th>
        <th>Miktar</th>
        <th>Sipariş Zamanı</th>
    </tr>
    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?php echo htmlspecialchars($order['table_number']); ?></td>
        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
        <td><?php echo $order['order_time']->toDateTime()->format('Y-m-d H:i:s'); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>