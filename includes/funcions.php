<?php
    function llegir_productes(){
            $fitxer = fopen("../data/productes.json", "r");
            $contingut = fread($fitxer, filesize("../data/productes.json"));
            return json_decode($contingut, true);
            fclose($fitxer);
    }

    
?>