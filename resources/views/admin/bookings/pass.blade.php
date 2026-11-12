<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Pass – {{ $booking->booking_id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #fff; color: #000; }

        .pass-wrapper { max-width: 680px; margin: 30px auto; padding: 20px; }

        .pass-header { background: #082B4D; color: #fff; padding: 18px 24px; border-radius: 8px 8px 0 0; display: flex; justify-content: space-between; align-items: center; }
        .pass-header h1 { font-size: 20px; letter-spacing: 1px; }
        .pass-header .logo-text { font-size: 13px; opacity: .85; }

        .pass-body { border: 2px solid #082B4D; border-top: none; border-radius: 0 0 8px 8px; padding: 24px; }

        .notice { background: #fff3cd; border: 2px solid #ffc107; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; font-size: 13px; font-weight: bold; text-align: center; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; border: 1px solid #ccc; border-radius: 6px; overflow: hidden; margin-bottom: 20px; }
        .info-cell { padding: 10px 14px; border-bottom: 1px solid #e0e0e0; }
        .info-cell:nth-last-child(-n+2) { border-bottom: none; }
        .info-cell label { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: .5px; color: #666; margin-bottom: 2px; }
        .info-cell .val { font-size: 15px; font-weight: bold; }
        .info-cell.full { grid-column: 1 / -1; }

        .stall-box { background: #082B4D; color: #fff; border-radius: 6px; padding: 14px 20px; text-align: center; margin-bottom: 20px; }
        .stall-box .label { font-size: 11px; letter-spacing: 1px; opacity: .8; }
        .stall-box .number { font-size: 48px; font-weight: bold; line-height: 1.1; }
        .stall-box .sub { font-size: 13px; opacity: .85; }

        .rules { font-size: 12px; color: #333; line-height: 1.6; border-top: 1px solid #ddd; padding-top: 16px; }
        .rules ul { padding-left: 18px; }

        .footer { margin-top: 20px; text-align: center; font-size: 11px; color: #666; }

        .print-btn { display: block; margin: 20px auto 0; padding: 10px 32px; background: #082B4D; color: #fff; border: none; border-radius: 6px; font-size: 15px; cursor: pointer; }

        @media print {
            .print-btn { display: none; }
            .pass-wrapper { margin: 0; padding: 10px; }
            body { background: #fff; }
        }
    </style>
</head>
<body>
<div class="pass-wrapper">

    <div class="pass-header">
        <div>
            <h1>PARKING PASS</h1>
            <div class="logo-text">BL Rentals · Discovery Bay Condo Parking</div>
        </div>
        <div style="text-align:right; font-size:13px;">
            <div style="opacity:.7;">Booking #</div>
            <div style="font-size:17px; font-weight:bold; letter-spacing:1px;">{{ $booking->booking_id }}</div>
        </div>
    </div>

    <div class="pass-body">

        <div class="notice">
            ⚠ PRINT AND PLACE VISIBLY ON VEHICLE DASHBOARD
        </div>

        @if($booking->stall_number)
        <div class="stall-box">
            <div class="label">ASSIGNED STALL NUMBER</div>
            <div class="number">{{ $booking->stall_number }}</div>
            <div class="sub">Reserved · No parking code required</div>
        </div>
        @endif

        <div class="info-grid">
            <div class="info-cell">
                <label>Guest Name</label>
                <div class="val">{{ $booking->full_name }}</div>
            </div>
            <div class="info-cell">
                <label>Stall Type</label>
                <div class="val">{{ ucwords(str_replace('_', ' ', $booking->stall_type)) }}</div>
            </div>
            <div class="info-cell">
                <label>Check-In Date</label>
                <div class="val">{{ $booking->check_in_date->format('m/d/Y') }}</div>
            </div>
            <div class="info-cell">
                <label>Check-Out Date</label>
                <div class="val">{{ $booking->check_out_date->format('m/d/Y') }}</div>
            </div>
            <div class="info-cell">
                <label>Check-In Time</label>
                <div class="val">12:00 AM</div>
            </div>
            <div class="info-cell">
                <label>Check-Out By</label>
                <div class="val">11:59 PM</div>
            </div>
            <div class="info-cell full">
                <label>Location</label>
                <div class="val">Discovery Bay Condo Parking · 1778 Ala Moana Blvd., Honolulu, HI</div>
            </div>
            <div class="info-cell full">
                <label>Entrance</label>
                <div class="val" style="font-size:13px; font-weight:normal;">Accessible via Hobron Lane or the back street off Kaio'o Drive.</div>
            </div>
        </div>

        <div class="rules">
            <strong>Important:</strong>
            <ul>
                <li>This pass must be printed and placed visibly on the vehicle dashboard at all times.</li>
                <li>No parking code is required for reserved stall holders.</li>
                <li>Parking is only valid for the dates shown above.</li>
                <li>Questions? Contact us at blrentals@gmail.com</li>
            </ul>
        </div>

        <div class="footer">
            Issued by BL Rentals · blparkingrentals.com · This pass is non-transferable.
        </div>
    </div>

    <button class="print-btn" onclick="window.print()">🖨 Print This Pass</button>
</div>
</body>
</html>
