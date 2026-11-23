# Projecte Cantina — Instruccions per acabar

Document complementari a `estat_actual.md`. Cada punt té: **descripció**, **què cal fer**, **com fer-ho** i **resultat esperat**.

Ordre recomanat: 4 → 1 → 2 → 3 (obligatoris) → 5 (millora) → 6–7 (organització) → extras.

| # | Punt | Tipus | Dificultat |
|---|------|-------|-----------|
| 1 | Login d'administració | Obligatori | Mitjana |
| 2 | Llistat de tiquets a l'admin | Obligatori | Mitjana |
| 3 | Nom del lloc web al header | Obligatori | Baixa |
| 4 | Fusionar/tancar branques pendents | Previ a l'admin | Baixa |
| 5 | CRUD de productes | Millora | Alta |
| 6 | Trello/Jira compartit | Lliurable | Baixa |
| 7 | GitHub: autories i accés del professorat | Lliurable | Baixa |
| E1–E7 | Extras | Recomanat | Variable |

---

## 1. Login d'administració (obligatori)

**Descripció.** Els requisits demanen que s'accedeixi a l'administració "amb algun mecanisme de seguretat". Ara `public/admin.php` és buit i qualsevol persona hi pot entrar.

**Què cal fer.**
- Un formulari d'usuari i contrasenya.
- Mantenir la sessió de l'administrador.
- Bloquejar tot el contingut de l'admin si no s'ha iniciat sessió.
- Un botó per tancar sessió.

