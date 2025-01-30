const orders = []; // Sipariş listesini saklamak için

// Ürün eklemek için fonksiyon
function addItem(name, price) {
    orders.push({ name, price });
    updateSummary();
}

// Özet bilgileri güncellemek için fonksiyon
function updateSummary() {
    const totalAmount = orders.reduce((sum, item) => sum + item.price, 0);
    const totalItems = orders.length;
    document.getElementById("total-amount").innerText = totalAmount;
    document.getElementById("total-items").innerText = totalItems;
}

// Sipariş gönderme fonksiyonu
function submitOrder() {
    fetch('save_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orders)
    })
    .then(response => response.text()) // Yanıtı metin olarak alın
    .then(text => {
        try {
            const data = JSON.parse(text); // JSON formatına çevirmeyi deneyin
            if (data.success) {
                alert("Sipariş başarıyla kaydedildi.");
                orders.length = 0; // Sipariş listesini sıfırla
                updateSummary();
            } else {
                alert("Sipariş kaydedilirken bir hata oluştu: " + data.message);
            }
        } catch (error) {
            console.error("Yanıt JSON formatında değil:", text);
            alert("Bir hata oluştu. Yanıt JSON formatında değil.");
        }
    })
    .catch(error => console.error('Error:', error));
}

// "Sipariş Ver" butonuna tıklama olayı
document.querySelector(".order-btn").addEventListener("click", submitOrder);

