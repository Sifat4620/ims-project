@extends('partials.layouts.layoutTop')

@section('content')

<div class="row gy-4">
    <!-- Clock Widget -->
    <div class="col-xxl-12 col-sm-6">
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log("DOM fully loaded and parsed"); // Check if the DOM content is loaded
        
        // Function to update the clock
        function updateClock() {
            const clockElement = document.getElementById('clock');
            
            if (clockElement) {
                console.log("Clock element found"); // Check if the clock element is found
                
                const options = {
                    timeZone: 'Asia/Dhaka', // Set timezone to Dhaka
                    hour12: true, // 12-hour format
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                };
                
                const currentTime = new Date().toLocaleTimeString('en-US', options);
                console.log("Current Time: " + currentTime); // Log current time to the console
                clockElement.textContent = currentTime; // Update the clock text
            } else {
                console.log("Clock element not found."); // If the clock element is not found
            }
        }

        // Update the clock every second
        setInterval(updateClock, 1000); 
        updateClock(); // Update the clock immediately on load
    });
</script>
@endsection
