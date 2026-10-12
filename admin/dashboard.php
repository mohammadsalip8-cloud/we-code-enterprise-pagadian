<?php include '../includes/db_connect.php'; 
$rooms = $conn->query("SELECT COUNT(*) as c FROM rooms")->fetch_assoc()['c'] ?? 0; 
$boarders = $conn->query("SELECT COUNT(*) as c FROM boarders")->fetch_assoc()['c'] ?? 0; 
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WE CODE | Enterprise Boarding System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="sidebar">
<div>
<div class="brand"><i class="bi bi-code-slash"></i> WE <span>CODE</span> <small>BOARDING SYSTEM</small></div>
<div class="menu">
<a href="dashboard.php" class="active"><i class="bi bi-grid-fill"></i> Dashboard</a>
<a href="rooms.php"><i class="bi bi-door-open-fill"></i> Rooms Management</a>
<a href="boarders.php"><i class="bi bi-people-fill"></i> Boarders / Tenants</a>
<a href="billing.php"><i class="bi bi-receipt-cutoff"></i> Billing & Payments</a>
<a href="logs.php"><i class="bi bi-qr-code-scan"></i> QR Logs</a>
</div>
</div>
<div>
<div style="background:rgba(255,255,255,0.06);border-radius:12px;padding:12px">
<small style="color:#64748b">Capstone Project</small><br>
<strong style="font-size:13px">BSIT-4 • 2026</strong><br>
<small style="color:#f59e0b">Security active!</small>
</div>
</div>
</div>

<div class="main">
<div class="d-flex justify-content-between align-items-center mb-4">
<div>
<h4 style="font-weight:800;margin:0">Dashboard Overview</h4>
<small class="text-muted">Boarding House Management • Enterprise Edition</small>
</div>
<div class="d-flex gap-2">
<input class="form-control" style="width:260px;border-radius:10px" placeholder="Search boarders, rooms...">
<a href="../scan.php" class="btn btn-dark" style="border-radius:10px;background:#0f172a"><i class="bi bi-qr-code"></i> Scan QR</a>
</div>
</div>

<div class="row g-3 mb-4">
<div class="col-md-3"><div class="stat"><div class="d-flex justify-content-between"><div><small class="text-muted">Total Rooms</small><h3 style="font-weight:800"><?php echo $rooms; ?></h3><small class="text-success"><i class="bi bi-arrow-up"></i> <?php echo $rooms; ?> available</small></div><div class="icon-box" style="background:#dbeafe;color:#1e40af"><i class="bi bi-door-closed"></i></div></div></div></div>
<div class="col-md-3"><div class="stat"><div class="d-flex justify-content-between"><div><small class="text-muted">Total Boarders</small><h3 style="font-weight:800"><?php echo $boarders > 0 ? $boarders : 0; ?></h3><small class="text-success"><i class="bi bi-arrow-up"></i> Active tenants</small></div><div class="icon-box" style="background:#fef3c7;color:#b45309"><i class="bi bi-people"></i></div></div></div></div>
<div class="col-md-3"><div class="stat"><div class="d-flex justify-content-between"><div><small class="text-muted">Monthly Collection</small><h3 style="font-weight:800">₱68,500</h3><small class="text-muted">5 unpaid</small></div><div class="icon-box" style="background:#dcfce7;color:#15803d"><i class="bi bi-cash-stack"></i></div></div></div></div>
<div class="col-md-3"><div class="stat"><div class="d-flex justify-content-between"><div><small class="text-muted">Occupancy Rate</small><h3 style="font-weight:800">87%</h3><small class="text-success">High occupancy</small></div><div class="icon-box" style="background:#e0e7ff;color:#4338ca"><i class="bi bi-graph-up-arrow"></i></div></div></div></div>
</div>

<div class="row g-3">
<div class="col-md-8"><div class="card-main">
<div class="d-flex justify-content-between mb-3"><h6 style="font-weight:700">Recent Rooms</h6><a href="rooms.php" class="btn btn-sm btn-gold">View All Rooms</a></div>
<table class="table table-hover" style="font-size:14px">
<thead class="text-muted" style="font-size:12px"><tr><th>ROOM NO</th><th>TYPE</th><th>RATE</th><th>STATUS</th></tr></thead>
<tbody>
<?php 
$res=$conn->query("SELECT * FROM rooms ORDER BY id DESC LIMIT 5");
if($res && $res->num_rows>0){
while($row=$res->fetch_assoc()){ 
echo "<tr><td><span class='badge bg-light text-dark border'><i class='bi bi-door-open'></i> {$row['room_number']}</span></td><td>{$row['room_type']}</td><td>₱{$row['monthly_rate']}</td><td><span class='badge bg-success'>{$row['status']}</span></td></tr>"; 
}
} else {
echo "<tr><td colspan='4' class='text-center text-muted py-3'>No rooms yet - Add room in Rooms Management</td></tr>";
}
?>
</tbody>
</table>
</div></div>
<div class="col-md-4">
<div class="card-main" style="background:#0f172a;color:#fff">
<h6 style="font-weight:700;color:#f59e0b"><i class="bi bi-lightning-charge-fill"></i> System Features</h6>
<small style="color:#94a3b8">System Overview - key Features:</small>
<ul style="font-size:13px;margin-top:12px;line-height:24px;color:#cbd5e1">
<li>✓ QR Code Security Entry/Exit</li>
<li>✓ Automated Billing (Rent+Water+Light)</li>
<li>✓ SMS Notification to Parents</li>
<li>✓ GCash Payment Upload</li>
<li>✓ Occupancy & Collection Reports</li>
</ul>
<div class="btn btn-warning w-100 mt-2" style="font-weight:800;border-radius:10px;background:#f59e0b;border:none;color:#0f172a">Built for Safety & Security!</div>
</div>
<div class="card-main mt-3">
<h6 style="font-weight:700">Room Status</h6>
<div class="d-flex justify-content-between mt-2"><small>Occupied</small><small class="fw-bold">24 — 86%</small></div>
<div class="progress" style="height:8px"><div class="progress-bar bg-warning" style="width:86%"></div></div>
<div class="d-flex justify-content-between mt-2"><small>Vacant</small><small class="fw-bold">3 — 11%</small></div>
<div class="progress" style="height:8px"><div class="progress-bar bg-success" style="width:11%"></div></div>
</div>
</div>
</div>
</div>
<script src="../assets/js/main.js"></script>
</body>
</html>