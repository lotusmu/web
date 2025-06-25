import './bootstrap';

// Core stream management
import './components/stream-manager.js';

// Refactored components
import './components/stream-widget.js';
import './components/streams-page.js';

// Global cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.StreamManager) {
        window.StreamManager.destroyAll();
    }
});
