// =============================================================================
// BULMA ALPINE.JS COMPONENTS
// Interactive components for Bulma CSS framework integration
// =============================================================================

// Import specialized components
import dataGrid from './dataGridBulmaComponent.js';

// Ensure Alpine is available before trying to register components
if (typeof Alpine === 'undefined') {
    console.error('Alpine.js is not available when loading Bulma components. Make sure Alpine is loaded first.');
    throw new Error('Alpine.js is required for Bulma components');
}

// -----------------------------------------------------------------------------
// DROPDOWN COMPONENT
// Replaces Fomantic-UI dropdown with Alpine.js + Bulma
// -----------------------------------------------------------------------------
Alpine.data('dropdown', (options = {}) => ({
    open: false,
    get isOpen() { return this.open; }, // Alias for backward compatibility
    activeIndex: -1,
    boundHandleClickOutside: null,
    
    init() {
        // Store bound function reference for proper cleanup
        this.boundHandleClickOutside = this.handleClickOutside.bind(this);
        
        // Handle click outside to close
        this.$watch('open', (value) => {
            if (value) {
                this.$nextTick(() => {
                    document.addEventListener('click', this.boundHandleClickOutside);
                });
            } else {
                document.removeEventListener('click', this.boundHandleClickOutside);
                this.activeIndex = -1;
            }
        });
        
        // Handle escape key
        this.$el.addEventListener('keydown', this.handleKeydown.bind(this));
    },
    
    toggle() {
        this.open = !this.open;
    },
    
    close() {
        this.open = false;
    },
    
    select(value, text = null) {
        // Emit custom event for parent components
        this.$dispatch('dropdown-selected', { value, text });
        this.close();
    },
    
    handleClickOutside(event) {
        if (this.$el && this.$el.contains && !this.$el.contains(event.target)) {
            this.close();
        }
    },
    
    handleKeydown(event) {
        if (!this.open) return;
        
        const items = this.$el.querySelectorAll('[data-dropdown-item]');
        
        switch (event.key) {
            case 'Escape':
                event.preventDefault();
                this.close();
                break;
            case 'ArrowDown':
                event.preventDefault();
                this.activeIndex = Math.min(this.activeIndex + 1, items.length - 1);
                this.focusActiveItem();
                break;
            case 'ArrowUp':
                event.preventDefault();
                this.activeIndex = Math.max(this.activeIndex - 1, 0);
                this.focusActiveItem();
                break;
            case 'Enter':
                event.preventDefault();
                if (this.activeIndex >= 0 && items[this.activeIndex]) {
                    items[this.activeIndex].click();
                }
                break;
        }
    },
    
    focusActiveItem() {
        const items = this.$el.querySelectorAll('[data-dropdown-item]');
        if (items[this.activeIndex]) {
            items[this.activeIndex].focus();
        }
    }
}));

// -----------------------------------------------------------------------------
// MODAL COMPONENT
// Replaces Fomantic-UI modal with Alpine.js + Bulma
// -----------------------------------------------------------------------------
Alpine.data('modal', (options = {}) => ({
    isActive: false,
    closeOnBackdrop: options.closeOnBackdrop ?? true,
    closeOnEscape: options.closeOnEscape ?? true,
    
    init() {
        // Handle escape key
        if (this.closeOnEscape) {
            document.addEventListener('keydown', this.handleEscape);
        }
        
        // Prevent body scroll when modal is open
        this.$watch('isActive', (value) => {
            if (value) {
                document.body.classList.add('is-clipped');
                this.$dispatch('modal-opened');
            } else {
                document.body.classList.remove('is-clipped');
                this.$dispatch('modal-closed');
            }
        });
    },
    
    open() {
        this.isActive = true;
    },
    
    close() {
        this.isActive = false;
    },
    
    toggle() {
        this.isActive = !this.isActive;
    },
    
    handleEscape(event) {
        if (event.key === 'Escape' && this.isActive) {
            event.preventDefault();
            this.close();
        }
    },
    
    handleBackdropClick(event) {
        if (this.closeOnBackdrop && event.target === this.$el) {
            this.close();
        }
    }
}));

// -----------------------------------------------------------------------------
// TABS COMPONENT
// Replaces Fomantic-UI tabs with Alpine.js + Bulma
// -----------------------------------------------------------------------------
Alpine.data('tabs', (options = {}) => ({
    activeTab: options.defaultTab || null,
    urlSync: options.urlSync ?? false,
    
    init() {
        // Set initial active tab from URL hash or first tab
        if (this.urlSync && window.location.hash) {
            const hash = window.location.hash.substring(1);
            this.activeTab = hash;
        } else if (!this.activeTab) {
            const firstTab = this.$el.querySelector('[data-tab]');
            if (firstTab) {
                this.activeTab = firstTab.getAttribute('data-tab');
            }
        }
        
        // Load initial content
        this.loadTabContent(this.activeTab);
        
        // Handle browser back/forward
        if (this.urlSync) {
            window.addEventListener('popstate', () => {
                const hash = window.location.hash.substring(1);
                if (hash) {
                    this.setActiveTab(hash);
                }
            });
        }
    },
    
    setActiveTab(tabId) {
        if (this.activeTab !== tabId) {
            this.activeTab = tabId;
            this.loadTabContent(tabId);
            
            // Update URL if sync enabled
            if (this.urlSync) {
                history.pushState(null, null, `#${tabId}`);
            }
            
            // Emit event
            this.$dispatch('tab-changed', { activeTab: tabId });
        }
    },
    
    isActive(tabId) {
        return this.activeTab === tabId;
    },
    
    loadTabContent(tabId) {
        const contentElement = document.getElementById(`${tabId}-content`);
        if (!contentElement) return;
        
        // Check if content needs to be loaded via HTMX
        const loadUrl = contentElement.getAttribute('data-load-url');
        if (loadUrl && !contentElement.hasChildNodes()) {
            // Use HTMX to load content
            htmx.ajax('GET', loadUrl, {
                target: `#${tabId}-content`,
                swap: 'innerHTML'
            });
        }
    }
}));

