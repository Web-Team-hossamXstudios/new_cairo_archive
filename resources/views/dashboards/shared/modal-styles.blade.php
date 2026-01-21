<style>
/* Professional Modal Styles */
.modal {
    z-index: 1060 !important;
}

.modal-backdrop {
    z-index: 1050 !important;
}

.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    position: relative;
    z-index: 1061 !important;
}

.modal-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    padding: 1.25rem 1.5rem;
}

.modal-header.modal-header-primary {
    background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
    color: white;
}

.modal-header.modal-header-success {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    color: white;
}

.modal-header.modal-header-warning {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
    color: white;
}

.modal-header.modal-header-danger {
    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    color: white;
}

.modal-header.modal-header-info {
    background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
    color: white;
}

.modal-header .modal-title {
    font-weight: 600;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.modal-header .btn-close:hover {
    opacity: 1;
}

.modal-body {
    padding: 1.5rem;
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

.modal-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.08);
    padding: 1rem 1.5rem;
    background: #f8fafc;
}

/* Form Sections */
.form-section {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1.25rem;
    margin-bottom: 1.25rem;
    border: 1px solid #e2e8f0;
}

.form-section-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e2e8f0;
}

.form-section-header i {
    font-size: 1.25rem;
    color: #000000;
}

.form-section-header h6 {
    margin: 0;
    font-weight: 600;
    color: #1e293b;
    font-size: 0.95rem;
}

.form-section-header .badge {
    margin-right: auto;
}

/* Enhanced Form Controls */
.form-label {
    font-weight: 500;
    color: #475569;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.form-label .text-danger {
    font-size: 0.75rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1.5px solid #e2e8f0;
    padding: 0.625rem 0.875rem;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #000000;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.15);
}

.form-control::placeholder {
    color: #94a3b8;
}

.form-control-sm, .form-select-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
}

/* Input Groups */
.input-group .form-control, .input-group .form-select {
    border-radius: 8px;
}

.input-group .btn {
    border-radius: 8px;
    margin-right: -1px;
}

.input-group > :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
    margin-right: -1px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.input-group > :not(:last-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

/* Enhanced Buttons */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1a1a1a 0%, #333333 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    border: none;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
    background: linear-gradient(135deg, #047857 0%, #059669 100%);
    transform: translateY(-1px);
}

.btn-danger {
    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
    border: none;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
    transform: translateY(-1px);
}

.btn-warning {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
    border: none;
    color: white;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
}

.btn-warning:hover {
    background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
    color: white;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #475569;
}

.btn-secondary:hover {
    background: #e2e8f0;
    border-color: #cbd5e1;
    color: #334155;
}

.btn-outline-primary {
    border: 1.5px solid #000000;
    color: #000000;
}

.btn-outline-primary:hover {
    background: #000000;
    border-color: #000000;
    color: white;
}

/* Cards within modals */
.modal .card {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.modal .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 0.75rem 1rem;
    font-weight: 600;
}

.modal .card-body {
    padding: 1rem;
}

/* Data display in show modals */
.data-row {
    display: flex;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.data-row:last-child {
    border-bottom: none;
}

.data-label {
    font-weight: 600;
    color: #64748b;
    min-width: 140px;
    font-size: 0.875rem;
}

.data-value {
    color: #1e293b;
    flex: 1;
}

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-badge-success {
    background: #dcfce7;
    color: #15803d;
}

.status-badge-warning {
    background: #fef3c7;
    color: #a16207;
}

.status-badge-danger {
    background: #fee2e2;
    color: #b91c1c;
}

.status-badge-info {
    background: #e0f2fe;
    color: #0369a1;
}

/* Info boxes */
.info-box {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border: 1px solid #93c5fd;
    border-radius: 10px;
    padding: 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.info-box i {
    color: #3b82f6;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.info-box-content {
    flex: 1;
}

.info-box-title {
    font-weight: 600;
    color: #1e40af;
    margin-bottom: 0.25rem;
}

.info-box-text {
    color: #3b82f6;
    font-size: 0.875rem;
}

/* Warning boxes */
.warning-box {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border: 1px solid #fcd34d;
    border-radius: 10px;
    padding: 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.warning-box i {
    color: #f59e0b;
    font-size: 1.25rem;
    flex-shrink: 0;
}

/* Danger boxes */
.danger-box {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border: 1px solid #fca5a5;
    border-radius: 10px;
    padding: 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.danger-box i {
    color: #ef4444;
    font-size: 1.25rem;
    flex-shrink: 0;
}

/* Avatar/Icon circles */
.modal-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.modal-icon i {
    font-size: 2.5rem;
}

.modal-icon-primary {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: #3b82f6;
}

.modal-icon-success {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    color: #10b981;
}

.modal-icon-warning {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    color: #f59e0b;
}

.modal-icon-danger {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    color: #ef4444;
}

.modal-icon-info {
    background: linear-gradient(135deg, #ecfeff 0%, #cffafe 100%);
    color: #06b6d4;
}

/* Loading states */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 12px;
}

.loading-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #e2e8f0;
    border-top-color: #000000;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Tabs in modals */
.modal .nav-tabs {
    border-bottom: 2px solid #e2e8f0;
    gap: 0.5rem;
}

.modal .nav-tabs .nav-link {
    border: none;
    border-radius: 8px 8px 0 0;
    padding: 0.75rem 1.25rem;
    color: #64748b;
    font-weight: 500;
    background: transparent;
}

.modal .nav-tabs .nav-link:hover {
    color: #000000;
    background: #f8fafc;
}

.modal .nav-tabs .nav-link.active {
    color: #000000;
    background: #eff6ff;
    border-bottom: 2px solid #000000;
    margin-bottom: -2px;
}

/* Animations */
.modal.fade .modal-dialog {
    transform: scale(0.9) translateY(-20px);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: scale(1) translateY(0);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-body {
        padding: 1rem;
    }

    .form-section {
        padding: 1rem;
    }

    .data-row {
        flex-direction: column;
        gap: 0.25rem;
    }

    .data-label {
        min-width: auto;
    }
}
</style>
