<?php
session_start();

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin-login.php');
    exit;
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'YOUR_DB_NAME');
define('DB_USER', 'YOUR_DB_USER');
define('DB_PASSWORD', 'YOUR_DB_PASSWORD');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Handle Delete Request
$delete_msg = '';
if (isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $conn->query("DELETE FROM mumbai_darshan_bookings WHERE id = $delete_id");
    $delete_msg = 'success';
}

$where = "";
if (!empty($_GET['date'])) {
    $filter_date = $conn->real_escape_string($_GET['date']);
    $where = "WHERE journey_date = '$filter_date'";
}

$result       = $conn->query("SELECT * FROM mumbai_darshan_bookings $where ORDER BY created_at DESC");
$total_rows   = $result->num_rows;
$total_amount = $conn->query("SELECT SUM(total_amount) as total FROM mumbai_darshan_bookings $where")->fetch_assoc()['total'];

// Today's bookings
$today        = date('Y-m-d');
$today_result = $conn->query("SELECT COUNT(*) as cnt, SUM(total_amount) as total FROM mumbai_darshan_bookings WHERE journey_date = '$today'");
$today_data   = $today_result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Mumbai Darshan Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background: #f5f5f5; }

        /* Header */
        .admin-header {
            background: linear-gradient(135deg, #d35400, #2c3e50);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 15px rgba(0,0,0,0.2);
        }
        .admin-header .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .admin-header img { height: 40px; }
        .admin-header h1 { color: white; font-size: 18px; }
        .admin-header p { color: #ffe0cc; font-size: 12px; }
        .header-right { display: flex; align-items: center; gap: 15px; }
        .welcome-text { color: #ffe0cc; font-size: 13px; }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 18px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,0.3);
            transition: background 0.2s;
        }
        .logout-btn:hover { background: rgba(255,255,255,0.3); }

        /* Content */
        .content { padding: 25px 30px; }

        /* Stats */
        .stats { display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap; }
        .stat-box {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 3px 15px rgba(0,0,0,0.07);
            border-top: 4px solid #d35400;
            flex: 1;
            min-width: 160px;
        }
        .stat-box h3 { font-size: 28px; color: #d35400; margin-bottom: 5px; }
        .stat-box p { color: #888; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Filter */
        .filter-bar {
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            box-shadow: 0 3px 15px rgba(0,0,0,0.07);
        }
        .filter-bar label { font-size: 13px; font-weight: 600; color: #555; }
        .filter-bar input { padding: 9px 12px; border: 2px solid #eee; border-radius: 8px; font-size: 13px; font-family: 'Poppins', sans-serif; outline: none; }
        .filter-bar input:focus { border-color: #d35400; }
        .filter-bar button { padding: 9px 18px; background: #d35400; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px; font-family: 'Poppins', sans-serif; }
        .filter-bar a { color: #d35400; text-decoration: none; font-size: 13px; font-weight: 600; }

        /* Table */
        .table-wrap { overflow-x: auto; background: white; border-radius: 12px; box-shadow: 0 3px 15px rgba(0,0,0,0.07); }
        table { width: 100%; border-collapse: collapse; min-width: 950px; }
        th { background: #2c3e50; color: white; padding: 14px 12px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 12px; border-bottom: 1px solid #f0f0f0; font-size: 13px; color: #333; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fff9f0; }
        .paid { background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .no-data { text-align: center; padding: 50px; color: #888; }
        .delete-btn {
            background: #fff0f0;
            color: #e74c3c;
            border: 1px solid #f5c6c6;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s;
        }
        .delete-btn:hover { background: #e74c3c; color: white; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; }
        .modal-overlay.active { display: flex; }
        .modal-box { background: white; padding: 35px; border-radius: 16px; width: 90%; max-width: 380px; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.3); animation: zoomIn 0.3s ease; }
        .modal-icon { font-size: 48px; margin-bottom: 15px; }
        .modal-box h3 { color: #e74c3c; margin-bottom: 8px; font-size: 20px; }
        .modal-box p { color: #666; font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        .modal-btn-row { display: flex; gap: 10px; }
        .modal-btn-row button { flex: 1; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; font-family: 'Poppins', sans-serif; }
        .btn-confirm { background: #e74c3c; color: white; }
        .btn-confirm:hover { background: #c0392b; }
        .btn-cancel { background: #f0f0f0; color: #333; }
        .btn-cancel:hover { background: #ddd; }
        @keyframes zoomIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        /* Toast */
        .toast { position: fixed; bottom: 30px; right: 30px; padding: 14px 24px; border-radius: 10px; font-size: 14px; font-weight: 600; z-index: 99999; display: none; box-shadow: 0 5px 20px rgba(0,0,0,0.2); }
        .toast.success { background: #27ae60; color: white; }
        .toast.show { display: block; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        @media (max-width: 768px) {
            .content { padding: 15px; }
            .admin-header { padding: 12px 15px; }
            .admin-header h1 { font-size: 15px; }
            .welcome-text { display: none; }
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="admin-header">
    <div class="brand">
        <img src="https://mumbaidarshanbus.co/wp-content/uploads/2024/10/mumbai-darshan-bus-logo.png" alt="Logo">
        <div>
            <h1>Admin Dashboard</h1>
            <p>Booking Management System</p>
        </div>
    </div>
    <div class="header-right">
        <span class="welcome-text">👋 Welcome, <?= htmlspecialchars($_SESSION['admin_user']) ?></span>
        <a href="/admin-logout.php" class="logout-btn">🚪 Logout</a>
    </div>
</div>

<!-- Content -->
<div class="content">

    <?php if ($delete_msg === 'success'): ?>
    <script>
        window.addEventListener('load', function() {
            showToast('✅ Booking deleted successfully!');
        });
    </script>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-box">
            <h3><?= $total_rows ?></h3>
            <p>Total Bookings</p>
        </div>
        <div class="stat-box">
            <h3>&#8377;<?= number_format($total_amount ?? 0) ?></h3>
            <p>Total Revenue</p>
        </div>
        <div class="stat-box">
            <h3><?= $today_data['cnt'] ?? 0 ?></h3>
            <p>Today's Bookings</p>
        </div>
        <div class="stat-box">
            <h3>&#8377;<?= number_format($today_data['total'] ?? 0) ?></h3>
            <p>Today's Revenue</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="filter-bar">
        <form method="GET" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <label>Filter by Date:</label>
            <input type="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
            <button type="submit">🔍 Filter</button>
            <a href="/admin-dashboard.php">✖ Clear</a>
        </form>
    </div>

    <!-- Table -->
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
                        <td style="font-size:11px; color:#888;"><?= $row['order_id'] ?></td>
                        <td><strong><?= $row['name'] ?></strong></td>
                        <td><?= $row['phone'] ?></td>
                        <td><?= $row['journey_date'] ?></td>
                        <td><?= $row['pickup'] ?></td>
                        <td><?= $row['package'] ?></td>
                        <td style="text-align:center;"><?= $row['seats'] ?></td>
                        <td><strong style="color:#d35400;">&#8377;<?= $row['total_amount'] ?></strong></td>
                        <td><span class="paid"><?= $row['payment_status'] ?></span></td>
                        <td style="font-size:12px; color:#888;"><?= date('d M Y h:i A', strtotime($row['created_at'])) ?></td>
                        <td>
                            <button class="delete-btn" onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name']) ?>')">
                                🗑 Delete
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="12" class="no-data">📭 No bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">🗑️</div>
        <h3>Delete Booking?</h3>
        <p id="modal-msg">Are you sure?</p>
        <div class="modal-btn-row">
            <button class="btn-cancel" onclick="closeModal()">✖ Cancel</button>
            <button class="btn-confirm" onclick="submitDelete()">🗑 Yes, Delete</button>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" method="POST">
    <input type="hidden" name="delete_id" id="delete_id_input">
</form>

<!-- Toast -->
<div class="toast success" id="toast"></div>

<script>
let currentDeleteId = null;

function confirmDelete(id, name) {
    currentDeleteId = id;
    document.getElementById('modal-msg').innerText = 'Delete booking of "' + name + '"? This cannot be undone.';
    document.getElementById('deleteModal').classList.add('active');
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('active');
    currentDeleteId = null;
}

function submitDelete() {
    document.getElementById('delete_id_input').value = currentDeleteId;
    document.getElementById('deleteForm').submit();
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

function showToast(msg) {
    const toast = document.getElementById('toast');
    toast.innerText = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}
</script>

</body>
</html>
<?php $conn->close(); ?>
