import './bootstrap';

// Initialize Alpine JS FIRST to guarantee UI works
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Initialize Echo / Websockets which might fail if VITE env vars are missing
import './echo';
import ImageCompressor from './image-compressor';
window.ImageCompressor = ImageCompressor;
