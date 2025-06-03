// LogViewer Component
export default class LogViewer {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Add any log viewer specific JavaScript here
        document.addEventListener('DOMContentLoaded', () => {
            this.setupDetailsModal();
        });
    }

    setupDetailsModal() {
        const modal = document.getElementById('detailsModal');
        if (!modal) return;

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.hideDetails();
            }
        });

        // Close modal when pressing escape
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
