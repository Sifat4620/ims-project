@extends('partials.layouts.layoutTop')

@section('content')

<div class="row gy-4">

    <!-- Current Time & Date -->
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="card px-24 py-16 shadow-sm radius-8 border h-100 bg-gradient-start-6">
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <span class="w-40-px h-40-px bg-primary-600 text-white d-flex justify-content-center align-items-center radius-8">
                                <iconify-icon icon="mdi:clock-outline"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="fw-medium text-md text-secondary-light">Current Time & Date</span>
                            <h6 class="fw-bold my-1" id="clock">--:--:--</h6>
                            <span class="text-secondary-light" id="date">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Role -->
    <div class="col-md-6 col-lg-6 col-xl-6">
        <a href="{{ route('view.profile', auth()->user()) }}" class="text-decoration-none d-block">
            <div class="card px-24 py-16 shadow-sm radius-8 border h-100 bg-gradient-start-4">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <span class="w-40-px h-40-px bg-warning text-white d-flex justify-content-center align-items-center radius-8">
                                <iconify-icon icon="carbon:user-avatar"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="fw-medium text-md text-secondary-light">Your Roles</span>
                            <div class="mt-2">
                                @foreach ($roles as $role)
                                    <span class="badge bg-primary">{{ $role->title }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>

<div class="row gy-4 mt-2">

    <!-- Stock In -->
    <div class="col-xxl-3 col-sm-6">
        <a href="{{ route('logistics.instock') }}" class="text-decoration-none d-block">
            <div class="card px-24 py-16 border shadow-sm radius-8 bg-gradient-start-3 hover-scale">
                <div class="card-body p-0 d-flex align-items-center">
                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                        <span class="w-40-px h-40-px bg-primary-600 text-white d-flex justify-content-center align-items-center radius-8">
                            <iconify-icon icon="mdi:package-variant-closed"></iconify-icon>
                        </span>
                    </div>
                    <div>
                        <span class="text-secondary-light fw-medium">Stock In</span>
                        <h5 class="fw-bold mb-0 counter" data-target="{{ $totalStockIn ?? 0 }}">0</h5>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Stock Out -->
    <div class="col-xxl-3 col-sm-6">
        <a href="{{ route('logistics.deliverychallan') }}" class="text-decoration-none d-block">
            <div class="card px-24 py-16 border shadow-sm radius-8 bg-gradient-start-2 hover-scale">
                <div class="card-body p-0 d-flex align-items-center">
                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                        <span class="w-40-px h-40-px bg-purple text-white d-flex justify-content-center align-items-center radius-8">
                            <iconify-icon icon="mdi:truck-delivery-outline"></iconify-icon>
                        </span>
                    </div>
                    <div>
                        <span class="text-secondary-light fw-medium">Stock Out</span>
                        <h5 class="fw-bold mb-0 counter" data-target="{{ $totalStockOut ?? 0 }}">0</h5>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Return Items -->
    <div class="col-xxl-3 col-sm-6">
        <a href="{{ route('logistics.returnable') }}" class="text-decoration-none d-block">
            <div class="card px-24 py-16 border shadow-sm radius-8 bg-gradient-start-5 hover-scale">
                <div class="card-body p-0 d-flex align-items-center">
                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                        <span class="w-40-px h-40-px bg-danger text-white d-flex justify-content-center align-items-center radius-8">
                            <iconify-icon icon="mdi:recycle-variant"></iconify-icon>
                        </span>
                    </div>
                    <div>
                        <span class="text-secondary-light fw-medium">Return Items</span>
                        <h5 class="fw-bold mb-0 counter" data-target="{{ $totalReturns ?? 0 }}">0</h5>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Faulty Items -->
    <div class="col-xxl-3 col-sm-6">
        <a href="{{ route('logistics.defective') }}" class="text-decoration-none d-block">
            <div class="card px-24 py-16 border shadow-sm radius-8 bg-gradient-start-4 hover-scale">
                <div class="card-body p-0 d-flex align-items-center">
                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                        <span class="w-40-px h-40-px bg-success-main text-white d-flex justify-content-center align-items-center radius-8">
                            <iconify-icon icon="mdi:alert-decagram-outline"></iconify-icon>
                        </span>
                    </div>
                    <div>
                        <span class="text-secondary-light fw-medium">Faulty Items</span>
                        <h5 class="fw-bold mb-0 counter" data-target="{{ $totalDamaged ?? 0 }}">0</h5>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>

<!-- Brand & Category Summary -->
@if(!empty($brandCategorySummary) && count($brandCategorySummary) > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border radius-10 shadow-sm">
            {{-- <div class="card-header bg-primary text-white fw-bold">
                Brand & Category Summary
            </div> --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">Brand</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Total Products</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brandCategorySummary as $brand => $categories)
                                @foreach($categories as $category => $products)
                                    <tr>
                                        <td class="text-center">{{ $brand }}</td>
                                        <td class="text-center">{{ $category }}</td>
                                        <td class="text-center">{{ count($products) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Scripts -->
<script>
    // Clock
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').textContent = now.toLocaleTimeString();
        document.getElementById('date').textContent = now.toLocaleDateString(undefined, { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    }
    setInterval(updateClock, 1000); updateClock();

    // Counter animation
    document.querySelectorAll('.counter').forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const inc = target / 100;
            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 20);
            } else counter.innerText = target;
        };
        updateCount();
    });
</script>

<style>
.hover-scale { transition: all 0.2s ease; }
.hover-scale:hover { transform: scale(1.03); }
.table th, .table td { vertical-align: middle; padding: 0.75rem 1rem; }
.table-hover tbody tr:hover { background-color: rgba(0, 123, 255, 0.1); transition: background-color 0.3s ease; }
.table-responsive { max-height: 400px; overflow-y: auto; }
</style>

@endsection
