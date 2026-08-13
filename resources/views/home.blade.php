@extends('layouts.app')

@section('title', 'Book a Parking Stall')

@section('content')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2">BL Rentals Stall Booking</h1>
        <p class="lead mb-0">Fast, easy, no account required. Book your parking stall online in minutes.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center g-4">
        <!-- Booking Form -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Check Availability</h5>
                </div>
                <div class="card-body p-4">
                    <form id="availabilityForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Select Stall Type</label>
                            <div class="d-flex gap-3">
                                <div class="form-check form-check-inline border rounded p-3 flex-fill" style="cursor:pointer">
                                    <input class="form-check-input" type="radio" name="stall_type" id="stallReserved" value="reserved" required>
                                    <label class="form-check-label w-100" for="stallReserved" style="cursor:pointer">
                                        <strong>Reserved Stall</strong><br>
                                        <span class="price-badge">$45/day</span>
                                        <small class="d-block text-muted mt-1">Limited to 2 stalls. Confirmation printout sent.</small>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline border rounded p-3 flex-fill" style="cursor:pointer">
                                    <input class="form-check-input" type="radio" name="stall_type" id="stallNonReserved" value="non_reserved" required>
                                    <label class="form-check-label w-100" for="stallNonReserved" style="cursor:pointer">
                                        <strong>Non-Reserved Stall</strong><br>
                                        <span class="price-badge" style="background:#02A38A">$35/day</span>
                                        <small class="d-block text-muted mt-1">Parking pass with code required.</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="check_in_date" class="form-label">Check-in Date</label>
                                <input type="date" class="form-control" id="check_in_date" name="check_in_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="check_out_date" class="form-label">Check-out Date</label>
                                <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
                            </div>
                        </div>

                        <div id="availabilityResult" class="d-none"></div>

                        <button type="submit" class="btn btn-primary w-100" id="checkBtn">
                            <i class="bi bi-search me-1"></i> Check Availability
                        </button>
                    </form>

                    <!-- Checkout form (shown after availability check) -->
                    <div id="checkoutSection" class="d-none mt-4">
                        <hr>
                        <h6 class="fw-bold mb-3 bg-primary text-white p-3 rounded d-flex align-items-center">
                            <i class="bi bi-person-fill me-2"></i> Complete your Information & Book
                        </h6>
                        <form action="{{ route('booking.store-session') }}" method="POST" id="checkoutForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone_number" class="form-control" required pattern="[0-9\-\+\(\)\s]+" inputmode="tel" oninput="this.value=this.value.replace(/[^0-9\-\+\(\)\s]/g,'')">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>

                            <div id="refundPlanSection" class="mt-3 p-3 border rounded bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="refund_plan" value="1" id="refundPlan">
                                    <label class="form-check-label" for="refundPlan">
                                        <strong>Add Refund Protection Plan &ndash; $25.00</strong>
                                        <small class="d-block text-muted">Receive a full refund if you need to cancel.</small>
                                    </label>
                                </div>
                            </div>

                            <div id="totalDisplay" class="booking-summary mt-3"></div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" value="1" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="https://blparkingrentals.com/terms-condition/" target="_blank">Terms &amp; Conditions</a>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-success w-100 mt-3">
                                <i class="bi bi-lock-fill me-1"></i> Continue to Payment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-info-circle-fill text-primary me-2"></i>What You Get</h6>
                    <ul class="small mb-0">
                        <li><strong>Reserved Stall:</strong> Dedicated spot. Print confirmation for dashboard.</li>
                        <li class="mt-1"><strong>Non-Reserved:</strong> Flexible space. Must show parking pass with access code at garage entry.</li>
                    </ul>
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-clock-fill text-warning me-2"></i>Booking Deadlines</h6>
                    <ul class="small mb-0">
                        <li>No same-day bookings</li>
                        <li>No next-day bookings after 4:00 PM</li>
                        <li>Weekend bookings close Friday 12:00 PM</li>
                        <li>No Sunday bookings on Saturday</li>
                    </ul>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-currency-dollar text-success me-2"></i>Pricing</h6>
                    <table class="table table-sm mb-0 small">
                        <tr><td>Reserved Stall</td><td class="text-end fw-bold">$45/day</td></tr>
                        <tr><td>Non-Reserved Stall</td><td class="text-end fw-bold">$35/day</td></tr>
                        <tr><td>Refund Protection</td><td class="text-end">+$25.00</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Who, What and Where Section -->
<div class="bg-white py-5 border-top">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" style="color:#082B4D;">Who, What &amp; Where</h2>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4">
                    <div class="mb-3" style="font-size:2.5rem; color:#0f4c81;"><i class="bi bi-people-fill"></i></div>
                    <h5 class="fw-bold">Who Is This For?</h5>
                    <p class="text-muted">Anyone needing convenient, affordable parking near our facility. No account required &mdash; book in minutes as a guest. International visitors welcome.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="mb-3" style="font-size:2.5rem; color:#02A38A;"><i class="bi bi-car-front-fill"></i></div>
                    <h5 class="fw-bold">What Do You Get?</h5>
                    <p class="text-muted">Choose a <strong>Reserved Stall</strong> ($45/day) for a guaranteed dedicated spot, or a <strong>Non-Reserved Stall</strong> ($35/day) for flexible open parking with an access code. All bookings include a confirmation email.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="mb-3" style="font-size:2.5rem; color:#dc3545;"><i class="bi bi-geo-alt-fill"></i></div>
                    <h5 class="fw-bold">Where Is It?</h5>
                    <p class="text-muted">Our parking garage is conveniently located near the venue. Reserved stall holders park in designated spots. Non-reserved holders present their parking pass with access code at the garage entrance.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function hawaiiDateString(offsetDays = 0) {
    const d = new Date(new Date().toLocaleString('en-US', { timeZone: 'Pacific/Honolulu' }));
    d.setDate(d.getDate() + offsetDays);
    return d.getFullYear() + '-' +
        String(d.getMonth() + 1).padStart(2, '0') + '-' +
        String(d.getDate()).padStart(2, '0');
}

