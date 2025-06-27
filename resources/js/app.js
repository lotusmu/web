import './bootstrap';

import StreamManager from './components/stream-manager.js';
import './components/stream-widget.js';
import './components/streams-page.js';

window.StreamManager = StreamManager;

window.addEventListener('beforeunload', () => {
    if (window.StreamManager) {
        window.StreamManager.destroyAll();
    }
});
