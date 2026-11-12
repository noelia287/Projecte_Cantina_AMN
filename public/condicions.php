<?php
$titol = "Condicions legals";
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titol) ?></title>
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/src/js/main.js"></script>
    <link rel="icon" type="image/png" href="../frontend/public/logo.png">
</head>
<body>
<?php
include '../includes/header.php';
?>
<main>

<article class="condicions" style="max-width: 800px; margin: 0 auto; padding: 20px; line-height: 1.6;">
    <h1 style="color: #ec8a1b;">Termes i condicions d'ús</h1>
    <p><strong>Última actualització:</strong> 10 de novembre de 2026</p>

    <h2>1. Informació general</h2>
    <p>Aquest lloc web té com a objectiu oferir informació sobre els productes, els menús i els serveis de la cantina del centre educatiu.</p>
    <p>L'accés i la utilització d'aquest lloc web impliquen l'acceptació dels presents termes i condicions d'ús.</p>

    <h2>2. Ús del lloc web</h2>
    <p>Els usuaris es comprometen a utilitzar el lloc web de manera responsable, respectuosa i d'acord amb la legislació vigent.</p>
    <p>No està permès:</p>
    <ul>
        <li>Utilitzar la web amb finalitats il·legals o fraudulentes.</li>
        <li>Introduir informació falsa o de terceres persones sense autorització.</li>
        <li>Intentar accedir a zones restringides del sistema.</li>
        <li>Realitzar accions que puguin perjudicar el funcionament del lloc web.</li>
    </ul>

    <h2>3. Productes i serveis</h2>
    <p>La cantina ofereix diferents productes alimentaris i begudes destinats principalment als alumnes, professors i personal del centre educatiu.</p>
    <p>Els productes, preus i disponibilitat que apareixen al lloc web poden estar subjectes a canvis sense avís previ.</p>
    <p>La cantina es reserva el dret de modificar, substituir o retirar qualsevol producte del seu catàleg.</p>

    <h2>4. Comandes i disponibilitat</h2>
    <p>En cas que el lloc web permeti realitzar comandes en línia, l'usuari haurà de proporcionar la informació necessària per gestionar-les correctament.</p>
    <p>La confirmació d'una comanda estarà subjecta a la disponibilitat dels productes i a les condicions establertes per la cantina.</p>
    <p>La cantina es reserva el dret de cancel·lar una comanda en cas d'errors, manca d'estoc o circumstàncies justificades.</p>

    <h2>5. Preus i pagaments</h2>
    <p>Els preus dels productes es mostraran en euros (€) i inclouran els impostos aplicables, quan correspongui.</p>
    <p>Els mètodes de pagament disponibles seran els que s'indiquin expressament al lloc web o a les instal·lacions de la cantina.</p>
    <p>Qualsevol modificació dels preus serà comunicada mitjançant els canals disponibles.</p>

    <h2>6. Al·lèrgies i intoleràncies alimentàries</h2>
    <p>La cantina posa a disposició dels usuaris informació sobre els productes alimentaris i els possibles al·lèrgens presents.</p>
    <p>És responsabilitat de cada usuari consultar aquesta informació abans de consumir qualsevol producte.</p>
    <p>En cas d'al·lèrgies o intoleràncies alimentàries, es recomana contactar directament amb el personal de la cantina per obtenir informació addicional.</p>

    <h2>7. Responsabilitat</h2>
    <p>La cantina procurarà mantenir la informació del lloc web actualitzada i correcta.</p>
    <p>Tot i això, no es garanteix l'absència d'errors, interrupcions tècniques o incidències en el funcionament del lloc web.</p>
    <p>La cantina no serà responsable dels danys derivats d'un ús inadequat del lloc web per part dels usuaris.</p>

    <h2>8. Propietat intel·lectual</h2>
    <p>Tots els continguts del lloc web, incloent-hi textos, imatges, logotips, dissenys i altres elements gràfics, estan protegits per la normativa de propietat intel·lectual i industrial.</p>
    <p>No està permesa la reproducció, distribució o modificació d'aquests continguts sense l'autorització corresponent.</p>

    <h2>9. Protecció de dades personals</h2>
    <p>Les dades personals que es puguin recollir a través del lloc web seran tractades d'acord amb la normativa vigent en matèria de protecció de dades, especialment el Reglament General de Protecció de Dades (RGPD) i la Llei orgànica de protecció de dades personals i garantia dels drets digitals (LOPDGDD).</p>
    <p>Les dades només s'utilitzaran per a les finalitats informades a l'usuari.</p>
    <p>Per obtenir més informació sobre el tractament de dades personals, es pot consultar la Política de privacitat del lloc web.</p>

    <h2>10. Modificacions dels termes i condicions</h2>
    <p>La cantina es reserva el dret de modificar aquests termes i condicions en qualsevol moment per adaptar-los a possibles canvis en els serveis, el funcionament del lloc web o la normativa aplicable.</p>
    <p>Les modificacions entraran en vigor des del moment en què es publiquin al lloc web.</p>

    <h2>11. Legislació aplicable</h2>
    <p>Aquests termes i condicions es regeixen per la legislació espanyola i europea vigent.</p>
    <p>En cas de conflicte o discrepància, les parts se sotmetran als jutjats i tribunals competents d'acord amb la normativa aplicable.</p>

    <h2>12. Contacte</h2>
    <p>Per a qualsevol consulta, suggeriment o incidència relacionada amb el lloc web o els serveis de la cantina, els usuaris poden posar-se en contacte amb l'administració de la cantina a través dels canals de contacte disponibles al lloc web.</p>
</article>

</main>

<?php include '../includes/footer.php'; ?>
</body>
</html>