// -----------------------------------------------------------------------------
// NOTIFICATION COMPONENT
// Replaces Fomantic-UI message with Alpine.js + Bulma notification
// -----------------------------------------------------------------------------
Alpine.data('notification', (options = {}) => ({
    visible: true,
    autoHide: options.autoHide ?? false,
    duration: options.duration ?? 5000,
    
    init() {
        if (this.autoHide) {
            setTimeout(() => {
                this.hide();
            }, this.duration);
        }
    },
    
    hide() {
        this.visible = false;
        this.$dispatch('notification-hidden');
        
        // Remove from DOM after animation
        setTimeout(() => {
            this.$el.remove();
        }, 300);
    },
    
    show() {
        this.visible = true;
        this.$dispatch('notification-shown');
    }
}));

// -----------------------------------------------------------------------------
// NAVBAR COMPONENT
// Handle mobile navbar toggle
// -----------------------------------------------------------------------------
Alpine.data('navbar', () => ({
    isActive: false,
    
    toggle() {
        this.isActive = !this.isActive;
    },
    
    close() {
        this.isActive = false;
    }
}));

// -----------------------------------------------------------------------------
// ACCORDION COMPONENT
// Simple accordion functionality
// -----------------------------------------------------------------------------
Alpine.data('accordion', (options = {}) => ({
    openItems: new Set(options.defaultOpen || []),
    multiple: options.multiple ?? false,
    
    isOpen(itemId) {
        return this.openItems.has(itemId);
    },
    
    toggle(itemId) {
        if (this.isOpen(itemId)) {
            this.openItems.delete(itemId);
        } else {
            if (!this.multiple) {
                this.openItems.clear();
            }
            this.openItems.add(itemId);
        }
        
        this.$dispatch('accordion-changed', { 
            itemId, 
            isOpen: this.isOpen(itemId),
            openItems: Array.from(this.openItems)
        });
    }
}));

// -----------------------------------------------------------------------------
// FORM VALIDATION COMPONENT
// Enhanced form validation with Bulma styling
// -----------------------------------------------------------------------------
Alpine.data('formValidation', () => ({
    errors: {},
    
    validate(field, value, rules = []) {
        const fieldErrors = [];
        
        for (const rule of rules) {
            if (rule.type === 'required' && (!value || value.trim() === '')) {
                fieldErrors.push(rule.message || `${field} is required`);
            } else if (rule.type === 'email' && value && !this.isValidEmail(value)) {
                fieldErrors.push(rule.message || `${field} must be a valid email`);
            } else if (rule.type === 'min' && value && value.length < rule.value) {
                fieldErrors.push(rule.message || `${field} must be at least ${rule.value} characters`);
            } else if (rule.type === 'max' && value && value.length > rule.value) {
                fieldErrors.push(rule.message || `${field} must be no more than ${rule.value} characters`);
            }
        }
        
        if (fieldErrors.length > 0) {
            this.errors[field] = fieldErrors;
        } else {
            delete this.errors[field];
        }
        
        return fieldErrors.length === 0;
    },
    
    hasError(field) {
        return this.errors[field] && this.errors[field].length > 0;
    },
    
    getError(field) {
        return this.errors[field] ? this.errors[field][0] : '';
    },
    
    getFieldClass(field) {
        if (this.hasError(field)) {
            return 'is-danger';
        }
        return '';
    },
    
    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },
    
    clearErrors() {
        this.errors = {};
    }
}));

// -----------------------------------------------------------------------------
// INITIALIZE COMPONENTS
// Components are registered immediately when module is imported
// -----------------------------------------------------------------------------
console.log('Bulma Alpine.js components initialized');

// Global helper functions available in all Alpine components
Alpine.magic('bulma', () => ({
    // Helper to toggle Bulma classes
    toggleClass: (element, className) => {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        if (element) {
            element.classList.toggle(className);
        }
    },
    
    // Helper to add loading state
    setLoading: (element, loading = true) => {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        if (element) {
            if (loading) {
                element.classList.add('is-loading');
                element.setAttribute('disabled', true);
            } else {
                element.classList.remove('is-loading');
                element.removeAttribute('disabled');
            }
        }
    },
    
    // Helper for notifications
    notify: (message, type = 'info', duration = 5000) => {
        const notification = document.createElement('div');
        notification.className = `notification is-${type}`;
        notification.innerHTML = `
            <button class="delete" onclick="this.parentElement.remove()"></button>
            ${message}
        `;
        
        const container = document.querySelector('.notifications-container') || document.body;
        container.appendChild(notification);
        
        if (duration > 0) {
            setTimeout(() => {
                notification.remove();
            }, duration);
        }
    }
}));

// -----------------------------------------------------------------------------
// DATAGRID COMPONENT
// Register the imported dataGrid component
// -----------------------------------------------------------------------------
Alpine.data('dataGrid', dataGrid);