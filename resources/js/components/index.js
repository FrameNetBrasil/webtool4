/**
 * Bulma Component Library - Export Index
 * Centralized export for all Bulma Alpine.js components
 */

// Import all Bulma components
import dataGrid from './dataGridBulmaComponent.js';

// Component registry for easy access
export const BulmaComponents = {
    // Data components
    dataGrid,
    
    // Interactive components (from bulmaComponents.js)
    dropdown: 'dropdown',
    modal: 'modal',
    tabs: 'tabs',
    accordion: 'accordion',
    navbar: 'navbar',
    notification: 'notification',
    formValidation: 'formValidation',
    
    // Utility functions
    register: (Alpine) => {
        // Register data grid component
        Alpine.data('dataGrid', dataGrid);
        
        // Other components are registered in bulmaComponents.js
        console.log('✅ Bulma Component Library registered');
    },
    
    // Component factory for dynamic creation
    create: (componentName, config = {}) => {
        switch (componentName) {
            case 'dataGrid':
                return dataGrid(config);
            default:
                console.warn(`Component '${componentName}' not found in Bulma library`);
                return null;
        }
    }
};

// Auto-register when Alpine is available
if (window.Alpine) {
    BulmaComponents.register(window.Alpine);
} else {
    // Wait for Alpine to load
    document.addEventListener('alpine:init', () => {
        BulmaComponents.register(Alpine);
    });
}

// Export individual components for tree shaking
export { default as dataGrid } from './dataGridBulmaComponent.js';
export { default as bulmaComponents } from './bulmaComponents.js';

// Default export
export default BulmaComponents;