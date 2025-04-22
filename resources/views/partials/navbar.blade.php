<div class="navbar-header">
    <div class="row align-items-center justify-content-between">
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-4">
                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                </button>
                <button type="button" class="sidebar-mobile-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                </button>
            </div>
        </div>
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
                
                <!-- Language Dropdown -->
                <div class="dropdown d-none d-sm-inline-block">
                    <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                        <img src="{{ asset('assets/images/lang-flag.png') }}" alt="image" class="w-24 h-24 object-fit-cover rounded-circle">
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-0">Choose Your Language</h6>
                            </div>
                        </div>
                        <div class="max-h-400-px overflow-y-auto scroll-sm pe-8">
                            <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="english">
                                    <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/images/flags/flag1.png') }}" alt="" class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                        <span class="text-md fw-semibold mb-0">English</span>
                                    </span>
                                </label>
                                <input class="form-check-input" type="radio" name="crypto" id="english">
                            </div>
                            <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="bangladesh">
                                    <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="{{ asset('assets/images/flags/flag6.png') }}" alt="" class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                        <span class="text-md fw-semibold mb-0">Bangladesh</span>
                                    </span>
                                </label>
                                <input class="form-check-input" type="radio" name="crypto" id="bangladesh">
                            </div>
                        </div>
                    </div>
                </div><!-- Language dropdown end -->

                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
                        <!-- Use dynamic user avatar here -->
                        <img src="{{ Auth::user()->avatar ? asset('storage/avatars/' . Auth::user()->avatar) : asset('assets/images/user.png') }}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
                    </button>
                    @auth
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <!-- Display full_name from Authenticated User -->
                                <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ Auth::user()->full_name }}</h6>
                                <span class="text-secondary-light fw-medium text-sm">
                                    @foreach (Auth::user()->roles as $role)
                                        {{ $role->title }}@if (!$loop->last), @endif
                                    @endforeach
                                </span>
                            </div>
                            <button type="button" class="hover-text-danger">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>
                        <ul class="to-top-list">
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('view.profile', ['user' => Auth::id()]) }}">
                                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> My Profile
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3">
                                        <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
                @guest
                    <p>Please log in to access the dropdown.</p>
                @endguest
                
                
                </div><!-- Profile dropdown end -->
            </div>
        </div>
    </div>
</div>
