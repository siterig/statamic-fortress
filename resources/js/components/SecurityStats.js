// SecurityStats Component
export default {
    name: 'SecurityStats',
    mounted() {
        this.initializeEventListeners();
    },
    methods: {
        initializeEventListeners() {
            document.addEventListener('DOMContentLoaded', () => {
                this.setupRefreshButton();
            });
        },
        setupRefreshButton() {
            const refreshButton = document.querySelector('[data-refresh-stats]');
            if (refreshButton) {
                refreshButton.addEventListener('click', () => {
                    this.refreshStats();
                });
            }
        },
        async refreshStats() {
            try {
                const response = await fetch('/cp/fortress/stats');
                const data = await response.json();
                this.updateStats(data);
            } catch (error) {
                console.error('Failed to refresh stats:', error);
            }
        },
        updateStats(data) {
            // Update each stat element with new data
            Object.entries(data).forEach(([key, value]) => {
                const element = document.querySelector(`[data-stat="${key}"]`);
                if (element) {
                    element.textContent = value;
                }
            });
        }
    }
}; 
 