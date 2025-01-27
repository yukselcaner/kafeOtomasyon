<?php
session_start();

if (isset($_GET['masa_no'])) {
    $_SESSION['masa_no'] = $_GET['masa_no'];
}

if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}

// Ürün ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $name = $_POST['product_name'];
    $price = (float)$_POST['product_price'];

    // Ürün sepette var mı kontrol et
    $found = false;
    foreach ($_SESSION['orders'] as &$order) {
        if ($order['name'] === $name) {
            $order['quantity'] += 1; // Ürün varsa miktarı artır
            $found = true;
            break;
        }
    }

    if (!$found) {
        // Ürün sepette yoksa yeni ürün olarak ekle
        $_SESSION['orders'][] = [
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        ];
    }

    // Sayfa yenilenmesinden sonra tekrar veri eklenmesini engelle
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Siparişi temizleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_cart'])) {
    $_SESSION['orders'] = [];
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Toplam tutar ve ürün sayısını hesaplama
$totalAmount = 0;
$totalItems = 0;
foreach ($_SESSION['orders'] as $order) {
    $totalAmount += $order['price'] * $order['quantity'];
    $totalItems += $order['quantity'];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Menü</title>
    <link rel="stylesheet" href="menu.css">
</head>
<body>

<header>
        <div class="header-left">
            <a href="/index.html">Ana Sayfa</a>
        </div>
</header>

<div class="menu">
    <form action="" method="POST">
        <div class="menu-item">
            <img src="image/cay.png" alt="Çay" class="item-image">
            <p class="item-name">Çay</p>
            <p class="item-price">10 TL</p>
            <p class="item-description">Taze demlenmiş Türk çayı</p>
            <input type="hidden" name="product_name" value="Çay">
            <input type="hidden" name="product_price" value="10">
            <button type="submit" name="add_to_cart" class="add-btn">+</button>
        </div>
    </form>

    <form action="" method="POST">
        <div class="menu-item">
            <img src="image/kahve.jpg" alt="Kahve" class="item-image">
            <p class="item-name">Kahve</p>
            <p class="item-price">20 TL</p>
            <p class="item-description">Espresso bazlı sıcak kahve</p>
            <input type="hidden" name="product_name" value="Kahve">
            <input type="hidden" name="product_price" value="20">
            <button type="submit" name="add_to_cart" class="add-btn">+</button>
        </div>
    </form>

	<!-- Espresso -->
    <form action="" method="POST">
        <div class="menu-item">
            <img src="image/espresso.png" alt="Espresso" class="item-image">
            <p class="item-name">Espresso</p>
            <p class="item-price">15 TL</p>
            <p class="item-description">Yoğun ve aromatik bir kahve</p>
            <input type="hidden" name="product_name" value="Espresso">
            <input type="hidden" name="product_price" value="15">
            <button type="submit" name="add_to_cart" class="add-btn">+</button>
        </div>
    </form>

	<!-- Latte -->
    <form action="" method="POST">
        <div class="menu-item">
            <img src="image/latte.png" alt="Latte" class="item-image">
            <p class="item-name">Latte</p>
            <p class="item-price">18 TL</p>
            <p class="item-description">Espresso ve sıcak süt karışımı</p>
            <input type="hidden" name="product_name" value="Latte">
            <input type="hidden" name="product_price" value="18">
            <button type="submit" name="add_to_cart" class="add-btn">+</button>
        </div>
    </form>

	<!-- Cappuccino -->
    <form action="" method="POST">
        <div class="menu-item">
            <img src="image/cappuccino.png" alt="Cappuccino" class="item-image">
            <p class="item-name">Cappuccino</p>
            <p class="item-price">20 TL</p>
            <p class="item-description">Espresso, sıcak süt ve süt köpüğü</p>
            <input type="hidden" name="product_name" value="Cappuccino">
            <input type="hidden" name="product_price" value="20">
            <button type="submit" name="add_to_cart" class="add-btn">+</button>
        </div>
    </form>

	<!-- Mocha -->
    <form action="" method="POST">
        <div class="menu-item">
            <img src="image/mocha.png" alt="Mocha" class="item-image">
            <p class="item-name">Mocha</p>
            <p class="item-price">22 TL</p>
            <p class="item-description">Espresso, çikolata ve süt karışımı</p>
            <input type="hidden" name="product_name" value="Mocha">
            <input type="hidden" name="product_price" value="22">
            <button type="submit" name="add_to_cart" class="add-btn">+</button>
        </div>
    </form>

	<!-- Su -->
    <form action="" method="POST">
        <div class="menu-item">
            <img src="image/su.png" alt="Su" class="item-image">
            <p class="item-name">Su</p>
            <p class="item-price">5 TL</p>
            <p class="item-description">0.5L su</p>
            <input type="hidden" name="product_name" value="Su">
            <input type="hidden" name="product_price" value="5">
            <button type="submit" name="add_to_cart" class="add-btn">+</button>
        </div>
    </form>

<!-- Salep -->
    <form action="" method="POST">
        <div class="menu-item">
            <img src="image/salep.jpg" alt="Salep" class="item-image">
            <p class="item-name">Salep</p>
            <p class="item-price">17 TL</p>
            <p class="item-description">Sıcacık salep</p>
            <input type="hidden" name="product_name" value="Salep">
            <input type="hidden" name="product_price" value="17">
            <button type="submit" name="add_to_cart" class="add-btn">+</button>
        </div>
    </form>

</div>

<div class="order-summary">
    <p>Ödenecek Tutar: <span id="total-amount"><?= $totalAmount; ?></span> TL, Ürün Miktarı: <span id="total-items"><?= $totalItems; ?></span></p>
    <button class="order-btn" onclick="document.getElementById('order-details-modal').style.display='block'">Siparişi Tamamla</button>
    
    <!-- Siparişi Temizle Butonu -->
    <form action="" method="POST" style="display:inline;">
        <button type="submit" name="clear_cart" class="clear-btn">Siparişi Temizle</button>
    </form>
</div>

<div id="order-details-modal" class="order-details-modal" style="display: none;">
    <div class="modal-content">
        <h3>Sipariş Detayları</h3>
        <ul>
            <?php foreach ($_SESSION['orders'] as $order): ?>
                <li><?= $order['name'] ?> - <?= $order['quantity'] ?> x <?= $order['price'] ?> TL</li>
            <?php endforeach; ?>
        </ul>
        <form action="/save_order.php" method="POST">
            <button type="submit" class="confirm-order-btn">Siparişi Onayla</button>
        </form>
        <span class="close-btn" onclick="document.getElementById('order-details-modal').style.display='none'">&times;</span>
    </div>
</div>

</body>
</html>
