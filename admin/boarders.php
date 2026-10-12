<?php include '../includes/db_connect.php';
if(isset($_POST['add_boarder'])){
$qr = "QR-".time().rand(10,99);
$name=$conn->real_escape_string($_POST['fullname']);
$contact=$conn->real_escape_string($_POST['contact']);
$parent=$conn->real_escape_string($_POST['parent_contact']);
$room=(int)$_POST['room_id'];
// Check if room exists
$conn->query("INSERT INTO boarders (qr_code, fullname, contact, parent_contact, room_id, date_registered, status) VALUES ('$qr','$name','$contact','$parent',$room, CURDATE(),'Active')");
// Update room to Occupied if full
$conn->query("UPDATE rooms SET status='Occupied' WHERE id=$room");
header("Location: boarders.php?success=1"); exit;
}
if(isset($_GET['delete'])){
$id=(int)$_GET['delete'];
// Get room id before delete to set vacant
$get=$conn->query("SELECT room_id FROM boarders WHERE id=$id")->fetch_assoc();
if($get){ $conn->query("UPDATE rooms SET status='Vacant' WHERE id={$get['room_id']}"); }
$conn->query("DELETE FROM boarders WHERE id=$id");
header("Location: boarders.php?deleted=1"); exit;
}
?>
<!DOCTYPE html><html><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Boarders • WE CODE Enterprise</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
</head><body>
<div class="sidebar"><div><div class="brand"><i class="bi bi-code-slash"></i> WE <span>CODE</span><small>ENTERPRISE v2.0</small></div>
<div class="menu"><a href="dashboard.php"><i class="bi bi-grid-fill"></i> Dashboard</a><a href="rooms.php"><i class="bi bi-door-open-fill"></i> Rooms Management</a><a href="boarders.php" class="active"><i class="bi bi-people-fill"></i> Boarders / Tenants</a><a href="billing.php"><i class="bi bi-receipt-cutoff"></i> Billing & Payments</a><a href="logs.php"><i class="bi bi-qr-code-scan"></i> QR Logs</a></div></div>
<div style="background:rgba(245,158,11,0.12);border-radius:12px;padding:12px;border:1px solid rgba(245,158,11,0.2)"><small style="color:#f59e0b;font-weight:700"><i class="bi bi-shield-check"></i> QR Secured System</small><br><small style="color:#94a3b8;font-size:11px">Auto QR + SMS Alerts</small></div></div>

<div class="main">
<div class="d-flex justify-content-between align-items-center mb-4">
<div><h4 style="font-weight:800;margin:0">Boarders / Tenants</h4><small class="text-muted">QR Enabled • Auto Generate • SMS to Parents • Enterprise</small></div>
<button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-person-plus-fill"></i> Add Boarder + QR</button>
</div>

<?php if(isset($_GET['success'])) echo '<div class="alert alert-success border-0" style="border-radius:12px;background:#dcfce7;color:#166534"><i class="bi bi-qr-code"></i> <strong>Success!</strong> Boarder added with QR Code - Ready to scan at gate!</div>'; ?>
<?php if(isset($_GET['deleted'])) echo '<div class="alert alert-warning border-0" style="border-radius:12px"><i class="bi bi-trash"></i> Boarder removed - Room set to Vacant</div>'; ?>

<div class="card-main">
<div class="table-responsive">
<table class="table table-hover" style="font-size:14px">
<thead><tr><th>QR Code</th><th>Fullname</th><th>Contact / Parent Contact</th><th>Room</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
<?php
$res=$conn->query("SELECT b.*, r.room_number, r.room_type FROM boarders b LEFT JOIN rooms r ON b.room_id=r.id ORDER BY b.id DESC");
if($res && $res->num_rows>0){
while($r=$res->fetch_assoc()){
echo "<tr>
<td><span class='badge bg-dark' style='padding:7px 12px;border-radius:8px;font-size:12px'><i class='bi bi-qr-code'></i> {$r['qr_code']}</span></td>
<td><strong>{$r['fullname']}</strong><br><small class='text-muted'><i class='bi bi-calendar'></i> {$r['date_registered']}</small></td>
<td><i class='bi bi-phone'></i> {$r['contact']}<br><small class='text-muted'><i class='bi bi-phone-vibrate'></i> Parent: {$r['parent_contact']}</small></td>
<td><span class='badge bg-light text-dark border' style='padding:6px 12px'><i class='bi bi-door-open'></i> {$r['room_number']} - {$r['room_type']}</span></td>
<td><span class='badge bg-success'>Active</span></td>
<td>
<a href='?delete={$r['id']}' class='btn btn-sm btn-outline-danger' style='border-radius:8px' onclick='return confirm(\"Delete this boarder? Room will be Vacant.\")'><i class='bi bi-trash'></i></a>
<a href='https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={$r['qr_code']}' target='_blank' class='btn btn-sm btn-dark' style='border-radius:8px' title='View QR'><i class='bi bi-qr-code'></i> QR</a>
</td>
</tr>";
}
} else {
echo "<tr><td colspan='6' class='text-center py-5'><div style='color:#94a3b8'><i class='bi bi-people' style='font-size:32px'></i><br><strong>No boarders yet</strong><br><small>Click Add Boarder + QR to start</small></div></td></tr>";
}
?>
</tbody>
</table>
</div>
</div>

