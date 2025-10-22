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

    // Export Selected
    const btnExport = document.getElementById('btnExportSelected');
    const exportForm = document.getElementById('exportForm');
    if (btnExport) {
        btnExport.addEventListener('click', () => {
            const checked = document.querySelectorAll('input[name="ids[]"]:checked');
            if (checked.length === 0) {
                showToast('Pilih minimal satu pengguna untuk di-export!', 'error');
                return;
            }
            exportForm.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
            checked.forEach(cb => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'ids[]';
                hidden.value = cb.value;
                exportForm.appendChild(hidden);
            });
            exportForm.submit();
        });
    }

    // Search Input Enhancement
    const enhanceSearchInput = () => {
        const searchInput = penggunaPage.querySelector('#keywordInput');
        const searchBtn = penggunaPage.querySelector('.search-btn');
        if (!searchInput || !searchBtn) return;

        searchInput.addEventListener('focus', () => {
            searchInput.style.transform = 'scale(1.02)';
            searchInput.style.transition = 'all 0.3s ease';
        });

        searchInput.addEventListener('blur', () => {
            searchInput.style.transform = 'scale(1)';
        });

        searchInput.addEventListener('input', () => {
            if (searchInput.value.length >= 3) {
                searchBtn.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
                searchBtn.innerHTML = '<i class="fas fa-search"></i> Cari Sekarang';
            } else if (searchInput.value.length === 0) {
                searchBtn.style.background = 'linear-gradient(135deg, #3b82f6, #1e40af)';
                searchBtn.innerHTML = '<i class="fas fa-search"></i> Cari';
            } else {
                searchBtn.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';
                searchBtn.innerHTML = '<i class="fas fa-search"></i> Ketik lebih banyak...';
            }
        });
    };

    // Button Enhancements
    const enhanceButtons = () => {
        const buttons = penggunaPage.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', () => {
                if (!button.disabled) {
                    button.style.transform = 'translateY(-2px) scale(1.05)';
                }
            });
            button.addEventListener('mouseleave', () => {
                button.style.transform = 'translateY(0) scale(1)';
            });
            button.addEventListener('click', () => {
                button.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    button.style.transform = 'translateY(0) scale(1)';
                }, 150);
            });
        });
    };

    // Form Validation
    const enhanceFormValidation = () => {
        const forms = penggunaPage.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', e => {
                const requiredInputs = form.querySelectorAll('[required]');
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
            const headers = ['No', 'Pengguna', 'Status', 'Email', 'Group', 'Aksi', 'Pilih'];
            cells.forEach((cell, index) => {
                const headerIndex = index % headers.length;
                cell.setAttribute('data-label', headers[headerIndex]);
            });
        }
    };

    // Checkbox Select All
    const handleCheckboxes = () => {
        const selectAll = penggunaPage.querySelector('#selectAll');
        const checkboxes = penggunaPage.querySelectorAll('.row-checkbox');
        const bulkForm = penggunaPage.querySelector('#bulkDeleteForm');

        if (!selectAll || checkboxes.length === 0) return;

        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const total = checkboxes.length;
                const checked = penggunaPage.querySelectorAll('.row-checkbox:checked').length;
                selectAll.checked = total === checked;
            });
        });

        if (bulkForm) {
            bulkForm.addEventListener('submit', e => {
                // Pastikan event submit hanya dari bulkDeleteForm, bukan form lain
                if (e.target.id !== 'bulkDeleteForm') return;

                const selected = penggunaPage.querySelectorAll('.row-checkbox:checked');
                if (selected.length === 0) {
                    e.preventDefault();
                    showToast('Pilih minimal satu akun untuk dihapus.', 'error');
                }
            });
        }
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
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        `;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
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

    
    // Initialize Enhancements
    setTimeout(() => {
        animateTableRows();
        enhanceSearchInput();
        enhanceButtons();
        enhanceFormValidation();
        // enhanceAlerts();
        handleResponsiveTable();
        handleCheckboxes();
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

.pengguna-page .btn:active {
    transform: scale(0.95) !important;
}

.pengguna-page .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
`;

if (!document.querySelector('#pengguna-additional-css')) {
    const style = document.createElement('style');
    style.id = 'pengguna-additional-css';
    style.textContent = additionalCSS;
    document.head.appendChild(style);
}