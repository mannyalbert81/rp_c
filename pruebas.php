<?php
/*$clave_fecha_hoy = date("Y-m-d");
echo $clave_fecha_hoy;
$clave_fecha_siguiente_mes = date("Y-m-d",strtotime($clave_fecha_hoy."+ 1 month"));
echo $clave_fecha_siguiente_mes;*/

/*for($i=361;$i<=10000;$i++){
    echo'-';echo $i;echo',';
}*/
?>

<?php
header('Content-Type: text/plain; charset=utf-8');
$datos = array (
 0 => array
    ( 'ANO' => 2016,
    'NOMBRE' => 'ANDONI ALDA',
    'USCOD' => '000001',
    'ENCARGADO' => 'ANDONI ALDA',
    'USRTELEFONO' => 685358487,
    'DIASEMANA' => 1,
    'PARADAS' =>
    array ( 0 => array
        ('HORA' => 855,
        'PARADA' => 1,
        'CALLE' => 'C/MAYOR',
        ),
        1 => array
        ( 'HORA' => 1040,
        'PARADA' => 1,
        'CALLE' => 'C/MENOR',
        ),
    ),
),
 1 => array
    ( 'ANO' => 2016,
    'NOMBRE' => 'ANDONI ALDA',
    'USCOD' => '000001',
    'ENCARGADO' => 'ANDONI ALDA',
    'USRTELEFONO' => 685358487,
    'DIASEMANA' => 2,
    'PARADAS' =>
    array ( 0 => array
        ('HORA' => 855,
        'PARADA' => 1,
        'CALLE' => 'C/MAYOR',
        ),
        1 => array
        ( 'HORA' => 1040,
        'PARADA' => 1,
        'CALLE' => 'C/MENOR',
        ),
    ),
),

2 => array
    ( 'ANO' => 2016,
    'NOMBRE' => 'ANDONI ALDA',
    'USCOD' => '000001',
    'ENCARGADO' => 'ANDONI ALDA',
    'USRTELEFONO' => 685358487,
    'DIASEMANA' => 3,
    'PARADAS' =>
    array ( 0 => array
        ('HORA' => 855,
        'PARADA' => 1,
        'CALLE' => 'C/MAYOR',
        ),
        1 => array
        ( 'HORA' => 1040,
        'PARADA' => 1,
        'CALLE' => 'C/MENOR',
        ),
    ),
),
3 => array
    ( 'ANO' => 2016,
    'NOMBRE' => 'ANDONI ALDA',
    'USCOD' => '000001',
    'ENCARGADO' => 'ANDONI ALDA',
    'USRTELEFONO' => 685358487,
    'DIASEMANA' => 4,
    'PARADAS' =>
    array ( 0 => array
        ('HORA' => 855,
        'PARADA' => 1,
        'CALLE' => 'C/MAYOR',
        ),
        1 => array
        ( 'HORA' => 1040,
        'PARADA' => 1,
        'CALLE' => 'C/MENOR',
        ),
    ),
),
4 => array
    ( 'ANO' => 2016,
    'NOMBRE' => 'ANDONI ALDA',
    'USCOD' => '000001',
    'ENCARGADO' => 'ANDONI ALDA',
    'USRTELEFONO' => 685358487,
    'DIASEMANA' => 5,
    'PARADAS' =>
    array ( 0 => array
        ('HORA' => 855,
        'PARADA' => 1,
        'CALLE' => 'CALLE C/PUERTOURRACO',
        ),
        1 => array
        ( 'HORA' => 1040,
        'PARADA' => 1,
        'CALLE' => 'Avenida de la playa',
        ),
    ),
));
$resultado = [];
foreach($datos as $dato) {
    if (!isset($resultado[$dato['USCOD']])) {
        $resultado[$dato['USCOD']] = $dato;
        unset(
          $resultado[$dato['USCOD']]['DIASEMANA'],
          $resultado[$dato['USCOD']]['PARADAS']
        );
        $resultado[$dato['USCOD']]['RUTAS'] = [];
    }
    $clave = [];
    foreach($dato['PARADAS'] as $parada) {
        array_push($clave, $parada['HORA'], $parada['CALLE']);
    }
    $clave = implode(':', $clave);
    if (!isset($resultado[$dato['USCOD']]['RUTAS'][$clave])) {
        $resultado[$dato['USCOD']]['RUTAS'][$clave] = [
            'DIAS' => [],
            'PARADAS' => $dato['PARADAS'],
        ];
    }
    array_push($resultado[$dato['USCOD']]['RUTAS'][$clave]['DIAS'], $dato['DIASEMANA']);
}

foreach($resultado as $clave => $valor) {
  $resultado[$clave]['RUTAS'] = array_values($valor['RUTAS']);
}
$resultado = array_values($resultado);
echo json_encode($resultado, JSON_PRETTY_PRINT);