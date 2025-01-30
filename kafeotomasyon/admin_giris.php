<?php

$baglanti = new MongoDB\Driver\Manager('mongodb://localhost:27017');

$sorgu = new MongoDB\Driver\Query([]);
$sonuc = $baglanti->executeQuery('kafedb.admin', $sorgu);

$kayitlar = $sonuc->toArray();

if(count($kayitlar) > 0){
    foreach ($kayitlar as $kayit) {
        if($kayit->adi == $_POST['adi'] && $kayit->sifre == $_POST['sifre']){
            header('Location: admin.html');
            exit;
        }
    }
} else {
    echo 'Kayıt bulunamadı!';
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Giriş Ekranı</title>
</head>
<body>

<form method="POST">
    <label for="adi">Adı:</label>
    <input type="text" id="adi" name="adi"><br><br>
    <label for="sifre">Şifre:</label>
    <input type="password" id="sifre" name="sifre"><br><br>
    <button type="submit">Giriş Yap</button>
</form>

<?php if(isset($_POST['adi']) && isset($_POST['sifre'])){ ?>
    <?php if(count($kayitlar) > 0){
        foreach ($kayitlar as $kayit) {
            if($kayit->adi == $_POST['adi'] && $kayit->sifre == $_POST['sifre']){
                header('Location: admin.html');
                exit;
            } else {
                echo 'Hatalı giriş!';
            }
        }
    } else {
        echo 'Kayıt bulunamadı!';
    } ?>
<?php } ?>

</body>
</html>