**Com fer-ho.**
1. Definir les credencials a `config/config.php`: un nom d'usuari i el **hash** de la contrasenya (generat amb la funció `password_hash` de PHP). Mai guardar la contrasenya en text pla al repositori.
2. Crear un fitxer nou d'includes (per exemple `includes/funcions_admin.php`) amb dues funcions: una que digui si l'administrador ha iniciat sessió i una altra que validi usuari i contrasenya amb `password_verify`. Si les dades són correctes, regenerar l'identificador de sessió (`session_regenerate_id`) per evitar la fixació de sessió i marcar la sessió com a administrador.
3. A l'inici d'`admin.php`, activar la sessió, gestionar l'enviament del formulari de login i la petició de sortir (destruir la sessió i redirigir).
4. Si no hi ha sessió d'administrador, mostrar **només** el formulari de login i aturar l'execució abans de llegir cap dada. El missatge d'error ha de ser genèric ("Usuari o contrasenya incorrectes"), sense indicar quin dels dos camps falla.
5. Crear els estils del login i del panell en un SCSS de pàgina nou (registrat a l'índex de pàgines), reaprofitant les variables dels temes clar i fosc.

**Resultat esperat.**
- Entrar a `admin.php` sense sessió només mostra el login.
- Credencials incorrectes: missatge d'error i no s'entra.
- Credencials correctes: es veu el panell, i el botó de sortir torna al login.
- No hi ha cap contrasenya en text pla al repositori.

---

## 2. Llistat de tiquets a l'administració (obligatori)

**Descripció.** L'admin ha de mostrar **tots els tiquets** desats. Les comandes estan a `data/comandes.txt`, una per línia en format JSON, amb número, data, dades del client, productes i total.

**Què cal fer.** Llegir el fitxer i mostrar cada comanda de manera clara, de la més recent a la més antiga.

**Com fer-ho.**
1. Crear una funció per llegir les comandes: si el fitxer no existeix retorna una llista buida; si existeix, llegeix cada línia no buida, la descodifica i **ignora les línies que no es puguin descodificar** perquè una línia corrupta no trenqui la pàgina. Al final inverteix l'ordre perquè surtin primer les més recents.
2. A `admin.php`, només amb la sessió validada, recórrer les comandes i mostrar-les amb la funció `mostrar_tiquet()`, que ja existeix. Si es prefereix una taula resum (número, data, client, total), es pot partir del que hi ha a la branca `origin/admin`.
3. Mostrar un comptador de comandes i un missatge "Encara no hi ha comandes" quan estigui buit.
4. Comprovar que tota la sortida passa per `htmlspecialchars`: nom, cognom i correu els escriu l'usuari.

**Resultat esperat.**
- Cada compra feta a la web apareix al panell amb data, dades del client, productes, quantitats i total.
- Amb el fitxer buit surt el missatge corresponent, sense errors de PHP.
- Una línia malformada al fitxer no trenca la pàgina.

---

## 3. Nom del lloc web al header (obligatori)

**Descripció.** El header ha de tenir logo **i** nom del lloc. Ara només té el logo (`includes/header.php`).

**Què cal fer.** Afegir el nom ("Cantina" o "Projecte Cantina") al costat del logo, enllaçat a l'inici.

**Com fer-ho.**
1. A `header.php`, afegir un element de text al costat del logo.
2. A `frontend/src/css/layout/_header.scss`, col·locar logo i text en línia i alineats verticalment. Si no hi cap en pantalles petites, amagar el text només a partir d'una amplada concreta.
3. Mantenir l'alineació amb el contingut (feta al commit `1d2971a`) i provar els temes clar i fosc.

**Resultat esperat.** En totes les pàgines i mides (escriptori, tauleta i mòbil) es veu logo + nom, sense trencar el menú hamburguesa.

---

## 4. Fusionar o tancar les branques pendents

**Descripció.** `origin/admin` i `origin/header` no estan a `main`. L'admin conté feina aprofitable (taula i estils) que es podria perdre.

**Què cal fer.** Decidir per a cada branca si es fusiona o es descarta.

**Com fer-ho.**
1. Actualitzar amb `git fetch` i revisar què aporta cada branca amb un `git diff` contra `main`.
2. Crear una branca de treball des de `main`, fusionar-hi la branca remota i resoldre els conflictes.
3. Atenció a `data/productes.json`: la branca `admin` el modifica, i no s'han de perdre els camps de `main` (`franges` i `preu_oferta`).
4. Fer-ho **abans** dels punts 1 i 2, i obrir una Pull Request a GitHub perquè quedi constància.

**Resultat esperat.** `git branch -r --no-merged main` ja no mostra branques amb feina útil pendent.

---

## 5. CRUD de productes a l'administració (millora)

**Descripció.** Poder afegir, modificar i eliminar productes des de l'admin. Les dades estan a `data/productes.json` (id, nom, descripció, preu, unitats, imatge, preu d'oferta i franges).

**Què cal fer.** Una secció "Productes" a l'admin, protegida pel login del punt 1, amb llistat i les tres accions.

**Com fer-ho.**
1. **Funció d'escriptura comuna.** Ara només existeix la lectura (`llegir_productes`); l'escriptura està dins `restar_unitats`. Extreure-la a una funció pròpia que guardi tot el JSON amb bloqueig d'escriptura i el mateix format, i fer que `restar_unitats` la reaprofiti.
2. **Afegir.** Formulari amb nom, descripció, preu, unitats, preu d'oferta (0 vol dir sense oferta), franges (caselles d'esmorzar, dinar i berenar) i imatge. L'`id` nou és el màxim dels existents més u.
3. **Modificar.** El mateix formulari ja emplenat, identificant el producte pel seu `id`.
4. **Eliminar.** Un botó amb confirmació de JavaScript, enviat sempre per **POST**, mai per GET.
5. **Validació al servidor** (no només a JavaScript):
   - El nom no pot ser buit.
   - El preu ha de ser major que 0.
   - Les unitats han de ser un enter major o igual que 0.
   - El preu d'oferta ha de ser 0 o menor que el preu.
   - Cal almenys una franja, i només dins de les tres permeses.
