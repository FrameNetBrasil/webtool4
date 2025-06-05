/**
 * CollapsibleSidebar - Complete implementation for sidebar with collapsible sections
 */
class CollapsibleSidebar {
    constructor(sidebarSelector = '.app-sidebar') {
        console.log('CollapsibleSidebar: Constructor called with selector:', sidebarSelector);

        this.sidebarSelector = sidebarSelector;
        this.sidebar = document.querySelector(sidebarSelector);
        this.sections = [];
        this.collapsedSections = new Set();
        this.isInitialized = false;

        console.log('CollapsibleSidebar: Sidebar element in constructor:', this.sidebar);

        // Configuration - Updated for your CSS structure
        this.config = {
            storageKey: 'sidebar-collapsed-sections',
            animationDuration: 300,
            autoCollapseThreshold: 5,
            sectionSelector: '.nav-section.collapsible',
            headerSelector: '.section-header',
            contentSelector: '.section-items',
            toggleIconSelector: '.collapse-icon',
            // Default state configuration
            defaultClosed: true,  // Set to true to start all sections closed
            rememberState: true   // Set to false to ignore localStorage
        };

        // Bind methods to preserve 'this' context
        this.handleSectionToggle = this.handleSectionToggle.bind(this);
        this.handleWindowResize = this.handleWindowResize.bind(this);
    }

    /**
     * Initialize the sidebar
     */
    init() {
        console.log('CollapsibleSidebar: Init called, sidebar:', this.sidebar);

        if (!this.sidebar) {
            console.warn('CollapsibleSidebar: Sidebar element not found with selector:', this.sidebarSelector);
            console.log('CollapsibleSidebar: Available elements with class collapsible-sidebar:', document.querySelectorAll('.collapsible-sidebar'));
            return;
        }

        if (this.isInitialized) {
            console.warn('CollapsibleSidebar: Already initialized');
            return;
        }

        console.log('CollapsibleSidebar: Initializing...');

        try {
            // Hide all collapsible sections immediately to prevent flickering
            this.hideAllSectionsImmediately();

            this.initializeSections();

            // Use requestAnimationFrame to ensure DOM is ready
            requestAnimationFrame(() => {
                this.loadCollapsedState();
                this.initializeUserContext();
                this.handleResponsive();
                this.attachEventListeners();

                // Mark sidebar as fully initialized
                this.sidebar.classList.add('js-initialized');

                this.isInitialized = true;
                console.log('CollapsibleSidebar: Initialized successfully');
            });

        } catch (error) {
            console.error('CollapsibleSidebar: Initialization failed', error);
        }
    }

    /**
     * Hide all collapsible sections immediately to prevent flickering
     */
    hideAllSectionsImmediately() {
        const sections = this.sidebar.querySelectorAll(this.config.sectionSelector);
        sections.forEach(section => {
            const content = section.querySelector(this.config.contentSelector);
            if (content) {
                content.style.height = '0px';
                content.style.overflow = 'hidden';
                content.style.transition = 'none';
                section.classList.add('collapsed');

                const toggleIcon = section.querySelector(this.config.toggleIconSelector);
                if (toggleIcon) {
                    toggleIcon.style.transform = 'rotate(-90deg)';
                }
            }
        });
    }

