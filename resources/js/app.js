import './bootstrap';
import Alpine from 'alpinejs';

// Lazy load Chart.js only when needed
const Chart = () => import('chart.js/auto');

// Core utilities - loaded immediately
import svgPanZoom from "svg-pan-zoom";
import ky from 'ky';
import Split from 'split.js';

// Component imports
import './components/messengerComponent.js';
import browseSearchComponent from './components/browseSearchComponent.js';
import searchComponent from './components/searchComponent.js';
import treeComponent from './components/treeComponent.js';
import searchFormComponent from './components/searchFormComponent.js';
import dataGridComponent from './components/dataGridComponent.js';

// Bulma components will be imported after Alpine is available

// Import stylesheets (SASS will replace LESS gradually)
import '../css/app.less';
import '../sass/app.scss';

// Lazy load Chart.js and make it available globally
window.Chart = async () => {
    const { default: ChartJS } = await Chart();
    return ChartJS;
};
window.svgPanZoom = svgPanZoom;
window.ky = ky;
window.Split = Split;

// Make Alpine available globally before any components try to use it
window.Alpine = Alpine;

document.addEventListener("DOMContentLoaded", () => {
    // Register legacy components
    Alpine.data('searchFormComponent', searchFormComponent);
    Alpine.data('searchComponent', searchComponent);
    Alpine.data('treeComponent', treeComponent);
    Alpine.data('browseSearchComponent', browseSearchComponent);
    Alpine.data('dataGrid', dataGridComponent);
    
    // Import and register Bulma components after Alpine is available globally
    import('./components/bulmaComponents.js').then(() => {
        console.log('Bulma components loaded successfully');
        Alpine.start();
    }).catch(error => {
        console.error('Error loading Bulma components:', error);
        Alpine.start(); // Start Alpine even if Bulma components fail to load
    });
});

