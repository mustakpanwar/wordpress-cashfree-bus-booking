<?php
$order_id = $_GET['order_id'] ?? '';
$token    = $_GET['token']    ?? '';

$booking  = json_decode(base64_decode($token), true);

$name     = htmlspecialchars($booking['name']    ?? '');
$phone    = htmlspecialchars($booking['phone']   ?? '');
$date     = htmlspecialchars($booking['date']    ?? '');
$pickup   = htmlspecialchars($booking['pickup']  ?? '');
$package  = htmlspecialchars($booking['package'] ?? '');
$seats    = htmlspecialchars($booking['seats']   ?? '');
$total    = htmlspecialchars($booking['total']   ?? '0');
?>
<style>
    .ps-card { background: white; border-radius: 20px; padding: 40px 30px; max-width: 480px; width: 100%; text-align: center; box-shadow: 0 15px 40px rgba(0,0,0,0.1); margin: 40px auto; }
    .ps-checking { color: #666; font-size: 18px; padding: 20px 0; }
    .ps-success { display: none; }
    .ps-failure { display: none; }
    .ps-card h2 { margin-bottom: 10px; }
    .ps-summary { background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 20px 0; text-align: left; font-size: 14px; border: 1px solid #eee; }
    .ps-summary p { margin: 8px 0; border-bottom: 1px dashed #ddd; padding-bottom: 8px; display: flex; justify-content: space-between; }
    .ps-summary p:last-child { border-bottom: none; }
    .ps-order-id { font-size: 12px; color: #888; margin-bottom: 15px; }
    .ps-whatsapp-btn { background: #25D366; color: white; padding: 14px 30px; border-radius: 50px; text-decoration: none; font-weight: 700; display: inline-block; margin-top: 15px; width: 100%; text-align: center; }
    .ps-download-btn { background: #2c3e50; color: white; padding: 14px 30px; border-radius: 50px; font-weight: 700; border: none; cursor: pointer; width: 100%; margin-top: 10px; font-size: 15px; font-family: 'Poppins', sans-serif; }
    .ps-retry-btn { background: #d35400; color: white; padding: 14px 30px; border-radius: 50px; text-decoration: none; font-weight: 700; display: inline-block; margin-top: 15px; width: 100%; text-align: center; }
</style>

<div class="ps-card">
    <div class="ps-checking" id="ps-checking">⏳ Verifying your payment...</div>

    <div class="ps-success" id="ps-success">
        <h2 style="color:#27ae60">🎉 Payment Successful!</h2>
        <p class="ps-order-id" id="ps-order-info"></p>
        <div class="ps-summary">
            <p><strong>Name:</strong>       <span><?= $name ?></span></p>
            <p><strong>Date:</strong>       <span><?= $date ?></span></p>
            <p><strong>Pickup:</strong>     <span><?= $pickup ?></span></p>
            <p><strong>Package:</strong>    <span><?= $package ?></span></p>
            <p><strong>Passengers:</strong> <span><?= $seats ?></span></p>
            <p><strong>Total Paid:</strong> <span style="color:#d35400;font-weight:700;">₹<?= $total ?></span></p>
        </div>
        <a id="ps-whatsapp-link" href="#" class="ps-whatsapp-btn">✅ Confirm Seat on WhatsApp</a>
        <button onclick="psDownloadReceipt()" class="ps-download-btn">📄 Download Receipt</button>
    </div>

    <div class="ps-failure" id="ps-failure">
        <h2 style="color:#e74c3c">❌ Payment Failed</h2>
        <p style="color:#666; margin-top:10px;">Don't worry, no money was deducted. Please try again.</p>
        <a href="/booking-test/" class="ps-retry-btn">🔄 Try Again</a>
    </div>
</div>

<script>
const psOrderId = '<?= htmlspecialchars($order_id) ?>';
const psName    = '<?= $name ?>';
const psPhone   = '<?= $phone ?>';
const psDate    = '<?= $date ?>';
const psPickup  = '<?= addslashes($pickup) ?>';
const psPkg     = '<?= addslashes($package) ?>';
const psSeats   = '<?= $seats ?>';
const psTotal   = '<?= $total ?>';

if (psOrderId) {
    const verifyUrl = '/cashfree-verify-sandbox.php?order_id=' + psOrderId +
        '&name='    + encodeURIComponent(psName) +
        '&phone='   + encodeURIComponent(psPhone) +
        '&date='    + encodeURIComponent(psDate) +
        '&pickup='  + encodeURIComponent(psPickup) +
        '&package=' + encodeURIComponent(psPkg) +
        '&seats='   + encodeURIComponent(psSeats) +
        '&total='   + encodeURIComponent(psTotal);

    fetch(verifyUrl)
    .then(r => r.json())
    .then(data => {
        document.getElementById('ps-checking').style.display = 'none';

        if (data.order_status === 'PAID') {
            document.getElementById('ps-success').style.display = 'block';
            document.getElementById('ps-order-info').innerText = 'Order ID: ' + psOrderId;

            // WhatsApp message
            const msg = "Hello, I have made a payment for Mumbai Darshan.%0a" +
                        "-----------------------%0a" +
                        "*ORDER ID:* " + psOrderId + " (PAID)%0a" +
                        "-----------------------%0a" +
                        "*Name:* " + psName + "%0a" +
                        "*Phone:* " + psPhone + "%0a" +
                        "*Date:* " + psDate + "%0a" +
                        "*Pickup:* " + psPickup + "%0a" +
                        "*Package:* " + psPkg + "%0a" +
                        "*Passengers:* " + psSeats + "%0a" +
                        "*Total Paid:* ₹" + psTotal + "%0a" +
                        "-----------------------%0a" +
                        "Booked on YOUR-DOMAIN.com";

            document.getElementById('ps-whatsapp-link').href = 'https://wa.me/YOUR_WHATSAPP_NUMBER?text=' + msg;

            // Send admin email via PHPMailer (backend)
            fetch('/send-mail.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    order_id : psOrderId,
                    name     : psName,
                    phone    : psPhone,
                    date     : psDate,
                    pickup   : psPickup,
                    package  : psPkg,
                    seats    : psSeats,
                    total    : psTotal
                })
            })
            .then(r => r.json())
            .then(res => console.log('Mail result:', res))
            .catch(e => console.log('Mail Error:', e));

            localStorage.removeItem('pendingBooking');

        } else {
            document.getElementById('ps-failure').style.display = 'block';
        }
    })
    .catch(() => {
        document.getElementById('ps-checking').style.display = 'none';
        document.getElementById('ps-failure').style.display = 'block';
    });

} else {
    document.getElementById('ps-checking').style.display = 'none';
    document.getElementById('ps-failure').style.display = 'block';
}

function psDownloadReceipt() {
    const canvas = document.createElement('canvas');
    canvas.width = 600;
    canvas.height = 750;
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, 600, 750);
    ctx.fillStyle = '#d35400';
    ctx.fillRect(0, 0, 600, 8);
    ctx.fillStyle = '#fff9f0';
    ctx.fillRect(0, 8, 600, 100);
    ctx.fillStyle = '#d35400';
    ctx.font = 'bold 28px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Mumbai Darshan', 300, 50);
    ctx.fillStyle = '#666';
    ctx.font = '14px Arial';
    ctx.fillText('Bus Booking Receipt', 300, 75);
    ctx.fillStyle = '#27ae60';
    ctx.font = 'bold 16px Arial';
    ctx.fillText('PAYMENT SUCCESSFUL', 300, 115);
    ctx.fillStyle = '#888';
    ctx.font = '13px Arial';
    ctx.fillText('Order ID: ' + psOrderId, 300, 138);
    ctx.strokeStyle = '#f0f0f0';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(40, 155);
    ctx.lineTo(560, 155);
    ctx.stroke();
    const details = [
        ['Name', psName], ['Phone', psPhone], ['Journey Date', psDate],
        ['Pickup', psPickup], ['Package', psPkg], ['Passengers', psSeats], ['Total Paid', '₹' + psTotal]
    ];
    let y = 190;
    details.forEach(([label, value]) => {
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(40, y - 22, 520, 36);
        ctx.fillStyle = '#555';
        ctx.font = 'bold 14px Arial';
        ctx.textAlign = 'left';
        ctx.fillText(label, 60, y);
        ctx.fillStyle = '#222';
        ctx.font = '14px Arial';
        ctx.textAlign = 'right';
        ctx.fillText(value || '-', 540, y);
        ctx.strokeStyle = '#eeeeee';
        ctx.lineWidth = 1;
        ctx.setLineDash([4, 4]);
        ctx.beginPath();
        ctx.moveTo(40, y + 10);
        ctx.lineTo(560, y + 10);
        ctx.stroke();
        ctx.setLineDash([]);
        y += 50;
    });
    ctx.fillStyle = '#fff3e0';
    ctx.beginPath();
    ctx.roundRect(40, y, 520, 55, 10);
    ctx.fill();
    ctx.fillStyle = '#d35400';
    ctx.font = 'bold 20px Arial';
    ctx.textAlign = 'left';
    ctx.fillText('Total Paid', 60, y + 33);
    ctx.font = 'bold 22px Arial';
    ctx.textAlign = 'right';
    ctx.fillText('₹' + psTotal, 540, y + 33);
    ctx.fillStyle = '#d35400';
    ctx.fillRect(0, 700, 600, 50);
    ctx.fillStyle = '#ffffff';
    ctx.font = '13px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Thank you for booking with Mumbai Darshan!', 300, 722);
    ctx.fillText('YOUR-DOMAIN.com', 300, 740);
    const link = document.createElement('a');
    link.download = 'Mumbai-Darshan-Receipt-' + psOrderId + '.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}
</script>