    /**
     * Initialize all sidebar sections
     */
    initializeSections() {
        const sectionElements = this.sidebar.querySelectorAll(this.config.sectionSelector);

        console.log('CollapsibleSidebar: Found sections:', sectionElements.length);

        sectionElements.forEach((section, index) => {
            const sectionId = section.dataset.section || `section-${index}`;
            const header = section.querySelector(this.config.headerSelector);
            const content = section.querySelector(this.config.contentSelector);

            if (!header || !content) {
                console.warn(`CollapsibleSidebar: Section ${sectionId} missing header or content`);
                console.log('Header:', header, 'Content:', content);
                return;
            }

            // Ensure section has proper attributes
            section.dataset.section = sectionId;

            // Find or create toggle icon
            let toggleIcon = header.querySelector(this.config.toggleIconSelector);
            if (!toggleIcon) {
                // Check if there's already a .collapse-icon
                toggleIcon = header.querySelector('.collapse-icon');
                if (!toggleIcon) {
                    // Create new toggle icon
                    toggleIcon = document.createElement('i');
                    toggleIcon.className = 'dropdown icon collapse-icon';
                    header.appendChild(toggleIcon);
                }
            }

            // Store section data
            const sectionData = {
                id: sectionId,
                element: section,
                header: header,
                content: content,
                toggleIcon: toggleIcon
            };

            this.sections.push(sectionData);

            // Set initial heights for animation
            this.setContentHeight(content);

            // Mark section as initialized
            section.classList.add('initialized');

            console.log(`CollapsibleSidebar: Section ${sectionId} initialized`);
        });
    }

    /**
     * Set content height for smooth animations
     */
    setContentHeight(content) {
        // Force a reflow to ensure accurate measurements
        content.offsetHeight;

        // Temporarily make content visible to measure height accurately
        const originalTransition = content.style.transition;
        const originalHeight = content.style.height;
        const originalOverflow = content.style.overflow;

        // Reset styles to get natural height
        content.style.transition = 'none';
        content.style.height = 'auto';
        content.style.overflow = 'visible';

        // Force another reflow
        content.offsetHeight;

        // Measure the natural height
        const naturalHeight = content.scrollHeight;
        content.dataset.naturalHeight = naturalHeight;

        // Restore original styles
        content.style.transition = originalTransition;
        content.style.height = originalHeight;
        content.style.overflow = originalOverflow;

        console.log(`CollapsibleSidebar: Measured natural height: ${naturalHeight}px for section`);
    }

    /**
     * Attach event listeners
     */
    attachEventListeners() {
        this.sections.forEach(section => {
            section.header.removeEventListener('click', this.handleSectionToggle);
            section.header.addEventListener('click', this.handleSectionToggle);

            const interactiveElements = section.header.querySelectorAll('a, button, input, select');
            interactiveElements.forEach(element => {
                element.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            });
        });

        window.removeEventListener('resize', this.handleWindowResize);
        window.addEventListener('resize', this.handleWindowResize);

        console.log('CollapsibleSidebar: Event listeners attached');
    }

    /**
     * Handle section toggle clicks
     */
    handleSectionToggle(event) {
        event.preventDefault();
        event.stopPropagation();

        const header = event.currentTarget;
        const section = header.closest('.nav-section');
        const sectionId = section.dataset.section;

        console.log(`CollapsibleSidebar: Toggling section ${sectionId}`);

        this.toggleSection(sectionId);
    }

    /**
     * Toggle a specific section
     */
    toggleSection(sectionId) {
        const sectionData = this.sections.find(s => s.id === sectionId);
        if (!sectionData) {
            console.warn(`CollapsibleSidebar: Section ${sectionId} not found`);
            return;
        }

        const { element } = sectionData;
        const isCurrentlyCollapsed = element.classList.contains('collapsed');

        if (isCurrentlyCollapsed) {
            this.expandSection(sectionData);
            this.collapsedSections.delete(sectionId);
        } else {
            this.collapseSection(sectionData);
            this.collapsedSections.add(sectionId);
        }

        this.saveCollapsedState();
    }

    /**
     * Expand a section
     */
    expandSection(sectionData) {
        const { element, content, toggleIcon } = sectionData;

        console.log(`CollapsibleSidebar: Expanding section ${sectionData.id}`);

        element.classList.remove('collapsed');

        const naturalHeight = content.dataset.naturalHeight || content.scrollHeight;
        content.style.height = naturalHeight + 'px';

        toggleIcon.style.transform = 'rotate(0deg)';

        setTimeout(() => {
            if (!element.classList.contains('collapsed')) {
                content.style.height = 'auto';
            }
        }, this.config.animationDuration);
    }

