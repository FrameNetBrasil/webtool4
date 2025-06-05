/**
 * AccordionSidebar - Fomantic-UI based collapsible sidebar navigation
 * Fixes the initialization and state management issues
 */
class AccordionSidebar {
    constructor(sidebarSelector = '#sidebar-navigation', options = {}) {
        this.sidebar = $(sidebarSelector);
        this.searchInput = $('#nav-search');

        // Default options - balanced for performance and functionality
        this.options = {
            exclusive: false,        // Allow multiple sections open
            collapsible: true,       // Allow sections to close
            duration: 300,           // Back to standard duration
            easing: 'easeOutQuad',   // Standard easing
            animateChildren: false,  // Keep disabled to prevent layout shifts
            closeNested: false,      // Keep nested accordions independent
            ...options
        };

        this.init();
    }

    init() {
        // Wait for DOM to be fully ready
        if (this.sidebar.length === 0) {
            console.error('Sidebar element not found:', this.sidebar.selector);
            return;
        }

        this.initAccordion();
        this.initSearch();
        this.initNavigation();

        // Load saved state after a brief delay to ensure accordion is ready
        setTimeout(() => {
            this.loadSavedState();
        }, 100);
    }

    initAccordion() {
        try {
            // Destroy any existing accordion to start fresh
            if (this.sidebar.hasClass('ui')) {
                this.sidebar.accordion('destroy');
            }

            // Initialize main accordion with layout shift prevention
            this.sidebar.accordion({
                selector: {
                    trigger: '.title'
                },
                exclusive: this.options.exclusive,
                collapsible: this.options.collapsible,
                duration: this.options.duration,
                easing: this.options.easing,
                animateChildren: false,
                closeNested: this.options.closeNested,

                // Callback handlers with layout shift prevention
                onOpening: (activeContent) => {
                    this.preventLayoutShift(activeContent, 'opening');
                },
                onOpen: (activeContent) => {
                    this.cleanupLayoutShift(activeContent);
                    this.onSectionOpen(activeContent);
                },
                onClosing: (activeContent) => {
                    this.preventLayoutShift(activeContent, 'closing');
                },
                onClose: (activeContent) => {
                    this.cleanupLayoutShift(activeContent);
                    this.onSectionClose(activeContent);
                },
                onChange: (activeContent) => {
                    this.onSectionChange(activeContent);
                }
            });

            // Initialize nested accordions separately
            this.initNestedAccordions();

            console.log('Accordion initialized successfully');

        } catch (error) {
            console.error('Failed to initialize accordion:', error);
        }
    }

    initNestedAccordions() {
        // Find and initialize nested accordions
        this.sidebar.find('.content .ui.accordion').each((index, element) => {
            const $nested = $(element);

            try {
                $nested.accordion({
                    exclusive: false,
                    collapsible: true,
                    duration: 250,
                    animateChildren: false
                });
            } catch (error) {
                console.warn('Failed to initialize nested accordion:', error);
            }
        });
    }

