<?php

// Rutes dels fitxers de dades
// __DIR__ és la carpeta d'aquest fitxer (config/), així la ruta funciona des de qualsevol pàgina
define('RUTA_PRODUCTES', __DIR__ . '/../data/productes.json');
define('RUTA_COMANDES', __DIR__ . '/../data/comandes.txt');

// Franges horàries: hora en què comença cada franja i hora de tancament
define('HORA_ESMORZAR', 8);
define('HORA_DINAR', 12);
define('HORA_BERENAR', 15);
define('HORA_TANCAMENT', 18);

// Per fer proves: 'esmorzar', 'dinar' o 'berenar' per veure aquella franja. Buit ('') fa servir l'hora real
define('FRANJA_PROVES', '');

// Accés a l'administració (usuari i contrasenya)
define('ADMIN_USUARI', 'admin');
// Contrasenya guardada amb md5. Per canviar-la: php -r "echo md5('nova');"
define('ADMIN_CLAU_HASH', 'ee38d22151f4e120bcc50d0cfa81c4f0');
?>