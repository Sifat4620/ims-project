@extends('partials.layouts.layoutTop')

@section('content')

        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
                    <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                    </select>
                    <form class="navbar-search">
                        <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>
                </div>
                <a href="{{ route('user.create') }}" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                    Add New User
                </a>
            </div>
            {{-- Admin user end --}}
            <div class="card-body p-24">
                <div class="row gy-4">
                    {{-- Display the authenticated user card first --}}
                    <div class="col-xxl-3 col-md-6 user-grid-card">
                        <div class="position-relative border radius-16 overflow-hidden">
                            <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" alt="" class="w-100 object-fit-cover">
                            <div class="dropdown position-absolute top-0 end-0 me-16 mt-16">
                                <button type="button" data-bs-toggle="dropdown" aria-expanded="false" class="bg-white-gradient-light w-32-px h-32-px radius-8 border border-light-white d-flex justify-content-center align-items-center text-white">
                                    <iconify-icon icon="entypo:dots-three-vertical" class="icon "></iconify-icon>
                                </button>
                                <ul class="dropdown-menu p-12 border bg-base shadow">
                                    <li>
                                        <a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" href="#">
                                            Edit
                                        </a>
                                    </li>
                                    <li>
                                        <button type="button" class="delete-btn dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-danger-100 text-hover-danger-600 d-flex align-items-center gap-10">
                                            Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="ps-16 pb-16 pe-16 text-center mt--50">
                                <img src="{{ asset('assets/images/user-grid/user-grid-img1.png') }}" alt="" class="border br-white border-width-2-px w-100-px h-100-px rounded-circle object-fit-cover">
                                <h6 class="text-lg mb-0 mt-4">{{ Auth::user()->full_name }}</h6>
                                <span class="text-secondary-light mb-16">{{ Auth::user()->email }}</span>
            
                                <div class="center-border position-relative bg-danger-gradient-light radius-8 p-12 d-flex align-items-center gap-4">
                                    <div class="text-center w-50">
                                        <h6 class="text-md mb-0">{{ Auth::user()->department }}</h6>
                                        <span class="text-secondary-light text-sm mb-0">Department</span>
                                    </div>
                                    <div class="text-center w-50">
                                        <h6 class="text-md mb-0">
                                            @if(Auth::user()->designation)
                                                {{ \App\Models\Role::find(Auth::user()->designation)->designation ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
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
            {{-- Admin user end --}}
            {{-- Loop through other start --}}
                    @foreach($users as $user)
                        @if(Auth::id() !== $user->id) {{-- Ensure this is not the authenticated user --}}
                            <div class="col-xxl-3 col-md-6 user-grid-card">
                                <div class="position-relative border radius-16 overflow-hidden">
                                    <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" alt="" class="w-100 object-fit-cover">
                                    
                                    {{-- Dropdown for Edit and Delete --}}
                                    <div class="dropdown position-absolute top-0 end-0 me-16 mt-16">
                                        <button type="button" data-bs-toggle="dropdown" aria-expanded="false" class="bg-white-gradient-light w-32-px h-32-px radius-8 border border-light-white d-flex justify-content-center align-items-center text-white">
                                            <iconify-icon icon="entypo:dots-three-vertical" class="icon "></iconify-icon>
                                        </button>
                                        <ul class="dropdown-menu p-12 border bg-base shadow">
                                            <li>
                                                <a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10" href="#">
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="delete-btn dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-danger-100 text-hover-danger-600 d-flex align-items-center gap-10">
                                                    Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="ps-16 pb-16 pe-16 text-center mt--50">
                                        <img src="{{ asset('assets/images/user-grid/user-grid-img1.png') }}" alt="" class="border br-white border-width-2-px w-100-px h-100-px rounded-circle object-fit-cover">
                                        <h6 class="text-lg mb-0 mt-4">{{ $user->full_name }}</h6>
                                        <span class="text-secondary-light mb-16">{{ $user->email }}</span>

                                        <div class="center-border position-relative bg-danger-gradient-light radius-8 p-12 d-flex align-items-center gap-4">
                                            <div class="text-center w-50">
                                                <h6 class="text-md mb-0">{{ $user->department }}</h6>
                                                <span class="text-secondary-light text-sm mb-0">Department</span>
                                            </div>
                                            <div class="text-center w-50">
                                                <h6 class="text-md mb-0">
                                                    @if($user->role && $user->role->designation)
                                                        {{ $user->role->designation }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </h6>
                                                <span class="text-secondary-light text-sm mb-0">Designation</span>
                                            </div>
                                        </div>

                                        {{-- View Profile Button --}}
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
            {{--  Loop through other users end --}}
        </div>

        @push('scripts')
            <script>
                $(".delete-btn").on("click", function() {
                    $(this).closest(".user-grid-card").addClass("d-none");
                });
            </script>
        @endpush


@endsection  
@section('extra-js')
    <!-- Include your script file using Laravel's asset() helper -->
    <script src="{{ asset('assets/js/homeFiveChart.js') }}"></script>

    <!-- Inline script: this is typically placed at the bottom of the page, or within a @section('extra-js') -->
    <script>
        $(document).ready(function() {
            $(".remove-item-btn").on("click", function() {
                $(this).closest("tr").addClass("d-none");
            });
        });
    </script>
@endsection