# Projecte Cantina — Estat actual

Revisió feta el 22/11/2026 sobre la branca `main` (58 commits), comparada amb `requisits-cantina.md`.

Llegenda: ✅ fet · 🟡 parcial · ❌ pendent

## Resum

| Bloc | Estat |
|------|-------|
| Requisits obligatoris | 4 de 5 pàgines acabades; **falta l'administració** (només hi ha un esquelet buit) |
| Millores | 3 de 4 fetes; falta el CRUD de productes |
| Organització (Trello/GitHub) | GitHub en marxa; Trello/Jira no es pot verificar des del codi |

**Bloquejant per al lliurament:** la pàgina d'administració (login + llistat de tiquets) no existeix a `main`.

---

## Requisits obligatoris

### Pàgina inicial (`public/index.php`)
- ✅ Header i footer comuns (via `includes/layout.php`).
- ✅ Imatge de fons (banner `banner--inici`, amb `hero.png`).

### Header
- ✅ Logo (`includes/header.php`).
- 🟡 **Nom del lloc web**: el header només mostra el logo; el nom surt al `<title>` i al banner, però no al header. Cal afegir-lo o confirmar que el logo el conté.
- ✅ Menú de navegació (amb menú hamburguesa i botó de tema clar/fosc).

### Footer
- ✅ Grup de treball (Grup AMN — Aaron, Marc i Noelia).
- ✅ Adreça de la cantina.
- ✅ Enllaç a condicions legals.

### Menú de navegació
- ✅ Enllaç a Compra.
- ✅ Enllaç a Administració.

### Pàgina de compra (`public/compra.php`)
- ✅ Mostra els productes disponibles (els esgotats surten apagats).
- ✅ Afegir productes al tiquet (botó carret + selector `-`/`+`).
- ✅ Treure productes del tiquet.
- ✅ Finalitzar la compra → `finalitzacio.php`.

### Pàgina de finalització (`public/finalitzacio.php`)
- ✅ Tiquet / resum de la compra.
- ✅ Formulari amb les dades de l'usuari.
- ✅ Missatge d'èxit després d'enviar.
- ✅ Tiquet desat al servidor (`data/comandes.txt`, una comanda JSON per línia, amb `LOCK_EX`). També es resten les unitats a `productes.json`.

### Formulari
- ✅ Nom, telèfon i correu (+ cognom opcional).
- ✅ Dades desades juntament amb la comanda.
- ✅ Validació al client (`formulari.js`) i al servidor (`validar_dades`).

### Pàgina d'administració (`public/admin.php`)
- ❌ **Mostra tots els tiquets**: a `main` el fitxer és només un esquelet (`<main></main>` buit).
- ❌ **Mecanisme de seguretat** (login usuari/contrasenya): no existeix (ni sessió d'admin, ni credencials a `config.php`).
- 🟡 La branca `origin/admin` té el frontend (taula + estils `_tables.scss`) però **no està fusionada** i tampoc llegeix `comandes.txt` ni té login.

### Pàgina de condicions legals (`public/condicions.php`)
- ✅ 12 apartats de contingut legal (RGPD/LOPDGDD inclosos).

---

## Millores opcionals

| Millora | Estat | Notes |
|---------|-------|-------|
| Ofertes del dia a l'inici | ✅ | `llegir_ofertes()` + secció "Ofertes del dia" |
| Tiquet actualitzat sense recarregar (JS) | ✅ | `carret.js`, tiquet en viu i carret desat a `localStorage` |
| Productes segons hora (esmorzar/dinar/berenar) | ✅ | `franja_actual()` + `franges` a `productes.json`; `FRANJA_PROVES` a `config.php` per provar |
| CRUD de productes a l'administració | ❌ | Afegir, eliminar i modificar: pendent (depèn de tenir l'admin) |

---

## Organització i seguiment

- 🟡 **GitHub**: repositori `noelia287/Projecte_Cantina_AMN`, 58 commits, branques per funcionalitat. Autors: Noelia Gonzalez (35), Marc Cirio (13), aarxn212 (7), Noèlia (3), ajandaoui (2). Cal comprovar que cada membre té els seus commits amb el seu compte (hi ha dues identitats per a Noelia) i **compartir el repositori amb el professorat**.
- ❓ **Trello/Jira**: no es pot verificar des del codi. Cal confirmar que existeix i que està compartit amb el professorat.

---

## Què queda per acabar (per prioritat)

1. **Admin amb login** (obligatori): formulari d'usuari/contrasenya, `session_start()`, credencials a `config/config.php` (idealment contrasenya amb `password_hash`/`password_verify`), botó de sortir.
2. **Admin: llistar tiquets** (obligatori): llegir `comandes.txt` línia a línia (`json_decode`), mostrar-los en taula/targetes; reaprofitar `mostrar_tiquet()`. Es pot partir de la branca `origin/admin`.
3. **Header: nom del lloc web** (obligatori): afegir text al costat del logo.
4. **CRUD de productes** (millora): afegir, modificar i eliminar sobre `data/productes.json`, protegit pel mateix login. Cal validar al servidor i gestionar `id` nous i imatges.
5. **Trello/Jira i GitHub**: verificar i compartir amb el professorat; revisar autories.
6. **Fusionar o tancar branques** pendents: `origin/admin` i `origin/header` no estan a `main`.

## Riscos i punts tècnics a revisar abans de lliurar

- **Vite en mode desenvolupament forçat**: `includes/layout.php` carrega `http://localhost:5173/...` fixe. Sense `npm run dev` en marxa, **la pàgina es veu sense estils ni JS**. Per al lliurament cal compilar (`npm run build`, ja genera `manifest.json`) i fer que PHP llegeixi `dist/`, o documentar clarament com executar-ho. `frontend/dist` està al `.gitignore`.
- **`README.md` buit** (només "Estructura Proyecto Cantina??"): cal escriure com instal·lar i executar el projecte (XAMPP + Node/Vite), estructura de carpetes i credencials d'admin.
- **Permisos d'escriptura**: `data/comandes.txt` i `data/productes.json` han de ser escrivibles per Apache.
- **Dades a Git**: `comandes.txt` (dades personals dels clients) està versionat; convé afegir-lo al `.gitignore` o mantenir-lo buit al repositori.
- **Condicions legals**: esmenten una "Política de privacitat" que no existeix; revisar o treure la referència.
- **Proves manuals** pendents: compra completa en cada franja, producte esgotat, formulari amb dades invàlides, i tancada la cantina (fora d'horari es mostren tots els productes).
