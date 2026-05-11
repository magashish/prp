<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0; }
        .wrapper { max-width:600px; margin:30px auto; background:#fff; border-radius:8px; overflow:hidden; }
        .header { background:#1a3c5e; color:#fff; padding:24px 32px; }
        .header h2 { margin:0; font-size:1.3rem; }
        .body { padding:32px; color:#333; line-height:1.6; }
        .booking-box { background:#e9f3ff; border-left:4px solid #1a3c5e; border-radius:4px; padding:16px; margin:20px 0; }
        .booking-box table { width:100%; border-collapse:collapse; font-size:.9rem; }
        .booking-box td { padding:4px 8px; }
        .booking-box td:first-child { font-weight:bold; width:40%; }
        .total-row td { font-weight:bold; font-size:1rem; color:#1a3c5e; }
        .code-box { background:#fff3cd; border:2px dashed #ffc107; border-radius:6px; text-align:center; padding:20px; margin:20px 0; font-size:1.6rem; font-weight:bold; letter-spacing:4px; }
        .footer { background:#f8f9fa; border-top:1px solid #dee2e6; padding:16px 32px; text-align:center; font-size:.8rem; color:#888; }
        .btn { display:inline-block; background:#0d6efd; color:#fff; text-decoration:none; padding:10px 24px; border-radius:4px; margin-top:12px; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header"><h2>{{ $headerTitle }}</h2></div>
    <div class="body">{{ $slot }}</div>
    <div class="footer">Parking Stall Rental &bull; This is an automated message, please do not reply directly.</div>
</div>
</body>
</html>
