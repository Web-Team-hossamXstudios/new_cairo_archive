<!-- Sidenav Menu Start -->
<div class="sidenav-menu">
    <div class="scrollbar" data-simplebar>
              <!-- Create Order -->
            {{-- <li class="side-nav-item"> --}}
                    {{-- <a href="{{ route('admin.orders.create') }}"
                        class="side-nav-link d-flex align-items-center gap-2 btn btn-primary">
                        <span class="menu-icon ">
                            <i class="ti ti-plus"></i>
                            <span>New Order</span>
                        </span>
                    </a> --}}
            {{-- </li> --}}
        <!-- User -->

        <div class="sidenav-user text-nowrap border border-dashed rounded-3">
            <a href="{{ route('admin.dashboard') }}" class="sidenav-user-name d-flex align-items-center">
                <img src="{{ asset('logo.jpg') }}" width="50"
                    class="rounded-circle me-2 d-flex" alt="user-image">
                <span>
                    <h5 class="my-0 fw-semibold">
                        {{ auth()->user()->first_name . ' ' . auth()->user()->last_name ?? x('Admin User', 'dashboards.shared.sidebar') }}
                    </h5>
                    <small
                        class="text-muted">{{ auth()->user()->roles->first()->name ?? x('Administrator', 'dashboards.shared.sidebar') }}</small>
                </span>
            </a>
        </div>
        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <!-- Dashboard -->
            @can('view-dashboard')
                <li class="side-nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="side-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                        <span class="menu-text">{{ x_('Dashboard', 'dashboards.shared.sidebar') }}</span>
                    </a>
                </li>
            @endcan
            <!-- Orders Module -->
          @canany(['view-orders', 'view-order-items', 'view-order-status-history', 'view-invoices', 'view-payments'])
              <li class="side-nav-item">
                  <a data-bs-toggle="collapse" href="#sidebarOrders"
                      aria-expanded="{{ request()->routeIs('admin.orders*', 'admin.invoices*', 'admin.payments*','admin.order-items*', 'admin.order-status-history*') ? 'true' : 'false' }}"
                      class="side-nav-link">
                      <span class="menu-icon"><i class="ti ti-shopping-cart"></i></span>
                      <span class="menu-text">{{ x_('Orders', 'dashboards.shared.sidebar') }}</span>
                      <span class="menu-arrow"></span>
                  </a>
                  <div class="collapse {{ request()->routeIs('admin.orders*', 'admin.invoices*', 'admin.payments*', 'admin.order-items*', 'admin.order-status-history*') ? 'show' : '' }}"
                      id="sidebarOrders">
                      <ul class="sub-menu">
                          @can('view-orders')
                              <li class="side-nav-item">
                                  <a href="{{ route('admin.orders.index') }}"
                                      class="side-nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                                      <span class="menu-text">{{ x_('All Orders', 'dashboards.shared.sidebar') }}</span>
                                  </a>
                              </li>
                          @endcan
                          {{-- @can('view-order-items')
                              <li class="side-nav-item">
                                  <a href="{{ route('admin.order-items.index') }}"
                                      class="side-nav-link {{ request()->routeIs('admin.order-items*') ? 'active' : '' }}">
                                      <span class="menu-text">{{ x_('Order Items ', 'dashboards.shared.sidebar') }}</span>
                                  </a>
                              </li>
                          @endcan --}}
                          {{-- @can('view-order-status-history')
                              <li class="side-nav-item">
                                  <a href="{{ route('admin.order-status-history.index') }}"
                                      class="side-nav-link {{ request()->routeIs('admin.order-status-history*') ? 'active' : '' }}">
                                      <span
                                          class="menu-text">{{ x_('Order Status History', 'dashboards.shared.sidebar') }}</span>
                                  </a>
                              </li>
                          @endcan --}}
                          @can('view-invoices')
                              <li class="side-nav-item">
                                  <a href="{{ route('admin.invoices.index') }}"
                                      class="side-nav-link {{ request()->routeIs('admin.invoices*') ? 'active' : '' }}">
                                      <span class="menu-text">{{ x_('Invoices', 'dashboards.shared.sidebar') }}</span>
                                  </a>
                              </li>
                          @endcan
                          @can('view-payments')
                              <li class="side-nav-item">
                                  <a href="{{ route('admin.payments.index') }}"
                                      class="side-nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                                      <span class="menu-text">{{ x_('Payments', 'dashboards.shared.sidebar') }}</span>
                                  </a>
                              </li>
                          @endcan
                      </ul>
                  </div>
              </li>
          @endcanany
            <!-- Reports -->

            @canany(['view-reports', 'view-analytics'])
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarReports"
                        aria-expanded="{{ request()->routeIs('admin.reports*') ? 'true' : 'false' }}" class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-chart-bar"></i></span>
                        <span class="menu-text">{{ x_('Reports', 'dashboards.shared.sidebar') }}</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.reports*') ? 'show' : '' }}" id="sidebarReports">
                        <ul class="sub-menu">
                            @can('view-reports')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.reports.financial') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.reports.financial*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Financial Reports', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.reports.hr') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.reports.hr*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('HR Reports', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.reports.sales') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.reports.sales*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Sales Reports', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.reports.inventory') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.reports.inventory*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Inventory Reports', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany
            <!-- Accounting Module -->
            @canany(['view-payment-methods', 'view-accounts', 'view-income-sources', 'view-expense-sources',
                'view-income-transactions', 'view-expense-transactions', 'view-account-transfers', 'view-journal'])
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarAccounting"
                        aria-expanded="{{ request()->routeIs('admin.payment-methods*', 'admin.accounts*', 'admin.income-sources*', 'admin.expense-sources*', 'admin.income-transactions*', 'admin.expense-transactions*', 'admin.account-transfers*', 'admin.journal*') ? 'true' : 'false' }}"
                        class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-cash"></i></span>
                        <span class="menu-text">{{ x_('Accounting', 'dashboards.shared.sidebar') }}</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.payment-methods*', 'admin.accounts*', 'admin.income-sources*', 'admin.expense-sources*', 'admin.income-transactions*', 'admin.expense-transactions*', 'admin.account-transfers*', 'admin.journal*') ? 'show' : '' }}"
                        id="sidebarAccounting">
                        <ul class="sub-menu">
                            @can('view-journal')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.journal.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.journal*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Journal', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-accounts')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.accounts.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.accounts*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Accounts', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-payment-methods')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.payment-methods.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.payment-methods*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Payment Methods', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view-income-sources')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.income-sources.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.income-sources*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Income Sources', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-expense-sources')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.expense-sources.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.expense-sources*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Expense Sources', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-income-transactions')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.income-transactions.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.income-transactions*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Income Transactions', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-expense-transactions')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.expense-transactions.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.expense-transactions*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Expense Transactions', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-account-transfers')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.account-transfers.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.account-transfers*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Account Transfers', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </div>
                </li>
            @endcanany

            <!-- HRMS Module -->
            @canany(['view-departments', 'view-hr-jobs', 'view-employees', 'view-attendance-records',
                'view-piece-production', 'view-leave-types', 'view-leave-requests', 'view-public-holidays',
                'view-payroll-cycles', 'view-warnings', 'view-custodies', 'view-salaries', 'view-payslips'])
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarHRMS"
                        aria-expanded="{{ request()->routeIs('admin.departments*', 'admin.hr-jobs*', 'admin.employees*', 'admin.attendance*', 'admin.piece-production*', 'admin.leave-types*', 'admin.leave-requests*', 'admin.public-holidays*', 'admin.payroll-cycles*', 'admin.warnings*', 'admin.custodies*', 'admin.terminations*', 'admin.leave-balances*', 'admin.salaries*', 'admin.payslips*') ? 'true' : 'false' }}"
                        class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-users-group"></i></span>
                        <span class="menu-text">{{ x_('HRMS', 'dashboards.shared.sidebar') }}</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.departments*', 'admin.hr-jobs*', 'admin.employees*', 'admin.attendance*', 'admin.piece-production*', 'admin.leave-types*', 'admin.leave-requests*', 'admin.public-holidays*', 'admin.payroll-cycles*', 'admin.warnings*', 'admin.custodies*', 'admin.terminations*', 'admin.leave-balances*', 'admin.salaries*', 'admin.payslips*') ? 'show' : '' }}"
                        id="sidebarHRMS">
                        <ul class="sub-menu">
                            @canany(['view-departments', 'view-hr-jobs', 'view-employees', 'view-terminations',
                                'view-warnings', 'view-custodies', 'view-attendance-records', 'view-piece-production'])
                                <li class="side-nav-item">
                                    <a data-bs-toggle="collapse" href="#sidebarHRManagement"
                                        aria-expanded="{{ request()->routeIs('admin.departments*', 'admin.hr-jobs*', 'admin.employees*', 'admin.terminations*', 'admin.warnings*', 'admin.custodies*', 'admin.piece-production*', 'admin.attendance*', 'admin.terminations*') ? 'true' : 'false' }}"
                                        class="side-nav-link">
                                        <span class="menu-icon"><i class="ti ti-users"></i></span>
                                        <span class="menu-text">{{ x_('HR Management', 'dashboards.shared.sidebar') }}</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse {{ request()->routeIs('admin.departments*', 'admin.hr-jobs*', 'admin.employees*', 'admin.terminations*', 'admin.warnings*', 'admin.custodies*', 'admin.piece-production*', 'admin.attendance*', 'admin.terminations*') ? 'show' : '' }}"
                                        id="sidebarHRManagement">
                                        <ul class="sub-menu">
                                            @can('view-departments')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.departments.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.departments*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Departments', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view-hr-jobs')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.hr-jobs.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.hr-jobs*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Jobs', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view-employees')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.employees.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.employees*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Employees', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view-attendance-records')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.attendance.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.attendance*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Attendance', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('view-custodies')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.custodies.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.custodies*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Custodies', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('view-piece-production')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.piece-production.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.piece-production*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Piece Production', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view-warnings')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.warnings.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.warnings*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Warnings', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view-terminations')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.terminations.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.terminations*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Terminations', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                            @endcanany
                            @canany(['view-leave-types', 'view-leave-balances', 'view-leave-requests',
                                'view-public-holidays'])
                                <li class="side-nav-item">
                                    <a data-bs-toggle="collapse" href="#sidebarLeave"
                                        aria-expanded="{{ request()->routeIs('admin.leave-types*', 'admin.leave-balances*', 'admin.leave-requests*', 'admin.public-holidays*') ? 'true' : 'false' }}"
                                        class="side-nav-link">
                                        <span class="menu-icon"><i class="ti ti-calendar-off"></i></span>
                                        <span
                                            class="menu-text">{{ x_('Leave Management', 'dashboards.shared.sidebar') }}</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse {{ request()->routeIs('admin.leave-types*', 'admin.leave-balances*', 'admin.leave-requests*', 'admin.public-holidays*') ? 'show' : '' }}"
                                        id="sidebarLeave">
                                        <ul class="sub-menu">
                                            @can('view-leave-types')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.leave-types.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.leave-types*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Leave Types', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view-leave-balances')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.leave-balances.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.leave-balances*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Leave Balances', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view-leave-requests')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.leave-requests.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.leave-requests*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Leave Requests', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view-public-holidays')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.public-holidays.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.public-holidays*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Public Holidays', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                            @endcanany
                            @canany(['view-payroll-cycles', 'view-salaries', 'view-payslips'])
                                <li class="side-nav-item">
                                    <a data-bs-toggle="collapse" href="#sidebarPayroll"
                                        aria-expanded="{{ request()->routeIs('admin.payroll-cycles*', 'admin.salaries*', 'admin.payslips*') ? 'true' : 'false' }}"
                                        class="side-nav-link">
                                        <span class="menu-icon"><i class="ti ti-cash-banknote"></i></span>
                                        <span
                                            class="menu-text">{{ x_('Payroll Management', 'dashboards.shared.sidebar') }}</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse {{ request()->routeIs('admin.payroll-cycles*', 'admin.salaries*', 'admin.payslips*') ? 'show' : '' }}"
                                        id="sidebarPayroll">
                                        <ul class="sub-menu">
                                            @can('view-payroll-cycles')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.payroll-cycles.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.payroll-cycles*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Payroll Cycles', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view-salaries')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.salaries.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.salaries*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Salaries', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('view-payslips')
                                                <li class="side-nav-item">
                                                    <a href="{{ route('admin.payslips.index') }}"
                                                        class="side-nav-link {{ request()->routeIs('admin.payslips*') ? 'active' : '' }}">
                                                        <span
                                                            class="menu-text">{{ x_('Payslips', 'dashboards.shared.sidebar') }}</span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                            @endcanany
                        </ul>
                    </div>
                </li>
            @endcanany

            <!-- Clients Module -->
            @canany(['view-client-categories', 'view-clients', 'view-patterns', 'view-client-profiles'])
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarClients"
                        aria-expanded="{{ request()->routeIs('admin.client-categories*', 'admin.clients*', 'admin.patterns*', 'admin.client-profiles*') ? 'true' : 'false' }}"
                        class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-user-check"></i></span>
                        <span class="menu-text">{{ x_('Clients', 'dashboards.shared.sidebar') }}</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.client-categories*', 'admin.clients*', 'admin.patterns*', 'admin.client-profiles*') ? 'show' : '' }}"
                        id="sidebarClients">
                        <ul class="sub-menu">
                            @can('view-client-categories')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.client-categories.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.client-categories*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Client Categories', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-clients')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.clients.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.clients.index') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Clients', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-patterns')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.patterns.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.patterns*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Patterns', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            {{-- @can('view-client-profiles')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.client-profiles.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.client-profiles*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Client Profiles', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan --}}
                        </ul>
                    </div>
                </li>
            @endcanany

            <!-- Products Module -->
            @canany(['view-material-types', 'view-material-patterns', 'view-materials', 'view-buttons', 'view-padding',
                'view-lining', 'view-items', 'view-additional-items' ,])
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarProducts"
                        aria-expanded="{{ request()->routeIs('admin.material-types*', 'admin.material-patterns*', 'admin.materials*', 'admin.buttons*', 'admin.padding*', 'admin.lining*', 'admin.suits*', 'admin.additional-items*','admin.items*','admin.products*') ? 'true' : 'false' }}"
                        class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-box"></i></span>
                        <span class="menu-text">{{ x_('Our Products', 'dashboards.shared.sidebar') }}</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.material-types*', 'admin.material-patterns*', 'admin.materials*', 'admin.buttons*', 'admin.padding*', 'admin.lining*', 'admin.suits*', 'admin.additional-items*','admin.items*','admin.products*') ? 'show' : '' }}"
                        id="sidebarProducts">
                        <ul class="sub-menu">
                            @can('view-products')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.products.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Products', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view-items')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.items.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.items*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Main Items', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-additional-items')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.additional-items.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.additional-items*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Additional Items', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-material-types')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.material-types.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.material-types*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Material Types', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-material-patterns')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.material-patterns.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.material-patterns*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Material Patterns', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-materials')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.materials.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.materials*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Materials', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-buttons')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.buttons.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.buttons*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Buttons', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-padding')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.padding.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.padding*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Padding', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-lining')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.lining.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.lining*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Lining', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </div>
                </li>
            @endcanany


            <!-- Suppliers Module -->
            @canany(['view-suppliers', 'view-purchase-orders', 'view-purchase-order-items', 'view-supplier-products',
                'view-supplier-payments'])
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarSuppliers"
                        aria-expanded="{{ request()->routeIs('admin.suppliers*', 'admin.purchase-orders*', 'admin.supplier-payments*' , 'admin.purchase-order-items*', 'admin.supplier-products*') ? 'true' : 'false' }}"
                        class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-truck"></i></span>
                        <span class="menu-text">{{ x_('Suppliers', 'dashboards.shared.sidebar') }}</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.suppliers*', 'admin.purchase-orders*', 'admin.supplier-payments*', 'admin.purchase-order-items*', 'admin.supplier-products*') ? 'show' : '' }}"
                        id="sidebarSuppliers">
                        <ul class="sub-menu">
                            @can('view-suppliers')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.suppliers.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.suppliers.index') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Suppliers', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-purchase-orders')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.purchase-orders.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.purchase-orders*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Purchase Orders', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-purchase-order-items')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.purchase-order-items.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.purchase-order-items*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Purchase Order Items', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-supplier-products')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.supplier-products.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.supplier-products*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Supplier Products', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-supplier-payments')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.supplier-payments.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.supplier-payments*') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Supplier Payments', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            <!-- Users & Roles Module -->
            @canany(['view-users', 'view-roles'])
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarUsersRoles"
                        aria-expanded="{{ request()->routeIs('admin.users*', 'admin.roles*', 'admin.permissions*') ? 'true' : 'false' }}"
                        class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-shield-check"></i></span>
                        <span class="menu-text">{{ x_('Users & Roles', 'dashboards.shared.sidebar') }}</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.users*', 'admin.roles*', 'admin.permissions*') ? 'show' : '' }}"
                        id="sidebarUsersRoles">
                        <ul class="sub-menu">
                            @can('view-users')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Users', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-roles')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Roles', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            {{-- Employee Portal - Only show if user has access-employee-portal permission --}}
            @can('access-employee-portal')
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse show" href="#sidebarEmployeePortal"
                        aria-expanded="{{ request()->routeIs('employee.permanent.*') ? 'true' : 'false' }}"
                        class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-user-circle"></i></span>
                        <span class="menu-text">{{ x_('My Portal', 'dashboards.shared.sidebar') }}</span>

                    </a>
                    <div class="collapse show" id="sidebarEmployeePortal">
                        <ul class="sub-menu">
                            @can('view-own-dashboard')
                                <li class="side-nav-item">
                                    <a href="{{ route('employee.permanent.dashboard') }}"
                                        class="side-nav-link {{ request()->routeIs('employee.permanent.dashboard') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Dashboard', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-own-attendance')
                                <li class="side-nav-item">
                                    <a href="{{ route('employee.permanent.attendance.index') }}"
                                        class="side-nav-link {{ request()->routeIs('employee.permanent.attendance.*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('My Attendance', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-own-leaves')
                                <li class="side-nav-item">
                                    <a href="{{ route('employee.permanent.leaves.index') }}"
                                        class="side-nav-link {{ request()->routeIs('employee.permanent.leaves.*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('My Leaves', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-own-payslips')
                                <li class="side-nav-item">
                                    <a href="{{ route('employee.permanent.payslips.index') }}"
                                        class="side-nav-link {{ request()->routeIs('employee.permanent.payslips.*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('My Payslips', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-own-custodies')
                                <li class="side-nav-item">
                                    <a href="{{ route('employee.permanent.custodies.index') }}"
                                        class="side-nav-link {{ request()->routeIs('employee.permanent.custodies.*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('My Custodies', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-own-warnings')
                                <li class="side-nav-item">
                                    <a href="{{ route('employee.permanent.warnings.index') }}"
                                        class="side-nav-link {{ request()->routeIs('employee.permanent.warnings.*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('My Warnings', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcan

            @can('access-daily-worker-portal')
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse show" href="#sidebarDailyEmployeePortal"
                        aria-expanded="{{ request()->routeIs('employee.daily.*') ? 'true' : 'false' }}"
                        class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-user-circle"></i></span>
                        <span class="menu-text">{{ x_('Daily Portal', 'dashboards.shared.sidebar') }}</span>

                    </a>
                    <div class="collapse show" id="sidebarDailyEmployeePortal">
                        <ul class="sub-menu">
                            @can('view-own-production')
                                <li class="side-nav-item">
                                    <a href="{{ route('employee.daily.production.index') }}"
                                        class="side-nav-link {{ request()->routeIs('employee.daily.production.index') || request()->routeIs('employee.daily.production.show') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('My Production', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-own-production')
                                <li class="side-nav-item">
                                    <a href="{{ route('employee.daily.production.summary') }}"
                                        class="side-nav-link {{ request()->routeIs('employee.daily.production.summary') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Summary', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('create-own-production')
                                <li class="side-nav-item">
                                    <a href="{{ route('employee.daily.production.create') }}"
                                        class="side-nav-link {{ request()->routeIs('employee.daily.production.create') ? 'active' : '' }}">
                                        <span
                                            class="menu-text">{{ x_('Add Production', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcan




            {{-- Website Management --}}
            @canany(['view-website-home', 'view-website-about', 'view-website-videos', 'view-website-contact', 'view-website-gallery'])
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarWebsiteManagement"
                        aria-expanded="{{ request()->routeIs('admin.website.*') ? 'true' : 'false' }}"
                        class="side-nav-link">
                        <span class="menu-icon"><i class="ti ti-world"></i></span>
                        <span class="menu-text">{{ x_('Website Management', 'dashboards.shared.sidebar') }}</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.website.*') ? 'show' : '' }}" id="sidebarWebsiteManagement">
                        <ul class="sub-menu">
                            @can('view-website-home')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.website.home.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.website.home.*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Home', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-website-about')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.website.about.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.website.about.*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('About', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view-website-videos')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.website.videos.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.website.videos.*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Videos', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                            {{-- @can('view-website-contact')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.website.contact.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.website.contact.*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Contact', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan --}}
                            @can('view-website-gallery')
                                <li class="side-nav-item">
                                    <a href="{{ route('admin.website.gallery.index') }}"
                                        class="side-nav-link {{ request()->routeIs('admin.website.gallery.*') ? 'active' : '' }}">
                                        <span class="menu-text">{{ x_('Gallery', 'dashboards.shared.sidebar') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcanany

            {{-- Inquiries --}}
            @can('view-inquiries')
                <li class="side-nav-item">
                    <a href="{{ route('admin.inquiries.index') }}"
                        class="side-nav-link {{ request()->routeIs('admin.inquiries*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-mail"></i></span>
                        <span class="menu-text">{{ x_('Inquiries', 'dashboards.shared.sidebar') }}</span>
                    </a>
                </li>
            @endcan

            {{-- Settings --}}
            @can('view-settings')
                <li class="side-nav-item">
                    <a href="{{ route('admin.settings.index') }}"
                        class="side-nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="ti ti-settings"></i></span>
                        <span class="menu-text">{{ x_('Settings', 'dashboards.shared.sidebar') }}</span>
                    </a>
                </li>
            @endcan



            {{-- <div class="menu-collapse-box d-none d-xl-block">
                <button class="button-collapse-toggle">
                    <i data-lucide="square-chevron-left" class="align-middle flex-shrink-0"></i>
                    <span>Collapse Menu</span>
                </button>
            </div> --}}
        </ul>
    </div>
</div>
<!-- Sidenav Menu End -->
