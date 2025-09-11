<?php

error_reporting(1);
$directorioRaiz = './';

function buscarArchivosErrorLogRecursivamente($directorio)
{
    $archivosErrorLog = glob($directorio . 'error_log');

    $carpetas = glob($directorio . '*', GLOB_ONLYDIR);

    $archivosEncontrados = [];

    if ($archivosErrorLog) {
        $archivosEncontrados = array_merge($archivosEncontrados, $archivosErrorLog);
    }

    foreach ($carpetas as $carpeta) {
        $archivosEncontrados = array_merge($archivosEncontrados, buscarArchivosErrorLogRecursivamente($carpeta . '/'));
    }

    return $archivosEncontrados;
}

function total_mb($total)
{
    if ($total < 1024 * 1024)
        return number_format($total / 1024, 2) . " kb";
    else
        return number_format($total / 1024 / 1024, 2) . " mb";
}


$archivosEncontrados = buscarArchivosErrorLogRecursivamente($directorioRaiz);


if (!empty($archivosEncontrados)) {
    echo "Archivos 'error_log' encontrados:<br>";
    foreach ($archivosEncontrados as $archivo) {
        $total = filesize($archivo);
        echo $archivo . " => " . total_mb($total) . "<br>";

        unlink($archivo);
    }
} else {
    echo "No se encontraron archivos 'error_log' en el directorio raíz y sus subdirectorios.";
}