    initSearch() {
        if (this.searchInput.length === 0) return;

        let searchTimeout;

        this.searchInput.on('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.filterNavigation(e.target.value.trim());
            }, 200);
        });

        this.searchInput.on('keydown', (e) => {
            if (e.key === 'Escape') {
                this.clearSearch();
            }
        });
    }

    initNavigation() {
        // Handle navigation item clicks
        this.sidebar.on('click', '.nav-item', (e) => {
            e.preventDefault();
            e.stopPropagation(); // Prevent accordion toggle
            this.selectNavItem($(e.currentTarget));
        });
    }

    filterNavigation(query) {
        if (!query) {
            this.showAllItems();
            return;
        }

        const searchTerm = query.toLowerCase();
        let hasVisibleItems = false;

        // Search through all sections
        this.sidebar.children('.title').each((index, titleEl) => {
            const $title = $(titleEl);
            const $content = $title.next('.content');
            let sectionHasVisible = false;

            // Check section title
            const titleText = $title.text().toLowerCase();
            const titleMatches = titleText.includes(searchTerm);

            // Check navigation items in this section
            $content.find('.nav-item').each((itemIndex, itemEl) => {
                const $item = $(itemEl);
                const itemText = $item.text().toLowerCase();
                const matches = itemText.includes(searchTerm) || titleMatches;

                if (matches) {
                    $item.show();
                    sectionHasVisible = true;
                    hasVisibleItems = true;
                } else {
                    $item.hide();
                }
            });

            // Show/hide section based on matches
            if (sectionHasVisible || titleMatches) {
                $title.show();
                $content.show();
                // Open section if it has matches and isn't already open
                if (!$title.hasClass('active')) {
                    this.openSection(index);
                }
            } else {
                $title.hide();
                $content.hide();
            }
        });

        // Show "no results" message if needed
        this.toggleNoResults(!hasVisibleItems);
    }

    showAllItems() {
        this.sidebar.find('.title, .content, .nav-item').show();
        this.toggleNoResults(false);
    }

    clearSearch() {
        this.searchInput.val('');
        this.showAllItems();
    }

    toggleNoResults(show) {
        let $noResults = this.sidebar.find('.no-results');
        if (show && $noResults.length === 0) {
            $noResults = $('<div class="no-results" style="padding: 20px; text-align: center; color: #6c757d; font-style: italic;">No items found</div>');
            this.sidebar.append($noResults);
        } else if (!show && $noResults.length > 0) {
            $noResults.remove();
        }
    }

    selectNavItem($item) {
        // Remove active state from all items
        this.sidebar.find('.nav-item').removeClass('active');

        // Add active state to clicked item
        $item.addClass('active');

        // Get clean text without badges
        const itemText = $item.clone().find('.badge').remove().end().text().trim();

        // Emit custom event for integration
        this.sidebar.trigger('navigation:select', {
            item: $item,
            href: $item.attr('href') || '#',
            text: itemText,
            section: $item.closest('.content').prev('.title').text().trim()
        });

        console.log('Navigation selected:', itemText);
    }

    // Layout shift prevention methods
    preventLayoutShift(activeContent, phase) {
        const $content = $(activeContent);
        const $items = $content.find('.nav-item');

        // Capture current positions before animation
        $items.each((index, item) => {
            const $item = $(item);
            const rect = item.getBoundingClientRect();

            // Store original position
            $item.data('original-position', {
                top: rect.top,
                left: rect.left,
                height: rect.height
            });

            // Apply positioning constraints during animation
            $item.css({
                'position': 'relative',
                'transform': 'translateZ(0)',
                'backface-visibility': 'hidden'
            });
        });

        // Add animation class for CSS targeting
        $content.addClass(`layout-shift-${phase}`);
    }

    cleanupLayoutShift(activeContent) {
        const $content = $(activeContent);
        const $items = $content.find('.nav-item');

        // Remove positioning constraints after animation
        $items.each((index, item) => {
            const $item = $(item);
            $item.removeData('original-position');
            $item.css({
                'position': '',
                'transform': '',
                'backface-visibility': ''
            });
        });

        // Remove animation classes
        $content.removeClass('layout-shift-opening layout-shift-closing');
    }

    // Callback handlers
    onSectionOpening(activeContent) {
        const $content = $(activeContent);
        const $title = $content.prev('.title');
        const sectionId = $title.data('section-id');

        console.log('Section opening:', $title.text().trim());

        if (sectionId && !$content.data('loaded')) {
            // Placeholder for dynamic content loading
            console.log(`Loading content for section: ${sectionId}`);
            $content.data('loaded', true);
        }
    }

    onSectionClosing(activeContent) {
        const $content = $(activeContent);
        const $title = $content.prev('.title');
        console.log('Section closing:', $title.text().trim());
    }

    onSectionClose(activeContent) {
        const $content = $(activeContent);
        const $title = $content.prev('.title');
        console.log('Section closed:', $title.text().trim());

        // Save state when section closes
        this.saveState();
    }

    onSectionChange(activeContent) {
        // This fires on both open and close
        const $content = $(activeContent);
        const $title = $content.prev('.title');
        const isActive = $title.hasClass('active');

        console.log(`Section ${$title.text().trim()} changed to: ${isActive ? 'open' : 'closed'}`);
    }

    saveState() {
        if (typeof localStorage === 'undefined') return;

        try {
            const openSections = [];
            this.sidebar.children('.title.active').each((index, el) => {
                const sectionText = $(el).text().replace(/\s+/g, ' ').trim();
                openSections.push(sectionText);
            });

            const state = {
                openSections: openSections,
                timestamp: Date.now()
            };

            localStorage.setItem('sidebar-accordion-state', JSON.stringify(state));
            console.log('Saved accordion state:', state);

        } catch (error) {
            console.warn('Failed to save accordion state:', error);
        }
    }

    loadSavedState() {
        if (typeof localStorage === 'undefined') return;

        try {
            const saved = localStorage.getItem('sidebar-accordion-state');
            if (!saved) return;

            const state = JSON.parse(saved);
            const oneHourAgo = Date.now() - (60 * 60 * 1000);

            // Only restore state if less than 1 hour old
            if (state.timestamp < oneHourAgo) {
                console.log('Accordion state expired, starting fresh');
                return;
            }

            if (state.openSections && Array.isArray(state.openSections)) {
                console.log('Restoring accordion state:', state.openSections);

                // Close all sections first
                this.closeAll();

                // Open saved sections
                state.openSections.forEach(sectionText => {
                    this.sidebar.children('.title').each((index, el) => {
                        const currentText = $(el).text().replace(/\s+/g, ' ').trim();
                        if (currentText === sectionText) {
                            this.openSection(index);
                            return false; // Break loop
                        }
                    });
                });
            }

        } catch (error) {
            console.warn('Failed to restore accordion state:', error);
        }
    }

