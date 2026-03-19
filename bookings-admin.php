<?php
// Simple password protection
$password = "YOUR_ADMIN_PASSWORD";
if (!isset($_GET['pass']) || $_GET['pass'] !== $password) {
    die("<h2 style='font-family:sans-serif; text-align:center; margin-top:100px'>Access Denied!</h2>");
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'YOUR_DB_NAME');
define('DB_USER', 'YOUR_DB_USER');
define('DB_PASSWORD', 'YOUR_DB_PASSWORD');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Handle Delete Request
if (isset($_POST['delete_id']) && isset($_POST['delete_password'])) {
    if ($_POST['delete_password'] === $password) {
        $delete_id = intval($_POST['delete_id']);
        $conn->query("DELETE FROM mumbai_darshan_bookings WHERE id = $delete_id");
        $delete_msg = "success";
    } else {
        $delete_msg = "wrong_password";
    }
}

$where = "";
if (!empty($_GET['date'])) {
    $filter_date = $conn->real_escape_string($_GET['date']);
    $where = "WHERE journey_date = '$filter_date'";
}

$result = $conn->query("SELECT * FROM mumbai_darshan_bookings $where ORDER BY created_at DESC");
$total_rows = $result->num_rows;
$total_amount = $conn->query("SELECT SUM(total_amount) as total FROM mumbai_darshan_bookings $where")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mumbai Darshan - Bookings</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f5f5f5; padding: 20px; margin: 0; }
        h2 { color: #d35400; text-align: center; margin-bottom: 20px; }
        .stats { display: flex; gap: 20px; justify-content: center; margin-bottom: 20px; flex-wrap: wrap; }
        .stat-box { background: white; padding: 20px 40px; border-radius: 12px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.08); border-top: 4px solid #d35400; }
        .stat-box h3 { margin: 0; font-size: 28px; color: #d35400; }
        .stat-box p { margin: 5px 0 0; color: #666; font-size: 13px; }
        .filter { text-align: center; margin-bottom: 20px; }
        .filter input { padding: 10px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px; }
        .filter button { padding: 10px 20px; background: #d35400; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; margin-left: 10px; }
        .filter a { margin-left: 10px; color: #d35400; text-decoration: none; font-size: 14px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); min-width: 900px; }
        th { background: #d35400; color: white; padding: 14px; text-align: left; font-size: 13px; }
        td { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; font-size: 13px; }
        tr:hover td { background: #fff9f0; }
        .paid { background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .no-data { text-align: center; padding: 40px; color: #666; }
        .delete-btn { background: #e74c3c; color: white; border: none; padding: 6px 14px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; font-family: 'Poppins', sans-serif; }
        .delete-btn:hover { background: #c0392b; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: white; padding: 30px; border-radius: 16px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.3); animation: zoomIn 0.3s ease; }
        .modal-box h3 { color: #e74c3c; margin-bottom: 10px; }
        .modal-box p { color: #666; font-size: 14px; margin-bottom: 20px; }
        .modal-box input { width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 14px; margin-bottom: 15px; font-family: 'Poppins', sans-serif; text-align: center; letter-spacing: 2px; }
        .modal-box input:focus { border-color: #e74c3c; outline: none; }
        .modal-btn-row { display: flex; gap: 10px; }
        .modal-btn-row button { flex: 1; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; font-family: 'Poppins', sans-serif; }
        .btn-confirm { background: #e74c3c; color: white; }
        .btn-confirm:hover { background: #c0392b; }
        .btn-cancel { background: #f0f0f0; color: #333; }
        .btn-cancel:hover { background: #ddd; }
        .error-msg { color: #e74c3c; font-size: 13px; margin-bottom: 10px; display: none; }
        @keyframes zoomIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        /* Toast */
        .toast { position: fixed; bottom: 30px; right: 30px; background: #27ae60; color: white; padding: 14px 24px; border-radius: 10px; font-size: 14px; font-weight: 600; z-index: 99999; display: none; box-shadow: 0 5px 20px rgba(0,0,0,0.2); }
        .toast.error { background: #e74c3c; }
        .toast.show { display: block; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body>

<h2>&#128652; Mumbai Darshan Bookings</h2>

<?php if (isset($delete_msg)): ?>
<script>
    window.addEventListener('load', function() {
        <?php if ($delete_msg === 'success'): ?>
        showToast('✅ Booking deleted successfully!', 'success');
        <?php else: ?>
        showToast('❌ Wrong password! Deletion failed.', 'error');
        <?php endif; ?>
    });
</script>
<?php endif; ?>

<div class="stats">
    <div class="stat-box">
        <h3><?= $total_rows ?></h3>
        <p>Total Bookings</p>
    </div>
    <div class="stat-box">
        <h3>&#8377;<?= number_format($total_amount ?? 0) ?></h3>
        <p>Total Revenue</p>
    </div>
</div>

<div class="filter">
    <form method="GET">
        <input type="hidden" name="pass" value="<?= htmlspecialchars($_GET['pass']) ?>">
        <input type="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
        <button type="submit">Filter by Date</button>
        <a href="?pass=<?= htmlspecialchars($_GET['pass']) ?>">Clear Filter</a>
    </form>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Order ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Journey Date</th>
                <th>Pickup</th>
                <th>Package</th>
                <th>Seats</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Booked At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($total_rows > 0): ?>
                <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr id="row-<?= $row['id'] ?>">
                    <td><?= $i++ ?></td>
                    <td style="font-size:11px"><?= $row['order_id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['journey_date'] ?></td>
                    <td><?= $row['pickup'] ?></td>
                    <td><?= $row['package'] ?></td>
                    <td><?= $row['seats'] ?></td>
                    <td>&#8377;<?= $row['total_amount'] ?></td>
                    <td><span class="paid"><?= $row['payment_status'] ?></span></td>
                    <td><?= date('d M Y h:i A', strtotime($row['created_at'])) ?></td>
                    <td>
                        <button class="delete-btn" onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name']) ?>')">
                            🗑 Delete
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="12" class="no-data">No bookings found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3>🗑 Delete Booking</h3>
        <p id="modal-msg">Are you sure you want to delete this booking?</p>
        <p class="error-msg" id="error-msg">❌ Wrong password! Try again.</p>
        <input type="password" id="delete-password" placeholder="Enter admin password" />
        <div class="modal-btn-row">
            <button class="btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="btn-confirm" onclick="submitDelete()">Delete</button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast" id="toast"></div>

<!-- Hidden Delete Form -->
<form id="deleteForm" method="POST">
    <input type="hidden" name="delete_id" id="delete_id_input">
    <input type="hidden" name="delete_password" id="delete_password_input">
</form>

<script>
let currentDeleteId = null;

function confirmDelete(id, name) {
    currentDeleteId = id;
    document.getElementById('modal-msg').innerText = 'Are you sure you want to delete booking of "' + name + '"? This cannot be undone.';
    document.getElementById('delete-password').value = '';
    document.getElementById('error-msg').style.display = 'none';
    document.getElementById('deleteModal').classList.add('active');
    document.getElementById('delete-password').focus();
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('active');
    currentDeleteId = null;
}

function submitDelete() {
    const password = document.getElementById('delete-password').value;
    if (!password) {
        document.getElementById('error-msg').innerText = '❌ Please enter the password.';
        document.getElementById('error-msg').style.display = 'block';
        return;
    }
    document.getElementById('delete_id_input').value = currentDeleteId;
    document.getElementById('delete_password_input').value = password;
    document.getElementById('deleteForm').submit();
}

// Close modal on overlay click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Press Enter to confirm delete
document.getElementById('delete-password').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') submitDelete();
});

function showToast(msg, type) {
    const toast = document.getElementById('toast');
    toast.innerText = msg;
    toast.className = 'toast ' + type + ' show';
    setTimeout(() => { toast.className = 'toast'; }, 3000);
}
</script>

</body>
</html>
<?php $conn->close(); ?>
