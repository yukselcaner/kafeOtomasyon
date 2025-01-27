<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barista Ekle</title>
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
<form method="POST" action="barista_ekle.php" class="form-container">
    <label for="barista_adi">Barista Adı:</label>
    <input type="text" id="barista_adi" name="barista_adi" required><br><br>
    <label for="barista_sifre">Şifre:</label>
    <input type="password" id="barista_sifre" name="barista_sifre" required><br><br>
    <button type="submit">Kaydet</button>
</form>
</div>

<!-- Pop-up mesaj div -->
<div id="popup" class="popup">
    Barista başarıyla eklendi!
    <button onclick="closePopup()">Tamam</button>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['barista_adi']) && isset($_POST['barista_sifre'])) {
    // MongoDB bağlantısı
    $baglanti = new MongoDB\Driver\Manager('mongodb://localhost:27017');

    // Eklenecek veriyi hazırlayın
    $barista = [
        'adi' => $_POST['barista_adi'],
        'sifre' => $_POST['barista_sifre']
    ];

    // Insert komutunu oluşturun
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($barista);

    // Kafedb veritabanındaki barista koleksiyonuna ekleyin
    $sonuc = $baglanti->executeBulkWrite('kafedb.barista', $bulk);

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
