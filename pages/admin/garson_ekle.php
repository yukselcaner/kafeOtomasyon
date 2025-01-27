<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garson Ekle</title>
    <link rel="stylesheet" href="ekle.css">
    <style>
        /* Pop-up mesaj stilleri */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .popup button {
            background-color: white;
            color: #4CAF50;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>

	<header>
        	<div class="header-left">
           		<h1>Kafe Adı</h1>
        	</div>
        	<div class="header-right">
            		<a href="/index.html">Anasayfa</a>
        	</div>
    	</header>

<div class="container">
<form method="POST" action="garson_ekle.php" class="form-container">
    <label for="garson_adi">Garson Adı:</label>
    <input type="text" id="garson_adi" name="garson_adi" required><br><br>
    <label for="garson_sifre">Şifre:</label>
    <input type="password" id="garson_sifre" name="garson_sifre" required><br><br>
    <button type="submit">Kaydet</button>
</form>
</div>

<!-- Pop-up mesaj div -->
<div id="popup" class="popup">
    Garson başarıyla eklendi!
    <button onclick="closePopup()">Tamam</button>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['garson_adi']) && isset($_POST['garson_sifre'])) {
    // MongoDB bağlantısı
    $baglanti = new MongoDB\Driver\Manager('mongodb://localhost:27017');

    // Eklenecek veriyi hazırlayın
    $garson = [
        'adi' => $_POST['garson_adi'],
        'sifre' => $_POST['garson_sifre']
    ];

    // Insert komutunu oluşturun
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($garson);

    // Kafedb veritabanındaki garson koleksiyonuna ekleyin
    $sonuc = $baglanti->executeBulkWrite('kafedb.garson', $bulk);

    // Başarılı bir eklemeden sonra pop-up göster
    echo '<script>document.getElementById("popup").style.display = "block";</script>';
}
?>

<script>
// Pop-up mesajını kapatma fonksiyonu
function closePopup() {
    document.getElementById("popup").style.display = "none";
}
</script>

</body>
</html>
