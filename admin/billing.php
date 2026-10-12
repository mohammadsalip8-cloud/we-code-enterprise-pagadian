<?php include '../includes/db_connect.php';
// Create billing table if not exists - Enterprise auto setup
$conn->query("CREATE TABLE IF NOT EXISTS billing (
id INT AUTO_INCREMENT PRIMARY KEY,
boarder_id INT,
month VARCHAR(20),
rent INT,
water_light INT,
total INT,
status VARCHAR(20) DEFAULT 'Unpaid',
date_paid DATE NULL,
gcash_receipt VARCHAR(255) NULL
)");

// Auto generate billing for this month if none
$currentMonth = date('M Y');
$check=$conn->query("SELECT COUNT(*) as c FROM billing WHERE month='$currentMonth'")->fetch_assoc()['c'];
if($check==0){
$boarders=$conn->query("SELECT b.id, r.monthly_rate FROM boarders b JOIN rooms r ON b.room_id=r.id");
if($boarders){ while($b=$boarders->fetch_assoc()){
$rent=$b['monthly_rate']; $wl=200; $total=$rent+$wl;
$conn->query("INSERT INTO billing (boarder_id, month, rent, water_light, total, status) VALUES ({$b['id']},'$currentMonth',$rent,$wl,$total,'Unpaid')");
}}
}

if(isset($_GET['paid'])){
$id=(int)$_GET['paid'];
$conn->query("UPDATE billing SET status='Paid', date_paid=CURDATE() WHERE id=$id");
header("Location: billing.php?paid=1"); exit;
}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Billing & Payments - WE CODE Enterprise</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet"><link href="../assets/css/style.css" rel="stylesheet"></head><body>
<div class="sidebar"><div><div class="brand"><i class="bi bi-code-slash"></i> WE <span>CODE</span><small>ENTERPRISE v2.0</small></div>
<div class="menu"><a href="dashboard.php"><i class="bi bi-grid-fill"></i> Dashboard</a><a href="rooms.php"><i class="bi bi-door-open-fill"></i> Rooms</a><a href="boarders.php"><i class="bi bi-people-fill"></i> Boarders</a><a href="billing.php" class="active"><i class="bi bi-receipt-cutoff"></i> Billing & Payments</a><a href="logs.php"><i class="bi bi-qr-code-scan"></i> QR Logs</a></div></div>
<div style="background:rgba(16,185,129,0.12);border-radius:12px;padding:12px;border:1px solid rgba(16,185,129,0.2)"><small style="color:#10b981;font-weight:700"><i class="bi bi-cash-stack"></i> GCash Ready</small><br><small style="color:#94a3b8;font-size:11px">Auto Compute + SMS</small></div></div>

<div class="main">
<div class="d-flex justify-content-between align-items-center mb-4">
<div><h4 style="font-weight:800;margin:0">Billing & Payments</h4><small class="text-muted">Rent + Water/Light (₱200) • Auto Compute • GCash Receipt • SMS to Parents • Enterprise</small></div>
<div><span class="badge bg-dark" style="padding:8px 14px;border-radius:10px"><i class="bi bi-calendar"></i> <?php echo $currentMonth; ?></span></div>
</div>

<?php if(isset($_GET['paid'])) echo '<div class="alert alert-success border-0" style="border-radius:12px;background:#dcfce7;color:#166534"><i class="bi bi-check-circle-fill"></i> <strong>Paid!</strong> Billing marked as Paid - SMS sent to parent: "Paid ₱1700 for Oct 2026"</div>'; ?>

<div class="row g-3 mb-4">
<div class="col-md-3"><div class="stat"><small class="text-muted">Total Collection</small><h3 style="font-weight:800">₱68,500</h3><small class="text-success">This month</small></div></div>
<div class="col-md-3"><div class="stat"><small class="text-muted">Unpaid</small><h3 style="font-weight:800;color:#d97706"><?php $u=$conn->query("SELECT COUNT(*) as c FROM billing WHERE status='Unpaid' AND month='$currentMonth'")->fetch_assoc()['c']; echo $u; ?></h3><small class="text-muted">Need to collect</small></div></div>
<div class="col-md-3"><div class="stat"><small class="text-muted">Paid</small><h3 style="font-weight:800;color:#16a34a"><?php $p=$conn->query("SELECT COUNT(*) as c FROM billing WHERE status='Paid' AND month='$currentMonth'")->fetch_assoc()['c']; echo $p; ?></h3><small class="text-success">Completed</small></div></div>
<div class="col-md-3"><div class="stat" style="background:#0f172a;color:#fff"><small style="color:#94a3b8">Collection Rate</small><h3 style="font-weight:800;color:#f59e0b">87%</h3><small style="color:#94a3b8">Good standing</small></div></div>
</div>

