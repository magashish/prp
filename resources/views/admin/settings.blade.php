@extends('layouts.admin')
@section('title', 'Email Settings')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-gear-fill me-2"></i>Email Settings</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Admin Email</label>
                        <input type="email" name="admin_email" class="form-control @error('admin_email') is-invalid @enderror"
                               value="{{ old('admin_email', $adminEmail) }}" required>
                        <div class="form-text">Receives a copy of every new booking notification.</div>
                        @error('admin_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Parking Company Email</label>
                        <input type="email" name="parking_company_email" class="form-control @error('parking_company_email') is-invalid @enderror"
                               value="{{ old('parking_company_email', $parkingCompanyEmail) }}" required>
                        <div class="form-text">Receives non-reserved booking notifications.</div>
                        @error('parking_company_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
