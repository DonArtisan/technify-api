import namedColors from 'color-name-list';
const input = document.querySelector('#color');
const buttonColor = document.querySelector('#color-btn');

buttonColor.addEventListener('click', () => {
    buttonColor.style.backgroundColor = input.value;
});
