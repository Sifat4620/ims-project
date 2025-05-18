@extends('partials.layouts.layoutTop')

@section('content')
<div class="card h-100 p-0 radius-12">
    <div class="card-body p-24">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-8 col-lg-10">
                <div class="card border">
                    <div class="card-body">
                        <h4 class="text-md text-primary-light mb-16 text-center">Edit Upgrade Item</h4>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-16">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('upgrade.info.update', $item->id) }}" method="POST" id="item-edit-form">
                            @csrf
                            @method('PUT')

                            <!-- L/C or PO Type -->
                            <div class="mb-20">
                                <label for="lc_po_type" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    L/C or PO Type <span class="text-danger-600">*</span>
                                </label>
                                <input type="text" id="lc_po_type" name="lc_po_type" class="form-control radius-8" 
                                       value="{{ old('lc_po_type', $item->lc_po_type) }}" required>
                                @error('lc_po_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Category, Brand, Model No -->
                            <div class="row g-3 mb-20">
                                <div class="col-md-4">
                                    <label for="category" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Category <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text" id="category" name="category" class="form-control radius-8"
                                           value="{{ old('category', $item->category) }}" required>
                                    @error('category')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="brand" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Brand <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text" id="brand" name="brand" class="form-control radius-8"
                                           value="{{ old('brand', $item->brand) }}" required>
                                    @error('brand')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="model_no" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Model No <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text" id="model_no" name="model_no" class="form-control radius-8" 
                                           value="{{ old('model_no', $item->model_no) }}" required>
                                    @error('model_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Serial No -->
                            <div class="mb-20">
                                <label for="serial_no" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Serial No(s) <span class="text-danger-600">*</span>
                                </label>
                                <textarea id="serial_no" name="serial_no" rows="2" 
                                          class="form-control radius-8" 
                                          placeholder="Enter serial numbers" required>{{ old('serial_no', $item->serial_no) }}</textarea>
                                @error('serial_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Condition, Status, Holding Location, Date -->
                            <div class="row g-3 mb-20">
                                <div class="col-md-3">
                                    <label for="condition" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Condition <span class="text-danger-600">*</span>
                                    </label>
                                    <select id="condition" name="condition" class="form-select radius-8" required>
                                        <option value="Good" {{ old('condition', $item->condition) == 'Good' ? 'selected' : '' }}>Good</option>
                                        <option value="Faulty" {{ old('condition', $item->condition) == 'Faulty' ? 'selected' : '' }}>Faulty</option>
                                    </select>
                                    @error('condition')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="status" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Issued <span class="text-danger-600">*</span>
                                    </label>
                                    <select id="status" name="status" class="form-select radius-8" required>
                                        <option value="No" {{ strtolower(trim(old('status', $item->status))) == 'no' ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ strtolower(trim(old('status', $item->status))) == 'yes' ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="holding_location" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Location <span class="text-danger-600">*</span>
                                    </label>
                                    <select id="holding_location" name="holding_location" class="form-select radius-8" required>
                                        <option value="Floor 13" {{ old('holding_location', $item->holding_location) == 'Floor 13' ? 'selected' : '' }}>Floor 13</option>
                                        <option value="Floor 14" {{ old('holding_location', $item->holding_location) == 'Floor 14' ? 'selected' : '' }}>Floor 14</option>
                                        <option value="Basement" {{ old('holding_location', $item->holding_location) == 'Basement' ? 'selected' : '' }}>Basement</option>
                                    </select>
                                    @error('holding_location')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="date" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Date <span class="text-danger-600">*</span>
                                    </label>
                                    <input type="date" id="date" name="date" class="form-control radius-8"
                                           value="{{ old('date', $item->date ? \Carbon\Carbon::parse($item->date)->format('Y-m-d') : '') }}" required>
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Specification -->
                            <div class="mb-20">
                                <label for="specification" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Specification <span class="text-danger-600">*</span>
                                </label>
                                <textarea id="specification" name="specification" rows="3" 
                                          class="form-control radius-8" placeholder="Enter specifications" required>{{ old('specification', $item->specification) }}</textarea>
                                @error('specification')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <a href="{{ url()->previous() }}" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8 text-center text-decoration-none">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                    Update Item
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
    window.onload = function() {
        const dateInput = document.getElementById('date');
        if (!dateInput.value) {
            const today = new Date();
            dateInput.value = today.toISOString().split('T')[0];
        }
    };
</script>
@endsection
