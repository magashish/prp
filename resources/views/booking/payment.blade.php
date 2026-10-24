@extends('layouts.app')
@section('title', 'Secure Payment')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center g-4">

        {{-- Order Summary --}}
        <div class="col-lg-4 order-lg-2">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>Order Summary</h6>
                </div>
                <div class="card-body p-3">
                    <table class="table table-sm small mb-0">
                        <tr>
                            <td class="text-muted">Stall Type</td>
                            <td class="text-end fw-semibold">
                                {{ ucwords(str_replace('_', ' ', $pending['stall_type'])) }}
                                @if(!empty($pending['stall_number'])) #{{ $pending['stall_number'] }} @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Check-in</td>
                            <td class="text-end">{{ \Carbon\Carbon::parse($pending['check_in_date'])->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Check-out</td>
                            <td class="text-end">{{ \Carbon\Carbon::parse($pending['check_out_date'])->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Days</td>
                            <td class="text-end">{{ $pending['days'] }}</td>
                        </tr>
                        <tr><td colspan="2"><hr class="my-1"></td></tr>
                        <tr>
                            <td class="text-muted">Subtotal</td>
                            <td class="text-end">${{ number_format($pending['subtotal'], 2) }}</td>
                        </tr>
                        @if(!empty($pending['refund_plan']))
                        <tr>
                            <td class="text-muted">Refund Protection</td>
                            <td class="text-end">$25.00</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Tax (4.712%)</td>
                            <td class="text-end">${{ number_format($pending['tax'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Parking Reservation Fee ({{ round(($pending['fee_rate'] ?? 0.03) * 100) }}%)</td>
                            <td class="text-end">${{ number_format($pending['service_fee'], 2) }}</td>
                        </tr>
                        <tr class="table-primary">
                            <td class="fw-bold">Total Due</td>
                            <td class="text-end fw-bold">${{ number_format($pending['total'], 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm w-100 mt-3">
                <i class="bi bi-arrow-left me-1"></i> Change Dates / Stall Type
            </a>
        </div>

        {{-- Payment Form --}}
        <div class="col-lg-6 order-lg-1">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-lock-fill me-2"></i>Secure Payment</h5>
                </div>
                <div class="card-body p-4">

                    <div id="payment-error" class="alert alert-danger d-none mb-3" role="alert"></div>

                    <div class="mb-3">
                        <label class="form-label">Card Details</label>
                        <div id="card-container"></div>
                    </div>

                    <button id="pay-btn" class="btn btn-success w-100 py-2 mt-2">
                        <i class="bi bi-lock-fill me-1"></i> Pay ${{ number_format($pending['total'], 2) }}
                    </button>

                    <p class="payment-secure text-center mt-3 mb-0 small">
                        <i class="bi bi-shield-lock-fill me-1 text-success"></i>
                        Payments are encrypted and processed securely by Square.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Square Web Payments SDK --}}
@if(config('square.environment') === 'production')
<script src="https://web.squarecdn.com/v1/square.js"></script>
@else
<script src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
@endif

<style>
#card-container { min-height: 90px; }
</style>

<script>
const CSRF        = '{{ csrf_token() }}';
const APP_ID      = '{{ $squareAppId }}';
const LOCATION_ID = '{{ $squareLocationId }}';

async function initSquare() {
    if (!window.Square) {
        showError('Square failed to load. Please refresh the page.');
        return;
    }

    const payments = Square.payments(APP_ID, LOCATION_ID);

    const card = await payments.card({
        style: {
            '.input-container': { borderColor: '#ced4da', borderRadius: '6px' },
            '.input-container.is-focus': { borderColor: '#0d6efd' },
            '.input-container.is-error': { borderColor: '#dc3545' },
        }
    });

    await card.attach('#card-container');

    document.getElementById('pay-btn').addEventListener('click', async () => {
        const btn = document.getElementById('pay-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing…';
        document.getElementById('payment-error').classList.add('d-none');

        try {
            const result = await card.tokenize();
            if (result.status !== 'OK') {
                showError(result.errors?.map(e => e.message).join(' ') || 'Card tokenization failed.');
                resetBtn();
                return;
            }

            const res  = await fetch('{{ route("square.charge") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ source_id: result.token }),
            });
            const data = await res.json();

            if (data.success) {
                window.location.href = data.redirect;
            } else {
                showError(data.message || 'Payment failed. Please try again.');
                resetBtn();
            }
        } catch (err) {
            showError(err.message || 'An unexpected error occurred. Please try again.');
            resetBtn();
        }
    });
}

function showError(msg) {
    const el = document.getElementById('payment-error');
    el.textContent = msg;
    el.classList.remove('d-none');
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function resetBtn() {
    const btn = document.getElementById('pay-btn');
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-lock-fill me-1"></i> Pay ${{ number_format($pending["total"], 2) }}';
}

initSquare();
</script>
@endpush
