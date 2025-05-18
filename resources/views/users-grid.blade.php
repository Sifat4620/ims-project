@extends('partials.layouts.layoutTop')

@section('content')

<div class="card h-100 p-0 radius-12">

    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>

            <form method="GET" id="perPageForm" class="d-inline-block">
                <select name="per_page" class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" onchange="document.getElementById('perPageForm').submit()">
                    @foreach([1,2,3,4,5,6,7,8,9,10,15,20,30,50] as $count)
                        <option value="{{ $count }}" {{ request('per_page', 5) == $count ? 'selected' : '' }}>{{ $count }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="search" value="{{ request('search') }}">
            </form>

            <form class="navbar-search ms-3" method="GET">
                <input 
                    type="text" 
                    class="bg-base h-40-px w-auto" 
                    name="search" 
                    placeholder="Search" 
                    value="{{ request('search') }}"
                >
                <input type="hidden" name="per_page" value="{{ request('per_page', 5) }}">
                <button type="submit" style="border:none;background:none;cursor:pointer;">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </button>
            </form>
        </div>

        <a href="{{ route('user.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
            Add New User
        </a>
    </div>

    <div class="card-body p-24">
        <div class="row gy-4">

            {{-- Authenticated User Card --}}
            <div class="col-xxl-3 col-md-6 user-grid-card">
                <div class="position-relative border radius-16 overflow-hidden">
                    <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" alt="" class="w-100 object-fit-cover">

                    <div class="ps-16 pb-16 pe-16 text-center mt--50">
                        <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('assets/images/user-grid/user-grid-img1.png') }}" 
                             alt="User Image" 
                             class="border br-white border-width-2-px w-100-px h-100-px rounded-circle object-fit-cover"
                        >
                        <h6 class="text-lg mb-0 mt-4">{{ Auth::user()->full_name }}</h6>
                        <span class="text-secondary-light mb-16">{{ Auth::user()->email }}</span>

                        <div class="center-border position-relative bg-danger-gradient-light radius-8 p-12 d-flex align-items-center gap-4">
                            <div class="text-center w-50">
                                <h6 class="text-md mb-0">{{ Auth::user()->department ?? 'N/A' }}</h6>
                                <span class="text-secondary-light text-sm mb-0">Department</span>
                            </div>
                            <div class="text-center w-50">
                                <h6 class="text-md mb-0">
                                    @php
                                        $primaryRole = Auth::user()->roles->first();
                                    @endphp
                                    {{ $primaryRole ? $primaryRole->title : 'N/A' }}
                                </h6>
                                <span class="text-secondary-light text-sm mb-0">Designation</span>
                            </div>
                        </div>

                        <a href="{{ route('user.profile', ['user' => Auth::id()]) }}" class="bg-primary-50 text-primary-600 bg-hover-primary-600 hover-text-white p-10 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center justify-content-center mt-16 fw-medium gap-2 w-100">
                            View Profile
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon text-xl line-height-1"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Other Users --}}
            @foreach($users as $user)
                @if(Auth::id() !== $user->id)
                    <div class="col-xxl-3 col-md-6 user-grid-card">
                        <div class="position-relative border radius-16 overflow-hidden">
                            <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" alt="" class="w-100 object-fit-cover">

                            <div class="ps-16 pb-16 pe-16 text-center mt--50">
                                <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('assets/images/user-grid/user-grid-img1.png') }}" alt="" class="border br-white border-width-2-px w-100-px h-100-px rounded-circle object-fit-cover">
                                <h6 class="text-lg mb-0 mt-4">{{ $user->full_name }}</h6>
                                <span class="text-secondary-light mb-16">{{ $user->email }}</span>

                                <div class="center-border position-relative bg-danger-gradient-light radius-8 p-12 d-flex align-items-center gap-4">
                                    <div class="text-center w-50">
                                        <h6 class="text-md mb-0">{{ $user->department ?? 'N/A' }}</h6>
                                        <span class="text-secondary-light text-sm mb-0">Department</span>
                                    </div>
                                    <div class="text-center w-50">
                                        <h6 class="text-md mb-0">
                                            @php
                                                $primaryRole = $user->roles->first();
                                            @endphp
                                            {{ $primaryRole ? $primaryRole->title : 'N/A' }}
                                        </h6>
                                        <span class="text-secondary-light text-sm mb-0">Designation</span>
                                    </div>
                                </div>

                                <a href="{{ route('user.profile', ['user' => $user->id]) }}" class="bg-primary-50 text-primary-600 bg-hover-primary-600 hover-text-white p-10 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center justify-content-center mt-16 fw-medium gap-2 w-100">
                                    View Profile
                                    <iconify-icon icon="solar:alt-arrow-right-linear" class="icon text-xl line-height-1"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    </div>

    {{-- Pagination --}}
    <div class="px-24 py-12 d-flex justify-content-center">
        {{ $users->withQueryString()->links() }}
    </div>

</div>

@endsection

@section('extra-js')
<script src="{{ asset('assets/js/homeFiveChart.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".remove-item-btn").on("click", function() {
            $(this).closest("tr").addClass("d-none");
        });
    });
</script>
@endsection
