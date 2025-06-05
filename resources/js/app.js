import './bootstrap';
import './webcomponents';
//import CollapsibleSidebar from './collapsible_sidebar';
import AccordionSidebar from './AccordionSidebar.js'

import Chart from 'chart.js/auto';

import svgPanZoom from "svg-pan-zoom";
import ky from 'ky';
import Split from 'split.js'

// import '../css/fomantic-ui/semantic.less';
//import 'primeflex/primeflex.css';
import '../css/styles/app.less';
//import '../css_old_jun25/webcomponents.scss';

window.Chart = Chart;
window.svgPanZoom = svgPanZoom;
window.ky = ky;
window.Split = Split;
//window.CollapsibleSidebar = CollapsibleSidebar;
window.AccordionSidebar = AccordionSidebar;

