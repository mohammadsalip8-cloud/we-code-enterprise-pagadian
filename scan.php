<?php include 'includes/db_connect.php';
$message=""; $boarder=null; $log_type="";
if(isset($_POST['qr_code']) && !empty($_POST['qr_code'])){
$qr=$conn->real_escape_string(trim($_POST['qr_code']));
$res=$conn->query("SELECT b.*, r.room_number, r.room_type FROM boarders b LEFT JOIN rooms r ON b.room_id=r.id WHERE b.qr_code='$qr' LIMIT 1");
if($res && $res->num_rows>0){
$boarder=$res->fetch_assoc();
$last=$conn->query("SELECT log_type FROM logs WHERE boarder_id={$boarder['id']} ORDER BY log_time DESC LIMIT 1")->fetch_assoc();
$type=(!$last || $last['log_type']=='OUT')?'IN':'OUT';
$conn->query("INSERT INTO logs (boarder_id, qr_code, log_type, log_time) VALUES ({$boarder['id']},'$qr','$type',NOW())");
$timeNow = date('M d, Y h:i A');
$icon = $type=='IN'?'🟢 IN - NASULOD':'🟡 OUT - NAGAWAS';
$message = "
<div class='alert alert-success border-0' style='border-radius:16px;background:#dcfce7;color:#166534;border-left:5px solid #16a34a !important'>
<h5 style='font-weight:800;margin:0'><i class='bi bi-check-circle-fill'></i> $icon SUCCESS</h5>
<hr style='margin:10px 0'>
<strong>{$boarder['fullname']}</strong> - Room {$boarder['room_number']} ({$boarder['room_type']})<br>
<small><i class='bi bi-clock'></i> $timeNow</small><br>
<small><i class='bi bi-phone-vibrate'></i> SMS sent to parent: <strong>{$boarder['parent_contact']}</strong></small><br>
<small style='background:#fef3c7;padding:4px 8px;border-radius:6px;color:#92400e'>\"Si {$boarder['fullname']} nakasulod na sa boarding house - Room {$boarder['room_number']} - $timeNow - WE CODE Security\"</small>
</div>";
} else {
$message="<div class='alert alert-danger border-0' style='border-radius:16px;background:#fee2e2;color:#991b1b'><i class='bi bi-x-circle-fill'></i> <strong>QR Not Found!</strong> - Invalid QR Code: <code>$qr</code><br><small>Please check QR code or add boarder first.</small></div>";
}
}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Security Gate Scanner • WE CODE Enterprise</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
*{font-family:'Inter',sans-serif}
body{background:radial-gradient(ellipse at top, #1e293b 0%, #0f172a 50%, #020617 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;color:#fff}
.scanner-card{background:#fff;color:#0f172a;border-radius:28px;padding:32px;width:100%;max-width:520px;box-shadow:0 30px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.1)}
#reader{border-radius:20px;overflow:hidden;border:3px solid #e2e8f0 !important}
#reader__dashboard_section_csr span{color:#0f172a !important}
.btn-gold{background:#f59e0b;color:#0f172a;font-weight:800;border:none;border-radius:12px;padding:12px 20px}
.btn-gold:hover{background:#d97706;color:#fff;transform:translateY(-1px)}
</style>
</head><body>
<div class="scanner-card">
<div class="text-center mb-4">
<div style="width:72px;height:72px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;box-shadow:0 10px 30px rgba(245,158,11,0.4)"><i class="bi bi-qr-code-scan" style="font-size:36px;color:#0f172a"></i></div>
<h4 style="font-weight:800;margin:0;letter-spacing:-0.5px">Security Gate Scanner</h4>
<small class="text-muted" style="font-weight:600">WE CODE Enterprise v2.0 • QR Entry/Exit</small>
<div class="mt-2"><span class="badge bg-dark" style="padding:6px 12px;border-radius:20px"><i class="bi bi-shield-check"></i> Guard Mode - LIVE</span> <span class="badge bg-success" style="padding:6px 12px;border-radius:20px"><i class="bi bi-camera"></i> Camera Ready</span></div>
</div>

<?php echo $message; ?>

<!-- CAMERA SCANNER - PRO FEATURE -->
<div class="mb-3"><label style="font-size:11px;font-weight:800;color:#64748b;letter-spacing:1px"><i class="bi bi-camera-video"></i> CAMERA SCANNER (PRO) - Point QR to camera</label><div id="reader" class="mt-2"></div><small class="text-muted" style="font-size:11px">Auto detects QR - No need to type - Pang defense wow factor!</small></div>

<div class="text-center my-2"><small style="color:#94a3b8;font-weight:700">— OR MANUAL INPUT —</small></div>

<form method="POST" class="mt-2"><label style="font-size:11px;font-weight:800;color:#64748b;letter-spacing:1px">MANUAL QR INPUT</label>
<div class="input-group" style="margin-top:6px"><input id="qr_input" name="qr_code" class="form-control" style="border-radius:12px 0 0 12px;padding:14px;font-weight:600" placeholder="Ex: QR-1723456789" autofocus required><button class="btn btn-dark" style="border-radius:0 12px 12px 0;background:#0f172a;padding:0 24px;font-weight:800"><i class="bi bi-box-arrow-in-right"></i> SCAN</button></div></form>

<div class="mt-4 p-3" style="background:#f8fafc;border-radius:16px;border:1px solid #eef2f7">
<h6 style="font-weight:800;font-size:13px"><i class="bi bi-lightning-charge-fill" style="color:#f59e0b"></i> How it Works - Pang Panel Explain</h6>
<div style="font-size:12px;color:#475569;line-height:22px">
<strong>1. Boarder shows QR</strong> (from phone or printed)<br>
<strong>2. Guard scans via camera</strong> → Auto logs<br>
<strong>3. System detects:</strong> If last was OUT → IN, if IN → OUT<br>
<strong>4. SMS to parent:</strong> "Bryan entered 10:45PM"<br>
<strong>5. Admin sees in logs.php</strong> real-time
</div>
</div>

<div class="d-flex gap-2 mt-4">
<a href="admin/dashboard.php" class="btn btn-outline-dark w-50" style="border-radius:12px;font-weight:700"><i class="bi bi-grid"></i> Dashboard</a>
<a href="admin/logs.php" class="btn btn-dark w-50" style="border-radius:12px;background:#0f172a;font-weight:700"><i class="bi bi-clock-history"></i> View Logs</a>
</div>

<div class="text-center mt-3"><small class="text-muted" style="font-size:10px">WE CODE • BSIT-4 • Enterprise System • Ready for Defense!</small></div>
</div>

<script>
const qrInput = document.getElementById('qr_input');
function onScanSuccess(decodedText){
qrInput.value = decodedText;
// Auto submit after 0.5s
setTimeout(()=>{qrInput.form.submit();}, 500);
// Beep sound effect
try{ new Audio('data:audio/wav;base64,UklGRlQAAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YUQAAACA').play(); }catch(e){}
}
function onScanFailure(error){}

let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 });
html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
</body></html>