6. **Imatge.** Escollir-ne una de les que ja hi ha a `frontend/public/`, o pujar-ne una validant extensió (jpg, jpeg, png, webp), tipus MIME real i mida màxima, i canviant-li el nom.
7. **Seguretat.** Comprovar la sessió d'administrador a **cada** acció, afegir un token CSRF a cada formulari i escapar tota la sortida.
8. **Flux.** Després de cada POST, redirigir (patró Post/Redirect/Get) i mostrar un missatge d'èxit o d'error.

**Resultat esperat.**
- Un producte nou apareix a la pàgina de compra, a les franges triades, i a l'inici si té oferta.
- Els canvis de preu, unitats o franges es reflecteixen de seguida a la botiga.
- Un producte eliminat desapareix sense trencar les comandes antigues, perquè el tiquet guarda nom i preu.
- Les dades invàlides mostren errors i no toquen el JSON.
- Sense sessió d'administrador cap acció funciona, ni enviant el POST directament.

---

## 6. Trello o Jira compartit amb el professorat (lliurable)

**Descripció.** Cal usar un tauler d'organització i compartir-lo. No es pot verificar des del codi.

**Què cal fer.** Confirmar que existeix, que reflecteix la feina feta i que el professorat hi té accés.

**Com fer-ho.**
1. Columnes: Pendent, En curs, Revisió i Fet.
2. Una targeta per cada punt d'aquest document i per cada pàgina, amb membres assignats.
3. Moure a "Fet" tot el que ja és a `main`: inici, compra, finalització, condicions, formulari, ofertes, tiquet dinàmic i franges.
4. Compartir amb el correu del professorat i posar l'enllaç al `README.md`.

**Resultat esperat.** El professorat obre el tauler i veu qui ha fet què i què queda pendent.

---

## 7. GitHub: autories i accés del professorat (lliurable)

**Descripció.** Cada membre ha de fer commits amb el **seu** compte i el repositori ha d'estar compartit. Ara hi ha cinc identitats (Noelia Gonzalez, Marc Cirio, aarxn212, Noèlia, ajandaoui). "Noelia Gonzalez" i "Noèlia" semblen la mateixa persona amb dos noms configurats.

**Què cal fer.**
- Que cada membre tingui configurats el seu nom i el seu correu a git, i que el correu estigui verificat al seu compte de GitHub perquè els commits se li atribueixin.
- Repartir entre tots els commits que falten (admin, CRUD, README) i fer Pull Requests.
- Afegir el professorat com a col·laborador (Settings → Collaborators) o fer el repositori públic.

**Com fer-ho.**
1. Cada persona configura el seu nom i correu a git en local.
2. Revisar les identitats amb `git log` i comptar-les.
3. Opcionalment, afegir un fitxer `.mailmap` per unificar les dues identitats de Noelia a les estadístiques.
4. **No reescriure l'historial compartit** (rebase o force push) per arreglar-ho: trencaria les branques dels companys.

**Resultat esperat.** A Insights → Contributors surten tots els membres amb activitat, i el professorat pot veure el repositori.

---

# Extras recomanats

## E1. Estils i JS sense `npm run dev` (important)

**Descripció.** `includes/layout.php` carrega sempre els recursos des de `http://localhost:5173`. Si Vite no està en marxa, la web es veu **sense CSS ni JavaScript**, i deixen de funcionar el carret, el tiquet i la validació. Qui corregeixi el projecte sense Node ho veurà trencat.

**Què cal fer.** Que PHP serveixi els fitxers compilats quan no hi ha Vite.

**Com fer-ho.**
1. Compilar el frontend amb `npm run build`. Genera la carpeta `dist/` amb un `manifest.json`; `vite.config.js` ja ho té activat.
2. A `layout.php`, detectar si el servidor de Vite respon. Si respon, mantenir les etiquetes actuals. Si no, llegir el manifest i escriure les etiquetes de CSS i JS amb els fitxers compilats.
3. Decidir si es versiona `dist/`. Ara està al `.gitignore`; si es vol lliurar sense Node, s'ha de treure d'allà i fer commit del build.
4. Repetir la compilació abans de cada lliurament.

