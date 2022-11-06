import './bootstrap';

import Alpine from 'alpinejs';
import Toastify from "toastify-js/src/toastify";
import "toastify-js/src/toastify.css"

window.Alpine = Alpine;

Alpine.start();

window.addEventListener('wire::message', ({ detail }) => {
  Toastify({
    text: detail.message,
    gravity: 'bottom',
    position: 'right',
  }).showToast()
})
