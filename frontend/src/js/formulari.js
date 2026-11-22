// Validació del formulari de finalització de la compra


// FUNCIÓ: COMPROVAR EL NOM
// Retorna true si el text només té lletres (també amb accent), espais, apòstrofs o guions
function nomCorrecte(text) {
    const permesos = "abcdefghijklmnopqrstuvwxyzàáèéíïòóúüçñ·'’- ";
    const minuscules = text.toLowerCase();

    for (let i = 0; i < minuscules.length; i++) {
        if (!permesos.includes(minuscules[i])) {
            return false;
        }
    }

    return true;
}


// FUNCIÓ: COMPROVAR L'EMAIL
// Retorna true si l'email té el format nom@domini.com
function emailCorrecte(email) {
    const permesos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789._-@";

    // Només lletres sense accent, números, ".", "_", "-" i "@": es mira lletra per lletra
    for (let i = 0; i < email.length; i++) {
        if (!permesos.includes(email[i])) {
            return false;
        }
    }

    // No pot tenir dos punts seguits
    if (email.includes("..")) {
        return false;
    }

    // Talla l'email per la @: "nom@domini.com" es queda ["nom", "domini.com"]
    const parts = email.split("@");

    // Hi ha d'haver una sola @, és a dir, exactament 2 trossos
    if (parts.length !== 2) {
        return false;
    }

    // Desestructuració: guarda el primer tros de l'array a "usuari" i el segon a "domini"
    const [usuari, domini] = parts;

    // Hi ha d'haver alguna cosa abans de la @
    if (usuari === "") {
        return false;
    }

    // El domini ha de tenir un punt, però no al principi ni al final
    if (!domini.includes(".") || domini.startsWith(".") || domini.endsWith(".")) {
        return false;
    }

    return true;
}


// FUNCIÓ: COMPROVAR EL TELÈFON
// Retorna true si el telèfon té 9 xifres
function telefonCorrecte(telefon) {
    const senseEspais = telefon.replaceAll(" ", "");

    if (senseEspais.length !== 9) {
        return false;
    }

    // Tots els caràcters han de ser números: es mira lletra per lletra
    for (let i = 0; i < senseEspais.length; i++) {
        if (!"0123456789".includes(senseEspais[i])) {
            return false;
        }
    }

    return true;
}

const formulari = document.getElementById("formulari");

// Només a la pàgina de finalització, que és on hi ha el formulari
if (formulari) {
    const camps = ["nom", "cognom", "email", "tel"];
    const reseteja = document.getElementById("reseteja");


    // EVENT: RECUPERAR LES DADES GUARDADES
    // Recupera les dades guardades i esborra l'error quan s'escriu
    camps.forEach(id => {
        const input = document.getElementById(id);
        const guardat = localStorage.getItem(id);

        // Només si hi ha alguna cosa guardada. Si no, es queda el valor que ha posat el PHP
        if (guardat !== null) {
            input.value = guardat;
        }

        input.addEventListener("input", () => {
            localStorage.setItem(id, input.value);
            document.getElementById(`error-${id}`).textContent = "";
        });
    });


    // EVENT: VALIDAR EN ENVIAR
    // En enviar: s'esborren els errors d'abans i es comprova cada camp
    formulari.addEventListener("submit", (event) => {
        const nom = document.getElementById("nom").value.trim();
        const cognom = document.getElementById("cognom").value.trim();
        const email = document.getElementById("email").value.trim();
        const telefon = document.getElementById("tel").value.trim();

        camps.forEach(id => {
            document.getElementById(`error-${id}`).textContent = "";
        });

        let valid = true;

        if (nom.length < 2 || nom.length > 50 || !nomCorrecte(nom)) {
            document.getElementById("error-nom").textContent = "El nom ha de tenir entre 2 i 50 caràcters i només lletres.";
            valid = false;
        }

        // El cognom és opcional, però si s'escriu ha de ser vàlid
        if (cognom !== "" && (cognom.length < 2 || cognom.length > 60 || !nomCorrecte(cognom))) {
            document.getElementById("error-cognom").textContent = "El cognom ha de tenir entre 2 i 60 caràcters i només lletres.";
            valid = false;
        }

        if (email.length > 100 || !emailCorrecte(email)) {
            document.getElementById("error-email").textContent = "L'email no és vàlid (exemple: nom@domini.com).";
            valid = false;
        }

        if (!telefonCorrecte(telefon)) {
            document.getElementById("error-tel").textContent = "El telèfon no és vàlid (exemple: 612 345 678).";
            valid = false;
        }

        // Si hi ha errors, no s'envia el formulari
        if (!valid) {
            event.preventDefault();
            return;
        }

        // Tot és correcte: s'esborren les dades guardades i el PHP processa la compra
        camps.forEach(id => {
            localStorage.removeItem(id);
        });
    });


    // EVENT: BOTÓ RESETEJA
    // Buida els camps, les dades guardades i els errors
    reseteja.addEventListener("click", (event) => {
        event.preventDefault();

        camps.forEach(id => {
            document.getElementById(id).value = "";
            localStorage.removeItem(id);
            document.getElementById(`error-${id}`).textContent = "";
        });
    });
}
