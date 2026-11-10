import '../css/style.scss'

// Menú hamburguesa (mòbil)
const toggle = document.querySelector('.menu-toggle')
const menu = document.getElementById('menu-principal')

toggle.addEventListener('click', () => {
    menu.classList.toggle('is-open')
    toggle.textContent = menu.classList.contains('is-open') ? '✕' : '☰'
})