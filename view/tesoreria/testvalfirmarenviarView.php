<?php

// Autoload files using Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

$config = array(
    'url_pruebas' => 'https://celcer.sri.gob.ec',
    'url_produccion' => 'https://celcer.sri.gob.ec',
    'firmados' => '../documentos/docFirmados',
    'autorizados' => '../documentos/docAutorizados',
    'noautorizados' => 'docNoAutorizados',
    'generados' => '../documentos/docGenerados',
    'pdf' => '../documentos/docPdf',
    'logo' => '../documentos/logo.png1',
    'xsd' => '../documentos/docXsd',
    'pathFirma' => '../firma/firma.p12',
    'passFirma' => 'Sirenaice1.W'
);
echo __DIR__ . '/../vendor/autoload.php'; echo '<br>';
print_r($config); die();
$comprobante = new \Shara\ComprobantesController($config);

$clave = '2705201901171442249800110010010000000061234567812';

$xml = file_get_contents($config['generados'] . DIRECTORY_SEPARATOR . $clave.'.xml', FILE_USE_INCLUDE_PATH);

$aux = $comprobante->validarFirmarXml($xml, $clave);

if($aux['error'] === false){

    $aux = $comprobante->enviarXml($clave);

    if($aux['recibido'] === true){
        var_dump($aux);
        echo "<br>";
        echo "<br>";
        $aux = $comprobante->autorizacionXml($clave);
        var_dump($aux);
    }
    else
        var_dump($aux);
}
else
    var_dump($aux);
