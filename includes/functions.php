<?php
function getDolarEnPesos() {
    // Obteniendo el texto de la url dada
    $html = file_get_html("http://www.preciodolar.com/convert.php?from=USD&to=COP&val=1");
    
    /**
     * El texto es algo como => 1883.8 COP solo necesitamos el numero decimal
     * por lo tanto se corta y se convierte en un numero decimal valido
     */
    return floatval(number_format(substr($html->plaintext, 4, 7), 2, ".", ""));
}

function getBolivarEnPesos() {
    // Obteniendo el texto de la url dada y buscando el td que contiene
    // el monto como si de un css se tratara
    $html = file_get_html("http://www.laopinion.com.co/demo/");
    foreach($html->find('table.indicadores tbody tr.fondo td') as $item) {
        /**
         * Como es el 1ero el que importa, se rompe en bucle en la 1era iteracion
         * y se elimina el signo de pesos $, para luego convertirlo en un decimal valido
         */ 
        return floatval(number_format(substr($item->plaintext, 1), 2, ".", ""));
    }
}

function getUltimoRegistroDolar($link) {
    // Para generar un registro de los cambios, se busca el ultimo registrado y se compara, si hay cambios, se registra
    $sql = "SELECT * FROM `dl_dolar` order by id desc limit 1";
    $response = mysql_query($sql, $link);
    $row = mysql_fetch_array($response);
    return floatval($row['usd']);
}

function registrarCambioDolar($link, $dolar, $dolar_en_pesos, $vef_en_pesos, $fecha) {
    $ultimo_registro = getUltimoRegistroDolar($link);
    // Si hay algun cambio se inserta un nuevo registro
    if ( round(number_format($ultimo_registro, 12, ".", ""), 11) != round(number_format($dolar, 12, ".", ""), 11)) {
        $sql = "INSERT INTO dl_dolar (usd, cop, vef, created) VALUES ($dolar, $dolar_en_pesos, $vef_en_pesos, '$fecha')";
        mysql_query($sql);
    }
}