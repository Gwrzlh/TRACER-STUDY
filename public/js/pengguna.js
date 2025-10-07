document.addEventListener('DOMContentLoaded', () => {
    const penggunaPage = document.querySelector('.pengguna-page');
    if (!penggunaPage) return;

    console.log('Pengguna Index page initializing...');

    // Table Row Animation
    const animateTableRows = () => {
        const tableRows = penggunaPage.querySelectorAll('.modern-table tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            setTimeout(() => {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
    };

    // Search Input Enhancement
    const enhanceSearchInput = () => {
        const searchInput = penggunaPage.querySelector('.search-input');
        if (!searchInput) return;

        searchInput.addEventListener('focus', function () {
            this.closest('.search-wrapper').style.transform = 'scale(1.02)';
            this.closest('.search-wrapper').style.transition = 'all 0.3s ease';
        });

        searchInput.addEventListener('blur', function () {
            this.closest('.search-wrapper').style.transform = 'scale(1)';
        });

        searchInput.addEventListener('input', function () {
            const searchBtn = penggunaPage.querySelector('.search-btn');
            if (this.value.length >= 3) {
                searchBtn.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
                searchBtn.innerHTML = '<i class="fas fa-search"></i> Search Ready';
            } else if (this.value.length === 0) {
                searchBtn.style.background = 'linear-gradient(135deg, #3b82f6, #1e40af)';
                searchBtn.innerHTML = '<i class="fas fa-search"></i> Search';
            } else {
                searchBtn.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';
                searchBtn.innerHTML = '<i class="fas fa-search"></i> Type more...';
            }
        });
    };

    // Button Enhancements
    const enhanceButtons = () => {
        const buttons = penggunaPage.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function () {
                if (!this.disabled) {
                    this.style.transform = 'translateY(-2px) scale(1.05)';
                }
            });

            button.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0) scale(1)';
            });

            button.addEventListener('click', function () {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'translateY(0) scale(1)';
                }, 150);
            });
        });
    };

    // Form Validation
    const enhanceFormValidation = () => {
        const forms = penggunaPage.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                const requiredInputs = this.querySelectorAll('[required]');
                let isValid = true;
                let firstInvalidInput = null;

                requiredInputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        if (!firstInvalidInput) firstInvalidInput = input;
                        input.style.borderColor = '#ef4444';
                        input.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
                        input.style.animation = 'shake 0.5s ease-in-out';

                        setTimeout(() => {
                            input.style.borderColor = '#e2e8f0';
                            input.style.boxShadow = 'none';
                            input.style.animation = 'none';
                        }, 3000);
                    } else {
                        input.style.borderColor = '#22c55e';
                        input.style.boxShadow = '0 0 0 3px rgba(34, 197, 94, 0.1)';

                        setTimeout(() => {
                            input.style.borderColor = '#e2e8f0';
                            input.style.boxShadow = 'none';
                        }, 1000);
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    if (firstInvalidInput) {
                        firstInvalidInput.focus();
                        firstInvalidInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    showToast('Mohon lengkapi semua field yang diperlukan.', 'error');
                }
            });
        });
    };

    // Alert Auto-Dismiss
    const enhanceAlerts = () => {
        const alerts = penggunaPage.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const progressBar = document.createElement('div');
            progressBar.style.cssText = `
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                background: rgba(255, 255, 255, 0.3);
                width: 100%;
                transform-origin: left;
                animation: progressBar 5s linear forwards;
            `;
            alert.style.position = 'relative';
            alert.appendChild(progressBar);

            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (alert.parentNode) alert.parentNode.removeChild(alert);
                }, 500);
            }, 5000);
        });
    };

    // Responsive Table
    const handleResponsiveTable = () => {
        const table = penggunaPage.querySelector('.modern-table');
        if (!table) return;

        const isMobile = window.innerWidth <= 480;
        if (isMobile) {
            const cells = table.querySelectorAll('tbody td');
            const headers = ['No', 'Nama Pengguna', 'Email', 'Status', 'Group', 'Aksi'];
            cells.forEach((cell, index) => {
                const headerIndex = index % headers.length;
                cell.setAttribute('data-label', headers[headerIndex]);
            });
        }
    };

    // Modal Animations
    const enhanceModals = () => {
        const modals = penggunaPage.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function () {
                const modalContent = this.querySelector('.modal-content');
                modalContent.style.transform = 'scale(0.7) translateY(-50px)';
                modalContent.style.opacity = '0';

                setTimeout(() => {
                    modalContent.style.transition = 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
                    modalContent.style.transform = 'scale(1) translateY(0)';
                    modalContent.style.opacity = '1';
                }, 100);
            });

            modal.addEventListener('hide.bs.modal', function () {
                const modalContent = this.querySelector('.modal-content');
                modalContent.style.transition = 'all 0.2s ease';
                modalContent.style.transform = 'scale(0.9) translateY(-20px)';
                modalContent.style.opacity = '0';
            });
        });
    };

    // Delete Confirmation
    const enhanceDeleteButtons = () => {
        const deleteButtons = penggunaPage.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const row = this.closest('tr');
                const username = row.querySelector('td:nth-child(2)').textContent.trim();

                showConfirmModal(
                    'Konfirmasi Hapus Pengguna',
                    `Apakah Anda yakin ingin menghapus pengguna "${username}"?`,
                    'Tindakan ini tidak dapat dibatalkan.',
                    () => {
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                        this.disabled = true;
                        this.style.opacity = '0.6';
                        setTimeout(() => {
                            this.closest('form').submit();
                        }, 500);
                    }
                );
            });
        });
    };

    // Pagination Enhancement
    const enhancePagination = () => {
        const paginationLinks = penggunaPage.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function () {
                const tableContainer = penggunaPage.querySelector('.table-container');
                if (tableContainer) {
                    tableContainer.style.opacity = '0.7';
                    tableContainer.style.pointerEvents = 'none';
                    tableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    };

    // Accessibility Improvements
    const enhanceAccessibility = () => {
        const editButtons = penggunaPage.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            const row = button.closest('tr');
            const username = row?.querySelector('td:nth-child(2)')?.textContent?.trim();
            if (username) {
                button.setAttribute('aria-label', `Edit pengguna ${username}`);
                button.setAttribute('title', `Edit pengguna ${username}`);
            }
        });

        const deleteButtons = penggunaPage.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            const row = button.closest('tr');
            const username = row?.querySelector('td:nth-child(2)')?.textContent?.trim();
            if (username) {
                button.setAttribute('aria-label', `Hapus pengguna ${username}`);
                button.setAttribute('title', `Hapus pengguna ${username}`);
            }
        });

        const badges = penggunaPage.querySelectorAll('.badge-role, .badge-status');
        badges.forEach(badge => {
            badge.setAttribute('role', 'status');
            badge.setAttribute('aria-live', 'polite');
        });

        const tableRows = penggunaPage.querySelectorAll('.modern-table tbody tr');
        tableRows.forEach((row, index) => {
            row.setAttribute('tabindex', '0');
            row.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    const editButton = this.querySelector('.btn-edit');
                    if (editButton) editButton.click();
                }
                if (e.key === 'ArrowDown' && tableRows[index + 1]) {
                    tableRows[index + 1].focus();
                }
                if (e.key === 'ArrowUp' && tableRows[index - 1]) {
                    tableRows[index - 1].focus();
                }
            });
        });
    };

    // Utility Functions
    const showToast = (message, type = 'success') => {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        `;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        }, 100);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(toast)) document.body.removeChild(toast);
            }, 300);
        }, 3000);
    };

    const showConfirmModal = (title, message, subtitle, onConfirm) => {
        const modalHtml = `
            <div class="modal fade" id="confirmModal" tabindex="-1" style="z-index: 10000;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${title}</h5>
                        </div>
                        <div class="modal-body">
                            <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f59e0b; margin-bottom: 20px;"></i>
                            <p style="font-size: 16px; margin-bottom: 10px; font-weight: 600;">${message}</p>
                            <p style="font-size: 14px; color: #64748b;">${subtitle}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));

        document.getElementById('confirmDelete').onclick = () => {
            modal.hide();
            onConfirm();
        };

        document.getElementById('confirmModal').addEventListener('hidden.bs.modal', function () {
            this.remove();
        });

        modal.show();
    };

        // Checkbox Select All
    const handleCheckboxes = () => {
        const selectAll = penggunaPage.querySelector('#selectAll');
        const checkboxes = penggunaPage.querySelectorAll('.row-checkbox');
        const bulkForm = penggunaPage.querySelector('#bulkDeleteForm');

        if (!selectAll || checkboxes.length === 0) return;

        // Klik select all
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Kalau ada yang di-uncheck, update selectAll
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                const total = checkboxes.length;
                const checked = penggunaPage.querySelectorAll('.row-checkbox:checked').length;
                selectAll.checked = (total === checked);
            });
        });

        // Cegah submit tanpa pilihan
        if (bulkForm) {
            bulkForm.addEventListener('submit', function (e) {
                const selected = penggunaPage.querySelectorAll('.row-checkbox:checked');
                if (selected.length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal satu akun untuk dihapus.');
                }
            });
        }
    };


    // Initialize Enhancements
   setTimeout(() => {
        animateTableRows();
        enhanceSearchInput();
        enhanceButtons();
        enhanceFormValidation();
        enhanceAlerts();
        handleResponsiveTable();
        enhanceModals();
        enhanceDeleteButtons();
        enhancePagination();
        enhanceAccessibility();
        handleCheckboxes();   // ✅ panggil checkbox select all
        showToast('Halaman berhasil dimuat!', 'success');
    }, 300);

    window.addEventListener('resize', handleResponsiveTable);
});

