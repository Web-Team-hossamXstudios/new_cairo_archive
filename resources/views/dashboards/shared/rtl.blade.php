@if(app()->getLocale() === 'ar')
    <style id="rtl-styles">
        :root {
            --rtl-sidebar-width: 260px;
        }

        body {
            direction: rtl;
            text-align: right;
        }

        body .wrapper {
            direction: rtl;
        }

        /* Sidebar */
        body .sidenav-menu {
            position: fixed;
            top: 0;
            right: 0;
            left: auto;
            bottom: 0;
            width: var(--rtl-sidebar-width);
            border-right: none;
            border-left: 1px solid var(--bs-border-color);
            background: var(--bs-body-bg, #fff);
            z-index: 1030;
        } */

        body .sidenav-menu .scrollbar {
            height: 100%;
        }

        body .sidenav-menu .side-nav-link i,
        body .sidenav-menu .side-nav-title-icon {
            margin-left: .75rem;
            margin-right: 0;
        }

        /* Content */
        body .content-page {
            margin-right: var(--rtl-sidebar-width);
            margin-left: 0;
            
        }

        @media (max-width: 991.98px) {
            body .sidenav-menu {
                position: fixed;
                transform: translateX(100%);
            }

            body .content-page {
                margin-right: 0;
            }
        }

        /* Topbar */
        body .topbar {
            direction: rtl;
        }

        body .topbar .navbar-nav {
            flex-direction: row-reverse;
        }

        body .dropdown-menu {
            right: auto;
            left: 0;
            text-align: right;
        }

        /* Utilities */
        body .text-start {
            text-align: right !important;
        }

        body .text-end {
            text-align: left !important;
        }

        body .ms-2 {
            margin-right: .5rem !important;
            margin-left: 0 !important;
        }

        body .me-2 {
            margin-left: .5rem !important;
            margin-right: 0 !important;
        }

        /* Tables */
        body table.table th,
        body table.table td {
            text-align: right;
        }

        /* Forms */
        body .form-control {
            text-align: right;
        }

        body .form-check {
            padding-left: 0;
            padding-right: 1.5rem;
        }

        body .form-check-input {
            margin-left: 0;
            margin-right: -1.5rem;
        }

        /* Modals */
        body .modal {
            text-align: right;
        }

        body .modal-header .btn-close {
            margin-right: auto;
            margin-left: 0;
        }

        body .modal-footer {
            justify-content: flex-start;
        }
    </style>
@endif