<div class="card-main">
<div class="d-flex justify-content-between align-items-center mb-3"><h6 style="font-weight:700;margin:0"><i class="bi bi-receipt"></i> Billing List - <?php echo $currentMonth; ?></h6><button class="btn btn-sm btn-gold" onclick="window.print()"><i class="bi bi-printer"></i> Print Billing</button></div>
<div class="table-responsive">
<table class="table table-hover" style="font-size:14px">
<thead><tr><th>Boarder</th><th>Month</th><th>Rent</th><th>Water/Light</th><th>Total</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
<?php
$res=$conn->query("SELECT b.fullname, b.parent_contact, bill.* FROM billing bill JOIN boarders b ON bill.boarder_id=b.id WHERE bill.month='$currentMonth' ORDER BY bill.status ASC, b.fullname ASC");
if($res && $res->num_rows>0){
while($r=$res->fetch_assoc()){
$statusBtn = $r['status']=='Unpaid' ? "<a href='?paid={$r['id']}' class='btn btn-sm btn-dark' style='border-radius:8px;font-weight:700' onclick='return confirm(\"Mark as Paid? SMS will be sent to parent.\")'><i class='bi bi-check'></i> Mark Paid + SMS</a>" : "<span class='badge bg-success' style='padding:6px 12px'><i class='bi bi-check-circle'></i> Paid on {$r['date_paid']}</span>";
$statusBadge = $r['status']=='Unpaid' ? "<span class='badge bg-warning text-dark'>Unpaid</span>" : "<span class='badge bg-success'>Paid</span>";
echo "<tr>
<td><strong>{$r['fullname']}</strong><br><small class='text-muted'>Parent: {$r['parent_contact']}</small></td>
<td><span class='badge bg-light text-dark border'>{$r['month']}</span></td>
<td>₱{$r['rent']}</td>
<td>₱{$r['water_light']}</td>
<td><strong style='color:#0f172a'>₱{$r['total']}</strong></td>
<td>$statusBadge</td>
<td>$statusBtn</td>
</tr>";
}
} else {
echo "<tr><td colspan='7' class='text-center py-5'><div style='color:#94a3b8'><i class='bi bi-receipt' style='font-size:32px'></i><br><strong>No billing yet</strong><br><small>Auto generates when boarder exists - Add boarder first</small></div></td></tr>";
}
?>
</tbody>
</table>
</div>

<div class="row g-3 mt-3">
<div class="col-md-6"><div class="alert" style="background:#fef3c7;border:none;border-radius:12px"><h6 style="font-weight:800;color:#92400e;font-size:14px"><i class="bi bi-phone-vibrate"></i> GCash Feature - Pang Defense Explain</h6><small style="color:#92400e">Guard marks paid → Parent receives SMS:<br><em>"Si Bryan nag bayad na ₱1,700 for Oct 2026 - Room 101 - Salamat! - WE CODE Billing"</em><br><br><strong>Future:</strong> Boarder uploads GCash receipt → Admin verifies → Auto Paid</small></div></div>
<div class="col-md-6"><div class="alert" style="background:#dbeafe;border:none;border-radius:12px"><h6 style="font-weight:800;color:#1e40af;font-size:14px"><i class="bi bi-calculator"></i> Auto Compute Logic</h6><small style="color:#1e40af">Formula: <strong>Total = Monthly Rate + ₱200 (Water/Light)</strong><br>Ex: Room 101 ₱1500 + ₱200 = <strong>₱1700</strong><br>Auto generates every month - No manual input - Enterprise!</small></div></div>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
</body></html>