    /**
     * Collapse a section
     */
    collapseSection(sectionData) {
        const { element, content, toggleIcon } = sectionData;

        console.log(`CollapsibleSidebar: Collapsing section ${sectionData.id}`);

        content.style.height = content.scrollHeight + 'px';
        content.offsetHeight;

        element.classList.add('collapsed');
        content.style.height = '0px';

        toggleIcon.style.transform = 'rotate(-90deg)';
    }

    /**
     * Expand all sections
     */
    expandAll() {
        console.log('CollapsibleSidebar: Expanding all sections');
        this.sections.forEach(section => {
            if (section.element.classList.contains('collapsed')) {
                this.expandSection(section);
                this.collapsedSections.delete(section.id);
            }
        });
        this.saveCollapsedState();
    }

    /**
     * Collapse all sections
     */
    collapseAll() {
        console.log('CollapsibleSidebar: Collapsing all sections');
        this.sections.forEach(section => {
            if (!section.element.classList.contains('collapsed')) {
                this.collapseSection(section);
                this.collapsedSections.add(section.id);
            }
        });
        this.saveCollapsedState();
    }

    /**
     * Load collapsed state from localStorage
     */
    loadCollapsedState() {
        // If rememberState is false, use default state
        if (!this.config.rememberState) {
            this.applyDefaultState();
            return;
        }

        try {
            const saved = localStorage.getItem(this.config.storageKey);
            if (saved) {
                const collapsedIds = JSON.parse(saved);
                this.collapsedSections = new Set(collapsedIds);

                this.sections.forEach(section => {
                    // Disable transitions for initial load
                    section.content.style.transition = 'none';

                    if (this.collapsedSections.has(section.id)) {
                        section.element.classList.add('collapsed');
                        section.content.style.height = '0px';
                        section.content.style.overflow = 'hidden';
                        section.toggleIcon.style.transform = 'rotate(-90deg)';
                    } else {
                        section.element.classList.remove('collapsed');
                        const naturalHeight = section.content.dataset.naturalHeight || section.content.scrollHeight;
                        section.content.style.height = naturalHeight + 'px';
                        section.content.style.overflow = 'hidden';
                        section.toggleIcon.style.transform = 'rotate(0deg)';
                    }

                    // Re-enable transitions after initial load
                    setTimeout(() => {
                        section.content.style.transition = '';
                        if (!section.element.classList.contains('collapsed')) {
                            section.content.style.height = 'auto';
                        }
                    }, 50);
                });

                console.log('CollapsibleSidebar: Loaded collapsed state', Array.from(this.collapsedSections));
            } else {
                // No saved state, apply default
                this.applyDefaultState();
            }
        } catch (error) {
            console.warn('CollapsibleSidebar: Failed to load collapsed state', error);
            this.applyDefaultState();
        }
    }

    /**
     * Apply default state to all sections
     */
    applyDefaultState() {
        console.log('CollapsibleSidebar: Applying default state - defaultClosed:', this.config.defaultClosed);

        this.sections.forEach((section, index) => {
            // Small staggered delay to prevent all sections from animating at once
            setTimeout(() => {
                if (this.config.defaultClosed) {
                    // Start closed
                    section.element.classList.add('collapsed');
                    section.content.style.height = '0px';
                    section.content.style.overflow = 'hidden';
                    section.toggleIcon.style.transform = 'rotate(-90deg)';
                    this.collapsedSections.add(section.id);
                } else {
                    // Start open
                    section.element.classList.remove('collapsed');
                    const naturalHeight = section.content.dataset.naturalHeight || section.content.scrollHeight;
                    section.content.style.height = naturalHeight + 'px';
                    section.content.style.overflow = 'hidden';
                    section.toggleIcon.style.transform = 'rotate(0deg)';
                    this.collapsedSections.delete(section.id);
                }

                // Enable transitions after state is set
                setTimeout(() => {
                    section.content.style.transition = `height ${this.config.animationDuration}ms ease`;
                    section.toggleIcon.style.transition = 'transform 0.2s ease';

                    // If section should be open, set height to auto after transition is enabled
                    if (!this.config.defaultClosed && !this.collapsedSections.has(section.id)) {
                        setTimeout(() => {
                            section.content.style.height = 'auto';
                        }, 50);
                    }
                }, 50);
            }, index * 10); // Stagger by 10ms per section
        });

        // Save the default state if remembering state
        if (this.config.rememberState) {
            setTimeout(() => {
                this.saveCollapsedState();
            }, this.sections.length * 10 + 100);
        }
    }