// Public API methods
    openSection(index) {
        try {
            this.sidebar.accordion('open', index);
        } catch (error) {
            console.error('Failed to open section:', index, error);
        }
    }

    closeSection(index) {
        try {
            this.sidebar.accordion('close', index);
        } catch (error) {
            console.error('Failed to close section:', index, error);
        }
    }

    toggleSection(index) {
        try {
            this.sidebar.accordion('toggle', index);
        } catch (error) {
            console.error('Failed to toggle section:', index, error);
        }
    }

    closeAll() {
        try {
            // Close all sections by index
            const sectionCount = this.sidebar.children('.title').length;
            for (let i = 0; i < sectionCount; i++) {
                this.sidebar.accordion('close', i);
            }
        } catch (error) {
            console.error('Failed to close all sections:', error);
        }
    }

    openAll() {
        try {
            // Open all sections by index
            const sectionCount = this.sidebar.children('.title').length;
            for (let i = 0; i < sectionCount; i++) {
                this.sidebar.accordion('open', i);
            }
        } catch (error) {
            console.error('Failed to open all sections:', error);
        }
    }

    refresh() {
        try {
            this.sidebar.accordion('refresh');
            this.initNestedAccordions();
        } catch (error) {
            console.error('Failed to refresh accordion:', error);
        }
    }

    destroy() {
        try {
            if (this.sidebar.hasClass('ui')) {
                this.sidebar.accordion('destroy');
            }
            this.sidebar.off();
            this.searchInput.off();
        } catch (error) {
            console.error('Failed to destroy accordion:', error);
        }
    }

// Utility methods
    getSectionCount() {
        return this.sidebar.children('.title').length;
    }

    getOpenSections() {
        const openSections = [];
        this.sidebar.children('.title.active').each((index, el) => {
            openSections.push($(el).text().trim());
        });
        return openSections;
    }

    isReady() {
        return this.sidebar.length > 0 && this.sidebar.hasClass('ui');
    }
}

export default AccordionSidebar;
