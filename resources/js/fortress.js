// Fortress Addon JavaScript
import LogViewer from './components/LogViewer';
import SecurityStats from './components/SecurityStats';
import VulnerabilityScanner from './components/VulnerabilityScanner';

// Initialize components
document.addEventListener('DOMContentLoaded', () => {
    new LogViewer();
    new SecurityStats();
    new VulnerabilityScanner();
}); 
 