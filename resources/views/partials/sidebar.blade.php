<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ url('/') }}" class="sidebar-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            @foreach ($menuItems as $item)
                <li class="dropdown">
                    <a href="{{ $item['link'] }}">
                        <iconify-icon icon="{{ $item['icon'] }}" class="menu-icon"></iconify-icon>
                        <span class="fw-semibold my-1">{{ $item['title'] }}</span>
                    </a>
                    @if (!empty($item['submenus']))
                        <ul class="sidebar-submenu">
                            @foreach ($item['submenus'] as $submenu)
                                <li>
                                    <a href="{{ $submenu['link'] }}">
                                        {{ $submenu['name'] }}
                                    </a>
                                    @if (!empty($submenu['subchild']))
                                        <ul>
                                            @foreach ($submenu['subchild'] as $subchild)
                                                <li>
                                                    <a href="{{ $subchild['link'] }}">
                                                        <i class="ri-circle-fill circle-icon {{ $subchild['icon_color'] }} w-auto"></i>
                                                        {{ $subchild['name'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</aside>
