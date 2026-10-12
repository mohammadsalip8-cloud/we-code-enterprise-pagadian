<?php include '../includes/db_connect.php';
if(isset($_POST['add_room'])){
$num=$_POST['room_number']; $type=$_POST['room_type']; $cap=$_POST['capacity']; $rate=$_POST['rate'];
$conn->query("INSERT INTO rooms (room_number, room_type, capacity, monthly_rate, status) VALUES ('$num','$type','$cap','$rate','Vacant')");
header("Location: rooms.php"); exit;
}
if(isset($_GET['delete'])){ $id=$_GET['delete']; $conn->query("DELETE FROM rooms WHERE id=$id"); header("Location: rooms.php"); exit; }
?>
<!DOCTYPE html>
<html><head>
<meta charset="UTF-8">
<title>Rooms - WE CODE Enterprise</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
body{font-family:'Inter',sans-serif;background:#f8fafc;margin:0}
.sidebar{width:270px;height:100vh;background:#0f172a;position:fixed;left:0;top:0;padding:24px;color:#fff}
.brand{font-weight:800;font-size:24px}.brand span{color:#f59e0b}.brand small{color:#64748b;font-size:10px;letter-spacing:3px;display:block;font-weight:400}
.menu{margin-top:40px}.menu a{color:#94a3b8;text-decoration:none;display:flex;gap:14px;padding:12px 16px;border-radius:12px;margin-bottom:4px;font-size:14px}
.menu a:hover{background:rgba(255,255,255,0.07);color:#fff}.menu a.active{background:#f59e0b;color:#0f172a;font-weight:700}
.main{margin-left:270px;padding:32px}
.card-room{background:#fff;border-radius:18px;padding:20px;border:1px solid #eef2f7;box-shadow:0 4px 20px rgba(0,0,0,0.03);transition:0.3s}
.card-room:hover{transform:translateY(-4px);box-shadow:0 10px 30px rgba(0,0,0,0.1)}
</style>
</head><body>
<div class="sidebar">
<div><div class="brand"><i class="bi bi-code-slash"></i> WE <span>CODE</span> <small>BOARDING SYSTEM</small></div>
<div class="menu">
<a href="dashboard.php"><i class="bi bi-grid-fill"></i> Dashboard</a>
<a href="rooms.php" class="active"><i class="bi bi-door-open-fill"></i> Rooms Management</a>
<a href="boarders.php"><i class="bi bi-people-fill"></i> Boarders</a>
<a href="billing.php"><i class="bi bi-receipt-cutoff"></i> Billing</a>
<a href="logs.php"><i class="bi bi-qr-code-scan"></i> QR Logs</a>
</div></div>
</div>
<div class="main">
<div class="d-flex justify-content-between align-items-center mb-4">
<h4 style="font-weight:800">Rooms Management - Enterprise</h4>
<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addModal" style="font-weight:700;border-radius:10px;background:#f59e0b;border:none;color:#0f172a"><i class="bi bi-plus-circle"></i> Add New Room</button>
</div>
<div class="row g-3">
<?php $res=$conn->query("SELECT * FROM rooms ORDER BY id DESC");
while($r=$res->fetch_assoc()){
echo "<div class='col-md-4'><div class='card-room'>
<div class='d-flex justify-content-between align-items-center'><h5 style='font-weight:800'>{$r['room_number']}</h5><span class='badge bg-success'>{$r['status']}</span></div>
<small class='text-muted'><i class='bi bi-house'></i> {$r['room_type']} • Capacity: {$r['capacity']} persons</small>
<div class='mt-3'><h4 style='font-weight:800'>₱{$r['monthly_rate']}<small style='font-size:12px;font-weight:400'>/month</small></h4></div>
<div class='d-flex gap-2 mt-3'>
<a href='?delete={$r['id']}' onclick='return confirm(\"Delete this room?\")' class='btn btn-sm btn-outline-danger w-100' style='border-radius:8px'><i class='bi bi-trash'></i> Delete</a>
</div>
</div></div>";
} ?>
</div>
</div>

<div class="modal fade" id="addModal"><div class="modal-dialog"><form method="POST" class="modal-content" style="border-radius:16px"><div class="modal-header"><h6 style="font-weight:800">Add New Room - Enterprise</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
<input name="room_number" class="form-control mb-2" placeholder="Room No. e.g Room 103" required>
<select name="room_type" class="form-control mb-2"><option>Bed Spacer</option><option>Single Room</option><option>Double Room</option><option>Master Bedroom</option></select>
<input name="capacity" type="number" class="form-control mb-2" placeholder="Capacity (e.g. 4)" required>
<input name="rate" type="number" class="form-control mb-2" placeholder="Monthly Rate (e.g. 1500)" required>
</div><div class="modal-footer"><button name="add_room" class="btn btn-warning w-100" style="font-weight:800;border-radius:10px;background:#f59e0b;border:none;color:#0f172a">Save Room</button></div></form></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body></html>