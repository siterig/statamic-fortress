const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

// Create dist directory if it doesn't exist
const distDir = path.join(__dirname, 'resources/dist');
if (!fs.existsSync(distDir)) {
    fs.mkdirSync(distDir, { recursive: true });
}

// Create manifest.json
const manifest = {
    'resources/js/fortress.js': {
        'file': 'js/fortress.js',
        'src': 'resources/js/fortress.js',
        'isEntry': true
    },
    'resources/css/fortress.css': {
        'file': 'css/fortress.css',
        'src': 'resources/css/fortress.css',
        'isEntry': true
    }
};

fs.writeFileSync(
    path.join(distDir, 'manifest.json'),
    JSON.stringify(manifest, null, 2)
);

// Copy JavaScript files
const jsDir = path.join(distDir, 'js');
if (!fs.existsSync(jsDir)) {
    fs.mkdirSync(jsDir, { recursive: true });
}

// Bundle JavaScript files
const jsContent = `
// Fortress Addon JavaScript
class LogViewer {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupDetailsModal();
        });
    }

    setupDetailsModal() {
        const modal = document.getElementById('detailsModal');
        if (!modal) return;

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.hideDetails();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideDetails();
            }
        });
    }

    showDetails(details) {
        const content = document.getElementById('detailsContent');
        const modal = document.getElementById('detailsModal');
        
        if (content && modal) {
            content.textContent = JSON.stringify(details, null, 2);
            modal.classList.remove('hidden');
        }
    }

    hideDetails() {
        const modal = document.getElementById('detailsModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
}

class SecurityStats {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupRefreshButton();
        });
    }

    setupRefreshButton() {
        const refreshButton = document.querySelector('[data-refresh-stats]');
        if (refreshButton) {
            refreshButton.addEventListener('click', () => {
                this.refreshStats();
            });
        }
    }

    async refreshStats() {
        try {
            const response = await fetch('/cp/fortress/stats');
            const data = await response.json();
            this.updateStats(data);
        } catch (error) {
            console.error('Failed to refresh stats:', error);
        }
    }

    updateStats(data) {
        Object.entries(data).forEach(([key, value]) => {
            const element = document.querySelector(\`[data-stat="\${key}"]\`);
            if (element) {
                element.textContent = value;
            }
        });
    }
}

class VulnerabilityScanner {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupScanButton();
        });
    }

    setupScanButton() {
        const scanButton = document.querySelector('[data-scan-vulnerabilities]');
        if (scanButton) {
            scanButton.addEventListener('click', () => {
                this.startScan();
            });
        }
    }

    async startScan() {
        const scanButton = document.querySelector('[data-scan-vulnerabilities]');
        const statusElement = document.querySelector('[data-scan-status]');
        
        if (scanButton) {
            scanButton.disabled = true;
            scanButton.textContent = 'Scanning...';
        }

        if (statusElement) {
            statusElement.textContent = 'Scan in progress...';
        }

        try {
            const response = await fetch('/cp/fortress/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const data = await response.json();
            this.updateScanResults(data);
        } catch (error) {
            console.error('Scan failed:', error);
            if (statusElement) {
                statusElement.textContent = 'Scan failed. Please try again.';
            }
        } finally {
            if (scanButton) {
                scanButton.disabled = false;
                scanButton.textContent = 'Scan Now';
            }
        }
    }

    updateScanResults(data) {
        const statusElement = document.querySelector('[data-scan-status]');
        if (statusElement) {
            statusElement.textContent = \`Scan completed at \${new Date().toLocaleTimeString()}\`;
        }

        const listElement = document.querySelector('[data-vulnerability-list]');
        if (listElement && data.vulnerabilities) {
            listElement.innerHTML = data.vulnerabilities.map(vuln => \`
                <div class="p-4 border-b last:border-b-0">
                    <div class="flex items-center justify-between">
                        <h4 class="font-medium">\${vuln.package}</h4>
                        <span class="fortress-badge fortress-badge--\${vuln.severity}">
                            \${vuln.severity}
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">\${vuln.description}</p>
                </div>
            \`).join('');
        }
    }
}

// Initialize components
document.addEventListener('DOMContentLoaded', () => {
    new LogViewer();
    new SecurityStats();
    new VulnerabilityScanner();
});
`;

fs.writeFileSync(path.join(jsDir, 'fortress.js'), jsContent);

// Copy CSS files
const cssDir = path.join(distDir, 'css');
if (!fs.existsSync(cssDir)) {
    fs.mkdirSync(cssDir, { recursive: true });
}

const cssContent = `
/* Fortress Addon Styles */
.fortress-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.fortress-badge--success {
    background-color: #dcfce7;
    color: #166534;
}

.fortress-badge--warning {
    background-color: #fef9c3;
    color: #854d0e;
}

.fortress-badge--danger {
    background-color: #fee2e2;
    color: #991b1b;
}

.fortress-badge--info {
    background-color: #dbeafe;
    color: #1e40af;
}
`;

fs.writeFileSync(path.join(cssDir, 'fortress.css'), cssContent);

console.log('Assets built successfully!'); 