const minDate = hawaiiDateString(1);
const checkInEl  = document.getElementById('check_in_date');
const checkOutEl = document.getElementById('check_out_date');
checkInEl.min  = minDate;
checkInEl.value = minDate;
checkOutEl.min  = minDate;

checkInEl.addEventListener('change', function () {
    if (!this.value) return;
    const [y, m, d] = this.value.split('-').map(Number);
    const cin = new Date(y, m - 1, d + 1);
    const newMin = cin.getFullYear() + '-' + String(cin.getMonth() + 1).padStart(2, '0') + '-' + String(cin.getDate()).padStart(2, '0');
    checkOutEl.min = newMin;
    if (checkOutEl.value && checkOutEl.value <= this.value) {
        checkOutEl.value = newMin;
    }
    resetAvailability();
});

checkOutEl.addEventListener('change', resetAvailability);
document.querySelectorAll('input[name="stall_type"]').forEach(el => el.addEventListener('change', resetAvailability));

function resetAvailability() {
    availData = null;
    const rd = document.getElementById('availabilityResult');
    rd.classList.add('d-none');
    rd.innerHTML = '';
    document.getElementById('checkoutSection').classList.add('d-none');
    document.getElementById('totalDisplay').innerHTML = '';
}

const refundPlanCost = 25;
let availData = null;

document.getElementById('availabilityForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const availResultDiv = document.getElementById('availabilityResult');
    const cin  = checkInEl.value;
    const cout = checkOutEl.value;
    if (!cin || cin < minDate) {
        availResultDiv.classList.remove('d-none');
        availResultDiv.innerHTML = `<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Check-in date must be at least tomorrow (${minDate}).</div>`;
        checkInEl.value = minDate;
        return;
    }
    if (!cout || cout <= cin) {
        availResultDiv.classList.remove('d-none');
        availResultDiv.innerHTML = `<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Check-out date must be after the check-in date.</div>`;
        return;
    }

    const btn = document.getElementById('checkBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Checking...';

    const fd = new FormData(this);
    const res = await fetch('{{ route('booking.check') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: fd,
    });
    const data = await res.json();
    availData = data;

    availResultDiv.classList.remove('d-none');

    if (data.available) {
        availResultDiv.innerHTML = `<div class="alert alert-success"><i class="bi bi-check-circle-fill me-1"></i><strong>Available!</strong> ${data.days} day(s) selected.</div>`;
        document.getElementById('checkoutSection').classList.remove('d-none');
        updateTotal();
    } else {
        availResultDiv.innerHTML = `
            <div class="alert alert-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i>${data.message}</div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm flex-fill mb-2" onclick="selectAnotherDate()">
                    <i class="bi bi-calendar3 me-1"></i> Select Another Date
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill mb-2" onclick="changeStallType()">
                    <i class="bi bi-arrow-left-right me-1"></i> Change Stall Type
                </button>
            </div>`;
        document.getElementById('checkoutSection').classList.add('d-none');
    }

    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-search me-1"></i> Check Availability';
});

document.getElementById('refundPlan')?.addEventListener('change', updateTotal);

function selectAnotherDate() {
    document.getElementById('check_in_date').value = '';
    document.getElementById('check_out_date').value = '';
    resetAvailability();
    document.getElementById('check_in_date').focus();
}

function changeStallType() {
    document.querySelectorAll('input[name="stall_type"]').forEach(el => el.checked = false);
    resetAvailability();
}

function updateTotal() {
    if (!availData) return;
    const hasRefund = document.getElementById('refundPlan').checked;
    const baseSubtotal = parseFloat(availData.subtotal);
    const subtotal  = baseSubtotal + (hasRefund ? refundPlanCost : 0);
    const tax       = Math.round(subtotal * 0.04712 * 100) / 100;
    const resvFee   = Math.round((subtotal + tax) * 0.03 * 100) / 100;
    const total     = subtotal + tax + resvFee;
    const fmt = v => v.toFixed(2);
    document.getElementById('totalDisplay').innerHTML = `
        <table class="table table-sm small mb-0">
            ${hasRefund ? `<tr><td>Refund Protection Plan</td><td class="text-end">$${fmt(refundPlanCost)}</td></tr>` : ''}
            <tr><td>Subtotal</td><td class="text-end">$${fmt(subtotal)}</td></tr>
            <tr><td>Tax (4.712%)</td><td class="text-end">$${fmt(tax)}</td></tr>
            <tr><td>Reservation Fees (3%)</td><td class="text-end">$${fmt(resvFee)}</td></tr>
            <tr class="table-primary fw-bold"><td>Total Due</td><td class="text-end">$${fmt(total)}</td></tr>
        </table>`;
}
</script>
@endpush
