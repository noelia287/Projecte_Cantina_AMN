// IMPORTS
// Font Awesome: només el CSS base i les icones sòlides
import '@fortawesome/fontawesome-free/css/fontawesome.min.css'
import '@fortawesome/fontawesome-free/css/solid.min.css'
import '../css/style.scss'
import './formulari.js'
import './carret.js'


// EVENT: MENÚ HAMBURGUESA (mòbil)
const toggle = document.querySelector('.menu-toggle')
const menu = document.getElementById('menu-principal')

if (toggle && menu) {
    toggle.addEventListener('click', () => {
        const obert = menu.classList.toggle('is-open') // afegeix o treu la classe i diu si ara està obert

        // Si el menú està obert es mostra la creu, sinó les tres ratlles
        if (obert) {
            toggle.querySelector('i').className = 'fa-solid fa-xmark'
        } else {
            toggle.querySelector('i').className = 'fa-solid fa-bars'
        }

        toggle.setAttribute('aria-expanded', obert)
    })
}

// Tema clar/fosc
const themeToggle = document.querySelector('.theme-toggle')
const root = document.documentElement

function applyTheme(theme) {
    root.setAttribute('data-theme', theme)
    if (themeToggle) {
        themeToggle.textContent = theme === 'dark' ? '☀️' : '🌙'
        themeToggle.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false')
        themeToggle.setAttribute('aria-label', theme === 'dark' ? 'Canviar a tema clar' : 'Canviar a tema fosc')
    }
    try { localStorage.setItem('theme', theme) } catch (e) {}
}

const savedTheme = (function () {
    try { return localStorage.getItem('theme') } catch (e) { return null }
})() || root.getAttribute('data-theme') || 'dark'

applyTheme(savedTheme)

if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        const current = root.getAttribute('data-theme') || 'dark'
        applyTheme(current === 'dark' ? 'light' : 'dark')
    })
}
