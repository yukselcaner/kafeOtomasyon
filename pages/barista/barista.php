<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barista Ekranı</title>
    <link rel="stylesheet" href="barista.css">
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
        <aside class="sidebar">
            <ul>
                <li><a href="garson_ekle.php">Garson Ekle</a></li>
                <li><a href="barista_ekle.php">Barista Ekle</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h2>Barista Paneli</h2>

            <?php
            try {
                $baglanti = new MongoDB\Driver\Manager('mongodb://localhost:27017');
                //echo "MongoDB bağlantısı başarılı.<br>";
            } catch (MongoDB\Driver\Exception\Exception $e) {
                echo "MongoDB bağlantı hatası: " . $e->getMessage();
                exit;
            }

            // Verileri çekme
            $sorgu = new MongoDB\Driver\Query([]);
            $sonuc = $baglanti->executeQuery('kafedb.menu', $sorgu);

            $veriler = $sonuc->toArray();

            // Eğer veriler varsa, tablodaki veriyi göster
            if (!empty($veriler)) {
                echo "<table border='1' cellspacing='0' cellpadding='10'>";
                echo "<tr>";
                echo "<th>Masa No.</th>"; // Masa numarası başta
                echo "<th>Siparişler</th>";
                echo "<th>Tarih</th>";
                echo "<th>Onayla</th>";
                echo "</tr>";

                foreach ($veriler as $veri) {
                    // Veriyi diziye çevir
                    $veri = (array) $veri;

                    // Tarihi al ve UTC'den yerel saate dönüştür
                    if (isset($veri['date'])) {
                        // MongoDB'den gelen tarihi DateTime objesine çevir
                        $tarih = $veri['date']->toDateTime();

                        // Zaman dilimini ayarla (örneğin, Türkiye için 'Europe/Istanbul')
                        $tarih->setTimezone(new DateTimeZone('Europe/Istanbul'));

                        // Formatla
                        $tarih = $tarih->format('Y-m-d H:i:s');
                    } else {
                        $tarih = 'Bilinmiyor';
                    }

                    // Siparişleri al ve listele
                    $siparisler = $veri['orders'] ?? [];
                    $siparisListesi = '';
                    foreach ($siparisler as $siparis) {
                        $siparis = (array) $siparis;
                        $siparisListesi .= ($siparis['name'] ?? '') . "- Adet: " . ($siparis['quantity'] ?? '') . "<br>";
                    }

                    // Masa numarasını al
                    $masaNo = $veri['masa_no'] ?? 'Bilinmiyor';

                    // Tabloya veriyi ekle
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($masaNo) . "</td>"; // Masa numarası başta
                    echo "<td>" . $siparisListesi . "</td>";
                    echo "<td>" . htmlspecialchars($tarih) . "</td>";
                    echo "<td>
                            <form method='POST' action=''>
                                <input type='hidden' name='id' value='" . htmlspecialchars($veri['_id']->__toString()) . "' />
                                <button class='accept-btn' type='submit' name='onayla'>Onayla</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "Veri bulunamadı.";
            }

            // Onaylama işlemi
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['onayla'])) {
                $id = $_POST['id'];

                // Menu koleksiyonundan veri çekme
                $sorgu = new MongoDB\Driver\Query(['_id' => new MongoDB\BSON\ObjectId($id)]);
                $sonuc = $baglanti->executeQuery('kafedb.menu', $sorgu);
                $veri = $sonuc->toArray();

                if (!empty($veri)) {
                    // Sipariş koleksiyonuna veri ekleme
                    $siparis = (array) $veri[0];
                    $insertData = [
                        'orders' => $siparis['orders'],
                        'masa_no' => $siparis['masa_no'],
                        'date' => $siparis['date']
                    ];

                    $bulk = new MongoDB\Driver\BulkWrite;
                    $bulk->insert($insertData);
                    $baglanti->executeBulkWrite('kafedb.siparis', $bulk);

                    // Menu koleksiyonundan veri silme
                    $bulk = new MongoDB\Driver\BulkWrite;
                    $bulk->delete(['_id' => new MongoDB\BSON\ObjectId($id)]);
                    $baglanti->executeBulkWrite('kafedb.menu', $bulk);

                    // Onay işleminden sonra sayfayı yenile
                    echo "<script>
                            alert('Sipariş onaylandı ve silindi. Sayfa yenileniyor...');
                            window.location.href = window.location.href;
                          </script>";
                } else {
                    echo "Veri bulunamadı.";
                }
            }
            ?>

        </main>
    </div>
</body>
</html>
