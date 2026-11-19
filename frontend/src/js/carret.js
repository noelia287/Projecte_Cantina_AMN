const caixes = document.querySelectorAll(".producte__quantitat");

// Per a cada caixa de cada producte
caixes.forEach(caixa => {
    const carret = caixa.querySelector(".producte__carret");
    const selector = caixa.querySelector(".producte__selector");
    const menys = caixa.querySelector(".producte__menys");
    const mes = caixa.querySelector(".producte__mes");
    const numero = caixa.querySelector(".producte__numero");
    const input = caixa.querySelector("input");
    const maxim = Number(caixa.dataset.unitats); // Saber les unitats definides en el JSON

    let quantitat = 0;

    // Mostrar la quantitat depenent el cas 
    function actualitzar() {

        // Mostrar la quantitat a l'usuari i en el input
        numero.textContent = quantitat;
        input.value = quantitat;

        // Si quantitat es 0 mostra el carret, sinó mostra el selector
        if (quantitat === 0) {
            carret.hidden = false;
            selector.hidden = true;
        } else {
            carret.hidden = true;
            selector.hidden = false;
        }

        // Si la quantitat és igual al máxim d'unitats, es deshabilita el "+"
        if (quantitat === maxim) {
            mes.disabled = true;
        } else {
            mes.disabled = false;
        }
    }

    // Si es clica al carret, la quantitat és igual a 1 i es crida la funció
    carret.addEventListener("click", () => {
        quantitat = 1;
        actualitzar()
    });

    // Si es clica el "+" i la quantitat és més petita que el seu màxim
    // suma la quantitat i crida la funció per actualitzar el contingut 
    mes.addEventListener("click", () => {
        if (quantitat < maxim) {
            quantitat++;
            actualitzar();
        }
    });

    // Si es clica el "-", resta a la quantitat i actualitza
    menys.addEventListener("click", () => {
        quantitat--;
        actualitzar();
    });
});
