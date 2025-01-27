<?php
session_start();
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->kafedb;
$collection = $db->menu;

if (!empty($_SESSION['orders']) && isset($_SESSION['masa_no'])) {
    // Sipariş bilgileri ve masa numarasını MongoDB'ye kaydet
    $result = $collection->insertOne([
        'orders' => $_SESSION['orders'],
        'masa_no' => $_SESSION['masa_no'], // Masa numarasını ekliyoruz
        'date' => new MongoDB\BSON\UTCDateTime() // İsteğe bağlı: sipariş tarihini kaydedin
    ]);

    if ($result->getInsertedCount() == 1) {
        echo "Sipariş başarıyla kaydedildi.";
    } else {
        echo "Sipariş kaydedilirken bir hata oluştu.";
    }

    // Sipariş verilerini ve masa numarasını temizle
    $_SESSION['orders'] = [];
    unset($_SESSION['masa_no']);
    header("Location: /pages/menu/menu.php"); // Ana menü sayfasına yönlendir
    exit();
} else {
    echo "Sepetiniz boş veya masa numarası belirtilmedi!";
}
?>
