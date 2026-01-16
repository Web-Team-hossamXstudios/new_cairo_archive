        <!-- Topbar Start -->
        <header class="app-topbar">
            <div class="container-fluid topbar-menu">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <a href="{{ route('admin.dashboard') }}" class="logo-dark">
                            <span class="d-flex align-items-center gap-1">
                                <span class="avatar avatar-xs rounded-circle text-bg-dark">
                                    <span class="avatar-title">
                                        <img src="{{ asset('logo.jpg') }}" height="50" alt="Logo">
                                    </span>
                                </span>
                                <span class="logo-text text-body fw-bold fs-xl">Biry Suits</span>
                            </span>
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="logo-light">
                            <span class="d-flex align-items-center gap-1">
                                <span class="avatar avatar-xs rounded-circle text-bg-dark">
                                    <span class="avatar-title">
                                        <i data-lucide="sparkles" class="fs-md"></i>
                                    </span>
                                </span>
                                <span class="logo-text text-white fw-bold fs-xl">Biry Suits</span>
                            </span>
                        </a>
                    </div>

                    <div class="d-lg-none d-flex mx-1">
                        <a href="{{ route('admin.dashboard') }}">
                            <img src="{{ asset('logo.jpg') }}" height="28" alt="Logo">
                        </a>
                    </div>

                    <!-- Sidebar Hover Menu Toggle Button -->
                    <button class="button-collapse-toggle d-xl-none">
                        <i data-lucide="menu" class="fs-22 align-middle"></i>
                    </button>
                </div> <!-- .d-flex-->

                <div class="d-flex align-items-center gap-2">
                     @canany(['view-orders', 'view-order-items', 'view-order-status-history', 'view-invoices', 'view-payments'])
                    <a href="{{ route('admin.orders.create') }}"
                        class="side-nav-link d-flex align-items-center gap-2 btn btn-primary">
                        <span class="menu-icon ">
                            <i class="ti ti-plus"></i>
                            <span>New Order</span>
                        </span>
                    </a>
                    @endcanany
                    <!-- Theme Dropdown -->
                    {{-- <div class="topbar-item me-2">
                        <div class="dropdown" data-dropdown="custom">
                            <button class="topbar-link  fw-semibold" data-bs-toggle="dropdown" data-bs-offset="0,19"
                                type="button" aria-haspopup="false" aria-expanded="false">
                                <img data-trigger-img src="{{ asset('admin/assets/images/themes/shadcn.svg') }}"
                                    alt="user-image" class="w-100 rounded me-2" height="18">
                                <span data-trigger-label class="text-nowrap"> {{ x_('Shadcn', 'dashboards.shared.topbar') }} </span>
                                <span class="dot-blink" aria-label="live status indicator"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-1">
                                <div class="h-100" style="max-height: 250px;" data-simplebar>
                                    <div class="row g-0">
                                        <div class="col-md-6">

                                            <button class="dropdown-item position-relative drop-custom-active"
                                                data-skin="shadcn">
                                                <img src="{{ asset('admin/assets/images/themes/shadcn.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Shadcn', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="corporate">
                                                <img src="{{ asset('admin/assets/images/themes/corporate.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Corporate', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="spotify">
                                                <img src="{{ asset('admin/assets/images/themes/spotify.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Spotify', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="saas">
                                                <img src="{{ asset('admin/assets/images/themes/saas.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('SaaS', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="nature">
                                                <img src="{{ asset('admin/assets/images/themes/nature.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Nature', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="vintage">
                                                <img src="{{ asset('admin/assets/images/themes/vintage.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Vintage', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="leafline">
                                                <img src="{{ asset('admin/assets/images/themes/leafline.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Leafline', 'dashboards.shared.topbar') }}</span>
                                            </button>
                                        </div>

                                        <div class="col-md-6">
                                            <button class="dropdown-item position-relative" data-skin="ghibli">
                                                <img src="{{ asset('admin/assets/images/themes/ghibli.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Ghibli', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="slack">
                                                <img src="{{ asset('admin/assets/images/themes/slack.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Slack', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="material">
                                                <img src="{{ asset('admin/assets/images/themes/material.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Material Design', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="flat">
                                                <img src="{{ asset('admin/assets/images/themes/flat.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Flat', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="pastel">
                                                <img src="{{ asset('admin/assets/images/themes/pastel.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Pastel Pop', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="caffieine">
                                                <img src="{{ asset('admin/assets/images/themes/caffieine.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Caffieine', 'dashboards.shared.topbar') }}</span>
                                            </button>

                                            <button class="dropdown-item position-relative" data-skin="redshift">
                                                <img src="{{ asset('admin/assets/images/themes/redshift.svg') }}"
                                                    alt="" class="me-1 rounded" height="18">
                                                <span class="align-middle">{{ x_('Redshift', 'dashboards.shared.topbar') }}</span>
                                            </button>
                                        </div>
                                    </div> <!-- end row-->


                                </div> <!-- end .h-100-->
                            </div> <!-- .dropdown-menu-->

                        </div> <!-- end dropdown-->
                    </div> --}}
                     <!-- end topbar item-->

                    <!-- Language Dropdown -->
                    <!-- Language Dropdown -->
                    <div class="topbar-item">
                        <div class="dropdown" data-dropdown="custom">
                            @php
                                $locale = LaravelLocalization::getCurrentLocale();
                            @endphp

                            <button class="topbar-link fw-semibold" data-bs-toggle="dropdown" data-bs-offset="0,19"
                                type="button" aria-haspopup="false" aria-expanded="false">
                                <img data-trigger-img
                                    src="{{ $locale === 'ar' ? asset('admin/assets/images/flags/eg.svg') : asset('admin/assets/images/flags/us.svg') }}"
                                    alt="user-image" class="w-100 rounded me-2" height="18">

                                <span data-trigger-label>
                                    {{ strtoupper($locale) }}
                                </span>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end">
                                {{-- English --}}
                                <button type="button"
                                    onclick="window.location.href='{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}'"
                                    class="dropdown-item {{ $locale === 'en' ? 'drop-custom-active' : '' }}"
                                    data-lang="en">
                                    <img src="{{ asset('admin/assets/images/flags/us.svg') }}" alt="English"
                                        class="me-1 rounded" height="18">
                                    <span class="align-middle">English</span>
                                </button>

                                {{-- Arabic --}}
                                <button type="button"
                                    onclick="window.location.href='{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}'"
                                    class="dropdown-item {{ $locale === 'ar' ? 'drop-custom-active' : '' }}"
                                    data-lang="ar">
                                    <img src="{{ asset('admin/assets/images/flags/eg.svg') }}" alt="Arabic"
                                        class="me-1 rounded" height="18">
                                    <span class="align-middle">عربي</span>
                                </button>
                            </div> <!-- end dropdown-menu-->
                        </div> <!-- end -->

                    </div>
                    <!-- Button Trigger Customizer Offcanvas -->
                    {{-- <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" data-bs-toggle="offcanvas"
                            data-bs-target="#theme-settings-offcanvas" type="button">
                            <i data-lucide="settings" class="fs-xxl"></i>
                        </button>
                    </div> --}}

                    <!-- Light/Dark Mode Button -->
                    {{-- <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" id="light-dark-mode" type="button">
                            <i data-lucide="moon" class="fs-xxl mode-light-moon"></i>
                            <i data-lucide="sun" class="fs-xxl mode-light-sun"></i>
                        </button>
                    </div> --}}

                    <!-- Monochrome Mode Button -->
                    {{-- <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" id="monochrome-mode" type="button">
                            <i data-lucide="palette" class="fs-xxl mode-light-moon"></i>
                        </button>
                    </div> --}}

                    <!-- User Dropdown -->
                    <div class="topbar-item nav-user">
                        <div class="dropdown">
                            <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                                data-bs-offset="0,13" href="#!" aria-haspopup="false" aria-expanded="false">
                                @php
                                    $userAvatar = Auth::user()->getFirstMediaUrl('avatars');
                                    $userInitial = !empty(Auth::user()->first_name) ? strtoupper(substr(Auth::user()->first_name, 0, 1)) : (!empty(Auth::user()->name) ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'U');
                                @endphp
                                @if($userAvatar)
                                    <img src="{{ $userAvatar }}" width="40" height="40"
                                        class="rounded-circle d-flex" alt="user-image" style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="fw-bold">{{ $userInitial }}</span>
                                    </div>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- Header -->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">{{ x_('Welcome back!', 'dashboards.shared.topbar') }}</h6>
                                    <small class="text-muted">{{ Auth::user()->full_name ?: (Auth::user()->name ?: Auth::user()->email) }}</small>
                                </div>

                                <!-- My Profile -->
                                <a href="{{ route('admin.profile.index') }}" class="dropdown-item">
                                    <i class="ti ti-user-circle me-2 fs-17 align-middle"></i>
                                    <span class="align-middle">{{ x_('My Profile', 'dashboards.shared.topbar') }}</span>
                                </a>

                                <!-- Divider -->
                                <div class="dropdown-divider"></div>

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-semibold">
                                        <i class="ti ti-logout-2 me-2 fs-17 align-middle"></i>
                                        <span class="align-middle">{{ x_('Log Out', 'dashboards.shared.topbar') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Topbar End -->

        <script>
            // Skin Dropdown
            document.querySelectorAll('[data-dropdown="custom"]').forEach(dropdown => {
                const trigger = dropdown.querySelector(
                    'a[data-bs-toggle="dropdown"], button[data-bs-toggle="dropdown"]');
                const items = dropdown.querySelectorAll('button[data-skin]');

                const triggerImg = trigger.querySelector('[data-trigger-img]');
                const triggerLabel = trigger.querySelector('[data-trigger-label]');

                const config = JSON.parse(JSON.stringify(window.config));
                const currentSkin = config.skin;

                items.forEach(item => {
                    const itemSkin = item.getAttribute('data-skin');
                    const itemImg = item.querySelector('img')?.getAttribute('src');
                    const itemText = item.querySelector('span')?.textContent.trim();

                    // Set active on load
                    if (itemSkin === currentSkin) {
                        item.classList.add('drop-custom-active');
                        if (triggerImg && itemImg) triggerImg.setAttribute('src', itemImg);
                        if (triggerLabel && itemText) triggerLabel.textContent = itemText;
                    } else {
                        item.classList.remove('drop-custom-active');
                    }

                    // Click handler
                    item.addEventListener('click', function() {
                        items.forEach(i => i.classList.remove('drop-custom-active'));
                        this.classList.add('drop-custom-active');

                        const newImg = this.querySelector('img')?.getAttribute('src');
                        const newText = this.querySelector('span')?.textContent.trim();

                        if (triggerImg && newImg) triggerImg.setAttribute('src', newImg);
                        if (triggerLabel && newText) triggerLabel.textContent = newText;

                        if (typeof layoutCustomizer !== 'undefined') {
                            layoutCustomizer.changeSkin(itemSkin);
                        }
                    });
                });
            });
        </script>
