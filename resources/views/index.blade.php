@extends('partials.layouts.layoutTop')

@section('content')

<div class="row gy-4">
    <!-- Clock Widget -->
    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100 bg-gradient-start-6">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <!-- Clock Icon -->
                            <span class="mb-0 w-40-px h-40-px bg-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                <iconify-icon icon="mdi:clock-outline" class="icon"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold fw-medium text-md text-secondary-light mb-2 my-1">Current Time</span>
                            <h6 class="fw-semibold my-1" id="clock">Loading...</h6> <!-- This is where the clock will be displayed -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function updateClock() {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            
            const timeString = `${hours}:${minutes}:${seconds}`;
            
            // Update the clock's inner HTML
            document.getElementById('clock').textContent = timeString;
        }
    
        // Call the updateClock function every second
        setInterval(updateClock, 1000);
    
        // Call it once initially to avoid delay
        updateClock();
    </script>
    

    <!-- Roles Display Widget -->
    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100 bg-gradient-start-4">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <span class="mb-0 w-40-px h-40-px bg-warning flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                <iconify-icon icon="carbon:user-avatar" class="icon"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold fw-medium text-md text-secondary-light mb-2 my-1">Your Roles</span>
                            <h6 class="fw-semibold my-1">
                                @foreach ($roles as $role)
                                    <span class="badge bg-primary">{{ $role->title }}</span> <!-- Displaying the role name -->
                                @endforeach
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Stock In Widget -->
    <div class="col-xxl-3 col-sm-6">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100 bg-gradient-start-3">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <span class="mb-0 w-40-px h-40-px bg-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                <iconify-icon icon="flowbite:users-group-solid" class="icon"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold fw-medium text-md text-secondary-light mb-2 my-1">Stock In</span>
                            <h6 class="fw-semibold my-1">{{ $totalStockIn }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Out Widget -->
    <div class="col-xxl-3 col-sm-6">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100 bg-gradient-start-2">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <span class="mb-0 w-40-px h-40-px bg-purple flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl mb-0"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold fw-medium text-md text-secondary-light mb-2 my-1">Stock Out</span>
                            <h6 class="fw-semibold my-1">{{ $totalStockOut }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Return Widget -->
    <div class="col-xxl-3 col-sm-6">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100 bg-gradient-start-5">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <span class="mb-0 w-40-px h-40-px bg-red flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                <iconify-icon icon="fa6-solid:file-invoice-dollar" class="text-white text-2xl mb-0"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold fw-medium text-md text-secondary-light mb-2 my-1">Return Items</span>
                            <h6 class="fw-semibold my-1">{{ $totalReturns }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Damaged Widget -->
    <div class="col-xxl-3 col-sm-6">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100 bg-gradient-start-4">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <span class="mb-0 w-40-px h-40-px bg-success-main flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                <iconify-icon icon="streamline:bag-dollar-solid" class="icon"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="fw-semibold fw-medium text-md text-secondary-light mb-2 my-1">Faulty Items</span>
                            <h6 class="fw-semibold my-1">{{ $totalDamaged }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- Move the JavaScript outside the content section -->
@section('extra-js')
@endsection
