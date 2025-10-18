import './bootstrap';
import Alpine from 'alpinejs';
import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

// Start Livewire + Alpine
Livewire.start();
window.Alpine = Alpine;
Alpine.start();
