# Projecte Cantina — Requisits

## Objectiu

Practicar i entendre el funcionament bàsic d'una aplicació web i la navegació entre pàgines.

## Enunciat

Dissenyar i desenvolupar, utilitzant **codificació plana de PHP i JavaScript**, una aplicació web que permeti a un usuari **comprar productes típics d'una cantina d'un centre educatiu**, mostrant-li **el tiquet de la comanda** realitzada i **emmagatzemant les comandes en un fitxer**.

---

## Funcionament de l'aplicació (requisits obligatoris)

### Pàgina inicial
- [ ] Conté el **header** i el **footer** comuns a totes les pàgines.
- [ ] Té una **imatge de fons** relacionada.

### Header (comú a totes les pàgines)
- [ ] Logo de l'institut/cantina.
- [ ] Nom del lloc web.
- [ ] Menú de navegació.

### Footer (comú a totes les pàgines)
- [ ] Informació sobre el grup de treball.
- [ ] Adreça de la cantina.
- [ ] Enllaç a la pàgina de condicions legals.

### Menú de navegació
- [ ] Enllaç a la pàgina de compra.
- [ ] Enllaç a la pàgina d'administració.

### Pàgina de compra
- [ ] Mostra els productes de la cantina disponibles per comprar.
- [ ] L'usuari pot **seleccionar** productes i **afegir-los** al tiquet de compra.
- [ ] L'usuari pot **treure** productes del tiquet si canvia d'opinió.
- [ ] L'usuari pot **finalitzar la compra** → es mostra la pàgina de finalització.

### Pàgina de finalització
- [ ] Mostra el **tiquet de compra**.
- [ ] Mostra un **formulari** per recollir les dades de l'usuari.
- [ ] En enviar el formulari, es mostra un **missatge d'èxit**.
- [ ] El tiquet s'**emmagatzema en un fitxer al servidor**.

### Formulari
- [ ] Demana **nom**, **telèfon** i **correu**.
- [ ] Aquesta informació s'emmagatzema **juntament amb les dades de la comanda** en el fitxer.

### Pàgina d'administració
- [ ] Mostra **tots els tiquets** de les comandes.
- [ ] S'hi accedeix amb **algun mecanisme de seguretat** (p. ex. login amb usuari i contrasenya).

### Pàgina de condicions legals
- [ ] Mostra tota la informació legal de l'aplicació.

---

## Millores (opcionals / per pujar nota)

### Pàgina inicial
- [ ] Mostrar les **ofertes del dia**.

### Pàgina de compra
- [ ] En afegir/treure productes de la comanda, es **mostra el tiquet actualitzat** (sense recarregar, amb JavaScript).
- [ ] Mostrar **productes diferents segons l'hora del dia**: esmorzar, dinar i berenar.

### Pàgina d'administració
- [ ] Permetre **gestionar els productes**: afegir, eliminar i modificar.

---

## Organització i seguiment

- [ ] Utilitzar **Trello o Jira** per a l'organització i el seguiment del desenvolupament. **Compartir el panell amb el professorat implicat.**
- [ ] Utilitzar **GitHub** per al control de versions del codi.
  - Cada membre del grup ha d'usar **el seu propi compte** perquè es pugui valorar la seva feina.
  - **Compartir el repositori amb el professorat implicat.**

---

## Resum de pàgines a crear

| # | Pàgina | Obligatòria | Contingut clau |
|---|--------|-------------|----------------|
| 1 | Inicial | Sí | Header, footer, imatge de fons (millora: ofertes del dia) |
| 2 | Compra | Sí | Llistat de productes, afegir/treure del tiquet (millores: tiquet dinàmic, productes per franja horària) |
| 3 | Finalització | Sí | Tiquet + formulari (nom, telèfon, correu) + missatge d'èxit + desar en fitxer |
| 4 | Administració | Sí | Llistat de tiquets, accés protegit (millora: CRUD de productes) |
| 5 | Condicions legals | Sí | Informació legal |

## Lliurables a controlar

- [ ] Aplicació funcionant (PHP + JavaScript plans, sense frameworks)
- [ ] Fitxer al servidor amb les comandes i les dades dels usuaris
- [ ] Panell de Trello/Jira compartit amb el professorat
- [ ] Repositori de GitHub compartit, amb commits de tots els membres des del seu compte
