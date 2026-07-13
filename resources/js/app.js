import Alpine from 'alpinejs';
import { Chart } from 'chart.js/auto';
import { Network } from 'vis-network/standalone/esm/vis-network.js';

window.Alpine = Alpine;
window.Chart = Chart;
window.VisNetwork = Network;

Alpine.start();
