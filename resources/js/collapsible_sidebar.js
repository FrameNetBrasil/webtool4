/**
 * Collapsible Sidebar Navigation
 * Handles section collapsing, search filtering, and state persistence
 */

class CollapsibleSidebar {
    constructor(options = {}) {
        this.options = {
            sidebarSelector: '.app-sidebar',
            sectionSelector: '.nav-section',
            headerSelector: '.section-header',
            itemsSelector: '.section-items',
            searchSelector: '.sidebar-search input',
            navItemSelector: '.nav-item',
            storageKey: 'sidebar-state',
            autoSave: true,
            searchDelay: 300,
            ...options
        };

        this.searchTimeout = null;
        this.originalItems = new Map(); // Store original nav items for search

        this.init();
    }

    init() {
        this.bindEvents();
        this.loadState();
        this.initializeSearch();
        this.trackUsage();
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Section header clicks for collapsing
        document.addEventListener('click', (e) => {
            const header = e.target.closest(this.options.headerSelector);
            if (header) {
                this.toggleSection(header);
            }
        });

        // Search functionality
        const searchInput = document.querySelector(this.options.searchSelector);
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.handleSearch(e.target.value);
            });

            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    e.target.value = '';
                    this.clearSearch();
                }
            });
        }

        // Navigation item clicks for usage tracking
        document.addEventListener('click', (e) => {
            const navItem = e.target.closest(this.options.navItemSelector);
            if (navItem) {
                this.trackNavItemUsage(navItem);
            }
        });

        // Auto-save state on changes
        if (this.options.autoSave) {
            document.addEventListener('click', (e) => {
                if (e.target.closest(this.options.headerSelector)) {
                    setTimeout(() => this.saveState(), 100);
                }
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            this.handleKeyboardShortcuts(e);
        });
    }

    /**
     * Toggle section collapse/expand
     */
    toggleSection(header) {
        const section = header.closest(this.options.sectionSelector);
        const items = section.querySelector(this.options.itemsSelector);
        const icon = header.querySelector('.collapse-icon');
        
        if (!items) return;

        const isCollapsed = items.classList.contains('collapsed');
        
        if (isCollapsed) {
            this.expandSection(section, items, icon, header);
        } else {
            this.collapseSection(section, items, icon, header);
        }

        // Trigger custom event
        const event = new CustomEvent('sidebarSectionToggle', {
            detail: {
                section: section,
                collapsed: !isCollapsed,
                sectionName: this.getSectionName(header)
            }
        });
        document.dispatchEvent(event);
    }

    /**
     * Expand a section
     */
    expandSection(section, items, icon, header) {
        items.classList.remove('collapsed');
        header.classList.remove('collapsed');
        
        if (icon) {
            icon.style.transform = 'rotate(0deg)';
        }

        // Smooth animation
        items.style.maxHeight = items.scrollHeight + 'px';
        setTimeout(() => {
            items.style.maxHeight = '';
        }, 300);
    }

    /**
     * Collapse a section
     */
    collapseSection(section, items, icon, header) {
        items.style.maxHeight = items.scrollHeight + 'px';
        
        // Force reflow
        items.offsetHeight;
        
        items.style.maxHeight = '0';
        items.classList.add('collapsed');
        header.classList.add('collapsed');
        
        if (icon) {
            icon.style.transform = 'rotate(-90deg)';
        }
    }

    /**
     * Handle search input with debouncing
     */
    handleSearch(query) {
        clearTimeout(this.searchTimeout);
        
        this.searchTimeout = setTimeout(() => {
            if (query.trim() === '') {
                this.clearSearch();
            } else {
                this.performSearch(query.toLowerCase());
            }
        }, this.options.searchDelay);
    }

    /**
     * Perform the actual search
     */
    performSearch(query) {
        const sections = document.querySelectorAll(this.options.sectionSelector);
        let hasResults = false;

        sections.forEach(section => {
            const items = section.querySelectorAll(this.options.navItemSelector);
            let sectionHasResults = false;

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                const matches = text.includes(query);

                if (matches) {
                    item.style.display = '';
                    this.highlightMatch(item, query);
                    sectionHasResults = true;
                    hasResults = true;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide sections based on results
            if (sectionHasResults) {
                section.style.display = '';
                // Expand sections with results
                const header = section.querySelector(this.options.headerSelector);
                const itemsContainer = section.querySelector(this.options.itemsSelector);
                if (header && itemsContainer && itemsContainer.classList.contains('collapsed')) {
                    this.expandSection(section, itemsContainer, 
                        header.querySelector('.collapse-icon'), header);
                }
            } else {
                section.style.display = 'none';
            }
        });

        // Show "no results" message if needed
        this.toggleNoResultsMessage(!hasResults, query);
    }

    /**
     * Clear search and restore original view
     */
    clearSearch() {
        const sections = document.querySelectorAll(this.options.sectionSelector);
        
        sections.forEach(section => {
            section.style.display = '';
            
            const items = section.querySelectorAll(this.options.navItemSelector);
            items.forEach(item => {
                item.style.display = '';
                this.removeHighlight(item);
            });
        });

        this.toggleNoResultsMessage(false);
        this.loadState(); // Restore collapsed states
    }

    /**
     * Highlight search matches
     */
    highlightMatch(item, query) {
        const textNode = item.querySelector('.nav-text');
        if (!textNode) return;

        const text = textNode.textContent;
        const regex = new RegExp(`(${query})`, 'gi');
        const highlightedText = text.replace(regex, '<mark>$1</mark>');
        
        textNode.innerHTML = highlightedText;
    }

    /**
     * Remove search highlights
     */
    removeHighlight(item) {
        const textNode = item.querySelector('.nav-text');
        if (!textNode) return;

        textNode.innerHTML = textNode.textContent;
    }

    /**
     * Toggle no results message
     */
    toggleNoResultsMessage(show, query = '') {
        let noResultsDiv = document.querySelector('.sidebar-no-results');
        
        if (show && !noResultsDiv) {
            noResultsDiv = document.createElement('div');
            noResultsDiv.className = 'sidebar-no-results';
            noResultsDiv.innerHTML = `
                <div class="no-results-content">
                    <i class="search icon"></i>
                    <div class="no-results-text">No results found for "${query}"</div>
                    <div class="no-results-suggestion">Try a different search term</div>
                </div>
            `;
            
            const sidebarContent = document.querySelector('.sidebar-content');
            if (sidebarContent) {
                sidebarContent.appendChild(noResultsDiv);
            }
        } else if (!show && noResultsDiv) {
            noResultsDiv.remove();
        }
    }

    /**
     * Initialize search functionality
     */
    initializeSearch() {
        // Store original items for search restoration
        const sections = document.querySelectorAll(this.options.sectionSelector);
        sections.forEach((section, sectionIndex) => {
            const items = section.querySelectorAll(this.options.navItemSelector);
            this.originalItems.set(sectionIndex, Array.from(items));
        });
    }

    /**
     * Save sidebar state to localStorage
     */
    saveState() {
        const state = {
            collapsedSections: this.getCollapsedSections(),
            timestamp: Date.now()
        };

        try {
            localStorage.setItem(this.options.storageKey, JSON.stringify(state));
        } catch (e) {
            console.warn('Could not save sidebar state:', e);
        }
    }

    /**
     * Load sidebar state from localStorage
     */
    loadState() {
        try {
            const savedState = localStorage.getItem(this.options.storageKey);
            if (!savedState) return;

            const state = JSON.parse(savedState);
            
            // Don't restore very old states (older than 7 days)
            if (Date.now() - state.timestamp > 7 * 24 * 60 * 60 * 1000) {
                return;
            }

            this.restoreCollapsedSections(state.collapsedSections);
        } catch (e) {
            console.warn('Could not load sidebar state:', e);
        }
    }

    /**
     * Get currently collapsed sections
     */
    getCollapsedSections() {
        const collapsed = [];
        const headers = document.querySelectorAll(this.options.headerSelector);
        
        headers.forEach(header => {
            if (header.classList.contains('collapsed')) {
                collapsed.push(this.getSectionName(header));
            }
        });

        return collapsed;
    }

    /**
     * Restore collapsed sections from saved state
     */
    restoreCollapsedSections(collapsedSections) {
        const headers = document.querySelectorAll(this.options.headerSelector);
        
        headers.forEach(header => {
            const sectionName = this.getSectionName(header);
            
            if (collapsedSections.includes(sectionName)) {
                const section = header.closest(this.options.sectionSelector);
                const items = section.querySelector(this.options.itemsSelector);
                const icon = header.querySelector('.collapse-icon');
                
                // Collapse without animation on load
                items.classList.add('collapsed');
                header.classList.add('collapsed');
                if (icon) {
                    icon.style.transform = 'rotate(-90deg)';
                }
            }
        });
    }

    /**
     * Get section name from header
     */
    getSectionName(header) {
        return header.textContent.trim().replace(/[^\w\s]/gi, '');
    }

    /**
     * Track navigation item usage
     */
    trackNavItemUsage(navItem) {
        const itemText = navItem.querySelector('.nav-text')?.textContent || 
                        navItem.textContent.trim();
        
        try {
            let usage = JSON.parse(localStorage.getItem('sidebar-usage') || '{}');
            usage[itemText] = (usage[itemText] || 0) + 1;
            usage.lastAccess = Date.now();
            
            localStorage.setItem('sidebar-usage', JSON.stringify(usage));
        } catch (e) {
            console.warn('Could not save usage data:', e);
        }
    }

    /**
     * Initialize usage tracking
     */
    trackUsage() {
        // Optional: Auto-collapse unused sections after a certain period
        setTimeout(() => {
            this.optimizeBasedOnUsage();
        }, 1000);
    }

    /**
     * Optimize sidebar based on usage patterns
     */
    optimizeBasedOnUsage() {
        try {
            const usage = JSON.parse(localStorage.getItem('sidebar-usage') || '{}');
            const sections = document.querySelectorAll(this.options.sectionSelector);
            
            sections.forEach(section => {
                const sectionName = this.getSectionName(
                    section.querySelector(this.options.headerSelector)
                );
                
                const items = section.querySelectorAll(this.options.navItemSelector);
                let sectionUsage = 0;
                
                items.forEach(item => {
                    const itemText = item.querySelector('.nav-text')?.textContent || 
                                   item.textContent.trim();
                    sectionUsage += usage[itemText] || 0;
                });
                
                // Auto-collapse sections with very low usage
                if (sectionUsage < 2 && !this.isEssentialSection(sectionName)) {
                    const header = section.querySelector(this.options.headerSelector);
                    const items = section.querySelector(this.options.itemsSelector);
                    
                    if (header && items && !items.classList.contains('collapsed')) {
                        this.collapseSection(section, items, 
                            header.querySelector('.collapse-icon'), header);
                    }
                }
            });
        } catch (e) {
            console.warn('Could not optimize based on usage:', e);
        }
    }

    /**
     * Check if section is essential (should not auto-collapse)
     */
    isEssentialSection(sectionName) {
        const essential = ['main', 'navigation', 'current work', 'favorites'];
        return essential.some(name => 
            sectionName.toLowerCase().includes(name.toLowerCase())
        );
    }

    /**
     * Handle keyboard shortcuts
     */
    handleKeyboardShortcuts(e) {
        // Alt + S: Focus search
        if (e.altKey && e.key === 's') {
            e.preventDefault();
            const searchInput = document.querySelector(this.options.searchSelector);
            if (searchInput) {
                searchInput.focus();
            }
        }

        // Alt + Number: Jump to section
        if (e.altKey && e.key >= '1' && e.key <= '9') {
            e.preventDefault();
            const sectionIndex = parseInt(e.key) - 1;
            const sections = document.querySelectorAll(this.options.sectionSelector);
            
            if (sections[sectionIndex]) {
                const firstItem = sections[sectionIndex].querySelector(this.options.navItemSelector);
                if (firstItem) {
                    firstItem.focus();
                }
            }
        }

        // Alt + C: Collapse all sections
        if (e.altKey && e.key === 'c') {
            e.preventDefault();
            this.collapseAll();
        }

        // Alt + E: Expand all sections
        if (e.altKey && e.key === 'e') {
            e.preventDefault();
            this.expandAll();
        }
    }

    /**
     * Collapse all sections
     */
    collapseAll() {
        const headers = document.querySelectorAll(this.options.headerSelector);
        headers.forEach(header => {
            const section = header.closest(this.options.sectionSelector);
            const items = section.querySelector(this.options.itemsSelector);
            
            if (items && !items.classList.contains('collapsed')) {
                this.collapseSection(section, items, 
                    header.querySelector('.collapse-icon'), header);
            }
        });
        
        if (this.options.autoSave) {
            this.saveState();
        }
    }

    /**
     * Expand all sections
     */
    expandAll() {
        const headers = document.querySelectorAll(this.options.headerSelector);
        headers.forEach(header => {
            const section = header.closest(this.options.sectionSelector);
            const items = section.querySelector(this.options.itemsSelector);
            
            if (items && items.classList.contains('collapsed')) {
                this.expandSection(section, items, 
                    header.querySelector('.collapse-icon'), header);
            }
        });
        
        if (this.options.autoSave) {
            this.saveState();
        }
    }

    /**
     * Public API: Manually collapse a section by name
     */
    collapseSectionByName(sectionName) {
        const headers = document.querySelectorAll(this.options.headerSelector);
        
        headers.forEach(header => {
            if (this.getSectionName(header).toLowerCase().includes(sectionName.toLowerCase())) {
                const section = header.closest(this.options.sectionSelector);
                const items = section.querySelector(this.options.itemsSelector);
                
                if (items && !items.classList.contains('collapsed')) {
                    this.collapseSection(section, items, 
                        header.querySelector('.collapse-icon'), header);
                }
            }
        });
    }

    /**
     * Public API: Manually expand a section by name
     */
    expandSectionByName(sectionName) {
        const headers = document.querySelectorAll(this.options.headerSelector);
        
        headers.forEach(header => {
            if (this.getSectionName(header).toLowerCase().includes(sectionName.toLowerCase())) {
                const section = header.closest(this.options.sectionSelector);
                const items = section.querySelector(this.options.itemsSelector);
                
                if (items && items.classList.contains('collapsed')) {
                    this.expandSection(section, items, 
                        header.querySelector('.collapse-icon'), header);
                }
            }
        });
    }

    /**
     * Public API: Get usage statistics
     */
    getUsageStats() {
        try {
            return JSON.parse(localStorage.getItem('sidebar-usage') || '{}');
        } catch (e) {
            return {};
        }
    }

    /**
     * Public API: Clear all saved data
     */
    clearSavedData() {
        localStorage.removeItem(this.options.storageKey);
        localStorage.removeItem('sidebar-usage');
    }

    /**
     * Public API: Destroy the sidebar instance
     */
    destroy() {
        // Clear timeouts
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }

        // Remove event listeners would require storing references
        // For now, just clear saved data if requested
        // In production, you'd want to properly clean up event listeners
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Check if sidebar exists before initializing
    if (document.querySelector('.app-sidebar')) {
        window.sidebarInstance = new CollapsibleSidebar({
            // You can customize options here
            autoSave: true,
            searchDelay: 200
        });
    }
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CollapsibleSidebar;
}