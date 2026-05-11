@extends('layouts.admin')
@section('title', 'Calendar View')

@push('styles')
<style>
    .cal-grid { display:grid; grid-template-columns: repeat(7, 1fr); gap:4px; }
    .cal-header { text-align:center; font-weight:700; font-size:.75rem; text-transform:uppercase; padding:6px 0; color:#6c757d; }
    .cal-cell { min-height:90px; border:1px solid #e9ecef; border-radius:6px; padding:6px; background:#fff; font-size:.75rem; }
    .cal-cell.other-month { background:#f8f9fa; opacity:.5; }
    .cal-day-num { font-weight:700; color:#1a3c5e; margin-bottom:4px; }
    .event-s1 { background:#1a3c5e; color:#fff; border-radius:3px; padding:1px 5px; margin-bottom:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .event-s2 { background:#6f42c1; color:#fff; border-radius:3px; padding:1px 5px; margin-bottom:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .event-open { background:#198754; color:#fff; border-radius:3px; padding:1px 5px; }
    .event-open.warn { background:#fd7e14; }
    .legend-dot { width:12px; height:12px; border-radius:50%; display:inline-block; }
</style>
@endpush

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-secondary btn-sm" id="prevMonth"><i class="bi bi-chevron-left"></i> Prev</button>
            <h6 class="mb-0" id="calTitle">Loading...</h6>
            <button class="btn btn-outline-secondary btn-sm" id="nextMonth">Next <i class="bi bi-chevron-right"></i></button>
        </div>
        <div class="d-flex align-items-center gap-3 small">
            <span><span class="legend-dot me-1" style="background:#1a3c5e"></span>Stall 1 (Reserved)</span>
            <span><span class="legend-dot me-1" style="background:#6f42c1"></span>Stall 2 (Reserved)</span>
            <span><span class="legend-dot me-1" style="background:#198754"></span>Open (Booked Count)</span>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="cal-grid mb-2" id="calHeaders">
            @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d)
            <div class="cal-header">{{ $d }}</div>
            @endforeach
        </div>
        <div class="cal-grid" id="calBody">
            <div class="text-center py-5 col-span-7" style="grid-column:1/8"><span class="spinner-border"></span></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentMonth = new Date().getMonth() + 1;
let currentYear  = new Date().getFullYear();
const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];

async function loadCalendar(month, year) {
    document.getElementById('calTitle').textContent = `${monthNames[month-1]} ${year}`;
    const res = await fetch(`{{ route('admin.calendar.data') }}?month=${month}&year=${year}`);
    const data = await res.json();
    renderCalendar(data.days, month, year);
}

function renderCalendar(days, month, year) {
    const body = document.getElementById('calBody');
    body.innerHTML = '';

    const firstDay = new Date(year, month - 1, 1);
    // Monday-based: 0=Mon...6=Sun
    let startDow = firstDay.getDay(); // 0=Sun,1=Mon,...
    startDow = startDow === 0 ? 6 : startDow - 1;

    const daysInMonth = new Date(year, month, 0).getDate();
    const prevDays    = new Date(year, month - 1, 0).getDate();

    // Previous month filler
    for (let i = startDow - 1; i >= 0; i--) {
        const cell = document.createElement('div');
        cell.className = 'cal-cell other-month';
        cell.innerHTML = `<div class="cal-day-num">${prevDays - i}</div>`;
        body.appendChild(cell);
    }

    // Current month
    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = `${year}-${String(month).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const info    = days[dateStr] || {};
        const cell    = document.createElement('div');
        cell.className = 'cal-cell';
        let html = `<div class="cal-day-num">${d}</div>`;
        if (info.stall1)      html += `<div class="event-s1" title="${info.stall1}">S1 – ${info.stall1.split(' ')[0]}</div>`;
        if (info.stall2)      html += `<div class="event-s2" title="${info.stall2}">S2 – ${info.stall2.split(' ')[0]}</div>`;
        const open = info.open_booked || 0;
        const cls  = open >= 60 ? 'warn' : '';
        html += `<div class="event-open ${cls}">Open – ${open}</div>`;
        cell.innerHTML = html;
        body.appendChild(cell);
    }

    // Next month filler
    const total = startDow + daysInMonth;
    const trailing = total % 7 === 0 ? 0 : 7 - (total % 7);
    for (let d = 1; d <= trailing; d++) {
        const cell = document.createElement('div');
        cell.className = 'cal-cell other-month';
        cell.innerHTML = `<div class="cal-day-num">${d}</div>`;
        body.appendChild(cell);
    }
}

document.getElementById('prevMonth').addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 1) { currentMonth = 12; currentYear--; }
    loadCalendar(currentMonth, currentYear);
});
document.getElementById('nextMonth').addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 12) { currentMonth = 1; currentYear++; }
    loadCalendar(currentMonth, currentYear);
});

loadCalendar(currentMonth, currentYear);
</script>
@endpush
