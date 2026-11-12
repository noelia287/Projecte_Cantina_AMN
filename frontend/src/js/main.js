import '../css/style.scss'
import './formulari.js'

// Menú hamburguesa (mòbil)
const toggle = document.querySelector('.menu-toggle')
const menu = document.getElementById('menu-principal')

if (toggle && menu) {
    const setOpen = (open) => {
        menu.classList.toggle('is-open', open)
        toggle.textContent = open ? '✕' : '☰'
        toggle.setAttribute('aria-expanded', String(open))
        toggle.setAttribute('aria-label', open ? 'Tancar el menú' : 'Obrir el menú')
    }

    toggle.addEventListener('click', () => setOpen(!menu.classList.contains('is-open')))
    document.addEventListener('keydown', (e) => e.key === 'Escape' && setOpen(false))
    window.matchMedia('(min-width: 768px)').addEventListener('change', () => setOpen(false))
}
