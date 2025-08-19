
import './bootstrap';
import Alpine from 'alpinejs';

// Lazy load Chart.js only when needed
// const Chart = () => import('chart.js/auto');

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
import tabsComponent from './components/tabsComponent.js';

// Bulma components will be imported after Alpine is available

// Import stylesheets (SASS will replace LESS gradually)
//import '../sass/app.scss';
//import 'primeflex/primeflex.css';
import '../css/app.less';

// Lazy load Chart.js and make it available globally
// window.Chart = async () => {
//     const { default: ChartJS } = await Chart();
//     return ChartJS;
// };
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
    Alpine.data('tabsComponent', tabsComponent);
    Alpine.start();

});