**Resultat esperat.** Amb només Apache (sense Vite) la web es veu i funciona igual.

## E2. `README.md` complet

**Què ha de tenir:** descripció del projecte, integrants, requisits (XAMPP, PHP, Node), passos d'instal·lació i execució, estructura de carpetes, usuari i contrasenya de l'admin de prova, enllaç al Trello i com canviar `FRANJA_PROVES` per provar les franges horàries. Ara només diu "Estructura Proyecto Cantina??".

**Resultat esperat.** Algú que clona el repositori pot posar-lo en marxa seguint només el README.

## E3. Protegir `data/` d'accés directe

**Descripció.** La carpeta és dins del *document root* de XAMPP. Qualsevol persona pot obrir `http://localhost/Projecte_Cantina_AMN/data/comandes.txt` i **llegir noms, telèfons i correus**, saltant-se el login.

**Com fer-ho.** Crear un `.htaccess` dins de `data/` que denegui tot accés web (comprovant que Apache té `AllowOverride` activat). L'alternativa és moure `data/` fora de `htdocs` i canviar les rutes a `config.php`.

**Resultat esperat.** Obrir la URL del fitxer retorna un error 403, i l'aplicació continua llegint-lo des de PHP.

## E4. No versionar dades personals

`data/comandes.txt` és a Git. Afegir-lo al `.gitignore` i treure'l de l'índex amb `git rm --cached`, mantenint el fitxer en local (o fent que l'aplicació el creï si no existeix). Així no es pugen dades reals de clients.

## E5. Permisos d'escriptura

`comandes.txt` i `productes.json` han de ser escrivibles per l'usuari d'Apache. A macOS amb XAMPP, comprovar propietari, grup (`daemon`) i permisos. Provar una compra real: si no es pot escriure, l'aplicació mostra "Error en guardar el tiquet".

## E6. Condicions legals coherents

L'apartat 9 de `condicions.php` remet a una "Política de privacitat" que no existeix. Cal afegir-hi un apartat de privacitat o treure la frase. Revisar també que la data d'"Última actualització" sigui correcta.

## E7. Proves manuals abans de lliurar

- [ ] Compra completa a cada franja (canviant `FRANJA_PROVES` a esmorzar, dinar i berenar) i amb el valor buit (cantina tancada: es mostren tots els productes).
- [ ] Producte esgotat (la pizza): no es pot afegir.
- [ ] Intentar comprar més unitats de les disponibles, també forçant l'enviament: el servidor ho ha de limitar.
- [ ] Formulari amb dades invàlides: errors amb JavaScript i, desactivant-lo, errors del servidor.
- [ ] Recarregar la pàgina de finalització després de comprar: no es duplica la comanda.
- [ ] El carret es conserva en tornar enrere i es buida en finalitzar.
- [ ] Admin: login correcte i incorrecte, llistat, CRUD i accés directe sense sessió.
- [ ] Tema clar i fosc, i disseny responsive (escriptori, tauleta, mòbil) a totes les pàgines.
- [ ] Cap error ni avís de PHP, ni errors a la consola del navegador.

---

## Definició de "projecte acabat"

- [ ] Login d'admin funcionant i llistat de tots els tiquets (punts 1–2)
- [ ] Header amb logo, nom i menú (punt 3)
- [ ] Branques fusionades a `main` (punt 4)
- [ ] CRUD de productes (punt 5, per pujar nota)
- [ ] Tauler Trello/Jira i repositori GitHub compartits amb el professorat, amb commits de tots els membres (punts 6–7)
- [ ] La web funciona sense Vite en marxa (E1) i el README explica com executar-la (E2)
- [ ] `data/` protegit i sense dades personals al repositori (E3–E4)
