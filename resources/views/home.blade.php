@extends('layouts.app')

@section('title', 'Book a Parking Stall')

@section('content')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2">Parking Stall Rental</h1>
        <p class="lead mb-0">Fast, easy, no account required. Book your parking stall online in minutes.</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center g-4">
        <!-- Booking Form -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Check Availability & Book</h5>
                </div>
                <div class="card-body p-4">
                    <form id="availabilityForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Stall Type</label>
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
                                        <span class="price-badge" style="background:#198754">$35/day</span>
                                        <small class="d-block text-muted mt-1">Up to 75 spaces. Parking pass with code required.</small>
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
                        <h6 class="fw-bold mb-3"><i class="bi bi-person-fill me-1"></i>Your Information</h6>
                        <form action="{{ route('booking.store-session') }}" method="POST" id="checkoutForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone_number" class="form-control" required>
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
                                        <strong>Add Refund Protection Plan – $25.00</strong>
                                        <small class="d-block text-muted">Receive a full refund if you need to cancel.</small>
                                    </label>
                                </div>
                            </div>

                            <div id="totalDisplay" class="booking-summary mt-3"></div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" value="1" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="{{ route('terms') }}" target="_blank">Terms &amp; Conditions</a>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-success w-100 mt-3">
                                <i class="bi bi-paypal me-1"></i> Proceed to PayPal
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
                        <tr><td>Tax</td><td class="text-end">4.5%</td></tr>
                        <tr><td>Service Fee</td><td class="text-end">3%</td></tr>
                        <tr><td>Refund Protection</td><td class="text-end">+$25.00</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const today = new Date();
const tomorrow = new Date(today);
tomorrow.setDate(tomorrow.getDate() + 1);
const minDate = tomorrow.toISOString().split('T')[0];
document.getElementById('check_in_date').min = minDate;
document.getElementById('check_out_date').min = minDate;

document.getElementById('check_in_date').addEventListener('change', function () {
    const cin = new Date(this.value);
    cin.setDate(cin.getDate() + 1);
    document.getElementById('check_out_date').min = cin.toISOString().split('T')[0];
    resetAvailability();
});

document.getElementById('check_out_date').addEventListener('change', resetAvailability);
document.querySelectorAll('input[name="stall_type"]').forEach(el => el.addEventListener('change', resetAvailability));

function resetAvailability() {
    availData = null;
    const resultDiv = document.getElementById('availabilityResult');
    resultDiv.classList.add('d-none');
    resultDiv.innerHTML = '';
    document.getElementById('checkoutSection').classList.add('d-none');
    document.getElementById('totalDisplay').innerHTML = '';
}

const baseSubtotal  = { reserved: 45, non_reserved: 35 };
const refundPlanCost = 25;
let availData = null;

document.getElementById('availabilityForm').addEventListener('submit', async function (e) {
    e.preventDefault();
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

    const resultDiv = document.getElementById('availabilityResult');
    resultDiv.classList.remove('d-none');

    if (data.available) {
        resultDiv.innerHTML = `<div class="alert alert-success"><i class="bi bi-check-circle-fill me-1"></i><strong>Available!</strong> ${data.days} day(s) selected.</div>`;
        document.getElementById('checkoutSection').classList.remove('d-none');
        updateTotal();
    } else {
        resultDiv.innerHTML = `<div class="alert alert-warning"><i class="bi bi-exclamation-triangle-fill me-1"></i>${data.message}</div>`;
        document.getElementById('checkoutSection').classList.add('d-none');
    }

    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-search me-1"></i> Check Availability';
});

document.getElementById('refundPlan')?.addEventListener('change', updateTotal);

function updateTotal() {
    if (!availData) return;
    const hasRefund = document.getElementById('refundPlan').checked;
    const refund = hasRefund ? refundPlanCost : 0;
    const total = parseFloat(availData.total) + refund;
    document.getElementById('totalDisplay').innerHTML = `
        <table class="table table-sm small mb-0">
            <tr><td>Subtotal</td><td class="text-end">$${availData.subtotal}</td></tr>
            <tr><td>Tax (4.5%)</td><td class="text-end">$${availData.tax}</td></tr>
            <tr><td>Service Fee (3%)</td><td class="text-end">$${availData.service_fee}</td></tr>
            ${hasRefund ? `<tr><td>Refund Protection Plan</td><td class="text-end">$${refundPlanCost.toFixed(2)}</td></tr>` : ''}
            <tr class="table-primary fw-bold"><td>Total Due</td><td class="text-end">$${total.toFixed(2)}</td></tr>
        </table>`;
}
</script>
@endpush