// Additional CSS
const additionalCSS = `
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

@keyframes progressBar {
    from { transform: scaleX(1); }
    to { transform: scaleX(0); }
}

.pengguna-page .loading {
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.pengguna-page .btn:active {
    transform: scale(0.95) !important;
}

.pengguna-page .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

.pengguna-page .modern-table tbody tr:hover {
    cursor: pointer;
}

.pengguna-page *:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

.pengguna-page .btn:focus {
    outline: 3px solid rgba(59, 130, 246, 0.3);
    outline-offset: 2px;
}
`;

if (!document.querySelector('#pengguna-additional-css')) {
    const style = document.createElement('style');
    style.id = 'pengguna-additional-css';
    style.textContent = additionalCSS;
    document.head.appendChild(style);
}

// Utility Functions
window.penggunaPageUtils = {
    showToast: function (message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        `;
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        }, 100);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(toast)) document.body.removeChild(toast);
            }, 300);
        }, 3000);
    },

    refreshTable: function () {
        const tableContainer = document.querySelector('.pengguna-page .table-container');
        if (tableContainer) {
            tableContainer.classList.add('loading');
            setTimeout(() => {
                tableContainer.classList.remove('loading');
                this.showToast('Data berhasil dimuat ulang!', 'success');
                location.reload();
            }, 1000);
        }
    },

    handleResponsiveTable: function () {
        const table = document.querySelector('.pengguna-page .modern-table');
        const isMobile = window.innerWidth <= 480;
        if (table && isMobile) {
            const cells = table.querySelectorAll('tbody td');
            const headers = ['No', 'Nama Pengguna', 'Email', 'Status', 'Group', 'Aksi'];
            cells.forEach((cell, index) => {
                const headerIndex = index % headers.length;
                cell.setAttribute('data-label', headers[headerIndex]);
            });
        }
    }
};