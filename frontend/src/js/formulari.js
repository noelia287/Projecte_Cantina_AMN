// Validació del formulari de finalització de la compra
const formulari = document.querySelector("#formulari");

if (formulari) {
    const camps = ["nom", "cognom", "email", "tel"];

    // Recupera les dades guardades i esborra l'error quan s'escriu
    camps.forEach(id => {
        const input = document.getElementById(id);
        input.value = localStorage.getItem(id) || "";
        input.addEventListener("input", () => {
            localStorage.setItem(id, input.value);
            document.getElementById("error-" + id).textContent = "";
        });
    });

    const reseteja = document.querySelector("#reseteja");

    const regexNom = /^\p{L}+(?:[ '’·-]\p{L}+)*$/u;
    const regexEmail = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;
    const regexTel = /^(?:(?:\+|00)34[ ]?)?[6-9](?:[ ]?\d){8}$/;

    formulari.addEventListener("submit", (event) => {
        const nom = document.getElementById("nom").value.trim();
        const cognom = document.getElementById("cognom").value.trim();
        const email = document.getElementById("email").value.trim();
        const telefon = document.getElementById("tel").value.trim();

        camps.forEach(id => {
            document.getElementById("error-" + id).textContent = "";
        });

        let valid = true;

        if (nom.length < 2 || nom.length > 50 || !regexNom.test(nom)) {
            document.getElementById("error-nom").textContent = "El nom ha de tenir entre 2 i 50 caràcters i només lletres.";
            valid = false;
        }

        // El cognom és opcional, però si s'escriu ha de ser vàlid
        if (cognom !== "" && (cognom.length < 2 || cognom.length > 60 || !regexNom.test(cognom))) {
            document.getElementById("error-cognom").textContent = "El cognom ha de tenir entre 2 i 60 caràcters i només lletres.";
            valid = false;
        }

        if (!regexEmail.test(email) || email.length > 100) {
            document.getElementById("error-email").textContent = "L'email no és vàlid (exemple: nom@domini.com).";
            valid = false;
        }

        if (!regexTel.test(telefon)) {
            document.getElementById("error-tel").textContent = "El telèfon no és vàlid (exemple: 612 345 678).";
            valid = false;
        }

        // Si hi ha errors no s'envia; si tot és correcte, el PHP processa la compra
        if (!valid) {
            event.preventDefault();
            return;
        }

        camps.forEach(id => localStorage.removeItem(id));
    });

    if (reseteja) {
        reseteja.addEventListener("click", () => {
            camps.forEach(id => {
                localStorage.removeItem(id);
                document.getElementById("error-" + id).textContent = "";
            });
        });
    }
}
