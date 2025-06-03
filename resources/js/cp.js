import Vue from 'vue';
import SecurityStats from './components/SecurityStats';
import AttackLog from './components/AttackLog';
import VulnerabilityScanner from './components/VulnerabilityScanner';

// Register components globally
Vue.component('security-stats', SecurityStats);
Vue.component('attack-log', AttackLog);
Vue.component('vulnerability-scanner', VulnerabilityScanner);

// Initialize components when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new Vue({
        el: '#app'
    });
}); 
 