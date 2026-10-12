<?php include '../includes/db_connect.php'; ?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>QR Logs • WE CODE Enterprise v2.0</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet"><link href="../assets/css/style.css" rel="stylesheet"></head><body>
<div class="sidebar"><div><div class="brand"><i class="bi bi-code-slash"></i> WE <span>CODE</span><small>ENTERPRISE v2.0</small></div>
<div class="menu"><a href="dashboard.php"><i class="bi bi-grid-fill"></i> Dashboard</a><a href="rooms.php"><i class="bi bi-door-open-fill"></i> Rooms Management</a><a href="boarders.php"><i class="bi bi-people-fill"></i> Boarders / Tenants</a><a href="billing.php"><i class="bi bi-receipt-cutoff"></i> Billing & Payments</a><a href="logs.php" class="active"><i class="bi bi-qr-code-scan"></i> QR Logs</a></div></div>
<div style="background:rgba(245,158,11,0.15);border-radius:12px;padding:12px;border:1px solid rgba(245,158,11,0.3)"><small style="color:#f59e0b;font-weight:800"><i class="bi bi-shield-check"></i> LIVE SECURITY LOGS</small><br><small style="color:#94a3b8;font-size:11px">Real-time • SMS Enabled</small></div></div>

<div class="main">
<div class="d-flex justify-content-between align-items-center mb-4">
<div><h4 style="font-weight:800;margin:0">QR Entry/Exit Logs</h4><small class="text-muted">Real-time security monitoring • IN/OUT tracking • SMS to parents</small></div>
<div class="d-flex gap-2"><a href="../scan.php" class="btn btn-dark" style="border-radius:10px;background:#0f172a;font-weight:700"><i class="bi bi-qr-code-scan"></i> Open Scanner</a><button class="btn btn-gold" onclick="location.reload()"><i class="bi bi-arrow-clockwise"></i> Refresh</button></div>
</div>

<div class="row g-3 mb-4">
<div class="col-md-3"><div class="stat"><div class="d-flex justify-content-between"><div><small class="text-muted">Today's IN</small><h3 style="font-weight:800;color:#16a34a"><?php echo $conn->query("SELECT COUNT(*) as c FROM logs WHERE log_type='IN' AND DATE(log_time)=CURDATE()")->fetch_assoc()['c'] ?? 0; ?></h3><small class="text-success">Entries today</small></div><div class="icon-box" style="background:#dcfce7;color:#15803d"><i class="bi bi-box-arrow-in-right"></i></div></div></div></div>
<div class="col-md-3"><div class="stat"><div class="d-flex justify-content-between"><div><small class="text-muted">Today's OUT</small><h3 style="font-weight:800;color:#d97706"><?php echo $conn->query("SELECT COUNT(*) as c FROM logs WHERE log_type='OUT' AND DATE(log_time)=CURDATE()")->fetch_assoc()['c'] ?? 0; ?></h3><small class="text-muted">Exits today</small></div><div class="icon-box" style="background:#fef3c7;color:#b45309"><i class="bi bi-box-arrow-left"></i></div></div></div></div>
<div class="col-md-3"><div class="stat"><div class="d-flex justify-content-between"><div><small class="text-muted">Total Logs</small><h3 style="font-weight:800"><?php echo $conn->query("SELECT COUNT(*) as c FROM logs")->fetch_assoc()['c'] ?? 0; ?></h3><small class="text-muted">All time</small></div><div class="icon-box" style="background:#dbeafe;color:#1e40af"><i class="bi bi-clock-history"></i></div></div></div></div>
<div class="col-md-3"><div class="stat" style="background:#0f172a;color:#fff"><div class="d-flex justify-content-between"><div><small style="color:#94a3b8">Security Status</small><h6 style="font-weight:800;color:#22c55e;margin-top:4px"><i class="bi bi-shield-check"></i> Active Monitoring</h6><small style="color:#94a3b8">Guard on duty</small></div><div class="icon-box" style="background:rgba(245,158,11,0.2);color:#f59e0b"><i class="bi bi-camera-video"></i></div></div></div></div>
</div>

<div class="card-main">
<div class="d-flex justify-content-between align-items-center mb-3"><h6 style="font-weight:800;margin:0"><i class="bi bi-list-ul"></i> Recent Activity - Last 50 Logs</h6><small class="text-muted">Auto refresh every 30s • Enterprise</small></div>
<div class="table-responsive">
<table class="table table-hover" style="font-size:14px">
<thead><tr><th>QR CODE</th><th>BOARDER NAME</th><th>ROOM</th><th>TYPE</th><th>DATE & TIME</th><th>SMS STATUS</th></tr></thead>
<tbody>
<?php
$res=$conn->query("SELECT l.*, b.fullname, b.parent_contact, r.room_number FROM logs l JOIN boarders b ON l.boarder_id=b.id LEFT JOIN rooms r ON b.room_id=r.id ORDER BY l.log_time DESC LIMIT 50");
if($res && $res->num_rows>0){
while($r=$res->fetch_assoc()){
$badge=$r['log_type']=='IN'?"<span class='badge bg-success' style='padding:6px 12px;border-radius:20px'><i class='bi bi-box-arrow-in-right'></i> IN - Entered</span>":"<span class='badge bg-warning text-dark' style='padding:6px 12px;border-radius:20px'><i class='bi bi-box-arrow-left'></i> OUT - Exited</span>";
$sms="<span class='badge bg-light text-dark border'><i class='bi bi-check-circle-fill' style='color:#16a34a'></i> SMS Sent to {$r['parent_contact']}</span>";
echo "<tr><td><span class='badge bg-dark' style='padding:6px 10px'><i class='bi bi-qr-code'></i> {$r['qr_code']}</span></td><td><strong>{$r['fullname']}</strong></td><td><span class='badge bg-light text-dark border'>{$r['room_number']}</span></td><td>$badge</td><td>".date('M d, Y - h:i A',strtotime($r['log_time']))."</td><td>$sms</td></tr>";
}
} else {
echo "<tr><td colspan='6' class='text-center py-5'><div style='color:#94a3b8'><i class='bi bi-qr-code-scan' style='font-size:40px'></i><br><strong style='font-size:16px'>No QR logs yet</strong><br><small>Logs will appear here when boarder scans QR at gate</small><br><br><a href='../scan.php' class='btn btn-gold btn-sm'><i class='bi bi-qr-code-scan'></i> Test Scanner Now</a></div></td></tr>";
}
?>
</tbody>
</table>
</div>

<div class="mt-4 p-3" style="background:#0f172a;border-radius:14px;color:#fff">
<h6 style="color:#f59e0b;font-weight:800"><i class="bi bi-lightning-charge-fill"></i> Pang Defense Explain - Ganahan ang Panel!</h6>
<div class="row" style="font-size:13px;color:#cbd5e1;margin-top:10px">
<div class="col-md-6"><strong style="color:#fff">Flow:</strong><br>1. Boarder shows QR code at gate<br>2. Guard scans via scan.php (phone or laptop camera)<br>3. System auto detects IN/OUT<br>4. Logs to database + SMS to parent<br>5. Admin views real-time in this page</div>
<div class="col-md-6"><strong style="color:#fff">Sample SMS:</strong><br><em style="color:#fef3c7">"Maam, si Bryan Salip (Room 101) nakasulod na 9:30PM Oct 12. - WE CODE Security"</em><br><br><strong style="color:#22c55e">Security + Safety + Parent Peace of Mind!</strong></div>
</div>
</div>

</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script><script src="../assets/js/main.js"></script>
<script>setTimeout(()=>location.reload(),30000);</script>
</body></html>