<div class="row g-3 mt-1">
<div class="col-md-6"><div class="card-main" style="background:#0f172a;color:#fff"><h6 style="color:#f59e0b;font-weight:700"><i class="bi bi-lightning-charge-fill"></i> How QR Works - Pang Defense</h6><ol style="font-size:13px;color:#cbd5e1;line-height:26px;margin-top:10px"><li>Boarder registers → System auto generates QR (QR-1744...)</li><li>Boarder shows QR at gate → Guard scans via <code>scan.php</code></li><li>Logs IN/OUT time → SMS to parent: "Bryan entered 9:14PM"</li><li>Admin monitors occupancy real-time</li></ol></div></div>
<div class="col-md-6"><div class="card-main"><h6 style="font-weight:700"><i class="bi bi-phone-vibrate"></i> SMS Feature</h6><small class="text-muted">When boarder scans, parent receives:</small><div class="alert mt-2" style="background:#fef3c7;border:none;border-radius:10px;font-size:13px;color:#92400e"><i class="bi bi-chat-dots"></i> "Si Bryan Salip nakasulod na sa boarding house - Room 101 - 9:14PM - WE CODE Security"</div><small class="text-muted"></small></div></div>
</div>

</div>

<div class="modal fade" id="addModal"><div class="modal-dialog"><form method="POST" class="modal-content" style="border-radius:18px;border:none;box-shadow:0 20px 60px rgba(0,0,0,0.2)"><div class="modal-header" style="border:none;padding:24px 24px 0 24px"><h6 style="font-weight:800;font-size:16px"><i class="bi bi-person-plus"></i> Add Boarder • Auto QR Generation</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body" style="padding:24px">
<input name="fullname" class="form-control mb-3" style="border-radius:10px;padding:12px" placeholder="Fullname (Ex: Bryan Salip)" required>
<input name="contact" class="form-control mb-3" style="border-radius:10px;padding:12px" placeholder="Boarder Contact (09...)" required>
<input name="parent_contact" class="form-control mb-3" style="border-radius:10px;padding:12px" placeholder="Parent Contact for SMS (09...)" required>
<label class="form-label" style="font-size:12px;font-weight:700;color:#64748b">SELECT ROOM</label>
<select name="room_id" class="form-control mb-3" style="border-radius:10px;padding:12px" required>
<?php $rooms=$conn->query("SELECT * FROM rooms WHERE status='Vacant' ORDER BY room_number");
if($rooms && $rooms->num_rows>0){ while($rm=$rooms->fetch_assoc()){ echo "<option value='{$rm['id']}'>{$rm['room_number']} - {$rm['room_type']} - ₱{$rm['monthly_rate']} - {$rm['capacity']} pax</option>"; } } else { echo "<option disabled>No vacant rooms - Add room first</option>"; } ?>
</select>
<div class="alert" style="background:#f1f5f9;border:none;border-radius:10px;font-size:12px"><i class="bi bi-info-circle"></i> QR will be auto-generated: <strong>QR-<?php echo time(); ?></strong></div>
</div><div class="modal-footer" style="border:none;padding:0 24px 24px 24px"><button name="add_boarder" class="btn btn-gold w-100" style="padding:13px;font-size:15px"><i class="bi bi-qr-code"></i> Save + Generate QR Code</button></div></form></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
</body></html>