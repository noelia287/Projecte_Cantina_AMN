// Elements de la pàgina de compra: llista de productes, selectors de quantitat i total del tiquet
const llista = document.querySelector(".productes");
const caixes = document.querySelectorAll(".producte__quantitat");
const total = document.querySelector(".tiquet-viu__total");

// VARIABLES: CARRET GUARDAT AL NAVEGADOR
// Quantitats guardades al navegador. Si no n'hi ha, comença buit
let carretGuardat = JSON.parse(localStorage.getItem("carret")) || {};


// FUNCIÓ: FORMATAR EL PREU
// Formata el preu amb "," i dos decimals i el signe de l'euro
function formatPreu(preu) {
    return `${preu.toFixed(2).replace(".", ",")} €`;
}


// FUNCIÓ: CALCULAR EL TOTAL DEL TIQUET
// Suma el preu per la quantitat de cada producte i ho mostra al total del tiquet
function actualitzarTotal() {
    let suma = 0;

    caixes.forEach(caixa => {
        const preu = Number(caixa.dataset.preu);
        const quantitat = Number(caixa.querySelector("input").value);
        suma = suma + preu * quantitat;
    });

    total.textContent = formatPreu(suma);
}


// FUNCIÓ: ACTUALITZAR LA QUANTITAT D'UN PRODUCTE
// Mostra la nova quantitat d'un producte: carret o selector, botó "+", línia del tiquet i total
function actualitzar(caixa, quantitat) {
    const carret = caixa.querySelector(".producte__carret");
    const selector = caixa.querySelector(".producte__selector");
    const mes = caixa.querySelector(".producte__mes");
    const numero = caixa.querySelector(".producte__numero");
    const input = caixa.querySelector("input");
    const maxim = Number(caixa.dataset.unitats); // Unitats definides en el JSON
    const preu = Number(caixa.dataset.preu);
    const linia = document.getElementById(`linia-${caixa.dataset.id}`); // Línia del tiquet d'aquest producte

    // Mostra la quantitat a l'usuari i a l'input
    numero.textContent = quantitat;
    input.value = quantitat;

    // Si la quantitat és 0 mostra el carret, sinó mostra el selector
    if (quantitat === 0) {
        carret.hidden = false;
        selector.hidden = true;
    } else {
        carret.hidden = true;
        selector.hidden = false;
    }

    // Si la quantitat és igual al màxim d'unitats, es deshabilita el "+"
    if (quantitat === maxim) {
        mes.disabled = true;
    } else {
        mes.disabled = false;
    }

    // Actualitza la línia d'aquest producte al tiquet
    linia.querySelector(".linia__quantitat").textContent = quantitat;
    linia.querySelector(".linia__subtotal").textContent = formatPreu(preu * quantitat);

    // Si la quantitat és 0 s'amaga la línia, sinó es mostra
    if (quantitat === 0) {
        linia.hidden = true;
    } else {
        linia.hidden = false;
    }

    actualitzarTotal();

    // Guarda la quantitat al navegador perquè no es perdi en recarregar
    carretGuardat[caixa.dataset.id] = quantitat;
    localStorage.setItem("carret", JSON.stringify(carretGuardat));
}

// Només a la pàgina de compra, que és on hi ha la llista de productes
if (llista) {


    // EVENT: BOTONS DEL CARRET, + I -
    // Un sol listener per a tots els botons de tots els productes
    llista.addEventListener("click", event => {
        const boto = event.target.closest("button"); // Botó clicat (o null si s'ha clicat fora d'un botó)

        if (boto === null) {
            return;
        }

        const caixa = boto.closest(".producte__quantitat"); // Producte al qual pertany el botó
        const maxim = Number(caixa.dataset.unitats);
        let quantitat = Number(caixa.querySelector("input").value);

        // Carret: posa 1 · "+": suma si no arriba al màxim · "-": resta
        if (boto.classList.contains("producte__carret")) {
            quantitat = 1;
        } else if (boto.classList.contains("producte__mes") && quantitat < maxim) {
            quantitat++;
        } else if (boto.classList.contains("producte__menys")) {
            quantitat--;
        }

        actualitzar(caixa, quantitat);
    });


    // LÒGICA: RECUPERAR EL CARRET EN CARREGAR LA PÀGINA
    // En carregar la pàgina, torna a posar les quantitats que hi havia guardades
    caixes.forEach(caixa => {
        const guardada = carretGuardat[caixa.dataset.id] || 0;

        // Només si n'hi havia alguna i encara queden prou unitats
        if (guardada > 0 && guardada <= Number(caixa.dataset.unitats)) {
            actualitzar(caixa, guardada);
        }
    });
}


// LÒGICA: ESBORRAR EL CARRET DESPRÉS DE COMPRAR
// Si s'acaba de fer la compra (es veu el tiquet final), s'esborra el carret guardat
if (document.querySelector(".zona-tiquet")) {
    localStorage.removeItem("carret");
}
