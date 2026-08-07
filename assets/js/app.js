/**
 * SPGFood - Web3 Modern UI Components
 * Toast, Modal, Loading, Skeleton, Sidebar
 */

// Mobile Sidebar Toggle
class Sidebar {
    static toggle() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar && overlay) {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
    }

    static close() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar && overlay) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }
    }

    static init() {
        // Create toggle button if not exists
        if (!document.querySelector('.sidebar-toggle')) {
            const toggleBtn = document.createElement('button');
            toggleBtn.className = 'sidebar-toggle';
            toggleBtn.innerHTML = '☰';
            toggleBtn.onclick = () => this.toggle();
            document.body.appendChild(toggleBtn);
        }

        // Create overlay if not exists
        if (!document.querySelector('.sidebar-overlay')) {
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            overlay.onclick = () => this.close();
            document.body.appendChild(overlay);
        }

        // Close sidebar when clicking menu items on mobile
        const menuItems = document.querySelectorAll('.sidebar-menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    this.close();
                }
            });
        });
    }
}

// Initialize sidebar on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.sidebar')) {
        Sidebar.init();
    }
});

class Toast {
    static show(message, type = 'info', duration = 3000) {
        // Remove existing toast container if any
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };

        toast.innerHTML = `
            <span class="toast-icon">${icons[type] || icons.info}</span>
            <span class="toast-message">${message}</span>
            <button class="toast-close">&times;</button>
        `;

        container.appendChild(toast);

        // Close button functionality
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => this.dismiss(toast));

        // Auto dismiss
        setTimeout(() => this.dismiss(toast), duration);
    }

    static dismiss(toast) {
        toast.style.animation = 'slideInRight 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }
}

class Modal {
    static create(options = {}) {
        const {
            title = 'Modal',
            content = '',
            onConfirm = null,
            onCancel = null,
            confirmText = 'Confirm',
            cancelText = 'Cancel',
            showCancel = true
        } = options;

        // Remove existing modal if any
        const existingOverlay = document.querySelector('.modal-overlay');
        if (existingOverlay) {
            existingOverlay.remove();
        }

        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        
        const modal = document.createElement('div');
        modal.className = 'modal';
        
        modal.innerHTML = `
            <div class="modal-header">
                <h3 class="modal-title">${title}</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">${content}</div>
            <div class="modal-footer">
                ${showCancel ? `<button class="btn btn-outline modal-cancel">${cancelText}</button>` : ''}
                <button class="btn btn-primary modal-confirm">${confirmText}</button>
            </div>
        `;

        overlay.appendChild(modal);
        document.body.appendChild(overlay);

        // Show modal
        setTimeout(() => overlay.classList.add('active'), 10);

        // Event listeners
        const closeBtn = modal.querySelector('.modal-close');
        const cancelBtn = modal.querySelector('.modal-cancel');
        const confirmBtn = modal.querySelector('.modal-confirm');

        const closeModal = () => {
            overlay.classList.remove('active');
            setTimeout(() => overlay.remove(), 300);
            if (onCancel) onCancel();
        };

        closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
        
        confirmBtn.addEventListener('click', () => {
            if (onConfirm) onConfirm();
            closeModal();
        });

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeModal();
        });

        return {
            close: closeModal,
            element: overlay
        };
    }

    static confirm(message, onConfirm, onCancel = null) {
        return this.create({
            title: 'Confirm',
            content: `<p>${message}</p>`,
            onConfirm,
            onCancel,
            confirmText: 'Yes',
            cancelText: 'No'
        });
    }

    static alert(message, onConfirm = null) {
        return this.create({
            title: 'Information',
            content: `<p>${message}</p>`,
            onConfirm,
            showCancel: false,
            confirmText: 'OK'
        });
    }
}

class Loading {
    static show(container = null) {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        `;

        const spinner = document.createElement('div');
        spinner.className = 'spinner';
        spinner.style.cssText = `
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top-color: #00f5ff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        `;

        overlay.appendChild(spinner);
        document.body.appendChild(overlay);

        return overlay;
    }

    static hide(overlay) {
        if (overlay) {
            overlay.style.animation = 'fadeIn 0.3s ease reverse';
            setTimeout(() => overlay.remove(), 300);
        }
    }
}

class Skeleton {
    static create(options = {}) {
        const {
            width = '100%',
            height = '20px',
            count = 1,
            className = ''
        } = options;

        const fragment = document.createDocumentFragment();

        for (let i = 0; i < count; i++) {
            const skeleton = document.createElement('div');
            skeleton.className = `skeleton ${className}`;
            skeleton.style.cssText = `
                width: ${width};
                height: ${height};
                margin-bottom: 8px;
            `;
            fragment.appendChild(skeleton);
        }

        return fragment;
    }

