
    <!-- Theme Settings -->
    {{-- <div class="offcanvas offcanvas-end overflow-hidden" tabindex="-1" id="theme-settings-offcanvas">
        <div class="d-flex justify-content-between text-bg-primary gap-2 p-3"
            style="background-image: url({{ asset('admin/assets/images/user-bg-pattern.png') }});">
            <div>
                <h5 class="mb-1 fw-bold text-white text-uppercase">{{ x_('Admin Customizer', 'dashboards.shared.theme_settings') }}</h5>
                <p class="text-white text-opacity-75 fst-italic fw-medium mb-0">{{ x_('Easily configure layout, styles, and preferences for your admin interface.', 'dashboards.shared.theme_settings') }}</p>
            </div>

            <div class="flex-grow-0">
                <button type="button"
                    class="d-block btn btn-sm bg-white bg-opacity-25 text-white rounded-circle btn-icon"
                    data-bs-dismiss="offcanvas"><i class="ti ti-x fs-lg"></i></button>
            </div>
        </div>

        <div class="offcanvas-body p-0 h-100" data-simplebar>

            <div class="p-3 border-bottom border-dashed">
                <h5 class="mb-3 fw-bold">{{ x_('Color Scheme', 'dashboards.shared.theme_settings') }}</h5>
                <div class="row">
                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-bs-theme"
                                id="layout-color-light" value="light">
                            <label class="form-check-label p-0 w-100" for="layout-color-light">
                                <img src="{{ asset('admin/assets/images/layouts/light.svg') }}" alt="layout-img" class="img-fluid">
                            </label>
                        </div>
                        <h5 class="text-center text-muted mt-2 mb-0">{{ x_('Light', 'dashboards.shared.theme_settings') }}</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-bs-theme"
                                id="layout-color-dark" value="dark">
                            <label class="form-check-label p-0 w-100 overflow-hidden" for="layout-color-dark">
                                <img src="{{ asset('admin/assets/images/layouts/dark.svg') }}" alt="layout-img"
                                    class="img-fluid overflow-hidden">
                            </label>
                        </div>
                        <h5 class="text-center text-muted mt-2 mb-0">{{ x_('Dark', 'dashboards.shared.theme_settings') }}</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed">
                <h5 class="mb-3 fw-bold">{{ x_('Topbar Color', 'dashboards.shared.theme_settings') }}</h5>

                <div class="row g-3">
                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-topbar-color"
                                id="topbar-color-light" value="light">
                            <label class="form-check-label p-0 w-100" for="topbar-color-light">
                                <img src="{{ asset('admin/assets/images/layouts/topbar-light.svg') }}" alt="layout-img" class="img-fluid">
                            </label>
                        </div>
                        <h5 class="text-center text-muted mt-2 mb-0">{{ x_('Light', 'dashboards.shared.theme_settings') }}</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check card-radio">
                            <input class="form-check-input" type="radio" name="data-topbar-color"
                                id="topbar-color-dark" value="dark">
                            <label class="form-check-label p-0 w-100" for="topbar-color-dark">
                                <img src="{{ asset('admin/assets/images/layouts/topbar-dark.svg') }}" alt="layout-img" class="img-fluid">
                            </label>
                        </div>
                        <h5 class="fs-sm text-center text-muted mt-2 mb-0">{{ x_('Dark', 'dashboards.shared.theme_settings') }}</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed">
                <h5 class="mb-3 fw-bold">{{ x_('Sidenav Color', 'dashboards.shared.theme_settings') }}</h5>

                <div class="row g-3">
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-color"
                                id="sidenav-color-light" value="light">
                            <label class="form-check-label p-0 w-100" for="sidenav-color-light">
                                <img src="{{ asset('admin/assets/images/layouts/light.svg') }}" alt="layout-img" class="img-fluid">
                            </label>
                        </div>
                        <h5 class="fs-sm text-center text-muted mt-2 mb-0">{{ x_('Light', 'dashboards.shared.theme_settings') }}</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-color"
                                id="sidenav-color-dark" value="dark">
                            <label class="form-check-label p-0 w-100" for="sidenav-color-dark">
                                <img src="{{ asset('admin/assets/images/layouts/sidenav-dark.svg') }}" alt="layout-img" class="img-fluid">
                            </label>
                        </div>
                        <h5 class="fs-sm text-center text-muted mt-2 mb-0">{{ x_('Dark', 'dashboards.shared.theme_settings') }}</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed">
                <h5 class="mb-3 fw-bold">{{ x_('Sidebar Size', 'dashboards.shared.theme_settings') }}</h5>

                <div class="row g-3">
                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size"
                                id="sidenav-size-small-hover-active" value="default">
                            <label class="form-check-label p-0 w-100" for="sidenav-size-small-hover-active">
                                <img src="{{ asset('admin/assets/images/layouts/light.svg') }}" alt="layout-img" class="img-fluid">
                            </label>
                        </div>
                        <h5 class="mb-0 fs-base text-center text-muted mt-2">{{ x_('Default', 'dashboards.shared.theme_settings') }}</h5>
                    </div>

                    <div class="col-4">
                        <div class="form-check sidebar-setting card-radio">
                            <input class="form-check-input" type="radio" name="data-sidenav-size"
                                id="sidenav-size-small-hover" value="collapse">
                            <label class="form-check-label p-0 w-100" for="sidenav-size-small-hover">
                                <img src="{{ asset('admin/assets/images/layouts/sidebar-condensed.svg') }}" alt="layout-img"
                                    class="img-fluid">
                            </label>
                        </div>
                        <h5 class="mb-0 text-center text-muted mt-2">{{ x_('Collapse', 'dashboards.shared.theme_settings') }}</h5>
                    </div>
                </div>
            </div>

            <div class="p-3 border-bottom border-dashed">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">{{ x_('Layout Position', 'dashboards.shared.theme_settings') }}</h5>
                    <div class="btn-group radio" role="group">
                        <input type="radio" class="btn-check" name="data-layout-position"  id="layout-position-fixed" value="fixed">
                        <label class="btn btn-sm btn-soft-primary w-sm" for="layout-position-fixed">{{ x_('Fixed', 'dashboards.shared.theme_settings') }}</label>
                        <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-scrollable" value="scrollable">
                        <label class="btn btn-sm btn-soft-primary w-sm ms-0" for="layout-position-scrollable">{{ x_('Scrollable', 'dashboards.shared.theme_settings') }}</label>
                    </div>
                </div>
            </div>
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><label class="fw-bold m-0" for="sidebaruser-check">{{ x_('Sidebar User Info', 'dashboards.shared.theme_settings') }}</label>
                    </h5>
                    <div class="form-check form-switch fs-lg">
                        <input type="checkbox" class="form-check-input" name="sidebar-user" id="sidebaruser-check">
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