    /**
     * Save collapsed state to localStorage
     */
    saveCollapsedState() {
        try {
            const collapsedArray = Array.from(this.collapsedSections);
            localStorage.setItem(this.config.storageKey, JSON.stringify(collapsedArray));
            console.log('CollapsibleSidebar: Saved collapsed state', collapsedArray);
        } catch (error) {
            console.warn('CollapsibleSidebar: Failed to save collapsed state', error);
        }
    }

    /**
     * Initialize user context section
     */
    initializeUserContext() {
        // Look for user section as a collapsible section first
        const userSection = this.sidebar.querySelector('.nav-section.collapsible[data-section*="user"], .nav-section.user-context, .sidebar-footer');
        if (!userSection) {
            console.log('CollapsibleSidebar: No user context section found');
            return;
        }

        console.log('CollapsibleSidebar: Initializing user context', userSection);

        // If it's a collapsible section, it should already be handled by initializeSections
        if (userSection.classList.contains('collapsible')) {
            console.log('CollapsibleSidebar: User section is collapsible, handled by main sections');

            // Add special handling for user menu items
            const userMenuItems = userSection.querySelectorAll('.nav-item, .section-items .item');
            userMenuItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.handleUserAction(item);
                });
            });
        } else {
            // Handle non-collapsible user footer
            this.loadUserData();

            const userMenuItems = userSection.querySelectorAll('.footer-user, .user-menu-item');
            userMenuItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.handleUserAction(item);
                });
            });
        }
    }

    /**
     * Load user data
     */
    loadUserData() {
        const userData = {
            name: 'John Doe',
            role: 'Administrator',
            avatar: 'https://via.placeholder.com/32x32/4285f4/ffffff?text=JD',
            notifications: 3
        };

        this.updateUserDisplay(userData);
    }

    /**
     * Update user display in sidebar
     */
    updateUserDisplay(userData) {
        // Try multiple possible user section locations
        const userSection = this.sidebar.querySelector('.nav-section.user-context, .sidebar-footer, .nav-section[data-section*="user"]');
        if (!userSection) {
            console.log('CollapsibleSidebar: No user section found for display update');
            return;
        }

        // Update user name
        const usernameEl = userSection.querySelector('.user-name, .username, .section-title-content');
        if (usernameEl) {
            if (usernameEl.classList.contains('section-title-content')) {
                // If it's in a section header, update the text content
                const textNode = usernameEl.childNodes[0];
                if (textNode && textNode.nodeType === Node.TEXT_NODE) {
                    textNode.textContent = userData.name;
                }
            } else {
                usernameEl.textContent = userData.name;
            }
        }

        // Update user role
        const roleEl = userSection.querySelector('.user-role');
        if (roleEl) roleEl.textContent = userData.role;

        // Update avatar
        const avatarEl = userSection.querySelector('.user-avatar img, .ui.image');
        if (avatarEl && userData.avatar) {
            avatarEl.src = userData.avatar;
        } else {
            // If no img element, update background or create one
            const avatarContainer = userSection.querySelector('.user-avatar');
            if (avatarContainer && !avatarContainer.querySelector('img')) {
                const img = document.createElement('img');
                img.src = userData.avatar;
                img.alt = userData.name;
                avatarContainer.appendChild(img);
            }
        }

        // Update notification badge
        const notificationEl = userSection.querySelector('.nav-badge, .ui.label, .notification-badge');
        if (notificationEl && userData.notifications) {
            notificationEl.textContent = userData.notifications;
            notificationEl.style.display = userData.notifications > 0 ? 'inline-block' : 'none';
        }

        console.log('CollapsibleSidebar: User display updated', userData);
    }

    /**
     * Handle user action clicks
     */
    handleUserAction(item) {
        const text = item.textContent.trim().toLowerCase();

        console.log('CollapsibleSidebar: User action clicked', text);

        if (text.includes('profile')) {
            this.navigateToProfile();
        } else if (text.includes('preferences')) {
            this.openPreferences();
        } else if (text.includes('notifications')) {
            this.openNotifications();
        } else if (text.includes('logout')) {
            this.handleLogout();
        }
    }

    /**
     * Navigation methods
     */
    navigateToProfile() {
        console.log('CollapsibleSidebar: Navigate to profile');
        // window.location.href = '/profile';
    }

    openPreferences() {
        console.log('CollapsibleSidebar: Open preferences');
    }

    openNotifications() {
        console.log('CollapsibleSidebar: Open notifications');
    }

    handleLogout() {
        console.log('CollapsibleSidebar: Handle logout');
        if (confirm('Are you sure you want to logout?')) {
            // window.location.href = '/logout';
        }
    }

    /**
     * Handle responsive behavior
     */
    handleResponsive() {
        if (window.innerWidth < 768) {
            this.sections.forEach(section => {
                const items = section.content.querySelectorAll('.item');
                if (items.length > this.config.autoCollapseThreshold) {
                    this.collapseSection(section);
                    this.collapsedSections.add(section.id);
                }
            });
        }
    }

    /**
     * Handle window resize
     */
    handleWindowResize() {
        this.sections.forEach(section => {
            if (!section.element.classList.contains('collapsed')) {
                this.setContentHeight(section.content);
            }
        });
    }

    /**
     * Destroy the sidebar
     */
    destroy() {
        console.log('CollapsibleSidebar: Destroying...');

        this.sections.forEach(section => {
            section.header.removeEventListener('click', this.handleSectionToggle);
        });

        window.removeEventListener('resize', this.handleWindowResize);

        this.sections = [];
        this.collapsedSections.clear();
        this.isInitialized = false;

        console.log('CollapsibleSidebar: Destroyed');
    }

    /**
     * Get current state
     */
    getState() {
        return {
            isInitialized: this.isInitialized,
            sectionsCount: this.sections.length,
            collapsedSections: Array.from(this.collapsedSections)
        };
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('CollapsibleSidebar: DOM ready, checking for sidebar...');

    // Check for your actual sidebar class
    const sidebarElement = document.querySelector('.app-sidebar');
    if (sidebarElement) {
        console.log('CollapsibleSidebar: App sidebar found, initializing...');
        console.log('CollapsibleSidebar: Sidebar element:', sidebarElement);

        // Use the correct selector for your structure
        window.sidebarInstance = new CollapsibleSidebar('.app-sidebar');

        // Double-check the sidebar property
        console.log('CollapsibleSidebar: Instance sidebar property:', window.sidebarInstance.sidebar);

        window.sidebarInstance.init();
    } else {
        console.log('CollapsibleSidebar: No .app-sidebar found on this page');
        // Fallback check for the original class name
        const fallbackSidebar = document.querySelector('.collapsible-sidebar');
        if (fallbackSidebar) {
            console.log('CollapsibleSidebar: Found fallback .collapsible-sidebar');
            window.sidebarInstance = new CollapsibleSidebar('.collapsible-sidebar');
            window.sidebarInstance.init();
        }
    }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CollapsibleSidebar;
}


export default CollapsibleSidebar;