    static replace(element, content) {
        if (!element) return;
        
        element.innerHTML = '';
        if (typeof content === 'string') {
            element.innerHTML = content;
        } else if (content instanceof HTMLElement) {
            element.appendChild(content);
        }
    }
}

// Realtime Status Polling
class StatusPoller {
    constructor(options = {}) {
        this.idPesanan = options.idPesanan;
        this.kodePelanggan = options.kodePelanggan;
        this.interval = options.interval || 5000; // 5 seconds default
        this.onStatusChange = options.onStatusChange || (() => {});
        this.onError = options.onError || (() => {});
        this.pollingId = null;
        this.lastStatus = null;
    }

    start() {
        if (this.pollingId) {
            this.stop();
        }

        this.poll(); // Initial check
        this.pollingId = setInterval(() => this.poll(), this.interval);
    }

    stop() {
        if (this.pollingId) {
            clearInterval(this.pollingId);
            this.pollingId = null;
        }
    }

    async poll() {
        try {
            let url = 'api/cek_status_api.php?';
            if (this.idPesanan) {
                url += `id_pesanan=${this.idPesanan}`;
            } else if (this.kodePelanggan) {
                url += `kode_pelanggan=${this.kodePelanggan}`;
            } else {
                return;
            }

            const response = await fetch(url);
            const data = await response.json();

            if (data.success) {
                const currentStatus = data.status || (data.data && data.data.status);
                
                if (currentStatus && currentStatus !== this.lastStatus) {
                    this.lastStatus = currentStatus;
                    this.onStatusChange(data);
                }
            } else {
                this.onError(data.message || 'Gagal cek status');
            }
        } catch (error) {
            this.onError(error.message);
        }
    }
}

class FormValidator {
    static validate(form) {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        const errors = [];

        inputs.forEach(input => {
            const value = input.value.trim();
            const label = input.closest('.form-group')?.querySelector('.form-label')?.textContent || input.name;

            if (!value) {
                isValid = false;
                errors.push(`${label} is required`);
                input.style.borderColor = '#ff4466';
            } else {
                input.style.borderColor = '';
            }

            // Email validation
            if (input.type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    errors.push(`${label} must be a valid email`);
                    input.style.borderColor = '#ff4466';
                }
            }

            // Number validation
            if (input.type === 'number' && value) {
                const num = parseFloat(value);
                const min = parseFloat(input.min);
                const max = parseFloat(input.max);

                if (!isNaN(min) && num < min) {
                    isValid = false;
                    errors.push(`${label} must be at least ${min}`);
                    input.style.borderColor = '#ff4466';
                }

                if (!isNaN(max) && num > max) {
                    isValid = false;
                    errors.push(`${label} must be at most ${max}`);
                    input.style.borderColor = '#ff4466';
                }
            }
        });

        return {
            isValid,
            errors
        };
    }

    static clearErrors(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.style.borderColor = '';
        });
    }
}

class AnimationHelper {
    static fadeIn(element, duration = 300) {
        element.style.opacity = '0';
        element.style.display = 'block';
        
        setTimeout(() => {
            element.style.transition = `opacity ${duration}ms ease`;
            element.style.opacity = '1';
        }, 10);
    }

    static fadeOut(element, duration = 300, callback = null) {
        element.style.transition = `opacity ${duration}ms ease`;
        element.style.opacity = '0';
        
        setTimeout(() => {
            element.style.display = 'none';
            if (callback) callback();
        }, duration);
    }

    static slideIn(element, direction = 'up', duration = 300) {
        const transforms = {
            up: 'translateY(20px)',
            down: 'translateY(-20px)',
            left: 'translateX(20px)',
            right: 'translateX(-20px)'
        };

        element.style.transform = transforms[direction];
        element.style.opacity = '0';
        element.style.display = 'block';
        
        setTimeout(() => {
            element.style.transition = `all ${duration}ms ease`;
            element.style.transform = 'translate(0)';
            element.style.opacity = '1';
        }, 10);
    }

    static slideOut(element, direction = 'up', duration = 300, callback = null) {
        const transforms = {
            up: 'translateY(-20px)',
            down: 'translateY(20px)',
            left: 'translateX(-20px)',
            right: 'translateX(20px)'
        };

        element.style.transition = `all ${duration}ms ease`;
        element.style.transform = transforms[direction];
        element.style.opacity = '0';
        
        setTimeout(() => {
            element.style.display = 'none';
            if (callback) callback();
        }, duration);
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Add input focus effects
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });

    // Add button ripple effect
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                pointer-events: none;
                width: 100px;
                height: 100px;
                left: ${x - 50}px;
                top: ${y - 50}px;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
            `;

            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });
});

// Add ripple animation to styles
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Export for global use
window.Toast = Toast;
window.Modal = Modal;
window.Loading = Loading;
window.Skeleton = Skeleton;
window.FormValidator = FormValidator;
window.AnimationHelper = AnimationHelper;
