<!-- Show User Modal -->
<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h4 class="modal-title fw-bold" id="showModalLabel">{{ x_('User Details', 'dashboards.admin.pages.user.users.partials.show-modal') }}</h4>
                    <p class="text-muted mb-0">View user account information</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ x_('Close', 'dashboards.admin.pages.user.users.partials.show-modal') }}"></button>
            </div>
            <div class="modal-body pt-3" id="showContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">{{ x_('Close', 'dashboards.admin.pages.user.users.partials.show-modal') }}</button>
            </div>
        </div>
    </div>
</div>
