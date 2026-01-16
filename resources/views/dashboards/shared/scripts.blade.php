
    <!-- Vendor js -->
    <script src="{{ asset('dashboard/assets/js/vendors.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('dashboard/assets/js/app.js') }}"></script>

    <!-- Dashboard Page js -->
    @if (request()->routeIs('dashboard'))
        <script src="{{ asset('dashboard/assets/js/pages/dashboard.js') }}"></script>
    @endif

    <script src="{{asset('dashboard/assets/plugins/fullcalendar/index.global.min.js')  }}"></script>

    <script>
        (function () {
            function ensureToastContainer() {
                const existingSuccessToast = document.getElementById('successToast');
                const existingErrorToast = document.getElementById('errorToast');
                if (existingSuccessToast && existingErrorToast) {
                    const existingContainer = existingSuccessToast.closest('.toast-container');
                    if (existingContainer) {
                        existingContainer.classList.remove('bottom-0');
                        existingContainer.classList.add('position-fixed', 'top-0', 'end-0', 'p-3');
                        existingContainer.style.zIndex = existingContainer.style.zIndex || '9999';
                    }
                    return;
                }

                const container = document.createElement('div');
                container.className = 'toast-container position-fixed top-0 end-0 p-3';
                container.style.zIndex = '9999';
                container.innerHTML = `
                    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body d-flex align-items-center">
                                <i class="ti ti-check-circle fs-5 me-2"></i>
                                <span id="successToastMessage">Success!</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body d-flex align-items-center">
                                <i class="ti ti-alert-circle fs-5 me-2"></i>
                                <span id="errorToastMessage">Error!</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;
                document.body.appendChild(container);
            }

            window.showSuccessToast = function (message) {
                ensureToastContainer();
                const toastEl = document.getElementById('successToast');
                const messageEl = document.getElementById('successToastMessage');
                if (!toastEl || !messageEl || typeof bootstrap === 'undefined') {
                    return;
                }
                messageEl.textContent = message || 'Success!';
                new bootstrap.Toast(toastEl, { delay: 8000 }).show();
            };

            window.showErrorToast = function (message) {
                ensureToastContainer();
                const toastEl = document.getElementById('errorToast');
                const messageEl = document.getElementById('errorToastMessage');
                if (!toastEl || !messageEl || typeof bootstrap === 'undefined') {
                    return;
                }
                messageEl.textContent = message || 'An error occurred!';
                new bootstrap.Toast(toastEl, { delay: 10000 }).show();
            };

            document.addEventListener('DOMContentLoaded', ensureToastContainer);
        })();
    </script>
