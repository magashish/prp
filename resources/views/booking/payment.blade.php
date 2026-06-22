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
                        <tr>
                            <td class="text-muted">Tax (4.5%)</td>
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

                    {{-- PayPal Button --}}
                    <div id="paypal-button-container"></div>

                    {{-- Card fields divider --}}
                    <div class="divider">or pay with card</div>

                    {{-- Hosted Card Fields --}}
                    <div id="card-form" style="display:none">
                        <div class="mb-3">
                            <label class="form-label">Card Number</label>
                            <div id="card-number" class="card-field-wrap"></div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Expiry Date</label>
                                <div id="expiration-date" class="card-field-wrap"></div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">CVV</label>
                                <div id="cvv" class="card-field-wrap"></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Cardholder Name</label>
                            <input type="text" id="card-holder-name" class="form-control" placeholder="Name as it appears on card" autocomplete="cc-name">
                        </div>
                        <button id="card-submit" class="btn btn-success w-100 py-2" disabled>
                            <i class="bi bi-lock-fill me-1"></i> Pay ${{ number_format($pending['total'], 2) }}
                        </button>
                    </div>

                    {{-- Loading state --}}
                    <div id="card-fields-status" class="text-center text-muted py-3 small">
                        <div class="spinner-border spinner-border-sm me-1" role="status"></div>
                        Loading secure card fields…
                    </div>

                    <p class="payment-secure text-center mt-4 mb-0">
                        <i class="bi bi-shield-lock-fill me-1 text-success"></i>
                        Payments are encrypted and processed securely by PayPal.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- PayPal JS SDK: hosted-fields + buttons together --}}
<script src="https://www.paypal.com/sdk/js?client-id={{ $clientId }}&currency=USD&intent=capture&components=hosted-fields,buttons" data-csp-nonce=""></script>
<script>
const CSRF = '{{ csrf_token() }}';
const TOTAL_LABEL = '${{ number_format($pending['total'], 2) }}';

// Single order created once; both PayPal button and card fields share it.
let _orderPromise = null;
let _orderId = null;

function createOrder() {
    if (!_orderPromise) {
        _orderPromise = fetch('{{ route("paypal.create-order") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(d => {
            if (d.error) throw new Error(d.error);
            _orderId = d.id;
            return d.id;
        })
        .catch(err => {
            _orderPromise = null; // allow retry
            throw err;
        });
    }
    return _orderPromise;
}

async function captureAndRedirect(orderId) {
    const res = await fetch('{{ route("paypal.capture-inline") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ order_id: orderId }),
    });
    return res.json();
}

function showError(msg) {
    const el = document.getElementById('payment-error');
    el.textContent = msg;
    el.classList.remove('d-none');
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// ── PayPal Button ────────────────────────────────────────────────
paypal.Buttons({
    createOrder,
    onApprove: async (data) => {
        const result = await captureAndRedirect(data.orderID);
        if (result.success) {
            window.location.href = result.redirect;
        } else {
            showError(result.message || 'Payment failed. Please try again.');
            _orderPromise = null;
        }
    },
    onError: () => {
        showError('PayPal encountered an error. Please try again or pay with a card below.');
        _orderPromise = null;
    },
    onCancel: () => {
        _orderPromise = null;
    },
    style: { layout: 'vertical', color: 'blue', shape: 'rect', label: 'pay' },
}).render('#paypal-button-container');

// ── Hosted Card Fields ───────────────────────────────────────────
const statusEl = document.getElementById('card-fields-status');

if (paypal.HostedFields.isEligible()) {
    paypal.HostedFields.render({
        createOrder,
        styles: {
            'input': { 'font-size': '15px', 'color': '#212529', 'font-family': 'inherit' },
            ':focus': { 'color': '#212529' },
            '.invalid': { 'color': '#dc3545' },
        },
        fields: {
            number:         { selector: '#card-number',     placeholder: '•••• •••• •••• ••••' },
            expirationDate: { selector: '#expiration-date', placeholder: 'MM / YY' },
            cvv:            { selector: '#cvv',             placeholder: '•••' },
        },
    }).then(cardFields => {
        statusEl.style.display = 'none';
        document.getElementById('card-form').style.display = 'block';

        const btn = document.getElementById('card-submit');
        btn.disabled = false;

        // Focus / blur styling
        ['card-number', 'expiration-date', 'cvv'].forEach(id => {
            const wrap = document.getElementById(id);
            cardFields.on('focus', e => { if (e.emittedBy === id.replace('-', '') || e.emittedBy === (id === 'expiration-date' ? 'expirationDate' : id.replace('-', ''))) wrap.classList.add('focused'); });
            cardFields.on('blur',  e => { wrap.classList.remove('focused'); });
        });

        btn.addEventListener('click', async () => {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing…';
            document.getElementById('payment-error').classList.add('d-none');

            try {
                await cardFields.submit({
                    cardholderName: document.getElementById('card-holder-name').value,
                    contingencies: ['3D_SECURE'],
                });

                const result = await captureAndRedirect(_orderId);
                if (result.success) {
                    window.location.href = result.redirect;
                } else {
                    showError(result.message || 'Payment failed. Please try again.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-lock-fill me-1"></i> Pay ' + TOTAL_LABEL;
                    _orderPromise = null;
                    _orderId = null;
                }
            } catch (err) {
                showError(err.message || 'An error occurred. Please check your card details and try again.');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-lock-fill me-1"></i> Pay ' + TOTAL_LABEL;
                _orderPromise = null;
                _orderId = null;
            }
        });

    }).catch(() => {
        statusEl.textContent = 'Card payment is unavailable in this environment. Please use the PayPal button above.';
    });

} else {
    statusEl.textContent = 'Direct card payment requires a PayPal merchant account with Advanced Card Fields enabled. Please use the PayPal button above.';
}
</script>
@endpush
