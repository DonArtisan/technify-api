import './bootstrap';

import Alpine from 'alpinejs';
import Toastify from "toastify-js/src/toastify";
import "toastify-js/src/toastify.css"

window.Alpine = Alpine;

Alpine.start();

window.addEventListener('wire::message', ({ detail }) => {
  Toastify({
    className: 'capitalize',
    text: detail.message,
    gravity: 'top',
    position: 'right',
  }).showToast()
})

const dropdowns = document.querySelectorAll('#sidebar span[data-dropdown]')

dropdowns.forEach((elem) => {
    const target = elem.dataset.target;
    const itemsElement = document.querySelector(`[data-id=${target}]`)

    elem.addEventListener('click', () => {
        itemsElement.classList.toggle('hidden')
    })
})
