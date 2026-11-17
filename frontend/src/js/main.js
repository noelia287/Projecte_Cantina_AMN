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
