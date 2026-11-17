// Carret de la pàgina de compra
// Quantitat 0: es veu el carret. Quantitat 1 o més: es veu - quantitat +
const MAXIM = 20

document.querySelectorAll('.producte__quantitat').forEach((caixa) => {
    const carret = caixa.querySelector('.producte__carret')
    const selector = caixa.querySelector('.producte__selector')
    const numero = caixa.querySelector('.producte__numero')
    const input = caixa.querySelector('input')

    // Guarda la quantitat i decideix què es mostra
    const posarQuantitat = (quantitat) => {
        input.value = quantitat
        numero.textContent = quantitat
        carret.hidden = quantitat > 0
        selector.hidden = quantitat === 0
    }

    carret.addEventListener('click', () => posarQuantitat(1))

    caixa.querySelector('.producte__mes').addEventListener('click', () => {
        posarQuantitat(Math.min(Number(input.value) + 1, MAXIM))
    })

    caixa.querySelector('.producte__menys').addEventListener('click', () => {
        posarQuantitat(Number(input.value) - 1)
    })
})
