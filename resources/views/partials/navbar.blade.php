<!-- Load Iconify if not already included -->
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<!-- Navbar Header -->
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

                <!-- Language Dropdown (optional, currently disabled) -->
                {{-- ... language code remains commented out ... --}}

                <!-- User Profile Dropdown with Avatar Icon and Active Status -->
                <div class="dropdown">
                    <button class="d-flex justify-content-center align-items-center position-relative rounded-circle" 
                            type="button" data-bs-toggle="dropdown"
                            style="width: 40px; height: 40px; background-color: #f0f0f0;">
                        <!-- Avatar icon instead of image -->
                        <iconify-icon icon="ph:user-duotone" class="text-dark" style="font-size: 24px;"></iconify-icon>

                        <!-- Active blinking green dot -->
                        <span class="active-indicator position-absolute" style="bottom: 2px; right: 2px;"></span>
                    </button>

                    @auth
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
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
                </div>
                <!-- Profile dropdown end -->

            </div>
        </div>
    </div>
</div>

<!-- Blinking Green Dot CSS -->
<style>
    .active-indicator {
        width: 10px;
        height: 10px;
        background-color: #28a745;
        border: 2px solid #fff;
        border-radius: 50%;
        animation: blink 1s infinite;
        box-shadow: 0 0 5px #28a745;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
</style>
