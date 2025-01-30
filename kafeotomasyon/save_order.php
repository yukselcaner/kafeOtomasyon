<?php
header('Content-Type: application/json'); // JSON yanıtı sağlamak için

require 'vendor/autoload.php'; // MongoDB PHP sürücüsünü yüklemek için
use MongoDB\Client;

$client = new Client("mongodb://localhost:27017"); // MongoDB bağlantısını kurun
$db = $client->cafe; // Veritabanını seçin
$collection = $db->orders; // "orders" koleksiyonunu seçin

// JSON formatında gönderilen sipariş verilerini alın
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    // Veriyi MongoDB'ye ekleyin
    $insertResult = $collection->insertOne($data);
    echo json_encode(["success" => true, "message" => "Sipariş başarıyla kaydedildi."]);
} else {
    echo json_encode(["success" => false, "message" => "Sipariş verisi alınamadı."]);
}
?>

