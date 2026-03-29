import './bootstrap';
import './echo';

import Alpine from 'alpinejs';
import ImageCompressor from './image-compressor';

window.Alpine = Alpine;
window.ImageCompressor = ImageCompressor;

Alpine.start();
