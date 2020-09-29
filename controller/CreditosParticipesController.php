<?php
class CreditosParticipesController extends ControladorBase
{    
    public function index()
    {
        session_start();

        if (! isset($_SESSION['id_usuarios'])) {
            $this->redirect("Usuarios", "sesion_caducada");
        }

        $id_solicitud = isset($_POST['id_solicitud']) ? $_POST['id_solicitud'] : 0;
        $cedula_participes = isset($_POST['cedula_participes']) ? $_POST['cedula_participes'] : "";

        $this->view_Credito("CreditosParticipes", array(
            "id_solicitud" => $id_solicitud,
            "cedula_participes" => $cedula_participes
        ));
    }
    
    public function dateDifference($date_1, $date_2, $differenceFormat = '%y Años, %m Meses, %d Dias')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        
        $interval = date_diff($datetime1, $datetime2);
        
        return $interval->format($differenceFormat);
    }
    
    public function dateDifference1($date_1, $date_2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        
        $interval = date_diff($datetime1, $datetime2);
        
        return $interval->format($differenceFormat);
    }
    

    public function InfoSolicitud()
    {
        session_start();
        $response = array();

        try {
            ob_start();
            $id_solicitud = $_POST['id_solicitud'];
           
            $resultSet  = $this->obtenerDatosSolicitud($id_solicitud);
            
            $data = array();
            
            $data['nombre_tipo_credito_solicitud'] = $resultSet[0]->nombre_tipo_creditos;

            $html = '<div id="info_participe_solicitud " class="row bg-teal">
                    <div class="contenedor-titulo-solicitud">
                        <h3 class="titulo col-lg-11 col-md-11">Información de Solicitud</h3>
                        <button id="btn_cambiar_datos_cuentas_solicitud" class="col-lg-1 col-md-1 no-padding btn btn-default"><i class="fa fa-edit fa-2x" aria-hidden="true"></i></button>
                    </div>                    
                    <div class="col-lg-6 col-md-12">
                    <div class="box-footer no-padding bg-teal">
                        <div class="bio-row"><p><span class="tab">Tipo Crédito </span>: ' . $data['nombre_tipo_credito_solicitud'] . '</p></div>
                        <div class="bio-row"><p><span class="tab">Nombre Banco </span>: ' . $resultSet[0]->nombre_banco_cuenta_bancaria . '</p></div>
                        <div class="bio-row"><p><span class="tab">Número Cuenta </span>: ' . $resultSet[0]->numero_cuenta_cuenta_bancaria . '</p></div>
                    </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                    <div class="box-footer no-padding bg-teal">
                        <div class="bio-row"><p><span class="tab">Destino Dinero</span>: ' . $resultSet[0]->destino_dinero_datos_prestamo . '</p></div>
                        <div class="bio-row"><p><span class="tab">Tipo Cuenta </span>: ' . $resultSet[0]->tipo_cuenta_cuenta_bancaria . '</p></div>
                        <div class="bio-row"><p><span class="tab">Tipo de Pago </span>: ' . $resultSet[0]->tipo_pago_cuenta_bancaria . '</p></div>
                    </div>
                    </div>
                 </div>';
                      
            $salida = ob_get_clean();
            
            if( !empty(trim($salida)) )
            { 
                throw new Exception("");
            }
            
            $response['html'] = $html;
            $response['data'] = $data;

        } catch (Exception $e) {
            
            $html = '<h3 class="titulo">DATOS NO ENCONTRADOS</h3>';
            $response['html'] = $html;
            $response['mensaje'] = "Causa problable solicitud no encoontrada";
            $response['error'] = error_get_last();
            $response['buffer'] = $salida;
        }
       
        echo json_encode($response);
    }

    public function BuscarParticipe()
    {
        session_start();

        $participes = new ParticipesModel();

        $html = "";
        $icon = "";
        $response = array();

        ob_start();

        // METODO PARA CARGAR INFORMACION BASICA DEL PARTICIPE

        $cedula = (isset($_POST['cedula'])) ? $_POST['cedula'] : '';
        $id_participes = 0;

        $columnas = " core_estado_participes.nombre_estado_participes, core_participes.nombre_participes,
                    core_participes.apellido_participes, core_participes.ocupacion_participes,
                    core_participes.cedula_participes, core_entidad_patronal.nombre_entidad_patronal,
                    core_participes.telefono_participes, core_participes.direccion_participes,
                    core_estado_civil_participes.nombre_estado_civil_participes, core_genero_participes.nombre_genero_participes,
                    core_participes.id_participes";
        $tablas = " public.core_participes
                    INNER JOIN public.core_estado_participes ON core_participes.id_estado_participes = core_estado_participes.id_estado_participes
                    INNER JOIN core_entidad_patronal ON core_participes.id_entidad_patronal = core_entidad_patronal.id_entidad_patronal
                    INNER JOIN core_estado_civil_participes ON core_participes.id_estado_civil_participes=core_estado_civil_participes.id_estado_civil_participes
                    INNER JOIN core_genero_participes ON core_genero_participes.id_genero_participes = core_participes.id_genero_participes";

        $where = " core_participes.cedula_participes='$cedula'";

        $id = "core_participes.id_participes";

        $resultSet = $participes->getCondiciones($columnas, $tablas, $where, $id);

        $id_participes = $resultSet[0]->id_participes;

        $icon = ($resultSet[0]->nombre_genero_participes == "HOMBRE") ? '<i class="fa fa-male fa-3x" style="float: left;"></i>' : '<i class="fa fa-female fa-3x" style="float: left;"></i>';

        $html .= '<div class="box box-widget widget-user-2">';
        $html .= '<button class="btn btn-default pull-right" title="Simulación crédito"  onclick="SimulacionCreditoSinSolicitud()"><i class="fa fa-bank"></i></button>';
        $html .= '<div class="widget-user-header bg-olive">' . $icon;
        $html .= '<h3 class="widget-user-username">' . $resultSet[0]->nombre_participes . ' ' . $resultSet[0]->apellido_participes . '</h3>';
        $html .= '<h5 class="widget-user-desc">Estado: ' . $resultSet[0]->nombre_estado_participes . '</h5>';
        $html .= '<h5 class="widget-user-desc">CI: ' . $resultSet[0]->cedula_participes . '</h5>';
        $html .= '</div>';
        $html .= '<div class="box-footer no-padding">';
        $html .= '<ul class="nav nav-stacked">';
        $html .= '<table align="right" class="tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example">';
        $html .= '<tr>';
        $html .= '<th>Cargo:</th>';
        $html .= '<td>' . $resultSet[0]->ocupacion_participes . '</td>';
        $html .= '<th>Entidad Patronal:</th>';
        $html .= '<td>' . $resultSet[0]->nombre_entidad_patronal . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th>Teléfono:</th>';
        $html .= '<td>' . $resultSet[0]->telefono_participes . '</td>';
        $html .= '<th>Estado Civil:</th>';
        $html .= '<td>' . $resultSet[0]->nombre_estado_civil_participes . '</td>';
        $html .= '</tr>';
        $html .= '<tr >';
        $html .= '<th>Dirección:</th>';
        $html .= '<td colspan="3">' . $resultSet[0]->direccion_participes . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';

        $salida = ob_get_clean();

        if (! empty($salida)) {

            $html = '<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html .= '<h4>Aviso!!!</h4> <b>No se ha encontrado participes con número de cédula ' . $cedula . '</b>';
            $html .= '</div>';

            $response['html'] = $html;
            $response['id_participes'] = 0;
            $response['estatus'] = "ERROR";
        } else {
            $response['html'] = $html;
            $response['id_participes'] = $id_participes;
            $response['estatus'] = "OK";
        }

        echo json_encode($response);
    }

    public function BuscarParticipe001()
    {
        session_start();

        $html = "";
        $participes = new ParticipesModel();
        $icon = "";
        $respuesta = array();

        // METODO PARA CARGAR INFORMACION BASICA DEL PARTICIPE

        $cedula = (isset($_POST['cedula'])) ? $_POST['cedula'] : '';

        if (! empty($cedula)) {

            $columnas = "core_estado_participes.nombre_estado_participes, core_participes.nombre_participes,
                    core_participes.apellido_participes, core_participes.ocupacion_participes,
                    core_participes.cedula_participes, core_entidad_patronal.nombre_entidad_patronal,
                    core_participes.telefono_participes, core_participes.direccion_participes,
                    core_estado_civil_participes.nombre_estado_civil_participes, core_genero_participes.nombre_genero_participes,
                    core_participes.id_participes";
            $tablas = "public.core_participes INNER JOIN public.core_estado_participes
                    ON core_participes.id_estado_participes = core_estado_participes.id_estado_participes
                    INNER JOIN core_entidad_patronal
                    ON core_participes.id_entidad_patronal = core_entidad_patronal.id_entidad_patronal
                    INNER JOIN core_estado_civil_participes
                    ON core_participes.id_estado_civil_participes=core_estado_civil_participes.id_estado_civil_participes
                    INNER JOIN core_genero_participes
                    ON core_genero_participes.id_genero_participes = core_participes.id_genero_participes";

            $where = "core_participes.cedula_participes='$cedula'";

            $id = "core_participes.id_participes";

            $resultSet = $participes->getCondiciones($columnas, $tablas, $where, $id);

            if (! (empty($resultSet))) {
                if ($resultSet[0]->nombre_genero_participes == "HOMBRE")
                    $icon = '<i class="fa fa-male fa-3x" style="float: left;"></i>';
                else
                    $icon = '<i class="fa fa-female fa-3x" style="float: left;"></i>';

                $html .= '
        <div class="box box-widget widget-user-2">';
                $html .= '<button class="btn btn-default pull-right" title="Simulación crédito"  onclick="SimulacionCreditoSinSolicitud()"><i class="fa fa-bank"></i></button>';
                $html .= '<div class="widget-user-header bg-olive">' . $icon . '<h3 class="widget-user-username">' . $resultSet[0]->nombre_participes . ' ' . $resultSet[0]->apellido_participes . '</h3>
        
         <h5 class="widget-user-desc">Estado: ' . $resultSet[0]->nombre_estado_participes . '</h5>
        <h5 class="widget-user-desc">CI: ' . $resultSet[0]->cedula_participes . '</h5>
        
        </div>
        <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
        <table align="right" class="tablesorter table table-striped table-bordered dt-responsive nowrap dataTables-example">
        <tr>
        <th>Cargo:</th>
        <td>' . $resultSet[0]->ocupacion_participes . '</td>
        <th>Entidad Patronal:</th>
        <td>' . $resultSet[0]->nombre_entidad_patronal . '</td>
        </tr>
        <tr>
        <th>Teléfono:</th>
        <td>' . $resultSet[0]->telefono_participes . '</td>
        <th>Estado Civil:</th>
        <td>' . $resultSet[0]->nombre_estado_civil_participes . '</td>
        </tr>
        <tr >
        <th>Dirección:</th>
        <td colspan="3">' . $resultSet[0]->direccion_participes . '</td>
        </tr>
        </table>
        </ul>
        </div>
        </div>';

                array_push($respuesta, $html);
                array_push($respuesta, $resultSet[0]->id_participes);
            } else {
                $html .= '<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
                $html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
                $html .= '<h4>Aviso!!!</h4> <b>No se ha encontrado participes con número de cédula ' . $cedula . '</b>';
                $html .= '</div>';

                array_push($respuesta, $html);
                array_push($respuesta, 0);
            }

            echo json_encode($respuesta);
        }
    }

    public function AportesParticipe()
    {
        session_start();
        $id_participe = $_POST['id_participe'];
        $html = "";
        $participes = new ParticipesModel();
        $total = 0;
        $response = array();
        ob_start();

        $columnas = "fecha_registro_contribucion, nombre_contribucion_tipo, valor_personal_contribucion";
        $tablas = "core_contribucion INNER JOIN core_contribucion_tipo
                ON core_contribucion.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo";
        $where = "core_contribucion.id_participes=" . $id_participe . " AND core_contribucion.id_contribucion_tipo=1
                AND core_contribucion.id_estatus=1";
        $id = "fecha_registro_contribucion";

        $resultAportesPersonales = $participes->getCondiciones($columnas, $tablas, $where, $id);

        $columnas = "fecha_registro_contribucion, nombre_contribucion_tipo, valor_personal_contribucion, valor_patronal_contribucion";
        $tablas = "core_contribucion INNER JOIN core_contribucion_tipo
                ON core_contribucion.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo";
        $where = "core_contribucion.id_participes=" . $id_participe . " AND core_contribucion.id_estatus=1";
        $id = "fecha_registro_contribucion";

        $resultAportes = $participes->getCondiciones($columnas, $tablas, $where, $id);
        if (! (empty($resultAportes))) {
            foreach ($resultAportes as $res) {
                if ($res->valor_personal_contribucion != 0) {
                    $total += $res->valor_personal_contribucion;
                } else {
                    $total += $res->valor_patronal_contribucion;
                }
            }

            $personales = sizeof($resultAportesPersonales);
            $last = sizeof($resultAportes);
            $fecha_primer = $resultAportes[0]->fecha_registro_contribucion;
            $fecha_ultimo = $resultAportes[$last - 1]->fecha_registro_contribucion;
            $fecha_primer = substr($fecha_primer, 0, 10);
            $fecha_ultimo = substr($fecha_ultimo, 0, 10);
            $tiempo = $this->dateDifference($fecha_primer, $fecha_ultimo);
            $page = (isset($_REQUEST['page']) && ! empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
            $resultSet = $participes->getCantidad("*", $tablas, $where);
            $cantidadResult = (int) $resultSet[0]->total;
            $per_page = 20; // la cantidad de registros que desea mostrar
            $adjacents = 9; // brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            $resultAportes = $participes->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
            $last = sizeof($resultAportes);

            $total_pages = ceil($cantidadResult / $per_page);

            $html = '<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Aportaciones</h3>
            <h4 class="widget-user-desc"><b>Tiempo de Aportes:</b> ' . $tiempo . '</h4>
            <h4 class="widget-user-desc"><b>Número de Aportaciones Personales mensuales:</b> ' . $personales . '</h4>
            </div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="10%">№</th>
                        <th width="29%">FECHA DE APORTACION</th>
                        <th width="28%">TIPO DE APORTE</th>
                        <th width="29%">TOTAL</th>
                        <th width="1.5%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
            for ($i = $last - 1; $i >= 0; $i --) {
                $index = ($i + ($last - 1) * ($page - 1)) + 1;
                if ($resultAportes[$i]->valor_personal_contribucion != 0) {
                    $fecha = substr($resultAportes[$i]->fecha_registro_contribucion, 0, 10);
                    $monto = number_format((float) $resultAportes[$i]->valor_personal_contribucion, 2, ',', '.');
                    $html .= '<tr>
                                 <td bgcolor="white" width="10%"><font color="black">' . $index . '</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">' . $fecha . '</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">' . $resultAportes[$i]->nombre_contribucion_tipo . '</font></td>
                                 <td bgcolor="white" align="right" width="30%"><font color="black">' . $monto . '</font></td>
                                </tr>';
                } else {
                    $fecha = substr($resultAportes[$i]->fecha_registro_contribucion, 0, 10);
                    $monto = number_format((float) $resultAportes[$i]->valor_patronal_contribucion, 2, ',', '.');
                    $html .= '<tr>
                                 <td bgcolor="white"  width="10%"><font color="black">' . $index . '</font></td>
                                 <td bgcolor="white"  width="30%"><font color="black">' . $fecha . '</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">' . $resultAportes[$i]->nombre_contribucion_tipo . '</font></td>
                                 <td bgcolor="white" align="right" width="30%"><font color="black">' . $monto . '</font></td>
                                </tr>';
                }
            }
            $total = number_format((float) $total, 2, ',', '.');
            $html .= '</table>
                   </div>
                    <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th class="text-right">Acumulado Total de Aportes: ' . $total . '</th>
                        <th width="1.5%"></th>
                     </tr>
                   </table>';
            $html .= '<div class="table-pagination pull-right">';
            $html .= '' . $participes->allpaginate("index.php", $page, $total_pages, $adjacents, "buscar_aportes_participe") . '';
            $html .= '</div>
                    </div>';
        } else {
            $html .= '<div class="alert alert-warning alert-dismissable bg-olive" style="margin-top:40px;">';
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html .= '<h4>Aviso!!!</h4> <b>El participe no tiene aportaciones</b>';
            $html .= '</div>';
        }

        $salida = ob_get_clean();
        if (! empty($salida)) {
            echo "Error en la generacion de tabla de aportes participe";
        } else {
            $response['html'] = $html;
            echo json_encode($response);
        }
    }

    public function AportesParticipe001()
    {
        session_start();
        $id_participe = $_POST['id_participe'];
        $html = "";
        $participes = new ParticipesModel();
        $total = 0;

        $columnas = "fecha_registro_contribucion, nombre_contribucion_tipo, valor_personal_contribucion";
        $tablas = "core_contribucion INNER JOIN core_contribucion_tipo
                ON core_contribucion.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo";
        $where = "core_contribucion.id_participes=" . $id_participe . " AND core_contribucion.id_contribucion_tipo=1 
                AND core_contribucion.id_estatus=1";
        $id = "fecha_registro_contribucion";

        $resultAportesPersonales = $participes->getCondiciones($columnas, $tablas, $where, $id);

        $columnas = "fecha_registro_contribucion, nombre_contribucion_tipo, valor_personal_contribucion, valor_patronal_contribucion";
        $tablas = "core_contribucion INNER JOIN core_contribucion_tipo
                ON core_contribucion.id_contribucion_tipo = core_contribucion_tipo.id_contribucion_tipo";
        $where = "core_contribucion.id_participes=" . $id_participe . " AND core_contribucion.id_estatus=1";
        $id = "fecha_registro_contribucion";

        $resultAportes = $participes->getCondiciones($columnas, $tablas, $where, $id);
        if (! (empty($resultAportes))) {
            foreach ($resultAportes as $res) {
                if ($res->valor_personal_contribucion != 0) {
                    $total += $res->valor_personal_contribucion;
                } else {
                    $total += $res->valor_patronal_contribucion;
                }
            }

            $personales = sizeof($resultAportesPersonales);
            $last = sizeof($resultAportes);
            $fecha_primer = $resultAportes[0]->fecha_registro_contribucion;
            $fecha_ultimo = $resultAportes[$last - 1]->fecha_registro_contribucion;
            $fecha_primer = substr($fecha_primer, 0, 10);
            $fecha_ultimo = substr($fecha_ultimo, 0, 10);
            $tiempo = $this->dateDifference($fecha_primer, $fecha_ultimo);
            $page = (isset($_REQUEST['page']) && ! empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
            $resultSet = $participes->getCantidad("*", $tablas, $where);
            $cantidadResult = (int) $resultSet[0]->total;
            $per_page = 20; // la cantidad de registros que desea mostrar
            $adjacents = 9; // brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            $resultAportes = $participes->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
            $last = sizeof($resultAportes);

            $total_pages = ceil($cantidadResult / $per_page);

            $html = '<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Aportaciones</h3>
            <h4 class="widget-user-desc"><b>Tiempo de Aportes:</b> ' . $tiempo . '</h4>
            <h4 class="widget-user-desc"><b>Número de Aportaciones Personales mensuales:</b> ' . $personales . '</h4>
            </div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="10%">№</th>
                        <th width="29%">FECHA DE APORTACION</th>
                        <th width="28%">TIPO DE APORTE</th>
                        <th width="29%">TOTAL</th>
                        <th width="1.5%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
            for ($i = $last - 1; $i >= 0; $i --) {
                $index = ($i + ($last - 1) * ($page - 1)) + 1;
                if ($resultAportes[$i]->valor_personal_contribucion != 0) {
                    $fecha = substr($resultAportes[$i]->fecha_registro_contribucion, 0, 10);
                    $monto = number_format((float) $resultAportes[$i]->valor_personal_contribucion, 2, ',', '.');
                    $html .= '<tr>
                                 <td bgcolor="white" width="10%"><font color="black">' . $index . '</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">' . $fecha . '</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">' . $resultAportes[$i]->nombre_contribucion_tipo . '</font></td>
                                 <td bgcolor="white" align="right" width="30%"><font color="black">' . $monto . '</font></td>
                                </tr>';
                } else {
                    $fecha = substr($resultAportes[$i]->fecha_registro_contribucion, 0, 10);
                    $monto = number_format((float) $resultAportes[$i]->valor_patronal_contribucion, 2, ',', '.');
                    $html .= '<tr>
                                 <td bgcolor="white"  width="10%"><font color="black">' . $index . '</font></td>
                                 <td bgcolor="white"  width="30%"><font color="black">' . $fecha . '</font></td>
                                 <td bgcolor="white" width="30%"><font color="black">' . $resultAportes[$i]->nombre_contribucion_tipo . '</font></td>
                                 <td bgcolor="white" align="right" width="30%"><font color="black">' . $monto . '</font></td>
                                </tr>';
                }
            }
            $total = number_format((float) $total, 2, ',', '.');
            $html .= '</table>  
                   </div>
                    <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th class="text-right">Acumulado Total de Aportes: ' . $total . '</th>
                        <th width="1.5%"></th>
                     </tr>
                   </table>';
            $html .= '<div class="table-pagination pull-right">';
            $html .= '' . $this->paginate_aportes("index.php", $page, $total_pages, $adjacents, $id_participe, "AportesParticipe") . '';
            $html .= '</div>
                    </div>';

            echo $html;
        } else {
            $html .= '<div class="alert alert-warning alert-dismissable bg-olive" style="margin-top:40px;">';
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html .= '<h4>Aviso!!!</h4> <b>El participe no tiene aportaciones</b>';
            $html .= '</div>';
            echo $html;
        }
    }

    public function paginate_aportes($reload, $page, $tpages, $adjacents, $id_participe, $funcion = '')
    {
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';

        // previous label

        if ($page == 1) {
            $out .= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if ($page == 2) {
            $out .= "<li><span><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>$prevlabel</a></span></li>";
        } else {
            $out .= "<li><span><a href='javascript:void(0);' onclick='$funcion(" . $id_participe . "," . ($page - 1) . ")'>$prevlabel</a></span></li>";
        }

        // first label
        if ($page > ($adjacents + 1)) {
            $out .= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>1</a></li>";
        }
        // interval
        if ($page > ($adjacents + 2)) {
            $out .= "<li><a>...</a></li>";
        }

        // pages

        $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
        $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
        for ($i = $pmin; $i <= $pmax; $i ++) {
            if ($i == $page) {
                $out .= "<li class='active'><a>$i</a></li>";
            } else if ($i == 1) {
                $out .= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>$i</a></li>";
            } else {
                $out .= "<li><a href='javascript:void(0);' onclick='$funcion(" . $id_participe . "," . $i . ")'>$i</a></li>";
            }
        }

        // interval

        if ($page < ($tpages - $adjacents - 1)) {
            $out .= "<li><a>...</a></li>";
        }

        // last

        if ($page < ($tpages - $adjacents)) {
            $out .= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,$tpages)'>$tpages</a></li>";
        }

        // next

        if ($page < $tpages) {
            $out .= "<li><span><a href='javascript:void(0);' onclick='$funcion(" . $id_participe . "," . ($page + 1) . ")'>$nextlabel</a></span></li>";
        } else {
            $out .= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }

        $out .= "</ul>";
        return $out;
    }

    public function CreditosActivosParticipe()
    {
        session_start();
        $id_participe = $_POST['id_participe'];
        $html = "";
        $participes = new ParticipesModel();
        $total = 0;

        ob_start();
        $response = array();

        $columnas = "core_creditos.id_creditos,core_creditos.numero_creditos, core_creditos.fecha_concesion_creditos,
            		core_tipo_creditos.nombre_tipo_creditos, core_creditos.monto_otorgado_creditos,
            		core_creditos.saldo_actual_creditos, core_creditos.interes_creditos, 
            		core_estado_creditos.nombre_estado_creditos";
        $tablas = "public.core_creditos INNER JOIN public.core_tipo_creditos
        		ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos
        		INNER JOIN public.core_estado_creditos
        		ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos";
        $where = "core_creditos.id_participes=" . $id_participe . " AND core_creditos.id_estatus=1 AND core_creditos.id_estado_creditos=4";
        $id = "core_creditos.fecha_concesion_creditos";

        $resultCreditos = $participes->getCondiciones($columnas, $tablas, $where, $id);
        if (! (empty($resultCreditos))) {

            $page = (isset($_REQUEST['page']) && ! empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
            $resultSet = $participes->getCantidad("*", $tablas, $where);
            $cantidadResult = (int) $resultSet[0]->total;
            $per_page = 20; // la cantidad de registros que desea mostrar
            $adjacents = 9; // brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            $resultCreditos = $participes->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
            $last = sizeof($resultCreditos);

            $total_pages = ceil($cantidadResult / $per_page);

            $html = '<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Historial Prestamos</h3>
            </div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="2%">№</th>
                        <th width="4%">№ DE PRESTAMO</th>
                        <th width="15%">FECHA DE PRESTAMO</th>
                        <th width="15%">TIPO DE PRESTAMO</th>
                        <th width="14%">MONTO</th>
                        <th width="14%">SALDO CAPITAL</th>
                        <th width="14%">SALDO INTERES</th>
                        <th width="14%">ESTADO</th>
                        <th width="4%"></th>
                        <th width="2%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
            for ($i = $last - 1; $i >= 0; $i --) {
                $index = ($i + ($last - 1) * ($page - 1)) + 1;
                $monto = number_format((float) $resultCreditos[$i]->monto_otorgado_creditos, 2, ',', '.');
                $saldo = number_format((float) $resultCreditos[$i]->saldo_actual_creditos, 2, ',', '.');
                $saldo_int = number_format((float) $resultCreditos[$i]->interes_creditos, 2, ',', '.');
                $html .= '<tr>
                        <td bgcolor="white" width="2%"><font color="black">' . $index . '</font></td>
                         <td bgcolor="white" width="6.5%"><font color="black">' . $resultCreditos[$i]->numero_creditos . '</font></td>
                         <td bgcolor="white" width="15%"><font color="black">' . $resultCreditos[$i]->fecha_concesion_creditos . '</font></td>
                        <td bgcolor="white" width="15%"><font color="black">' . $resultCreditos[$i]->nombre_tipo_creditos . '</font></td>
                        <td bgcolor="white" width="14%"><font color="black">' . $monto . '</font></td>
                        <td bgcolor="white" width="14%"><font color="black">' . $saldo . '</font></td>
                        <td bgcolor="white" width="14%"><font color="black">' . $saldo_int . '</font></td>
                        <td bgcolor="white" width="14%"><font color="black">' . $resultCreditos[$i]->nombre_estado_creditos . '</font></td>
                        <td bgcolor="white" width="3.5%"><font color="black">';
                $html .= '<li class="dropdown messages-menu">';
                $html .= '<button type="button" class="btn bg-olive" data-toggle="dropdown">';
                $html .= '<i class="fa fa-reorder"></i>';
                $html .= '</button>';
                $html .= '<ul class="dropdown-menu">';
                $html .= '<li>';
                $html .= '<table style = "width:100%; border-collapse: collapse;" border="1">';
                $html .= '<tbody>';
                $html .= '<tr height = "25">';
                $html .= '<td><a class="btn bg-olive" title="Pagaré" href="index.php?controller=TablaAmortizacion&action=ReportePagare&id_creditos=' . $resultCreditos[$i]->id_creditos . '" role="button" target="_blank"><i class="glyphicon glyphicon-list"></i></a></font></td>';
                $html .= '</tr>';
                $html .= '<tr height = "25">';
                $html .= '<td><a class="btn bg-olive" title="Tabla Amortización" href="index.php?controller=TablaAmortizacion&action=ReporteTablaAmortizacion&id_creditos=' . $resultCreditos[$i]->id_creditos . '" role="button" target="_blank"><i class="glyphicon glyphicon-list-alt"></i></a></font></td>';
                $html .= '</tr>';
                $hoy = date("Y-m-d");
                $columnas = "id_estado_tabla_amortizacion";
                $tablas = "core_tabla_amortizacion INNER JOIN core_creditos
                        ON core_tabla_amortizacion.id_creditos = core_creditos.id_creditos
                        INNER JOIN core_estado_creditos
                        ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos";
                $where = "core_tabla_amortizacion.id_creditos=" . $resultCreditos[$i]->id_creditos . " AND core_tabla_amortizacion.id_estatus=1 AND fecha_tabla_amortizacion BETWEEN '" . $resultCreditos[$i]->fecha_concesion_creditos . "' AND '" . $hoy . "'
                        AND nombre_estado_creditos='Activo'";
                $resultCreditosActivos = $participes->getCondicionesSinOrden($columnas, $tablas, $where, "");
                if (! (empty($resultCreditosActivos))) {
                    $cuotas_pagadas = sizeof($resultCreditosActivos);
                    $mora = false;
                    foreach ($resultCreditosActivos as $res) {
                        if ($res->id_estado_tabla_amortizacion != 2)
                            $mora = true;
                    }
                    if ($cuotas_pagadas >= 6 && $mora == false) {
                        $html .= '<tr height = "25">';
                        $html .= '<td><button class="btn bg-olive" title="Renovación de crédito"  onclick="RenovacionCredito()"><i class="glyphicon glyphicon-refresh"></i></button></td>';
                        $html .= '</tr>';
                    }
                }
                $html .= '</tbody>';
                $html .= '</table>';
                $html .= '</li>';

                $html .= '</td>
                        </tr>';
            }
            $total = number_format((float) $total, 2, ',', '.');
            $html .= '</table>
                   </div>';
            $html .= '<div class="table-pagination pull-right">';
            $html .= '' . $this->paginate_creditos("index.php", $page, $total_pages, $adjacents, $id_participe, "CreditosActivosParticipe") . '';
            $html .= '</div>
                    </div>';
        } else {
            $html .= '<div class="alert alert-warning alert-dismissable bg-olive" style="margin-top:40px;">';
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html .= '<h4>Aviso!!!</h4> <b>El participe no tiene creditos activos</b>';
            $html .= '</div>';
        }

        $salida = ob_get_clean();
        if (! empty($salida)) {
            echo "Error en la generacion de tabla de aportes participe";
        } else {
            $response['html'] = $html;
            echo json_encode($response);
        }
    }

    public function paginate_creditos($reload, $page, $tpages, $adjacents, $id_participe, $funcion = '')
    {
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';

        // previous label

        if ($page == 1) {
            $out .= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if ($page == 2) {
            $out .= "<li><span><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>$prevlabel</a></span></li>";
        } else {
            $out .= "<li><span><a href='javascript:void(0);' onclick='$funcion(" . $id_participe . "," . ($page - 1) . ")'>$prevlabel</a></span></li>";
        }

        // first label
        if ($page > ($adjacents + 1)) {
            $out .= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>1</a></li>";
        }
        // interval
        if ($page > ($adjacents + 2)) {
            $out .= "<li><a>...</a></li>";
        }

        // pages

        $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
        $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
        for ($i = $pmin; $i <= $pmax; $i ++) {
            if ($i == $page) {
                $out .= "<li class='active'><a>$i</a></li>";
            } else if ($i == 1) {
                $out .= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,1)'>$i</a></li>";
            } else {
                $out .= "<li><a href='javascript:void(0);' onclick='$funcion(" . $id_participe . "," . $i . ")'>$i</a></li>";
            }
        }

        // interval

        if ($page < ($tpages - $adjacents - 1)) {
            $out .= "<li><a>...</a></li>";
        }

        // last

        if ($page < ($tpages - $adjacents)) {
            $out .= "<li><a href='javascript:void(0);' onclick='$funcion($id_participe,$tpages)'>$tpages</a></li>";
        }

        // next

        if ($page < $tpages) {
            $out .= "<li><span><a href='javascript:void(0);' onclick='$funcion(" . $id_participe . "," . ($page + 1) . ")'>$nextlabel</a></span></li>";
        } else {
            $out .= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }

        $out .= "</ul>";
        return $out;
    }

    public function verFechas()
    {
        $mes = date('m');
        $anio = date('Y');
        // CAMBIO TEMPORAL PARA PRUEBAS
        // $mes=10;

        $mes_fin = $mes - 1;

        if ($mes_fin == 0) {
            $anio_fin = $anio - 1;
            $mes_fin = 12;
        } else {
            $anio_fin = $anio;
            $mes_fin = $mes_fin;
        }

        $mes_ini = $mes - 3;
        if ($mes_ini < 1) {
            $anio_ini = $anio - 1;
            $mes_ini += 12;
        } else {
            $anio_ini = $anio;
            $mes_ini = $mes_ini;
        }

        $dia = date("d", (mktime(0, 0, 0, $mes_fin + 1, 1, $anio_fin) - 1));
        $fecha_inicio = $anio_ini . "-" . str_pad($mes_ini, 2, '0', STR_PAD_LEFT) . "-01";
        $fecha_fin = $anio_fin . "-" . str_pad($mes_fin, 2, '0', STR_PAD_LEFT) . "-" . $dia;

        echo $fecha_inicio;
        echo "<br>";
        echo $fecha_fin;
    }

    /**
     * *
     * fn para obtener listado de creditos a renovar
     */
    public function obtenerCreditosRenovar()
    {
        session_start();
        ob_start();
        $id_participe = $_POST['id_participe'];
        $tipo_credito = $_POST['tipo_creditos'];
        $response = array();
        $rp_capremci = new ParticipesModel();
        $total = 0.00;
        $html = '
        <table width="100%" class="table-condensed" >
        <tr>
        <th colspan="4" style="text-align:center">CREDITOS A RENOVAR</th>
        </tr>
        <tr>
        <th >№ DE PRESTAMO</th>
        <th >MONTO CREDITO</th>
        <th >FECHA DE PRESTAMO</th>
        <th>TIPO CREDITO</th>
        <th >SALDO CAPITAL</th>
        </tr>';

        $columnas = "id_tipo_creditos_a_renovar";
        $tablas = "core_tipo_creditos_renovacion INNER JOIN core_tipo_creditos
        ON core_tipo_creditos.id_tipo_creditos = core_tipo_creditos_renovacion.id_tipo_creditos";
        $where = "codigo_tipo_creditos='" . $tipo_credito . "' AND core_tipo_creditos_renovacion.id_estado=107";
        $id_creditos_renovar = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");

        $count_creditos_renovar = 0;
        foreach ($id_creditos_renovar as $res) 
        {
            $columnas = 'core_creditos.id_creditos,core_creditos.numero_creditos, core_creditos.fecha_concesion_creditos,
            		core_tipo_creditos.nombre_tipo_creditos, core_creditos.monto_otorgado_creditos,
            		core_creditos.saldo_actual_creditos, core_creditos.interes_creditos,
            		core_estado_creditos.nombre_estado_creditos';
            $tablas = 'public.core_creditos INNER JOIN public.core_tipo_creditos
        		ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos
        		INNER JOIN public.core_estado_creditos
        		ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos';

            $where = "core_creditos.id_participes=" . $id_participe . " AND core_creditos.id_estatus=1 AND core_estado_creditos.nombre_estado_creditos='Activo'
                    AND core_creditos.id_tipo_creditos=" . $res->id_tipo_creditos_a_renovar;

            $id_credito = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");

            foreach ($id_credito as $res1) {
                $count_creditos_renovar ++;
                $total += $res1->saldo_actual_creditos;
                $saldo = number_format((float) $res1->saldo_actual_creditos, 2, '.', '');
                $html .= '<tr>
                 <td >' . $res1->numero_creditos . '</font></td>
                 <td >' . $res1->monto_otorgado_creditos . '</font></td>
                 <td >' . $res1->fecha_concesion_creditos . '</font></td>
                 <td>' . $res1->nombre_tipo_creditos . '</td>
                 <td align="right" id="saldo_credito_a_renovar">' . $saldo . '</font></td>
                </tr>';
            }
        }

        $total = number_format((float) $total, 2, '.', '');
        $html .= '<tr>
        <th ></th>
        <th ></th>
        <th ></th>
        <th >Total:</th>
        <td align="right" id="total_saldo_renovar">' . $total . '</td>
        </tr>';

        $html .= '</table>';

        $response['html'] = $html;
        $response['cantidad'] = $count_creditos_renovar;

        $salida = ob_get_clean();
        if (! empty($salida)) {
            $html = "<span>ERROR AL OBTENER LISTA DE CREDITOS A RENOVAR</span>";
            $response['html'] = $html;
            $response['cantidad'] = 0;
        }

        echo json_encode($response);
    }

    public function obtenerAportesValidacion()
    {
        ob_start();
        session_start();
        $id_participe = $_POST['id_participe'];
        //$id_participe = $_GET['id_participe'];
        $html = "";
        $participes = new ParticipesModel();
        $response = array();

        $fechasValidacion = $this->getFechasUltimas3Cuotas();
        
        $queryConsulta  = "SELECT TO_CHAR(c.fecha_registro_contribucion, 'YYYY') AS anio, TO_CHAR(c.fecha_registro_contribucion, 'MM') AS mes, 
        SUM(c.valor_personal_contribucion) AS aporte
        FROM core_contribucion c 
        INNER JOIN core_participes p ON c.id_participes = p.id_participes
        WHERE p.id_participes='$id_participe' 
        AND p.id_estatus=1 
        AND c.id_contribucion_tipo=1  
        AND c.fecha_registro_contribucion BETWEEN '" . $fechasValidacion['desde'] . "' AND '" . $fechasValidacion['hasta'] . "' 
        AND c.id_estatus=1
        GROUP BY TO_CHAR(c.fecha_registro_contribucion, 'YYYY'), TO_CHAR(c.fecha_registro_contribucion, 'MM')
        HAVING SUM(c.valor_personal_contribucion) > 0
        ORDER BY TO_CHAR(c.fecha_registro_contribucion, 'YYYY') DESC, TO_CHAR(c.fecha_registro_contribucion, 'MM') DESC";
        
        $rsConsulta1    = $participes->enviaquery( $queryConsulta );

        $html = '<ul class="list-group">';
        foreach ($rsConsulta1 as $res) {
            $valor = number_format($res->aporte, 2, ".", "");
            $html .= '<li class="list-group-item"><div class=""><p><span class="tabulacion"><b> Fecha: </b>' . substr( strtoupper( $this->devuelveMesNombre($res->mes) ),0,3 )." ".$res->anio . '</span> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; <b>Valor Aporte: </b>' . $valor . '</p></div></li>';
        }
        $html .= '</ul>';

        $salida = ob_get_clean();
        if (! empty($salida)) {
            echo "Error en la generacion de Aportes" . $salida;
        } else {
            $response['html'] = $html;
            echo json_encode($response);
        }
    }

    public function obtenerHistorialMoras()
    {
        ob_start();
        session_start();
        $id_participe = $_POST['id_participe'];
        $html = "";
        $participes = new ParticipesModel();
        $response = array();

        /*
        $columnas = " aa.id_creditos, aa.fecha_tabla_amortizacion, aa.total_valor_tabla_amortizacion, aa.mora_tabla_amortizacion, bb.numero_creditos";
        $tablas = " core_tabla_amortizacion aa
            INNER JOIN core_creditos bb ON bb.id_creditos = aa.id_creditos
            INNER JOIN core_participes cc ON cc.id_participes = bb.id_participes";
        $where = " aa.id_estatus = 1
            AND coalesce( aa.mora_tabla_amortizacion, 0) > 0
            AND cc.id_participes = $id_participe";
        $order = " ORDER BY aa.id_creditos DESC, aa.fecha_tabla_amortizacion DESC";

        $rsConsultaHistorico = $participes->getCondicionesSinOrden($columnas, $tablas, $where, $order);
        */
        
        $qryBuscador    = "SELECT aa.id_creditos, aa.numero_creditos, aa.fecha_concesion_creditos, bb.nombre_tipo_creditos, aa.monto_otorgado_creditos, 
        aa.plazo_creditos, dd.cuotas_mora, dd.suma_mora, cc.nombre_estado_creditos
        FROM core_creditos aa
        INNER JOIN core_tipo_creditos bb ON bb.id_tipo_creditos = aa.id_tipo_creditos
        INNER JOIN core_estado_creditos cc ON cc.id_estado_creditos = aa.id_estado_creditos
        INNER JOIN (
            SELECT aaa.id_creditos,count(aaa.mora_tabla_amortizacion) \"cuotas_mora\",sum(aaa.mora_tabla_amortizacion) \"suma_mora\"
            FROM core_tabla_amortizacion aaa
            INNER JOIN core_creditos bbb ON bbb.id_creditos = aaa.id_creditos
            INNER JOIN core_participes ccc ON ccc.id_participes = bbb.id_participes
            INNER JOIN core_estado_creditos ddd ON ddd.id_estado_creditos = bbb.id_estado_creditos
            WHERE aaa.id_estatus = 1
            AND coalesce( aaa.mora_tabla_amortizacion, 0) > 0
            AND ddd.nombre_estado_creditos not in ( 'Anulado','Registrado')
            AND ccc.id_participes = '$id_participe'
            GROUP BY aaa.id_creditos
            ORDER BY aaa.id_creditos DESC
            )dd on dd.id_creditos = aa.id_creditos
            WHERE 1 = 1
            ORDER BY aa.id_creditos DESC";
        $rsConsultaHistorico = $participes->enviaquery($qryBuscador);

        $html = '<div class="col-lg-12 col-md-12 col-sm-12"><div id="divtblHistorialMoras" class="">';
        $html .= '<table class="table table-hover table-bordered">';
        $html .= '<thead>';
        $html .= '<thead>';
        $html .= '<tr style="">';
        $html .= '<th class="info">#</th>';
        $html .= '<th class="info">Numero Credito</th>';
        $html .= '<th class="info">Fecha Credito</th>';
        $html .= '<th class="info">Tipo Credito</th>';
        $html .= '<th class="info">Monto</th>';
        $html .= '<th class="info">Cuotas</th>';
        $html .= '<th class="info">Cuotas Mora</th>';
        $html .= '<th class="info">Total Mora</th>';
        $html .= '<th class="info">Estado</th>';
        $html .= '</tr>';
        $html .= '<tbody style="">';

        if (sizeof($rsConsultaHistorico) > 0) {
            $contador = 1;
            foreach ($rsConsultaHistorico as $res) {
              
                $html .= '<tr>';
                $html .= '<td>' . $contador . '</td>';
                $html .= '<td>' . $res->numero_creditos . '</td>';
                $html .= '<td>' . $res->fecha_concesion_creditos . '</td>';
                $html .= '<td>' . $res->nombre_tipo_creditos . '</td>';
                $html .= '<td>' . $res->monto_otorgado_creditos . '</td>';
                $html .= '<td>' . $res->plazo_creditos . '</td>';
                $html .= '<td>' . $res->cuotas_mora . '</td>';
                $html .= '<td>' . $res->suma_mora . '</td>';
                $html .= '<td>' . $res->nombre_estado_creditos . '</td>';
                $html .= '</tr>';
                $contador ++;
            }
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="9">No Existe Historial Moras</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div></div>';

        $salida = ob_get_clean();
        if (! empty($salida)) {
            echo "Error en la generacion de Aportes" . $salida;
        } else {
            $response['html'] = $html;
            echo json_encode($response);
        }
    }
        
    public function cargarProductoCredito()
    {
        ob_start();
        $creditos   = new CreditosModel();
        
        $codigo_tipo_creditos   = $_POST['codigo_tipo_creditos'];
        
        $col1   = " aa.id_creditos_productos, aa.nombre_creditos_productos";
        $tab1   = " core_creditos_productos aa
            INNER JOIN core_tipo_creditos bb ON bb.id_tipo_creditos = aa.id_tipo_creditos";
        $whe1   = " aa.id_estatus = 1
            AND bb.codigo_tipo_creditos = '$codigo_tipo_creditos' ";
        $id1    = " aa.id_creditos_productos";
        $rsConsulta1    = $creditos->getCondiciones($col1, $tab1, $whe1, $id1);
               
        $salida = ob_get_clean();
        if( !empty($salida) ){
            var_dump( $salida );
            echo "Existe error en function php";
        }else{
            $respuesta  = $rsConsulta1 ?? null;
            echo json_encode($respuesta);
        }        
    }

    /**
     * ************************************************************************** FUNCIONES UTILS DE CONTROLADOR ***************************
     */
    public function getFechasUltimas3Cuotas()
    {
        $resp = array();
        $fecha = new DateTime();

        $mes = $fecha->format('m');
        $anio = $fecha->format('Y');
        $mes_fin = $mes - 1;
        $anio_fin = 0;

        if ($mes_fin == 0) {
            $anio_fin = $anio - 1;
            $mes_fin = 12;
        } else {
            $anio_fin = $anio;
            $mes_fin = $mes_fin;
        }

        $mes_ini = $mes - 3;
        $anio_ini = 0;
        if ($mes_ini < 1) {
            $anio_ini = $anio - 1;
            $mes_ini += 12;
        } else {
            $anio_ini = $anio;
            $mes_ini = $mes_ini;
        }

        $fecha_desde = new DateTime($anio_ini . "-" . str_pad($mes_ini, 2, '0', STR_PAD_LEFT) . "-01");
        $fecha_hasta = new DateTime($anio_fin . "-" . str_pad($mes_fin, 2, '0', STR_PAD_LEFT) . "-01");

        $resp['desde'] = $fecha_desde->format('Y-m-d');
        $resp['hasta'] = $fecha_hasta->format('Y-m-t');

        return $resp;
    }
    
    public function validarDatosSolicitud()
    {
        ob_start();
        session_start();
        $participes = new ParticipesModel();
        
        $response   = array();
        $input  = json_decode( file_get_contents( "php://input" ) );
        
        $cedula_participes  = $input->cedula_participes;
        $fechasValidacion   = $this->getFechasUltimas3Cuotas();
        
        $col1   = " aa.id_creditos, aa.fecha_tabla_amortizacion, aa.total_valor_tabla_amortizacion, aa.mora_tabla_amortizacion, bb.numero_creditos ";
        $tab1   = " core_tabla_amortizacion aa
            INNER JOIN core_creditos bb ON bb.id_creditos = aa.id_creditos
            INNER JOIN core_participes cc ON cc.id_participes = bb.id_participes";
        $whe1   = " aa.id_estatus = 1
            AND COALESCE( aa.mora_tabla_amortizacion, 0) > 0
            AND bb.id_estado_creditos = 4
            AND ( aa.fecha_tabla_amortizacion BETWEEN '".$fechasValidacion['desde']."' AND '".$fechasValidacion['hasta']."' )
            AND cc.cedula_participes = '".$cedula_participes."'";
        $order  = " ORDER BY aa.id_creditos DESC, aa.fecha_tabla_amortizacion DESC";
        $rsConsulta1    = $participes->getCondicionesSinOrden($col1, $tab1, $whe1, $order);
        
        #VALIDAMOS si existe datos en mora
        if( !empty($rsConsulta1) )
        {
            $response['error']      = true;
            $response['mensaje']    = "Existen cuotas en Mora";
        }
        
        #ESTABLECEMOS valores de fecha inicio y fin
        $fecha_inicio   = $fechasValidacion['desde'];
        $fecha_fin      = $fechasValidacion['hasta'];        
        
        $col2   = " TO_CHAR(c.fecha_registro_contribucion, 'MM') AS mes, SUM(c.valor_personal_contribucion) AS aporte";
        $tab2   = " core_contribucion c INNER JOIN core_participes p ON c.id_participes = p.id_participes";
        $whe2   = "  p.id_estatus = 1 AND c.id_contribucion_tipo = 1  AND c.id_estatus = 1
            AND  p.cedula_participes='" . $cedula_participes . "'
            AND  c.fecha_registro_contribucion BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'";
        $gru2   = " TO_CHAR(c.fecha_registro_contribucion, 'MM')";
        $hav2   = " SUM( c.valor_personal_contribucion ) > 0";
        
        $rsAportes = $participes->getCondiciones_Grupo_Having($col2, $tab2, $whe2, $gru2, $hav2);
        
        if( sizeof($rsAportes) >= 0 )
        {
            $num_aporte = sizeof($rsAportes);
            
            if( $num_aporte < 3 )
            {
                #BUSCAMOS identificador del participe
                $col3   = " id_participes";
                $tab3   = " public.core_participes";
                $whe3   = " id_estatus = 1 and id_estado_participes = 1 and cedula_participes = '$cedula_participes' ";
                
                $rsParticipe = $participes->getCondicionesSinOrden($col3, $tab3, $whe3, "");
                
                #VALIDAMOS datos del participe
                if(  !empty( $rsParticipe ) )
                {
                    $id_participe   = $rsParticipe[0]->id_participes;
                    
                    #BUSCAMOS si existe reafiliacion
                    $reafiliacion = $this->validarReAfiliacion($id_participe);
                    
                    if( $reafiliacion )
                    {
                        #BUSCAMOS valores de contribucion tipo
                        $col4   = " bb.nombre_tipo_aportacion, aa.id_contribucion_tipo, aa.valor_contribucion_tipo_participes, 
                            aa.sueldo_liquido_contribucion_tipo_participes";
                        $tab4   = " public.core_contribucion_tipo_participes aa
                            INNER JOIN public.core_tipo_aportacion bb ON bb.id_tipo_aportacion = aa.id_tipo_aportacion";
                        $whe4   = " id_contribucion_tipo = 1  AND id_participes = $id_participe ";
                       
                        $rsContribucion = $participes->getCondicionesSinOrden($col4, $tab4, $whe4, "");
                        
                        if( !empty($rsContribucion) )
                        {
                            $tipo_aportacion    = $rsContribucion[0]->nombre_tipo_aportacion;
                            
                            if( $tipo_aportacion == 'PORCENTAJE' )
                            {
                                $rmu_participe  = $rsContribucion[0]->sueldo_liquido_contribucion_tipo_participes;
                                $valor  = $rsContribucion[0]->valor_contribucion_tipo_participes;
                                if( !empty($rmu_participe) && !empty($valor) )
                                {
                                    #OK
                                    $response['error']      = false;
                                    $response['mensaje']    = "";
                                }else
                                {
                                    #ERROR --
                                    $response['error']      = true;
                                    $response['mensaje']    = "Participe no tiene establecido valores 'core_contribucion_tipo_participes'";
                                }                                    
                                
                            }elseif( $tipo_aportacion == 'VALOR' )
                            {
                                if( !empty($rmu_participe) && !empty($valor) )
                                {
                                    #OK
                                    $response['error']      = false;
                                    $response['mensaje']    = "";
                                }else
                                {
                                    #ERROR --
                                    $response['error']      = true;
                                    $response['mensaje']    = "Partícipe no tiene establecido valores 'core_contribucion_tipo_participes'";
                                }  
                            }else
                            {
                                #ERROR --Partcipe no tiene establecido valor de aporte
                                $response['error']      = true;
                                $response['mensaje']    = "Partícipe no tiene establecido valores de aporte";
                            }
                            
                        }else
                        {
                            #ERROR --Partcipe no tiene establecido valor de aporte
                            $response['error']      = true;
                            $response['mensaje']    = "Partícipe no tiene establecido valores de aporte";
                        }
                        
                    }else
                    {
                        #ERROR --Participe no tiene Solicitud de reafiliación activa
                        $response['error']      = true;
                        $response['mensaje']    = "Partícipe no tiene 'Solicitud de Reafiliación' activa";
                    }
                    
                }else
                {
                    #ERROR --Partcipe no se encuentra Activo
                    $response['error']      = true;
                    $response['mensaje']    = "Partícipe no se encuentra Activo";
                }
                
            }else
            {
                #OK --numero de aportes aceptados
                $response['error']      = false;
                $response['mensaje']    = "";
            }
            
        }else
        {
            #ERROR --Partcipe no cuenta con numero de aportes necesarios
            $response['error']      = true;
            $response['mensaje']    = "Partícipe no cuenta con número de aportes necesarios";
        }
                
        $salida = ob_get_contents();
        if( !empty( $salida ) || $response['error'] )
        {
            $response['estatus'] = "ERROR";
            $response['buffer']  = $salida;
            echo json_encode( $response );
            
        }else
        {
            $response['estatus'] = "OK";
            $response['mensaje'] = "Validacion Exitosa";
            echo json_encode( $response );
        }
        
    }
    
    public function imprimirSimulacionCredito()
    {
        session_start();
        $data = json_decode($_POST['datos_tabla']);
        $tipo_creditos  = $_POST['tipo_creditos'];
        $monto_creditos = $_POST['monto_creditos'];
        $entidades = new EntidadesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
        
        if(!empty($rsdatosEmpresa) && count($rsdatosEmpresa)>0){
            //llenar nombres con variables que va en html de reporte
            $datos_empresa['NOMBREEMPRESA']=$rsdatosEmpresa[0]->nombre_entidades;
            $datos_empresa['DIRECCIONEMPRESA']=$rsdatosEmpresa[0]->direccion_entidades;
            $datos_empresa['TELEFONOEMPRESA']=$rsdatosEmpresa[0]->telefono_entidades;
            $datos_empresa['RUCEMPRESA']=$rsdatosEmpresa[0]->ruc_entidades;
            $datos_empresa['FECHAEMPRESA']=date('Y-m-d H:i');
            $datos_empresa['USUARIOEMPRESA']=( isset( $_SESSION['usuario_usuarios'] ) ) ? $_SESSION['usuario_usuarios'] : '';
        }
        
        $dictionary = array();
                
        $dictionary['TITULO']  = "SIMULACION CREDITO";
        $dictionary['TIPO_CREDITO']  = $tipo_creditos;
        $dictionary['MONTO_SOLICITADO']  = number_format( $monto_creditos ,2,'.',',');
                
        $html = "";        
        $hayDatos  = false;
        
        if( !empty( $data ) ){
            
            $hayDatos = true;
            
            $html.='<table class="1" cellspacing="0" border="1">';
            $html.='<tr>';
            $html.='<th >N° Cuota</th>';
            $html.='<th >Fecha</th>';
            $html.='<th >Capital</th>';
            $html.='<th >Interes</th>';
            $html.='<th >Seg. Desgravamen</th>';
            $html.='<th >Valor Cuota</th>';
            $html.='<th >Saldo</th>';
            $html.='</tr>';
            
            $i  = 0;
            foreach ( $data as $res ){
                
                $i++;
                if( $i == sizeof( $data ) )
                {
                    $html.='<tr>';
                    $html.='<td >'.$res[0].'</td>';
                    $html.='<td >'.$res[1].'</td>';
                    $html.='<td class="decimales" >'.$res[2].'</td>';
                    $html.='<td class="decimales" >'.$res[3].'</td>';
                    $html.='<td class="decimales" >'.$res[4].'</td>';
                    $html.='<td class="decimales" >'.$res[5].'</td>';
                    $html.='<td class="decimales" >'.$res[6].'</td>';
                    $html.='</tr>';
                }else
                {
                    $html.='<tr>';
                    $html.='<td >'.$res[0].'</td>';
                    $html.='<td >'.$res[1].'</td>';
                    $html.='<td class="decimales" >'.$res[2].'</td>';
                    $html.='<td class="decimales" >'.$res[3].'</td>';
                    $html.='<td class="decimales" >'.$res[4].'</td>';
                    $html.='<td class="decimales" >'.$res[5].'</td>';
                    $html.='<td class="decimales" >'.$res[6].'</td>';
                    $html.='</tr>';
                }
                
            }                        
            $html.='</table>';
                        
        }
        
        $textoDataEmpty    = '<h4 class="dataempty"> NO EXISTEN DATOS PARA MOSTRAR </h4>';
        
        if( $hayDatos ){            
            $dictionary['DETALLE_SIMULADOR']   = $html;
        }else{
            $dictionary['DETALLE_SIMULADOR']   = $textoDataEmpty;
        }
        
        $this->verReporte( "ReporteSimulacionCreditos", array(
            'datos_empresa'=> $datos_empresa,
            'dictionary'   => $dictionary
        )
            ) ;
        
    }
    
    private function devuelveMesNombre($_mes){
        
        $meses = array('enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
        $_intMes = (int)$_mes;
        return $meses[$_intMes-1];
        
    }

    public function usoFetch()
    {
        $input = json_decode(file_get_contents("php://input"));
        $var = $input->id_participes;
        $res = array(
            'estatus' => "OK",
            'data' => 'uno-dos',
            'post' => $var
        );
        echo json_encode($res);
    }
       
    public function obtenerAvaluoHipotecario()
    {        
        session_start();
        $rp_capremci = new PlanCuentasModel();
        $monto_maximo = 0;
        
        $id_solicitud = $_POST['id_solicitud'];
        $tipo_credito_hipotecario = $_POST['tipo_credito_hipotecario'];
        $columnas = "valor_avaluo_core_documentos_hipotecario";
        $tablas = "core_documentos_hipotecario";
        $where = "id_solicitud_credito=" . $id_solicitud;
        $avaluo_credito = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        if (sizeof($avaluo_credito) > 0) {
            $avaluo_credito = $avaluo_credito[0]->valor_avaluo_core_documentos_hipotecario;
            if ($tipo_credito_hipotecario == 1) {
                $monto_maximo = $avaluo_credito * 0.8;
                if ($monto_maximo > 100000)
                    $monto_maximo = 100000;
            } else {
                $monto_maximo = $avaluo_credito * 0.5;
                if ($monto_maximo > 45000)
                    $monto_maximo = 45000;
            }
            $avaluo_credito = number_format((float) $avaluo_credito, 2, ".", "");
            $monto_maximo = number_format((float) $monto_maximo, 2, ".", "");
            $html = '<table>
        <tr>
        <td><font size="3">Avalúo del bien : ' . $avaluo_credito . '</font></td>
        </tr>
        <tr>
        <td><font size="3" id="monto_disponible2">Monto máximo a recibir : ' . $monto_maximo . '</font></td>
        </tr>
        <tr>
        <td>
        <span class="input-group-btn">
        <button  type="button" class="btn bg-olive" title="Cambiar Modalidad" onclick="TipoCredito()"><i class="glyphicon glyphicon-refresh"></i></button>
        <button  type="button" class="btn bg-olive" title="Escrituras" onclick="TipoCredito()"><i class="glyphicon glyphicon-book"></i></button>
        <button  type="button" class="btn bg-olive" title="Certificado" onclick="TipoCredito()"><i class="glyphicon glyphicon-check"></i></button>
        <button  type="button" class="btn bg-olive" title="Impuestos" onclick="TipoCredito()"><i class="fa fa-black-tie"></i></button>
        <button  type="button" class="btn bg-olive" title="Avaluo" onclick="TipoCredito()"><i class="glyphicon glyphicon-usd"></i></button>
        </span>
        </td>
        </tr>
        </table>';
        } else 
        {
            $html = '<table>
        <tr>
        <td><font size="3">No hay avaluo registrado</font></td>
        </tr>
        <tr>
        <td><font size="3" id="monto_disponible2">Monto máximo a recibir : 0.00</font></td>
        </tr>
                
        </table>';
        }
        
        echo $html;
        
    }
    
    public function obtenerDatosCuentasBancosSolicitud()
    {        
        ob_start();
        
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions(); //modelo para webcapremci  
        
        $response   = array();
        
        $id_solicitud   = $_POST['id_solicitud'];
        
        $rsDatosBancos  = array();
        $rsDatosTipoCuenta = array();
        //se genera un array con los tipos de cuenta para enviar a la vista
        array_push($rsDatosTipoCuenta, (object)array( 'id_tipo_cuentas'=>"Ahorros",'nombre_tipo_cuentas'=>"AHORROS" ) );
        array_push($rsDatosTipoCuenta, (object)array( 'id_tipo_cuentas'=>"Corriente", 'nombre_tipo_cuentas'=>"CORRIENTE" ) ); 
        //$rsDatosTipoCuenta =  array((object)$rsDatosTipoCuenta); //se transforma en un objeto array 
        $rsValidadores  = array();
        
        $tipo_pago = "";
        $nombre_banco   = "";
        $id_banco_seleccionado = 0;
        $valor_tipo_cuenta_selecionada = "";
        $tipo_pago_selecionado  = "";
        $numero_cuenta_selecionada  = "";
        
        //buscamos bancos de --web capremci
        $columnas = " id_bancos, nombre_bancos";
        $tablas = " bancos";
        $where = " 1 = 1";
        $id = "nombre_bancos";
        $rsBancos = $db->getCondicionesDesc($columnas, $tablas, $where, $id);        
        
        
        $columnas = "nombre_banco_cuenta_bancaria, tipo_cuenta_cuenta_bancaria, numero_cuenta_cuenta_bancaria,
            numero_cedula_datos_personales, tipo_pago_cuenta_bancaria";
        $tablas = " public.solicitud_prestamo";
        $where = "id_solicitud_prestamo=$id_solicitud";
        $id = "id_solicitud_prestamo"; 
        $resultSoli = $db->getCondicionesDesc($columnas, $tablas, $where, $id);        
        
        if( !( empty( $resultSoli ) ) )
        {
            $tipo_pago  = $resultSoli[0]->tipo_pago_cuenta_bancaria;
            
            if( $tipo_pago == "Depósito")
            {   
                $tipo_pago_selecionado = "transferencia";
                
                $nombre_banco = $resultSoli[0]->nombre_banco_cuenta_bancaria;
                
                foreach( $rsBancos as $res )
                {
                    if( $res->nombre_bancos == $nombre_banco ){
                        $id_banco_seleccionado = $res->id_bancos;
                    }
                }
                $rsDatosBancos  = $rsBancos;
                
                foreach( $rsDatosTipoCuenta as $res)
                {
                    if( $res->nombre_tipo_cuentas == "AHORROS" )
                    {
                        $valor_tipo_cuenta_selecionada  = $res->id_tipo_cuentas;
                    }
                }
                
                $numero_cuenta_selecionada  = $resultSoli[0]->numero_cuenta_cuenta_bancaria;
                
                $rsValidadores['id_bancos'] = $id_banco_seleccionado;
                $rsValidadores['id_tipo_cuentas']   = $valor_tipo_cuenta_selecionada;
                $rsValidadores['numero_cuenta'] = $numero_cuenta_selecionada;
               
            }else
            {
                $tipo_pago_selecionado  = "cheque";
            }
            
            $response['estatus']    = "OK";
            $response['databancos'] = $rsDatosBancos;
            $response['datatipocuentas']    = $rsDatosTipoCuenta;
            $response['validadores']    = $rsValidadores;
            $response['tipo_pago']  = $tipo_pago_selecionado;            
                        
            $errores_cuentas = ob_get_clean();
            $errores_cuentas = trim($errores_cuentas);
        } else {
            $errores_cuentas = "NO SE PUDO CONSEGUIR LA INFO";
        }
        
        if( !empty( $errores_cuentas ) )
        {
            echo $errores_cuentas;
            echo "INFORMACION NO ENCONTRADA";
        }else
        {
            echo json_encode( $response );
        }       
       
    }
    
    public function ingresaCuentasBancariasSolicitud()
    {        
        try {
            
            require_once 'core/DB_Functions.php';
            $db = new DB_Functions(); //modelo para webcapremci  
            
            ob_start();
            
            $resp   = array();
            
            if( !isset( $_SESSION ) )
            {
                session_start();
            }
            
            $id_solicitud   = $_POST['id_solicitud'];
            $nombre_banco   = $_POST['nombre_bancos'];
            $nombre_tipo_cuentas    = $_POST['nombre_tipo_cuentas'];
            $numero_cuentas = $_POST["numero_cuentas"];
            //$tipo_pago  = "Depósito";
            
            if( !empty( error_get_last() ) )
            {
                throw new Exception("Variables no recibidas");
            }
            
            $colval = " nombre_banco_cuenta_bancaria = '$nombre_banco',
                tipo_cuenta_cuenta_bancaria = '$nombre_tipo_cuentas',
                numero_cuenta_cuenta_bancaria = '$numero_cuentas'";
            $tabla = " public.solicitud_prestamo";
            $where = " id_solicitud_prestamo =" . $id_solicitud;
            $db->ActualizarBy($colval, $tabla, $where);
                        
            if( !empty( error_get_last() ) )
            {
                throw new Exception("Error al actualizar Datos en la solicitud");
            }
            
            $resp['estatus'] = "OK";
            $resp['respuesta']  = 1;
            
            $salida = ob_get_clean();
            
            if( !empty( $salida ) )
            {
                throw new Exception("Buffer lleno");
            }
            
            echo json_encode( $resp );
            
        } catch (Exception $e) {
            
            echo $e->getMessage();
            print_r( error_get_last() );
        }        
    }
    
    /**
     * 2020/05/08
     **/
    public function InformacionCrediticiaParticipe()
    {
        session_start();
        $creditos = new ParticipesModel();
        $response = array();
        
        try {
            ob_start();
            $cedula_participes = $_POST['cedula_participe'];
            $id_participes = $_POST['id_participes'];
            $id_solicitud  = $_POST['id_solicitud'];
            
            if (! empty(error_get_last())) {
                throw new Exception('Variables no definidas');
            }
            
            $fechasValidacion = $this->getFechasUltimas3Cuotas();
                        
            //PARA PRUEBAS TEMPORAL --cambiar fecha inicio y final
            //2019-12-01 --estos valores cambiar a su arbitrariedad
            //2020-02-28
            //$fecha_inicio = '2020-04-01';
            //$fecha_fin = '2020-06-30';
            
            $saldo_credito = 0;
            $saldo_cta_individual = 0;   
            
            #APORTES obtener objeto Resulset de aportes Participes
            $aportes    = $this->obtenerAportesParticipes($cedula_participes, $fechasValidacion);
            $num_aporte = sizeof($aportes);
            
            #CONSULTAR cuenta individual de participe
            $saldo_cta_individual   = $this->obtenerCuentaIndividual($cedula_participes);
            
            #BUSCAR tipo de creditos con sus respectivos saldos
            $solicitudDatos = $this->obtenerDatosSolicitud($id_solicitud);
            if( !empty($solicitudDatos) )
            {
                $nombre_tipo_credito    = $solicitudDatos[0]->nombre_tipo_creditos;
                $sqlTipoCredito = "SELECT codigo_tipo_creditos FROM core_tipo_creditos WHERE UPPER(nombre_tipo_creditos) LIKE '%$nombre_tipo_credito%'";
                
                $rsTipoCredito  = $creditos->enviaquery($sqlTipoCredito);
                
                $codigo_tipo_creditos   = $rsTipoCredito[0]->codigo_tipo_creditos;
                
                $fecha_hoy  = date('Y-m-d');
                $colfun1    = " id_creditos_out, suma_capital_out, suma_seguro_out, suma_total_out";
                $tabfun1    = " cre_obtener_saldos_renovacion_creditos('$codigo_tipo_creditos','$fecha_hoy',$id_participes) ";
                $whefun1    = " 1 = 1";
                $rsConsulta1    = $creditos->getCondicionesSinOrden($colfun1, $tabfun1, $whefun1, "" );
                
                foreach ( $rsConsulta1 as $res )
                {
                    $saldo_credito   += $res->suma_total_out;
                }
                
            }
            
                        
            if ($saldo_cta_individual > 0 && $saldo_credito > 0) {
                
                if ($saldo_cta_individual > $saldo_credito) {
                    
                    $disponible = $saldo_cta_individual - $saldo_credito;
                } else {
                    
                    $disponible = 0.00;
                }
            } else if ($saldo_cta_individual == 0.00 && $saldo_credito > 0) {
                
                $disponible = 0.00;
            } else if ($saldo_cta_individual > 0 && $saldo_credito == 0.00) {
                
                $disponible = $saldo_cta_individual;
            } else {
                
                $disponible = 0.00;
            }
            
            $saldo_cta_individual = number_format((float) $saldo_cta_individual, 2, '.', '');
            $disponible = number_format((float) $disponible, 2, '.', '');
            
            $columnas = "id_participes, nombre_participes, apellido_participes, cedula_participes, fecha_nacimiento_participes";
            $tablas = "public.core_participes";
            $where = " id_estatus = 1 AND cedula_participes='" . $cedula_participes . "'";
            $id = "id_participes";
            
            $infoParticipe = $creditos->getCondiciones($columnas, $tablas, $where, $id);
            
            // VERIFICO LA EDAD DEL PARTICIPE
            $hoy = date("Y-m-d");
            $tiempo = $this->dateDifference($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
            $dias_hasta = $this->dateDifference1($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
            $dias_75 = 365 * 75;
            $diferencia_dias = $dias_75 - $dias_hasta;
            $diferencia_dias = $diferencia_dias / 30;
            $diferencia_dias = floor($diferencia_dias * 1) / 1;
            $edad = explode(",", $tiempo);
            $edad = $edad[0];
            $edad = explode(" ", $edad);
            $edad = $edad[0];
                        
            // valores para ser procesados en vista
            $data = array();
            
            $data['estado_solicitud']   = false;
            $data['mensaje_solicitud']  = "";
            $data['nombre_participe_credito']   = $infoParticipe[0]->nombre_participes . ' ' . $infoParticipe[0]->apellido_participes;
            $data['cedula_credito']     = $infoParticipe[0]->cedula_participes;
            $data['cuenta_individual']  = $saldo_cta_individual;
            $data['capital_creditos']   = $saldo_credito;
            $data['liquido_recibir']    = $disponible;
            $data['aportes_participe']  = $num_aporte;
            
            // validacion para ver si puede acceder al credito
            $observacion = "";
            
            # 2020/09/16 if( $disponible >= 150 && $edad >= 18 && $edad < 75 && $num_aporte == 3)
            if( $disponible >= 0 && $edad >= 18 && $edad < 75 && $num_aporte == 3)
            {
                $solicitud = "bg-olive";
                $data['estado_solicitud'] = true;
                
                $valor_permitido    = $saldo_cta_individual -  round( fmod($saldo_cta_individual, 10 ), 2);
                if( $saldo_credito > $valor_permitido )
                {
                    $observacion .= "Saldo de créditos supera valor permitido" ;
                    $solicitud = "bg-red";
                    $data['estado_solicitud'] = true;
                }
                
            }else
            {
                $data['estado_solicitud'] = false;
                $solicitud = "bg-red";
                
                if ($num_aporte < 3) {
                    
                    #validamos si hay reafiliacion
                    if( $this->validarReAfiliacion( $infoParticipe[0]->id_participes ) ){
                        $observacion .= "Partícipe en proceso de Reafiliación.";
                        $solicitud = "bg-orange";
                        $data['estado_solicitud'] = true;
                    }else{
                        $observacion .= "El participe no tiene los 3 últimos aportes pagados.";
                    }
                    
                }
                if( $edad <= 18 || $edad >= 75){
                    $observacion .= "Revisar edad Participe";
                }
            }
            
            $html = '<div id="info_participe_solicitud" class="row small-box bg-olive">
                    <h3 class="titulo">Antecedentes Participe</h3>
                    <div class="col-md-6 col-lg-6">
                    <div class="box-footer no-padding bg-olive">
                        <div class="bio-row"><p>' . $data['nombre_participe_credito'] . '</p></div>
                        <div class="bio-row"><p><span class="tab2">Identificaci&oacute;n</span>: ' . $data['cedula_credito'] . '</p></div>
                        <div class="bio-row"><p><span class="tab2">Fecha Nacimiento</span>: ' . $infoParticipe[0]->fecha_nacimiento_participes . '</p></div>
                        <div class="bio-row"><p><span class="tab2">Edad</span>: ' . $tiempo . '</p></div>
                        <div class="bio-row"><p><span class="tab2">Cuenta Individual  </span>: ' . $saldo_cta_individual . '</p></div>
                        <div class="bio-row"><p><span class="tab2">Capital de créditos </span>: ' . $saldo_credito . '</p></div>
                        <div class="bio-row"><p><span class="tab2">Disponible </span>: ' . $disponible . '</p></div>
                        <div class="bio-row ' . $solicitud . '"><p><span class="tab2">Observaci&oacute;n</span>: ' . $observacion . '</p></div>
                    </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                    <div id="div_info_garante"></div>
                    <div id="div_info_credito_renovar"></div>
                    </div>
                 </div>';
            
            $salida = ob_get_clean();
            $salida = trim($salida);
            if (! empty($salida)) {
                throw new Exception("");
            }
            
            $response['html'] = $html;
            $response['data'] = $data;
        } catch (Exception $e) {
            
            $response['html'] = "<span class= \" badge badge-warning \" >Error al  cargar la informaci&oacute;n Antecedes del Participe</span>";
            $response['estatus'] = "ERROR";
            $response['mensaje'] = "Error al cargar informacion de creditos";
            $response['buffer'] = error_get_last();
        }
        
        echo json_encode($response);
    }
    
    /** dc 2020/09/18 **/
    public function obtenerDatosSolicitud( $id_solicitud )
    {
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
                
        $columnas = " solicitud_prestamo.destino_dinero_datos_prestamo,
					  solicitud_prestamo.nombre_banco_cuenta_bancaria,
					  solicitud_prestamo.tipo_cuenta_cuenta_bancaria,
					  solicitud_prestamo.numero_cuenta_cuenta_bancaria,
					  solicitud_prestamo.tipo_pago_cuenta_bancaria,
				      tipo_creditos.nombre_tipo_creditos";        
        $tablas = "public.solicitud_prestamo 
            INNER JOIN public.tipo_creditos ON solicitud_prestamo.id_tipo_creditos=tipo_creditos.id_tipo_creditos";        
        $where = "solicitud_prestamo.id_solicitud_prestamo=" . $id_solicitud;
        
        $resultSet = $db->getCondiciones($columnas, $tablas, $where);
        
        return $resultSet ?? null;
    }
    /** end dc 2020/09/18 **/
        
    /**
     * dc 2020/05/08 *
     */
    public function obtenerTipoCredito()
    {
        session_start();
        ob_start();
        $response = array();
        $rp_capremci = new PlanCuentasModel();
        $columnas = "core_tipo_creditos.id_tipo_creditos, core_tipo_creditos.codigo_tipo_creditos, core_tipo_creditos.nombre_tipo_creditos";
        $tablas = "core_tipo_creditos INNER JOIN estado
                ON core_tipo_creditos.id_estado = estado.id_estado";
        $where = "core_tipo_creditos.id_estatus=1 AND estado.nombre_estado='ACTIVO'";
        $id = "core_tipo_creditos.id_tipo_creditos";
        $resultSet = $rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
        
        $salida = ob_get_clean();
        
        if (! empty($salida)) {
            $response['estatus'] = "ERROR";
            $response['mensaje'] = "ERROR AL BUSCAR TIPO CREDITOS.(PRODUCTOS)";
            $response['buffer'] = $salida;
        } else {
            $response['estatus'] = "OK";
            $response['mensaje'] = "";
            $response['data'] = $resultSet;
        }
        echo json_encode($response);
    }
    
    /**
     * dc 2020/05/08 *
     */
    public function getTipoCredito()
    {
        session_start();
        $rp_capremci = new PlanCuentasModel();
        $columnas = "core_tipo_creditos.id_tipo_creditos, core_tipo_creditos.codigo_tipo_creditos, core_tipo_creditos.nombre_tipo_creditos";
        $tablas = "core_tipo_creditos INNER JOIN estado
                ON core_tipo_creditos.id_estado = estado.id_estado";
        $where = "core_tipo_creditos.id_estatus=1 AND estado.nombre_estado='ACTIVO'";
        $id = "core_tipo_creditos.id_tipo_creditos";
        $resultSet = $rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
        
        $html = '<label for="tipo_credito" class="control-label">Tipo Crédito:</label>
        <select name="tipo_credito" id="tipo_credito"  class="form-control" onchange="TipoCredito()">
        <option value="" selected="selected">--Seleccione--</option>';
        foreach ($resultSet as $res) {
            $html .= '<option value="' . $res->codigo_tipo_creditos . '" >' . $res->nombre_tipo_creditos . '</option>';
        }
        
        $html .= '</select>';
        
        echo $html;
    }
    
    
    
    // METODO PARA DEVOLVER LOS CREDITOS PARA RENOVAR
    public function GetInfoCreditoRenovar()
    {
        session_start();
        $id_participe = $_POST['id_participe'];
        $tipo_credito = $_POST['tipo_creditos'];
        
        $rp_capremci = new ParticipesModel();
        $total = 0.00;
        $html = '
        <table width="100%">
        <tr>
        <th colspan="4" style="text-align:center">CREDITOS A RENOVAR</th>
        </tr>
        <tr>
        <th >№ DE PRESTAMO</th>
        <th >FECHA DE PRESTAMO</th>
        <th>TIPO CREDITO</th>
        <th >SALDO CAPITAL</th>
        </tr>';
        
        $columnas = "id_tipo_creditos_a_renovar";
        $tablas = "core_tipo_creditos_renovacion INNER JOIN core_tipo_creditos
        ON core_tipo_creditos.id_tipo_creditos = core_tipo_creditos_renovacion.id_tipo_creditos";
        $where = "codigo_tipo_creditos='" . $tipo_credito . "' AND core_tipo_creditos_renovacion.id_estado=107";
        $id_creditos_renovar = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        foreach ($id_creditos_renovar as $res) {
            $columnas = 'core_creditos.id_creditos,core_creditos.numero_creditos, core_creditos.fecha_concesion_creditos,
            		core_tipo_creditos.nombre_tipo_creditos, core_creditos.monto_otorgado_creditos,
            		core_creditos.saldo_actual_creditos, core_creditos.interes_creditos,
            		core_estado_creditos.nombre_estado_creditos';
            $tablas = 'public.core_creditos INNER JOIN public.core_tipo_creditos
        		ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos
        		INNER JOIN public.core_estado_creditos
        		ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos';
            
            $where = "core_creditos.id_participes=" . $id_participe . " AND core_creditos.id_estatus=1 AND core_estado_creditos.nombre_estado_creditos='Activo'
                    AND core_creditos.id_tipo_creditos=" . $res->id_tipo_creditos_a_renovar;
            
            $id_credito = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
            
            foreach ($id_credito as $res1) {
                $total += $res1->saldo_actual_creditos;
                $saldo = number_format((float) $res1->saldo_actual_creditos, 2, '.', '');
                $html .= '<tr>
                 <td >' . $res1->numero_creditos . '</font></td>
                 <td >' . $res1->fecha_concesion_creditos . '</font></td>
                 <td>' . $res1->nombre_tipo_creditos . '</td>
                <td align="right" id="saldo_credito_a_renovar">' . $saldo . '</font></td>
                </tr>';
            }
        }
        
        $total = number_format((float) $total, 2, '.', '');
        $html .= '<tr>
        <th ></th>
        <th ></th>
        <th >Total:</th>
        <td align="right" id="total_saldo_renovar">' . $total . '</td>
        </tr>';
        
        $html .= '</table>';
        
        echo $html;
    }
    
    public function CreditosActivosParticipeRenovacion()
    {
        session_start();
        $id_participe = $_POST['id_participe'];
        $html = "";
        $participes = new ParticipesModel();
        $total = 0;
        
        $columnas = "core_creditos.id_creditos,core_creditos.numero_creditos, core_creditos.fecha_concesion_creditos,
            		core_tipo_creditos.nombre_tipo_creditos, core_creditos.monto_otorgado_creditos,
            		core_creditos.saldo_actual_creditos, core_creditos.interes_creditos,
            		core_estado_creditos.nombre_estado_creditos";
        $tablas = "public.core_creditos INNER JOIN public.core_tipo_creditos
        		ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos
        		INNER JOIN public.core_estado_creditos
        		ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos";
        $where = "core_creditos.id_participes=" . $id_participe . " AND core_creditos.id_estatus=1 AND core_estado_creditos.nombre_estado_creditos='Activo'";
        $id = "core_creditos.fecha_concesion_creditos";
        
        $resultCreditos = $participes->getCondiciones($columnas, $tablas, $where, $id);
        if (! (empty($resultCreditos))) {
            
            $page = (isset($_REQUEST['page']) && ! empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
            $resultSet = $participes->getCantidad("*", $tablas, $where);
            $cantidadResult = (int) $resultSet[0]->total;
            $per_page = 20; // la cantidad de registros que desea mostrar
            $adjacents = 9; // brecha entre páginas después de varios adyacentes
            $offset = ($page - 1) * $per_page;
            $limit = " LIMIT   '$per_page' OFFSET '$offset'";
            $resultCreditos = $participes->getCondicionesPag($columnas, $tablas, $where, $id, $limit);
            $last = sizeof($resultCreditos);
            
            $total_pages = ceil($cantidadResult / $per_page);
            
            $html = '<div class="box box-solid bg-light-blue-active">
            <div class="box-header with-border">
            <h3 class="box-title">Historial Prestamos</h3>
            </div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-light-blue-active">
                        <th width="2%">№</th>
                        <th width="4%">№ DE PRESTAMO</th>
                        <th width="15%">FECHA DE PRESTAMO</th>
                        <th width="15%">TIPO DE PRESTAMO</th>
                        <th width="14%">MONTO</th>
                        <th width="14%">SALDO CAPITAL</th>
                        <th width="14%">SALDO INTERES</th>
                        <th width="14%">ESTADO</th>
                        <th width="4%"></th>
                        <th width="2%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
            for ($i = $last - 1; $i >= 0; $i --) {
                $index = ($i + ($last - 1) * ($page - 1)) + 1;
                $monto = number_format((float) $resultCreditos[$i]->monto_otorgado_creditos, 2, ',', '.');
                $saldo = number_format((float) $resultCreditos[$i]->saldo_actual_creditos, 2, ',', '.');
                $saldo_int = number_format((float) $resultCreditos[$i]->interes_creditos, 2, ',', '.');
                $html .= '<tr>
                        <td bgcolor="white" width="2%"><font color="black">' . $index . '</font></td>
                         <td bgcolor="white" width="8%"><font color="black">' . $resultCreditos[$i]->numero_creditos . '</font></td>
                         <td bgcolor="white" width="15%"><font color="black">' . $resultCreditos[$i]->fecha_concesion_creditos . '</font></td>
                        <td bgcolor="white" width="15%"><font color="black">' . $resultCreditos[$i]->nombre_tipo_creditos . '</font></td>
                        <td bgcolor="white" width="14%"><font color="black">' . $monto . '</font></td>
                        <td bgcolor="white" width="14%"><font color="black">' . $saldo . '</font></td>
                        <td bgcolor="white" width="14%"><font color="black">' . $saldo_int . '</font></td>
                        <td bgcolor="white" width="14%"><font color="black">' . $resultCreditos[$i]->nombre_estado_creditos . '</font></td>
                        <td bgcolor="white" width="3%"><font color="black">';
                
                $hoy = date("Y-m-d");
                $columnas = "id_estado_tabla_amortizacion";
                $tablas = "core_tabla_amortizacion INNER JOIN core_creditos
                        ON core_tabla_amortizacion.id_creditos = core_creditos.id_creditos
                        INNER JOIN core_estado_creditos
                        ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos";
                $where = "core_tabla_amortizacion.id_creditos=" . $resultCreditos[$i]->id_creditos . " AND core_tabla_amortizacion.id_estatus=1 AND fecha_tabla_amortizacion BETWEEN '" . $resultCreditos[$i]->fecha_concesion_creditos . "' AND '" . $hoy . "'
                        AND nombre_estado_creditos='Activo'";
                $resultCreditosActivos = $participes->getCondicionesSinOrden($columnas, $tablas, $where, "");
                if (! (empty($resultCreditosActivos))) {
                    $cuotas_pagadas = sizeof($resultCreditosActivos);
                    $mora = false;
                    foreach ($resultCreditosActivos as $res) {
                        if ($res->id_estado_tabla_amortizacion != 2)
                            $mora = true;
                    }
                    if ($cuotas_pagadas >= 6 /*&& $mora==false*/)
                    {
                        $html .= '<button type="button" class="btn bg-light-blue-active" title="Seleccionar crédito" onclick="SeleccionarCreditoRenovacion(' . $resultCreditos[$i]->id_creditos . ')">';
                        $html .= '<i class="fa  fa-check"></i>';
                        $html .= '</button>';
                    } else {
                        $html .= '<button type="button" class="btn bg-light-blue-active" title="Crédito no elegible" disabled>';
                        $html .= '<i class="fa  fa-close"></i>';
                        $html .= '</button>';
                    }
                }
                
                /*
                 * $hoy=date("Y-m-d");
                 * $columnas="id_estado_tabla_amortizacion";
                 * $tablas="core_tabla_amortizacion INNER JOIN core_creditos
                 * ON core_tabla_amortizacion.id_creditos = core_creditos.id_creditos
                 * INNER JOIN core_estado_creditos
                 * ON core_creditos.id_estado_creditos = core_estado_creditos.id_estado_creditos";
                 * $where="core_tabla_amortizacion.id_creditos=".$resultCreditos[$i]->id_creditos." AND core_tabla_amortizacion.id_estatus=1 AND fecha_tabla_amortizacion BETWEEN '".$resultCreditos[$i]->fecha_concesion_creditos."' AND '".$hoy."'
                 * AND nombre_estado_creditos='Activo'";
                 * $resultCreditosActivos=$participes->getCondicionesSinOrden($columnas, $tablas, $where, "");
                 * if(!(empty($resultCreditosActivos)))
                 * {
                 * $cuotas_pagadas=sizeof($resultCreditosActivos);
                 * $mora=false;
                 * foreach ($resultCreditosActivos as $res)
                 * {
                 * if ($res->id_estado_tabla_amortizacion!=2) $mora=true;
                 * }
                 * if($cuotas_pagadas>=6 && $mora==false)
                 * {
                 * $html.='<tr height = "25">';
                 * $html.='<td><button class="btn bg-olive" title="Renovación de crédito" onclick="RenovacionCredito()"><i class="glyphicon glyphicon-refresh"></i></button></td>';
                 * $html.='</tr>';
                 * }
                 *
                 * }
                 * $html.='</tbody>';
                 * $html.='</table>';
                 * $html.='</li>';
                 */
                 
                 $html .= '</td>
                        </tr>';
            }
            $total = number_format((float) $total, 2, ',', '.');
            $html .= '</table>
                   </div>';
            $html .= '<div class="table-pagination pull-right">';
            $html .= '' . $this->paginate_creditos("index.php", $page, $total_pages, $adjacents, $id_participe, "CreditosActivosParticipe") . '';
            $html .= '</div>
                    </div>';
            
            echo $html;
        } else {
            $html .= '<div class="alert alert-warning alert-dismissable bg-light-blue-active" style="margin-top:40px;">';
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html .= '<h4>Aviso!!!</h4> <b>El participe no tiene creditos activos</b>';
            $html .= '</div>';
            echo $html;
        }
    }
    
    // no habilitado
    public function Creditos_No_Renuevan()
    {
        session_start();
        $creditos = new ParticipesModel();
        $id_participe = $_POST['cedula_participe'];
        $tipo_credito = $_POST['tipo_credito'];
        $saldo_cta_individual = 0;
        $saldo_credito_renovar_diferente = 0;
        
        // AQUI OBTENGO TOTAL DE CUENTA INDIVIDUAL
        $columnas = "COALESCE(SUM(valor_personal_contribucion),0)+COALESCE(SUM(valor_patronal_contribucion),0) AS total";
        $tablas = "core_contribucion INNER JOIN core_participes
            ON core_contribucion.id_participes  = core_participes.id_participes";
        $where = "core_participes.cedula_participes='" . $id_participe . "' AND core_contribucion.id_estatus=1";
        $totalCtaIndividual = $creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        // capturo los saldos de las consultas
        $saldo_cta_individual = $totalCtaIndividual[0]->total;
        // $saldo_cta_individual=number_format((float)$saldo_cta_individual, 2, '.', '');
        
        // SALDO DE CREDITOS QUE NO SE RENUEVAN
        $columnas = "COALESCE(SUM(c.saldo_actual_creditos),0) AS total";
        $tablas = "core_creditos c inner join core_participes p ON c.id_participes  = p.id_participes
            inner join core_tipo_creditos ct on c.id_tipo_creditos=ct.id_tipo_creditos";
        $where = "p.cedula_participes='" . $id_participe . "' AND c.id_estatus=1 AND c.id_estado_creditos=4 and ct.codigo_tipo_creditos not in ('" . $tipo_credito . "')";
        $_result_creditos_renovar = $creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        $saldo_credito_renovar_diferente = $_result_creditos_renovar[0]->total;
        
        $saldo_credito_renovar_diferente = $saldo_cta_individual - $saldo_credito_renovar_diferente;
        $saldo_credito_renovar_diferente = number_format((float) $saldo_credito_renovar_diferente, 2, '.', '');
        
        echo $saldo_credito_renovar_diferente;
    }
    
    /**
     * *dc 2020/05/12 *
     */
    public function ObtenerInformacionGarante()
    {
        session_start();
        ob_start();
        $creditos = new ParticipesModel();
        $cedula_garante = $_POST['cedula_garante'];
        
        $saldo_credito = 0;
        $saldo_cta_individual = 0;
        
        $fechasValidacion = $this->getFechasUltimas3Cuotas();
        
        // PARA PRUEBAS TEMPORAL --cambiar fecha inicio y final dc 2020/05/12 DCTEMP
        // $fechasValidacion    = array('desde'=>'2020-06-01', 'hasta'=>'2020-08-31');
            
        #CONSULTA cuenta individula garante
        $saldo_cta_individual   = $this->obtenerCuentaIndividual($cedula_garante);
        
        #CONSULTA un objeto resulset para obtener aportes 
        $aportes = $this->obtenerAportesParticipes($cedula_garante, $fechasValidacion);
        $num_aporte = sizeof($aportes);
                
        // AQUI PONER ATENCION AL FINAL saldo_actual_creditos
        $columnas = "COALESCE(SUM(saldo_actual_creditos),0) AS total";
        $tablas = "core_creditos INNER JOIN core_participes
            ON core_creditos.id_participes  = core_participes.id_participes";
        $where = "core_participes.cedula_participes='" . $cedula_garante . "' AND core_creditos.id_estatus=1 AND core_creditos.id_estado_creditos=4";
        $saldo_actual_credito = $creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
               
        $saldo_credito = $saldo_actual_credito[0]->total;
        
        // variable para enviar a la vista
        $response = array();
        
        if ($saldo_cta_individual > 0 && $saldo_credito > 0) {
            
            if ($saldo_cta_individual > $saldo_credito) {
                
                $disponible = $saldo_cta_individual - $saldo_credito;
            } else {
                
                $disponible = 0.00;
            }
        } else if ($saldo_cta_individual == 0.00 && $saldo_credito > 0) {
            
            $disponible = 0.00;
        } else if ($saldo_cta_individual > 0 && $saldo_credito == 0.00) {
            
            $disponible = $saldo_cta_individual;
        } else {
            
            $disponible = 0.00;
        }
        
        $saldo_cta_individual = number_format((float) $saldo_cta_individual, 2, '.', '');
        $disponible = number_format((float) $disponible, 2, '.', '');
        
        $columnas = "  core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
        $tablas = "public.core_participes";
        $where = "core_participes.cedula_participes='" . $cedula_garante . "' AND core_participes.id_estado_participes=1";
        $id = "core_participes.id_participes";
        
        $infoParticipe = $creditos->getCondiciones($columnas, $tablas, $where, $id);
        
        if ( !(empty( $infoParticipe )) ) {
            
            $columnas = "id_creditos_garantias";
            $tablas = "core_creditos_garantias aa
                INNER JOIN core_participes bb ON bb.id_participes = aa.id_participes
                INNER JOIN estado cc ON cc.id_estado = aa.id_estado";
            $where = "bb.id_estatus = 1 AND bb.cedula_participes ='" . $cedula_garante . "' AND cc.nombre_estado='ACTIVO'";
            $id = "aa.id_creditos_garantias";
            
            $Garantias = $creditos->getCondiciones($columnas, $tablas, $where, $id);
            
            if(empty($Garantias))
            {
                $saldo_cta_individual = number_format((float) $saldo_cta_individual, 2, '.', '');
                $hoy = date("Y-m-d");
                
                $tiempo = $this->dateDifference($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
                $edad = explode(",", $tiempo);
                $edad = $edad[0];
                $edad = explode(" ", $edad);
                $edad = $edad[0];
                
                $nombre_completo = $infoParticipe[0]->nombre_participes . ' ' . $infoParticipe[0]->apellido_participes;
                $identificacion = $infoParticipe[0]->cedula_participes;
                $fecha_nacimiento = $infoParticipe[0]->fecha_nacimiento_participes;
                $observacion = "";
                $estado_garante = true;
                $solicitud = "bg-olive";
                if ($num_aporte < 3)
                {
                    $observacion .= "Garante no tiene los 3 últimos aportes pagados.";
                    $estado_garante = false;
                    $solicitud = "bg-red";
                }
                if( empty($disponible))
                {
                    $observacion .= " Garante no dispone de valores como garantias.";
                    $estado_garante = false;
                    $solicitud = "bg-red";
                }
                
                $html = '<div >
                    <div class="box-footer no-padding bg-olive">
                        <div class="bio-row"><p>' . $nombre_completo . ' ==> (GARANTE)</p></div>
                        <div class="bio-row"><p><span class="tab2">Identificaci&oacute;n</span>: ' . $identificacion . '</p></div>
                        <div class="bio-row"><p><span class="tab2">Fecha Nacimiento </span>: ' . $fecha_nacimiento . '</p></div>
                        <div class="bio-row"><p><span class="tab2">Edad </span>: ' . $tiempo . '</p></div>
                        <div class="bio-row"><p><span class="tab2">Cta. Individual </span>: ' . $saldo_cta_individual . '</p></div>
                        <div class="bio-row"><p><span class="tab2">Capital de créditos </span>: ' . $saldo_credito . '</p></div>
                        <div class="bio-row"><p><span class="tab2">Disponible </span>: ' . $disponible . '</p></div>
                        <div class="bio-row ' . $solicitud . '"><p><span class="tab2">Observaci&oacute;n </span>: ' . $observacion . '</p></div>
                    </div>
                    <div class="btn-contenedor">
                        <button class="btn-flotante" id="btn_cambiar_garante" title="Quitar garante" >
                          <span class="flotante">&times;</span>
                        </button>
                    </div>
                 </div>';
                
                $data = array();
                
                $data['estado_garante'] = $estado_garante;
                $data['edad'] = $edad;
                $data['cuenta_individual'] = $saldo_cta_individual;
                $data['saldo_creditos'] = $saldo_credito;
                $data['disponible_garante'] = $disponible;
                $data['mensaje_garante'] = $observacion;
                $data['edad_completa'] = $tiempo;
                $data['aporte_garante'] = $num_aporte;
                
                $response['html'] = $html;
                $response['estatus'] = "OK";
                $response['data'] = $data;
                
            } else {
                $response['estatus'] = "ERROR";
                $response['html'] = "<span>Garante no disponible</span>";
            }
        } else {
            $response['estatus'] = "ERROR";
            $response['html'] = "<span>Participe no encontrado</span>";
        }
        
        $salida = ob_get_clean();
        if (! empty($salida)) {
            $response['estatus'] = "ERROR";
            $response['html'] = "<span>Error al buscar el participe </span>";
        }
        
        echo json_encode($response);
    }
    
    /**
     * dc 2020/05/12 *
     */
    public function obtenerCuotas()
    {
        ob_start();
        session_start();
        $cuotas = new EstadoModel();
        $monto_credito = $_POST['monto_credito'];
        $cedula_participes = $_POST['cedula_participe'];
        $sueldo_partcipe = $_POST['sueldo_participe'];
        $tipo_credito = $_POST['tipo_credito'];
        
        // traigo informacion del participe para veriricar la edad
        $columnas = "   core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
        $tablas = "public.core_participes";
        
        $where = "core_participes.cedula_participes='" . $cedula_participes . "'";
        
        $id = "core_participes.id_participes";
        
        $infoParticipe = $cuotas->getCondiciones($columnas, $tablas, $where, $id);
        
        // consigo la edad del participe
        $hoy = date("Y-m-d");
        $dias_hasta = $this->dateDifference1($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
        $dias_75 = 365 * 75;
        $diferencia_dias = $dias_75 - $dias_hasta;
        $diferencia_dias = $diferencia_dias / 30;
        $diferencia_dias = floor($diferencia_dias * 1) / 1;
        
        // consigo la tasa de interes y plazo maximo del credito seleccionado
        $columnas = "interes_tipo_creditos, plazo_maximo_tipo_creditos";
        $tablas = "core_tipo_creditos";
        $where = "codigo_tipo_creditos='" . $tipo_credito . "'";
        $resultSet = $cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        $plazo_maximo_tipo_creditos = $resultSet[0]->plazo_maximo_tipo_creditos;
        
        // calculo interes mensual
        $tasa_interes = $resultSet[0]->interes_tipo_creditos;
        $tasa_interes = $tasa_interes / 100;
        $interes_mensual = $tasa_interes / 12;
        
        // sueldo del participe
        //$sueldo_partcipe = $sueldo_partcipe / 2; // ? porq divide entre 2 2020-06-15 // valor no divode ya viene de la vista
        
        // obtengo plazo maximo y minimo dependiendo del monto del credito
        $columnas = "cuotas_rango_plazos_creditos";
        $tablas = "public.core_plazos_creditos";
        $where = $monto_credito . ">=minimo_rango_plazos_creditos AND " . $monto_credito . " <= maximo_rango_plazos_creditos";
        $id = "core_plazos_creditos.id_plazos_creditos";
        $resultSet = $cuotas->getCondiciones($columnas, $tablas, $where, $id);
        
        // extraigo cuota maxima para el monto del credito
        $plazo_maximo = $resultSet[0]->cuotas_rango_plazos_creditos;
        
        if ($plazo_maximo > $plazo_maximo_tipo_creditos) {
            
            $plazo_maximo = $plazo_maximo_tipo_creditos;
        }
        
        // capturo la cuota para pagar mensualmente del nuevo credito solicitado
        $valor_cuota = ($monto_credito * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $plazo_maximo));
        $valor_cuota = round($valor_cuota, 2);
        
        // echo $valor_cuota;
        
        // verifico que la cuota a pagar es mayor al sueldo mensual del participe
        // verifico que el plazo maximo a dar el credito no sobrepase los 75 años del participe
        
        // echo $diferencia_dias;
        
        // if($valor_cuota>$sueldo_partcipe || $plazo_maximo>$diferencia_dias){
        
        while ($valor_cuota > $sueldo_partcipe || $plazo_maximo > $diferencia_dias) {
            
            $monto_credito -= 10;
            $where = $monto_credito . ">=minimo_rango_plazos_creditos AND " . $monto_credito . " <= maximo_rango_plazos_creditos";
            $resultSet = $cuotas->getCondiciones($columnas, $tablas, $where, $id);
            //!nota se establece que el minimo en el plazo es 3 -- si cambian cambiar aca  dc 2020/05/3/26
            $plazo_maximo = ( empty( $resultSet[0]->cuotas_rango_plazos_creditos ) ) ? 3 : $resultSet[0]->cuotas_rango_plazos_creditos ;
            
            $valor_cuota = ($monto_credito * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $plazo_maximo));
            $valor_cuota = round($valor_cuota, 2);
            
        }
        
        $data = array(); // variable donde guardo lo que envio a la vista
        
        for ( $plazo_maximo; $plazo_maximo >= 3; $plazo_maximo -= 3) {
            
            $valor_cuota = ($monto_credito * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $plazo_maximo));
            $valor_cuota = round($valor_cuota, 2);
            if ( $plazo_maximo <= $diferencia_dias && $valor_cuota <= $sueldo_partcipe) {
                
                $data[] = array('plazo'=>$plazo_maximo, 'valor'=>$valor_cuota); // $html.='<option value="'.$plazo_maximo.'">'.$plazo_maximo.'</option>';
            }
        }
        
        $response = array();
        $response['estatus'] = "OK";
        $response['cuotas'] = $data;
        $response['monto'] = $monto_credito;
        
        $salida = ob_get_clean();
        if (! empty($salida)) {
            $response = array();
            $response['estatus'] = "ERROR";
            $response['buffer'] = error_get_last();
            $response['mensaje'] = "ERROR al obtener cuotas disponibles";
            $response['salida'] = $salida;
        }
        
        echo json_encode($response);
    }
    
    
    /**
     * dc 2020/05/14 *
     */
    public function obtenerCuotasGarante()
    {
        ob_start();
        session_start();
        $cuotas = new EstadoModel();
        $monto_credito = $_POST['monto_credito'];
        $cedula_participes = $_POST['cedula_participe'];
        $cedula_garante = $_POST['cedula_garante'];
        $sueldo_partcipe = $_POST['sueldo_participe'];
        $sueldo_garante = $_POST['sueldo_garante'];
        $tipo_credito = $_POST['tipo_credito'];
        
        $columnas = "      core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
        $tablas = "public.core_participes";
        
        $where = "core_participes.cedula_participes='" . $cedula_participes . "'";
        
        $id = "core_participes.id_participes";
        
        $infoParticipe = $cuotas->getCondiciones($columnas, $tablas, $where, $id);
        
        $columnas = "      core_participes.nombre_participes,
                    core_participes.apellido_participes,
                    core_participes.cedula_participes,
                    core_participes.fecha_nacimiento_participes";
        $tablas = "public.core_participes";
        
        $where = "core_participes.cedula_participes='" . $cedula_garante . "' AND core_participes.id_estado_participes=1";
        
        $id = "core_participes.id_participes";
        
        $infoGarante = $cuotas->getCondiciones($columnas, $tablas, $where, $id);
        
        $hoy = date("Y-m-d");
        
        $dias_hasta = $this->dateDifference1($infoParticipe[0]->fecha_nacimiento_participes, $hoy);
        $dias_hasta_garante = $this->dateDifference1($infoGarante[0]->fecha_nacimiento_participes, $hoy);
        
        $dias_75 = 365 * 75;
        
        $diferencia_dias = $dias_75 - $dias_hasta;
        $diferencia_dias_garante = $dias_75 - $dias_hasta_garante;
        
        $diferencia_dias = $diferencia_dias / 30;
        $diferencia_dias_garante = $diferencia_dias_garante / 30;
        
        $diferencia_dias = floor($diferencia_dias * 1) / 1;
        $diferencia_dias_garante = floor($diferencia_dias_garante * 1) / 1;
        if ($diferencia_dias_garante < $diferencia_dias)
            $diferencia_dias = $diferencia_dias_garante;
            
            $columnas = "interes_tipo_creditos";
            $tablas = "core_tipo_creditos";
            $where = "codigo_tipo_creditos='" . $tipo_credito . "'";
            
            $resultSet = $cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
            $tasa_interes = $resultSet[0]->interes_tipo_creditos;
            $tasa_interes = $tasa_interes / 100;
            $interes_mensual = $tasa_interes / 12;
            
            $pago_garante = 1;
            
            //$sueldo_partcipe = $sueldo_partcipe / 2;  // dc2020-06-15 la capacidad de pago viene de la vista
            $sueldo_garante = $sueldo_garante / 2;
            
            $columnas = "cuotas_rango_plazos_creditos";
            $tablas = "public.core_plazos_creditos";
            $where = $monto_credito . ">=minimo_rango_plazos_creditos AND " . $monto_credito . " <= maximo_rango_plazos_creditos";
            $id = "core_plazos_creditos.id_plazos_creditos";
            $resultSet = $cuotas->getCondiciones($columnas, $tablas, $where, $id);
            $cuota = $resultSet[0]->cuotas_rango_plazos_creditos;
            
            $valor_cuota = ( $monto_credito * $interes_mensual ) / (1 - pow((1 + $interes_mensual), - $cuota));
            $valor_cuota = round($valor_cuota, 2);
            
            if ( $valor_cuota > $sueldo_partcipe || $cuota > $diferencia_dias) {
                
                //! nota dc 2020/05/26 el valor minimo de monto debe ser 100
                while( $valor_cuota > $sueldo_partcipe || $cuota > $diferencia_dias )  {
                    
                    $monto_credito -= 10;
                    $where = $monto_credito . " >= minimo_rango_plazos_creditos AND " . $monto_credito . " <= maximo_rango_plazos_creditos";
                    $resultSet = $cuotas->getCondiciones($columnas, $tablas, $where, $id);
                    // dc 2020/05/26 para validar que no llegue a cero numero minimo de plazo
                    $cuota = ( empty( $resultSet[0]->cuotas_rango_plazos_creditos ) ) ? 3 : $resultSet[0]->cuotas_rango_plazos_creditos ;
                    
                    $valor_cuota = ($monto_credito * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $cuota));
                    $valor_cuota = round($valor_cuota, 2);
                }
                
            }
            
            if ($valor_cuota > $sueldo_garante)
                $pago_garante = 0;
                
                $data = array(); // variable donde guarda las cuotas
                
                for( $cuota; $cuota >= 3; $cuota -= 3) {
                    
                    $valor_cuota = ($monto_credito * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $cuota));
                    $valor_cuota = round($valor_cuota, 2);
                    
                    if ($cuota <= $diferencia_dias && $valor_cuota <= $sueldo_partcipe)
                        $data[] = array( 'plazo'=>$cuota, 'valor'=>$valor_cuota);
                }
                
                $response = array();
                $response['estatus'] = "OK";
                $response['cuotas'] = $data;
                $response['monto'] = $monto_credito;
                $response['pago_garante'] = $pago_garante;
                
                $salida = ob_get_clean();
                if (! empty($salida)) {
                    $response['estatus'] = "ERROR";
                    $response['buffer'] = error_get_last();
                    $response['echo'] =$salida;
                }
                
                echo json_encode($response);
    }
    
    // genera tablas de amortizacion
    public function SimulacionCredito()
    {
        session_start();
        $cuotas = new PlanCuentasModel();
        $monto_credito = $_POST['monto_credito'];
        $id_solicitud = $_POST['id_solicitud'];
        $fecha_corte = date('Y-m-d');
        
        if ($id_solicitud == 0) {
            
            // para simulador
            $avaluo_bien = $_POST['avaluo_bien'];
        } else {
            // para produccion
            $avaluo_bien = 0;
        }
        
        $cuota = $_POST['plazo_credito'];
        $tipo_credito = $_POST['tipo_credito'];
        $renovacion_credito = $_POST['renovacion_credito'];
        
        if ($tipo_credito == "PH" && $id_solicitud != 0) {
            // producion hipotecario
            
            $columnas = "valor_avaluo_core_documentos_hipotecario";
            $tablas = "core_documentos_hipotecario";
            $where = "id_solicitud_credito=" . $id_solicitud;
            $avaluo_credito = $cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
            $avaluo_credito = $avaluo_credito[0]->valor_avaluo_core_documentos_hipotecario;
        } else {
            
            // simulador
            $avaluo_credito = $avaluo_bien;
        }
        
        // obtengo la taza de interes del credito seleccionado
        $columnas = "interes_tipo_creditos";
        $tablas = "core_tipo_creditos";
        $where = "codigo_tipo_creditos='" . $tipo_credito . "'";
        
        $resultSet = $cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $tasa_interes = $resultSet[0]->interes_tipo_creditos;
        $tasa_interes = $tasa_interes / 100;
        $interes_mensual = $tasa_interes / 12;
        
        $valor_cuota = ($monto_credito * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $cuota));
        $valor_cuota = round($valor_cuota, 2);
        
        if ($tipo_credito == "PH") {
            if ($renovacion_credito == "true") {
                $resultAmortizacion = $this->tablaAmortizacionRenovacionHipotecario($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes, $avaluo_credito);
            } else {
                $resultAmortizacion = $this->tablaAmortizacionHipotecario($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes, $avaluo_credito);
            }
        } else {
            if ($renovacion_credito == "true") {
                $resultAmortizacion = $this->tablaAmortizacionRenovacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes);
            } else {
                $resultAmortizacion = $this->tablaAmortizacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes);
            }
        }
        
        if ($tipo_credito == "PH") {
            $html = '<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Tabla de Amortización</h3>';
            if ($id_solicitud != 0)
                $html .= '<button class="btn btn-info pull-right" onclick="GuardarCredito()"><i class="glyphicon glyphicon-floppy-disk"></i> GUARDAR</button>';
                $html .= '</div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="5%">Cuota</th>
                        <th width="15%" >Fecha</th>
                        <th width="13%">Capital</th>
                        <th width="13%">Interes</th>
                        <th width="13%">Seg. Desgravamen</th>
                        <th width="13%">Seg. Incendio</th>
                        <th width="13%">Cuota</th>
                        <th width="13%">Saldo</th>
                        <th width="2%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
        } else {
            $html = '<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Tabla de Amortización</h3>';
            if ($id_solicitud != 0)
                $html .= '<button class="btn btn-info pull-right" onclick="GuardarCredito()"><i class="glyphicon glyphicon-floppy-disk"></i> GUARDAR</button>';
                $html .= '</div>
             <table border="1" width="100%">
                     <tr style="color:white;" class="bg-olive">
                        <th width="5%">Cuota</th>
                        <th width="18%" >Fecha</th>
                        <th width="15%">Capital</th>
                        <th width="15%">Interes</th>
                        <th width="15%">Seg. Desgravamen</th>
                        <th width="15%">Cuota</th>
                        <th width="15%">Saldo</th>
                        <th width="2%"></th>
                     </tr>
                   </table>
                   <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
        }
        
        $total = 0;
        $total1 = 0;
        $total_capital = 0;
        $total_desg = 0;
        $total_incendio = 0;
        
        foreach ($resultAmortizacion as $res) {
            
            $res['desgravamen'] = number_format((float) $res['desgravamen'], 2, ".", "");
            $total_desg += $res['desgravamen'];
            $res['interes'] = number_format((float) $res['interes'], 2, ".", "");
            $total += $res['interes'];
            $res['amortizacion'] = number_format((float) $res['amortizacion'], 2, ".", "");
            $total_capital += $res['amortizacion'];
            $res['pagos'] = number_format((float) $res['pagos'], 2, ".", "");
            $total1 += $res['pagos'];
            if ($tipo_credito == "PH") {
                $res['seguro_incendios'] = number_format((float) $res['seguro_incendios'], 2, ".", "");
                $total_incendio += $res['seguro_incendios'];
            }
        }
        $total = round($total, 2);
        $total1 = round($total1, 2);
        $num = $monto_credito - ($total1 - $total);
        $num = round($num, 2);
        $len = sizeof($resultAmortizacion);
        $res['amortizacion'] = round($res['amortizacion'], 2);
        $res['interes'] = round($res['interes'], 2);
        $res['pagos'] = round($res['pagos'], 2);
        
        $resultAmortizacion[$len - 1]['pagos'] = $resultAmortizacion[$len - 1]['pagos'] + $resultAmortizacion[$len - 1]['saldo_inicial'];
        $diferencia = ($resultAmortizacion[$len - 1]['pagos'] - $resultAmortizacion[$len - 1]['interes']);
        
        $resultAmortizacion[$len - 1]['amortizacion'] = $resultAmortizacion[$len - 1]['amortizacion'] + $resultAmortizacion[$len - 1]['saldo_inicial'];
        $resultAmortizacion[$len - 1]['saldo_inicial'] = 0.00;
        // $resultAmortizacion[$len-1]['interes']=$diferencia;
        
        $total = 0;
        $total1 = 0;
        $total_capital = 0;
        $total_desg = 0;
        $total_incendio = 0;
        foreach ($resultAmortizacion as $res) {
            
            $res['desgravamen'] = number_format((float) $res['desgravamen'], 2, ".", "");
            $total_desg += $res['desgravamen'];
            $res['interes'] = number_format((float) $res['interes'], 2, ".", "");
            $total += $res['interes'];
            $res['amortizacion'] = number_format((float) $res['amortizacion'], 2, ".", "");
            $total_capital += $res['amortizacion'];
            $res['pagos'] = number_format((float) $res['pagos'], 2, ".", "");
            $total1 += $res['pagos'] + $res['desgravamen'];
            
            if ($tipo_credito == "PH") {
                $res['seguro_incendios'] = number_format((float) $res['seguro_incendios'], 2, ".", "");
                $total_incendio += $res['seguro_incendios'];
            }
        }
        
        if ($tipo_credito == "PH") {
            foreach ($resultAmortizacion as $res) {
                /*
                 * <th width="5%">Cuota</th>
                 * <th width="15%" >Fecha</th>
                 * <th width="13%">Capital</th>
                 * <th width="13%">Interes</th>
                 * <th width="13%">Seg. Desgravamen</th>
                 * <th width="13%">Seg. Incendio</th>
                 * <th width="13%">Cuota</th>
                 * <th width="13%">Saldo</th>
                 * <th width="2%"></th>
                 */
                
                $html .= '<tr>';
                $html .= '<td width="5%" bgcolor="white"><font color="black">' . $res['pagos_trimestrales'] . '</font></td>';
                $html .= '<td width="15%" bgcolor="white" align="center"><font color="black">' . $res['fecha_pago'] . '</font></td>';
                $res['amortizacion'] = number_format((float) $res['amortizacion'], 2, ".", ",");
                $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $res['amortizacion'] . '</font></td>';
                $res['interes'] = number_format((float) $res['interes'], 2, ".", ",");
                $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $res['interes'] . '</font></td>';
                $cuota_pagar = $res['desgravamen'] + $res['pagos'];
                $res['desgravamen'] = number_format((float) $res['desgravamen'], 2, ".", ",");
                $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black" id="desgravamen' . $res['pagos_trimestrales'] . '">' . $res['desgravamen'] . '</font></td>';
                $res['seguro_incendios'] = number_format((float) $res['seguro_incendios'], 2, ".", ",");
                $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black" id="incendio' . $res['pagos_trimestrales'] . '">' . $res['seguro_incendios'] . '</font></td>';
                $cuota_pagar = number_format((float) $cuota_pagar, 2, ".", ",");
                $html .= '<td  width="13.2%" bgcolor="white" align="right"><font color="black" id="cuota_a_pagar' . $res['pagos_trimestrales'] . '">' . $cuota_pagar . '</font></td>';
                $res['saldo_inicial'] = number_format((float) $res['saldo_inicial'], 2, ".", ",");
                $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $res['saldo_inicial'] . '</font></td>';
                $html .= '</tr>';
            }
            
            $html .= '<tr>';
            $html .= '<td width="5%" bgcolor="white"><font color="black"></font></td>';
            $html .= '<td width="15%" bgcolor="white" align="center"><font color="black">Totales</font></td>';
            $total_capital = number_format((float) $total_capital, 2, ".", ",");
            $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $total_capital . '</font></td>';
            $total = number_format((float) $total, 2, ".", ",");
            $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $total . '</font></td>';
            $total_desg = number_format((float) $total_desg, 2, ".", ",");
            $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $total_desg . '</font></td>';
            $total_incendio = number_format((float) $total_incendio, 2, ".", ",");
            $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black" id="incendio' . $res['pagos_trimestrales'] . '">' . $total_incendio . '</font></td>';
            $total1 = number_format((float) $total1, 2, ".", ",");
            $html .= '<td width="13.2%" bgcolor="white" align="right"><font color="black">' . $total1 . '</font></td>';
            $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black"></font></td>';
            $html .= '</tr>';
        } else {
            foreach ($resultAmortizacion as $res) {
                
                $html .= '<tr>';
                $html .= '<td width="5%" bgcolor="white"><font color="black">' . $res['pagos_trimestrales'] . '</font></td>';
                $html .= '<td width="18%" bgcolor="white" align="center"><font color="black">' . $res['fecha_pago'] . '</font></td>';
                $res['amortizacion'] = number_format((float) $res['amortizacion'], 2, ".", ",");
                $html .= '<td width="15.2%" bgcolor="white" align="right"><font color="black">' . $res['amortizacion'] . '</font></td>';
                $res['interes'] = number_format((float) $res['interes'], 2, ".", ",");
                $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black">' . $res['interes'] . '</font></td>';
                $cuota_pagar = $res['desgravamen'] + $res['pagos'];
                $res['desgravamen'] = number_format((float) $res['desgravamen'], 2, ".", ",");
                $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black" id="desgravamen' . $res['pagos_trimestrales'] . '">' . $res['desgravamen'] . '</font></td>';
                $cuota_pagar = number_format((float) $cuota_pagar, 2, ".", ",");
                $html .= '<td  width="15.4%" bgcolor="white" align="right"><font color="black" id="cuota_a_pagar' . $res['pagos_trimestrales'] . '">' . $cuota_pagar . '</font></td>';
                $res['saldo_inicial'] = number_format((float) $res['saldo_inicial'], 2, ".", ",");
                $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black">' . $res['saldo_inicial'] . '</font></td>';
                $html .= '</tr>';
            }
            
            $html .= '<tr>';
            $html .= '<td width="5%" bgcolor="white"><font color="black"></font></td>';
            $html .= '<td width="18%" bgcolor="white" align="center"><font color="black">Totales</font></td>';
            $total_capital = number_format((float) $total_capital, 2, ".", ",");
            $html .= '<td width="15.2%" bgcolor="white" align="right"><font color="black">' . $total_capital . '</font></td>';
            $total = number_format((float) $total, 2, ".", ",");
            $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black">' . $total . '</font></td>';
            $total_desg = number_format((float) $total_desg, 2, ".", ",");
            $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black">' . $total_desg . '</font></td>';
            $total1 = number_format((float) $total1, 2, ".", ",");
            $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black">' . $total1 . '</font></td>';
            $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black"></font></td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>
              </div>';
        echo $html;
    }
    
    public function tablaAmortizacion($_capital_prestado_amortizacion_cabeza, $numero_cuotas, $interes_mensual, $valor_cuota, $fecha_corte, $_tasa_interes_amortizacion_cabeza)
    {
        // array donde guardar tabla amortizacion
        $resultAmortizacion = array();
        
        $formula_seguro_desgravamen = $this->devuelveFormulaDesgravamen();
        
        $formato_fecha = 'Y-m-d';
        $capital = $_capital_prestado_amortizacion_cabeza;
        $inter_ant = $interes_mensual;
        $interes_diario = $inter_ant / 30;
        $interes = $capital * $inter_ant;
        //$interes = floor($interes * 100) / 100;
        $amortizacion = $valor_cuota - $interes;
        $saldo_inicial = $capital - $amortizacion;
        $desgravamen = eval("return($formula_seguro_desgravamen);");
        //$desgravamen = floor($desgravamen * 100) / 100;
        $resultAmortizacion = array();
        $interes_concesion = 0;
        $diferencia_dias = 0;
        
        $interes = 0;
        $amortizacion = 0;
        $saldo_inicial = $capital;
        $fecha = new DateTime($fecha_corte);
        $elementos_fecha = explode("-", $fecha_corte);
        $lastday = $fecha->format('Y-m-t');
        $lastday = explode("-", $lastday);
        $lastday = $lastday[2];
        $diferencia_dias = $lastday - $elementos_fecha[2];
        $dia_actual = $elementos_fecha[2];
        $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
        $fecha = new DateTime($fecha_ultimo_dia);
        $fecha = $fecha->format($formato_fecha);
        $fecha_corte = $fecha;
        $valor = 0;
        $desgravamen = 0;
        $saldo_inicial_ant = $capital;
        
        for ($i = 1; $i <= $numero_cuotas + 1; $i ++)
        {
            if ($i == 1)
            {
                $elementos_fecha_corte = explode("-", $fecha_corte);
                $fecha_corte = $elementos_fecha_corte[0] . "-" . $elementos_fecha_corte[1] . "-" . $elementos_fecha_corte[2];
                $fecha = new DateTime($fecha_corte);
                $elementos_fecha = explode("-", $fecha_corte);
                $lastday = $fecha->format('Y-m-t');
                $lastday = explode("-", $lastday);
                $lastday = $lastday[2];
                $diferencia_dias = $lastday - $dia_actual;
                $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
                $fecha = new DateTime($fecha_ultimo_dia);
                $fecha = $fecha->format($formato_fecha);
                $fecha_corte = $fecha;
                $interes_concesion = $interes_diario * $diferencia_dias * $capital;
                $interes_concesion = round($interes_concesion, 2);
                $interes = $interes_concesion;
                
            }
            if ($i != 1)
            {
                $interes_concesion = 0;
                
                $saldo_inicial_ant = $saldo_inicial_ant - $amortizacion;
                
                //calculo de interes
                $interes    = $saldo_inicial_ant * $inter_ant;
                $interes    = round( $interes, 2);
                
                //calculo amortizacion-capital
                $amortizacion   = $valor_cuota - $interes;
                $amortizacion   = round($amortizacion, 2);
                
                $saldo_inicial = $saldo_inicial_ant - $amortizacion;
                
                //calculo seguro desgravamen
                $desgravamen = eval("return($formula_seguro_desgravamen);");
                $desgravamen    = round( $desgravamen, 3 );
                
                //para trabajar con la fecha
                $elementos_fecha_corte = explode("-", $fecha_corte);
                $elementos_fecha_corte[1] ++;
                $elementos_fecha_corte[2] = 15;
                if ($elementos_fecha_corte[1] > 12)
                {
                    $elementos_fecha_corte[1] = 1;
                    $elementos_fecha_corte[0] ++;
                }
                $fecha_corte = $elementos_fecha_corte[0] . "-" . $elementos_fecha_corte[1] . "-" . $elementos_fecha_corte[2];
                $fecha = new DateTime($fecha_corte);
                $elementos_fecha = explode("-", $fecha_corte);
                $lastday = $fecha->format('Y-m-t');
                $lastday = explode("-", $lastday);
                $lastday = $lastday[2];
                $diferencia_dias = $lastday - $elementos_fecha[2];
                $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
                $fecha = new DateTime($fecha_ultimo_dia);
                $fecha = $fecha->format($formato_fecha);
                $fecha_corte = $fecha;
                $valor = $valor_cuota;
            }
            
            $arreglo = array(
                'pagos_trimestrales' => $i,
                'saldo_inicial' => number_format( (float)$saldo_inicial, 2, ".", ""),
                'interes' => number_format( (float)$interes, 2, ".", ""),
                'amortizacion' => number_format( (float)$amortizacion, 2, ".", ""),
                'pagos' => number_format( (float)$valor, 2, ".", ""),
                'desgravamen' => number_format( (float)$desgravamen, 2, ".", ""),
                'fecha_pago' => $fecha,
                'interes_concesion' => number_format( (float)$interes_concesion, 2, ".", "")
            );
            
            array_push($resultAmortizacion, $arreglo);
        }
        
        if( !empty( $resultAmortizacion ) )
        {
            //se cambia la ultima cuota debido al sobrante que genera el metodo frances
            $len   = sizeof( $resultAmortizacion );
            
            $resultAmortizacion[$len - 1]['pagos']  = $resultAmortizacion[$len - 1]['pagos'] + $resultAmortizacion[$len - 1]['saldo_inicial'];
            $resultAmortizacion[$len - 1]['amortizacion']   = $resultAmortizacion[$len - 1]['amortizacion'] + $resultAmortizacion[$len - 1]['saldo_inicial'];
            $resultAmortizacion[$len - 1]['saldo_inicial']  = 0.00;
        }
        
        return $resultAmortizacion;
    }
    
    public function tablaAmortizacionHipotecario($_capital_prestado_amortizacion_cabeza, $numero_cuotas, $interes_mensual, $valor_cuota, $fecha_corte, $_tasa_interes_amortizacion_cabeza, $avaluo_bien)
    {
        // array donde guardar tabla amortizacion
        $resultAmortizacion = array();
        $formato_fecha = 'Y-m-d';
        
        $rp_capremci = new PlanCuentasModel();
        $columnas = "expresion_formulas";
        $tablas = "core_formulas INNER JOIN estado
                ON core_formulas.id_estado = estado.id_estado";
        $where = "descripcion_formulas='seguro_de_incendios' AND estado.nombre_estado='ACTIVO' AND estado.tabla_estado='core_formulas'";
        $formula_seguro_incendios = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $formula_seguro_incendios = $formula_seguro_incendios[0]->expresion_formulas;
        
        $columnas = "expresion_formulas";
        $tablas = "core_formulas INNER JOIN estado
                ON core_formulas.id_estado = estado.id_estado";
        $where = "descripcion_formulas='seguro_de_desgravamen' AND estado.nombre_estado='ACTIVO' AND estado.tabla_estado='core_formulas'";
        $formula_seguro_desgravamen = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $formula_seguro_desgravamen = $formula_seguro_desgravamen[0]->expresion_formulas;
        
        $capital = $_capital_prestado_amortizacion_cabeza;
        $inter_ant = $interes_mensual;
        $interes_diario = $inter_ant / 30;
        $interes = $capital * $inter_ant;
        $interes = floor($interes * 100) / 100;
        $amortizacion = $valor_cuota - $interes;
        $saldo_inicial = $capital - $amortizacion;
        $desgravamen = eval("return($formula_seguro_desgravamen);");
        $desgravamen = floor($desgravamen * 100) / 100;
        $resultAmortizacion = array();
        $interes_concesion = 0;
        $diferencia_dias = 0;
        
        $interes = 0;
        $amortizacion = 0;
        $saldo_inicial = $capital;
        $fecha = new DateTime($fecha_corte);
        $elementos_fecha = explode("-", $fecha_corte);
        $lastday = $fecha->format('Y-m-t');
        $lastday = explode("-", $lastday);
        $lastday = $lastday[2];
        $diferencia_dias = $lastday - $elementos_fecha[2];
        $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
        $fecha = new DateTime($fecha_ultimo_dia);
        $fecha = $fecha->format($formato_fecha);
        $fecha_corte = $fecha;
        $valor = 0;
        $desgravamen = 0;
        $saldo_inicial_ant = $capital;
        
        for ($i = 1; $i <= $numero_cuotas + 1; $i ++) {
            
            if ($i == 1) {
                $elementos_fecha_corte = explode("-", $fecha_corte);
                $fecha_corte = $elementos_fecha_corte[0] . "-" . $elementos_fecha_corte[1] . "-" . $elementos_fecha_corte[2];
                $fecha = new DateTime($fecha_corte);
                $fecha = date('Y-m-d', $fecha);
                $lastday = $fecha->format('Y-m-t');
                $lastday = explode("-", $lastday);
                $lastday = $lastday[2];
                $fecha = $fecha->format($formato_fecha);
                $elementos_fecha = explode("-", $fecha);
                $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
                $fecha = new DateTime($fecha_ultimo_dia);
                $fecha = $fecha->format($formato_fecha);
                $fecha_corte = $fecha;
                $interes_concesion = $interes_diario * $diferencia_dias * $capital;
                $interes_concesion = round($interes_concesion, 2);
                $interes = $interes_concesion;
            }
            if ($i != 1) {
                $interes_concesion = 0;
                $saldo_inicial_ant = $saldo_inicial_ant - $amortizacion;
                $interes = $saldo_inicial_ant * $inter_ant;
                $interes = floor($interes * 100) / 100;
                $amortizacion = $valor_cuota - $interes;
                
                $desgravamen = eval("return($formula_seguro_desgravamen);");
                $desgravamen = floor($desgravamen * 100) / 100;
                $saldo_inicial = $saldo_inicial_ant - $amortizacion;
                $elementos_fecha_corte = explode("-", $fecha_corte);
                $elementos_fecha_corte[1] ++;
                $elementos_fecha_corte[2] = 15;
                if ($elementos_fecha_corte[1] > 12) {
                    $elementos_fecha_corte[1] = 1;
                    $elementos_fecha_corte[0] ++;
                }
                
                $fecha_corte = $elementos_fecha_corte[0] . "-" . $elementos_fecha_corte[1] . "-" . $elementos_fecha_corte[2];
                $fecha = new DateTime($fecha_corte);
                $lastday = $fecha->format('Y-m-t');
                $lastday = explode("-", $lastday);
                $lastday = $lastday[2];
                $fecha = $fecha->format($formato_fecha);
                $elementos_fecha = explode("-", $fecha);
                $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
                $fecha = new DateTime($fecha_ultimo_dia);
                $fecha = $fecha->format($formato_fecha);
                $fecha_corte = $fecha;
                $seguro_incendios = eval("return($formula_seguro_incendios);");
                $valor = $valor_cuota;
            }
            
            $arreglo = array(
                'pagos_trimestrales' => $i,
                'saldo_inicial' => $saldo_inicial,
                'interes' => $interes,
                'amortizacion' => $amortizacion,
                'pagos' => $valor,
                'desgravamen' => $desgravamen,
                'fecha_pago' => $fecha,
                'interes_concesion' => $interes_concesion,
                'seguro_incendios' => $seguro_incendios
            );
            
            array_push($resultAmortizacion, $arreglo);
        }
        
        return $resultAmortizacion;
    }
    
    public function tablaAmortizacionRenovacion($_capital_prestado_amortizacion_cabeza, $numero_cuotas, $interes_mensual, $valor_cuota, $fecha_corte, $_tasa_interes_amortizacion_cabeza)
    {
        // array donde guardar tabla amortizacion
        $resultAmortizacion = array();
        
        $formato_fecha = 'Y-m-d';
        $capital = $_capital_prestado_amortizacion_cabeza;
        $inter_ant = $interes_mensual;
        $interes_diario = $inter_ant / 30;
        $interes = $capital * $inter_ant;
        //$interes = floor($interes * 1000) / 1000;
        $amortizacion = $valor_cuota - $interes;
        $saldo_inicial = $capital - $amortizacion;
        $desgravamen = $this->devuelveSegDesgravamen( $saldo_inicial );
        //$desgravamen = floor($desgravamen * 1000) / 1000;
        $resultAmortizacion = array();
        $interes_concesion = 0;
        $diferencia_dias = 0;
        
        $interes = 0;
        $amortizacion = 0;
        $saldo_inicial = $capital;
        $fecha = new DateTime($fecha_corte);
        $lastday = $fecha->format('Y-m-t');
        $elementos_fecha = explode("-", $fecha_corte);
        $lastday = explode("-", $lastday);
        $lastday = $lastday[2];
        $diferencia_dias = $lastday - $elementos_fecha[2];
        $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
        $fecha = new DateTime($fecha_ultimo_dia);
        $fecha = $fecha->format($formato_fecha);
        $fecha_corte = $fecha;
        $valor = 0;
        $desgravamen = 0;
        $saldo_inicial_ant = $capital;
        
        for ($i = 0; $i <= $numero_cuotas; $i ++) {
            
            if ($i == 0)
            {
                $elementos_fecha_corte = explode("-", $fecha_corte);
                $fecha_corte = $elementos_fecha_corte[0] . "-" . $elementos_fecha_corte[1] . "-" . $elementos_fecha_corte[2];
                $fecha = new DateTime($fecha_corte);
                $lastday = $fecha->format('Y-m-t');
                $lastday = explode("-", $lastday);
                $lastday = $lastday[2];
                $fecha = $fecha->format($formato_fecha);
                $elementos_fecha = explode("-", $fecha);
                $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
                $fecha = new DateTime($fecha_ultimo_dia);
                $fecha = $fecha->format($formato_fecha);
                $fecha_corte = $fecha;
                $interes_concesion = $interes_diario * ($diferencia_dias) * $capital;
                $interes_concesion = round($interes_concesion, 2);
            }
            if ($i != 0)
            {
                $saldo_inicial_ant = $saldo_inicial_ant - $amortizacion;
                
                $interes    = $saldo_inicial_ant * $inter_ant;
                $interes    = round( $interes, 2);
                //$interes = floor($interes * 100) / 100;
                
                $amortizacion = $valor_cuota - $interes;
                $amortizacion = round( $amortizacion,2);
                
                $saldo_inicial = $saldo_inicial_ant - $amortizacion;
                //$saldo_inicial = round( $saldo_inicial ,2);
                
                $desgravamen    = $this->devuelveSegDesgravamen($saldo_inicial);
                $desgravamen    = round( $desgravamen, 3 );
                
                //para trabajar con la fecha
                $elementos_fecha_corte = explode("-", $fecha_corte);
                $elementos_fecha_corte[1] ++;
                $elementos_fecha_corte[2] = 15;
                if ($elementos_fecha_corte[1] > 12)
                {
                    $elementos_fecha_corte[1] = 1;
                    $elementos_fecha_corte[0] ++;
                }
                $fecha_corte = $elementos_fecha_corte[0] . "-" . $elementos_fecha_corte[1] . "-" . $elementos_fecha_corte[2];
                $fecha = new DateTime($fecha_corte);
                $lastday = $fecha->format('Y-m-t');
                $lastday = explode("-", $lastday);
                $lastday = $lastday[2];
                $fecha = $fecha->format($formato_fecha);
                $elementos_fecha = explode("-", $fecha);
                $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
                $fecha = new DateTime($fecha_ultimo_dia);
                $fecha = $fecha->format($formato_fecha);
                $fecha_corte = $fecha;
                
                if ($i == 1)
                {
                    $valor = $valor_cuota + $interes_concesion;
                    $interes    += $interes_concesion;
                }else
                {
                    $valor = $valor_cuota;
                }
                
            }
            
            if ($i != 0)
            {
                //se setea el formato numero para que pueda ser considerado como float en el array
                $arreglo = array(
                    'pagos_trimestrales' => $i,
                    'saldo_inicial' => number_format( (float)$saldo_inicial,2,".",""),
                    'interes' => number_format( (float)$interes,2,".",""),
                    'amortizacion' => number_format( (float)$amortizacion,2,".",""),
                    'pagos' => number_format( (float)$valor,2,".",""),
                    'desgravamen' => number_format( (float)$desgravamen,2,".",""),
                    'fecha_pago' => $fecha,
                    'interes_concesion' => number_format($interes_concesion,2,".","")
                );
                
                array_push($resultAmortizacion, $arreglo);
            }
        }
        
        if( !empty( $resultAmortizacion ) )
        {
            //se cambia la ultima cuota debido al sobrante que genera el metodo frances
            $len   = sizeof( $resultAmortizacion );
            
            $resultAmortizacion[$len - 1]['pagos']  = $resultAmortizacion[$len - 1]['pagos'] + $resultAmortizacion[$len - 1]['saldo_inicial'];
            $resultAmortizacion[$len - 1]['amortizacion']   = $resultAmortizacion[$len - 1]['amortizacion'] + $resultAmortizacion[$len - 1]['saldo_inicial'];
            $resultAmortizacion[$len - 1]['saldo_inicial']  = 0.00;
        }
        return $resultAmortizacion;
    }
    
    public function tablaAmortizacionRenovacionHipotecario($_capital_prestado_amortizacion_cabeza, $numero_cuotas, $interes_mensual, $valor_cuota, $fecha_corte, $_tasa_interes_amortizacion_cabeza, $avaluo_bien)
    {
        // array donde guardar tabla amortizacion
        $resultAmortizacion = array();
        
        $rp_capremci = new PlanCuentasModel();
        $columnas = "expresion_formulas";
        $tablas = "core_formulas INNER JOIN estado
                ON core_formulas.id_estado = estado.id_estado";
        $where = "descripcion_formulas='seguro_de_incendios' AND estado.nombre_estado='ACTIVO' AND estado.tabla_estado='core_formulas'";
        $formula_seguro_incendios = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $formula_seguro_incendios = $formula_seguro_incendios[0]->expresion_formulas;
        
        $columnas = "expresion_formulas";
        $tablas = "core_formulas INNER JOIN estado
                ON core_formulas.id_estado = estado.id_estado";
        $where = "descripcion_formulas='seguro_de_desgravamen' AND estado.nombre_estado='ACTIVO' AND estado.tabla_estado='core_formulas'";
        $formula_seguro_desgravamen = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $formula_seguro_desgravamen = $formula_seguro_desgravamen[0]->expresion_formulas;
        
        $formato_fecha = 'Y-m-d';
        $capital = $_capital_prestado_amortizacion_cabeza;
        $inter_ant = $interes_mensual;
        $interes_diario = $inter_ant / 30;
        $interes = $capital * $inter_ant;
        $interes = floor($interes * 100) / 100;
        $amortizacion = $valor_cuota - $interes;
        $saldo_inicial = $capital - $amortizacion;
        $desgravamen = eval("return ($formula_seguro_desgravamen);");
        $desgravamen = floor($desgravamen * 100) / 100;
        $resultAmortizacion = array();
        $interes_concesion = 0;
        $diferencia_dias = 0;
        
        $interes = 0;
        $amortizacion = 0;
        $saldo_inicial = $capital;
        $fecha = new DateTime($fecha_corte);
        $elementos_fecha = explode("-", $fecha_corte);
        $lastday = $fecha->format('Y-m-t');
        $lastday = explode("-", $lastday);
        $lastday = $lastday[2];
        $diferencia_dias = $lastday - $elementos_fecha[2];
        $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
        $fecha = new DateTime($fecha_ultimo_dia);
        
        $fecha_corte = $fecha->format($formato_fecha);
        $valor = 0;
        $desgravamen = 0;
        $saldo_inicial_ant = $capital;
        
        for ($i = 0; $i <= $numero_cuotas; $i ++) {
            
            if ($i == 0) {
                $elementos_fecha_corte = explode("-", $fecha_corte);
                $fecha_corte = $elementos_fecha_corte[0] . "-" . $elementos_fecha_corte[1] . "-" . $elementos_fecha_corte[2];
                $fecha = new DateTime($fecha_corte);
                $elementos_fecha = explode("-", $fecha_corte);
                $lastday = $fecha->format('Y-m-t');
                $lastday = explode("-", $lastday);
                $lastday = $lastday[2];
                $diferencia_dias = $lastday - $elementos_fecha[2];
                $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
                $fecha = new DateTime($fecha_ultimo_dia);
                $fecha = $fecha->format($formato_fecha);
                $fecha_corte = $fecha;
                $interes_concesion = $interes_diario * $diferencia_dias * $capital;
                $seguro_incendios = ((($avaluo_bien * 0.0015) / 365) * $lastday) * 1.04 * 1.12;
                $interes_concesion = round($interes_concesion, 2);
            }
            if ($i != 0) {
                $saldo_inicial_ant = $saldo_inicial_ant - $amortizacion;
                $interes = $saldo_inicial_ant * $inter_ant;
                $interes = floor($interes * 100) / 100;
                if ($i == 1)
                    $interes += $interes_concesion;
                    $amortizacion = $valor_cuota - $interes;
                    
                    $desgravamen = eval("return ($formula_seguro_desgravamen);");
                    $desgravamen = floor($desgravamen * 100) / 100;
                    $saldo_inicial = $saldo_inicial_ant - $amortizacion;
                    $elementos_fecha_corte = explode("-", $fecha_corte);
                    $elementos_fecha_corte[1] ++;
                    $elementos_fecha_corte[2] = 15;
                    if ($elementos_fecha_corte[1] > 12) {
                        $elementos_fecha_corte[1] = 1;
                        $elementos_fecha_corte[0] ++;
                    }
                    $fecha_corte = $elementos_fecha_corte[0] . "-" . $elementos_fecha_corte[1] . "-" . $elementos_fecha_corte[2];
                    $fecha = new DateTime($fecha_corte);
                    $elementos_fecha = explode("-", $fecha_corte);
                    $lastday = $fecha->format('Y-m-t');
                    $lastday = explode("-", $lastday);
                    $lastday = $lastday[2];
                    $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
                    $fecha = new DateTime($fecha_ultimo_dia);
                    $fecha = $fecha->format($formato_fecha);
                    $fecha_corte = $fecha;
                    
                    $seguro_incendios = eval("return ($formula_seguro_incendios);");
                    $fecha_ultimo_dia = $elementos_fecha[0] . "-" . $elementos_fecha[1] . "-" . $lastday;
                    
                    if ($i == 1)
                        $valor = $valor_cuota + $interes_concesion;
                        else
                            $valor = $valor_cuota;
            }
            
            if ($i != 0) {
                $arreglo = array(
                    'pagos_trimestrales' => $i,
                    'saldo_inicial' => $saldo_inicial,
                    'interes' => $interes,
                    'amortizacion' => $amortizacion,
                    'pagos' => $valor,
                    'desgravamen' => $desgravamen,
                    'fecha_pago' => $fecha,
                    'interes_concesion' => $interes_concesion,
                    'seguro_incendios' => $seguro_incendios
                );
                
                array_push($resultAmortizacion, $arreglo);
            }
        }
        
        return $resultAmortizacion;
    }
    
    public function DesgloseTablaAmortizacion($id_tabla_amortizacion, $tipo_credito)
    {
        $rp_capremci = new PlanCuentasModel();
        $columnas = "*";
        $tablas = "core_tabla_amortizacion";
        $where = "id_tabla_amortizacion=" . $id_tabla_amortizacion;
        $datos_tabla_amortizacion = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        $columnas = "id_tabla_amortizacion_parametrizacion";
        $tablas = "core_tabla_amortizacion_parametrizacion INNER JOIN core_tipo_creditos
                    ON core_tabla_amortizacion_parametrizacion.id_tipo_creditos=core_tipo_creditos.id_tipo_creditos";
        $where = "codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=0 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
        $id_capital = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $id_capital = $id_capital[0]->id_tabla_amortizacion_parametrizacion;
        
        $query = "INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_capital, $id_tabla_amortizacion, '" . $datos_tabla_amortizacion[0]->capital_tabla_amortizacion . "',
                            '" . $datos_tabla_amortizacion[0]->capital_tabla_amortizacion . "', 1)";
        $rp_capremci->executeNonQuery($query);
        
        $where = "codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=1 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
        $id_interes = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $id_interes = $id_interes[0]->id_tabla_amortizacion_parametrizacion;
        
        $query = "INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_interes, $id_tabla_amortizacion, '" . $datos_tabla_amortizacion[0]->interes_tabla_amortizacion . "',
                            '" . $datos_tabla_amortizacion[0]->interes_tabla_amortizacion . "', 1)";
        $rp_capremci->executeNonQuery($query);
        
        $where = "codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=7 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
        $id_mora = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $id_mora = $id_mora[0]->id_tabla_amortizacion_parametrizacion;
        
        $query = "INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_mora, $id_tabla_amortizacion, '0.00',
                            '0.00', 1)";
        $rp_capremci->executeNonQuery($query);
        
        if ($tipo_credito != 'PH')
        {
            $where = "codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=8 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
            $id_desgravamen = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
            $id_desgravamen = $id_desgravamen[0]->id_tabla_amortizacion_parametrizacion;
            
            $query = "INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_desgravamen, $id_tabla_amortizacion, '" . $datos_tabla_amortizacion[0]->seguro_desgravamen_tabla_amortizacion . "',
                            '" . $datos_tabla_amortizacion[0]->seguro_desgravamen_tabla_amortizacion . "', 1)";
            $rp_capremci->executeNonQuery($query);
        } else
        {
            
            $where = "codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=9 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
            $id_desgravamen = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
            $id_desgravamen = $id_desgravamen[0]->id_tabla_amortizacion_parametrizacion;
            
            $query = "INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_desgravamen, $id_tabla_amortizacion, '" . $datos_tabla_amortizacion[0]->seguro_desgravamen_tabla_amortizacion . "',
                            '" . $datos_tabla_amortizacion[0]->seguro_desgravamen_tabla_amortizacion . "', 1)";
            $rp_capremci->executeNonQuery($query);
            
            $where = "codigo_tipo_creditos='$tipo_credito' AND tipo_tabla_amortizacion_parametrizacion=8 AND core_tabla_amortizacion_parametrizacion.id_estado=114";
            $id_incendios = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
            $id_incendios = $id_incendios[0]->id_tabla_amortizacion_parametrizacion;
            
            $query = "INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_incendios, $id_tabla_amortizacion, '" . $datos_tabla_amortizacion[0]->seguro_incendios_tabla_amortizacion . "',
                            '" . $datos_tabla_amortizacion[0]->seguro_incendios_tabla_amortizacion . "', 1)";
            $rp_capremci->executeNonQuery($query);
        }
    }
    
    public function ActualizarInfoParticipe($cedula_participe, $id_solicitud)
    {
        require_once 'core/DB_Functions.php';
        ob_start();
        $db = new DB_Functions();
        $reporte = new PlanCuentasModel();
        $columnas = " solicitud_prestamo.correo_solicitante_datos_personales,
					  solicitud_prestamo.fecha_nacimiento_datos_personales,
					  solicitud_prestamo.id_estado_civil_datos_personales,
					  solicitud_prestamo.separacion_bienes_datos_personales,
					  solicitud_prestamo.cargas_familiares_datos_personales,
                      solicitud_prestamo.numero_hijos_datos_personales,
					  solicitud_prestamo.nivel_educativo_datos_personales,
					  solicitud_prestamo.id_provincias_vivienda,
					  solicitud_prestamo.id_cantones_vivienda,
					  solicitud_prestamo.id_parroquias_vivienda,
                      solicitud_prestamo.barrio_sector_vivienda,
					  solicitud_prestamo.ciudadela_conjunto_etapa_manzana_vivienda,
					  solicitud_prestamo.calle_vivienda,
					  solicitud_prestamo.numero_calle_vivienda,
					  solicitud_prestamo.intersecion_vivienda,
                      solicitud_prestamo.tipo_vivienda,
					  solicitud_prestamo.vivienda_hipotecada_vivienda,
					  solicitud_prestamo.tiempo_residencia_vivienda,
					  solicitud_prestamo.nombre_propietario_vivienda,
					  solicitud_prestamo.celular_propietario_vivienda,
                      solicitud_prestamo.referencia_direccion_domicilio_vivienda,
					  solicitud_prestamo.numero_casa_solicitante,
					  solicitud_prestamo.numero_celular_solicitante,
					  solicitud_prestamo.numero_trabajo_solicitante,
                      solicitud_prestamo.extension_solicitante,
					  solicitud_prestamo.apellidos_referencia_personal,
					  solicitud_prestamo.nombres_referencia_personal,
					  solicitud_prestamo.relacion_referencia_personal,
					  solicitud_prestamo.numero_telefonico_referencia_personal,
					  solicitud_prestamo.apellidos_referencia_familiar,
					  solicitud_prestamo.nombres_referencia_familiar,
					  solicitud_prestamo.parentesco_referencia_familiar,
					  solicitud_prestamo.numero_telefonico_referencia_familiar,
					  solicitud_prestamo.id_provincias_asignacion,
					  solicitud_prestamo.id_cantones_asignacion,
					  solicitud_prestamo.id_parroquias_asignacion,
					  solicitud_prestamo.numero_telefonico_datos_laborales,
					  solicitud_prestamo.interseccion_datos_laborales,
					  solicitud_prestamo.calle_datos_laborales,
					  solicitud_prestamo.cargo_actual_datos_laborales,
					  solicitud_prestamo.sueldo_total_info_economica,
                      solicitud_prestamo.tipo_pago_cuenta_bancaria,
                      solicitud_prestamo.nombres_conyuge,
                      solicitud_prestamo.apellidos_conyuge,
                      solicitud_prestamo.numero_cedula_conyuge,
                      solicitud_prestamo.numero_hijos_datos_personales,
                      solicitud_prestamo.tiempo_residencia_vivienda,
					  solicitud_prestamo.nombre_propietario_vivienda,
					  solicitud_prestamo.celular_propietario_vivienda,
					  solicitud_prestamo.referencia_direccion_domicilio_vivienda,
                      solicitud_prestamo.nombres_referencia_personal,
					  solicitud_prestamo.numero_telefonico_referencia_personal";
        
        $tablas = "public.solicitud_prestamo";
        
        $where = "solicitud_prestamo.id_solicitud_prestamo=" . $id_solicitud;
        
        $resultSet = $db->getCondiciones($columnas, $tablas, $where);
        
        $columnas = "id_participes";
        $tablas = "core_participes";
        $where = "cedula_participes='" . $cedula_participe . "'";
        $id = "id_participes";
        $id_participes = $reporte->getCondiciones($columnas, $tablas, $where, $id);
        $id_participes = $id_participes[0]->id_participes;
        
        $direccion_participe = $resultSet[0]->barrio_sector_vivienda . " " . $resultSet[0]->ciudadela_conjunto_etapa_manzana_vivienda . " " . $resultSet[0]->calle_vivienda . " ";
        $direccion_participe .= $resultSet[0]->numero_calle_vivienda . " " . $resultSet[0]->intersecion_vivienda;
        
        $nombre_conyuge = "N/A";
        if (! (empty($resultSet[0]->nombres_conyuge)))
            $nombre_conyuge = $resultSet[0]->nombres_conyuge;
            $apellido_conyuge = "N/A";
            if (! (empty($resultSet[0]->apellidos_conyuge)))
                $apellido_conyuge = $resultSet[0]->apellidos_conyuge;
                $cedula_conyuge = "N/A";
                if (! (empty($resultSet[0]->numero_cedula_conyuge)))
                    $cedula_conyuge = $resultSet[0]->numero_cedula_conyuge;
                    
                    $where = "id_participes=" . $id_participes;
                    $tabla = "core_participes";
                    $colval = "fecha_nacimiento_participes='" . $resultSet[0]->fecha_nacimiento_datos_personales . "',
                   direccion_participes='" . $direccion_participe . "',
                   telefono_participes='" . $resultSet[0]->numero_casa_solicitante . "',
                   celular_participes='" . $resultSet[0]->numero_celular_solicitante . "',
                   ocupacion_participes='" . $resultSet[0]->cargo_actual_datos_laborales . "',
                   nombre_conyugue_participes='" . $nombre_conyuge . "',
                   apellido_esposa_participes='" . $apellido_conyuge . "',
                   cedula_conyugue_participes='" . $cedula_conyuge . "',
                   numero_dependencias_participes=" . $resultSet[0]->numero_hijos_datos_personales;
                    $reporte->UpdateBy($colval, $tabla, $where);
                    
                    $columnas = "nombre_parroquias";
                    $tablas = "public.parroquias";
                    
                    $where = "id_parroquias=" . $resultSet[0]->id_parroquias_vivienda;
                    
                    $nombre_parroquias = $db->getCondiciones($columnas, $tablas, $where);
                    $nombre_parroquias = $nombre_parroquias[0]->nombre_parroquias;
                    
                    $anios_residencia = $resultSet[0]->tiempo_residencia_vivienda;
                    $anios_residencia = explode(" ", $anios_residencia);
                    $anios_residencia = $anios_residencia[0];
                    
                    $where = "id_participes=" . $id_participes;
                    $tabla = "core_participes_informacion_adicional";
                    $colval = "parroquia_participes_informacion_adicional='" . $nombre_parroquias . "',
                    sector_participes_informacion_adicional='" . $resultSet[0]->barrio_sector_vivienda . "',
                    ciudadela_participes_informacion_adicional='" . $resultSet[0]->ciudadela_conjunto_etapa_manzana_vivienda . "',
                    calle_participes_informacion_adicional='" . $resultSet[0]->calle_vivienda . "',
                    numero_calle_participes_informacion_adicional='" . $resultSet[0]->numero_calle_vivienda . "',
                    interseccion_participes_informacion_adicional='" . $resultSet[0]->intersecion_vivienda . "',
                    anios_residencia_participes_informacion_adicional='" . $anios_residencia . "',
                    nombre_propietario_participes_informacion_adicional='" . $resultSet[0]->nombre_propietario_vivienda . "',
                    telefono_propietario_participes_informacion_adicional='" . $resultSet[0]->celular_propietario_vivienda . "',
                    direccion_referencia_participes_informacion_adicional='" . $resultSet[0]->referencia_direccion_domicilio_vivienda . "',
                    nombre_una_referencia_participes_informacion_adicional='" . $resultSet[0]->nombres_referencia_personal . "',
                    telefono_una_referencia_participes_informacion_adicional='" . $resultSet[0]->numero_telefonico_referencia_personal . "'";
                    $reporte->UpdateBy($colval, $tabla, $where);
                    
                    $errores_actualizacion = ob_get_clean();
                    $errores_actualizacion = trim($errores_actualizacion);
                    return $errores_actualizacion;
    }
    
    public function ActualizarCuentasParticipes($id_solicitud, $numero_creditos)
    {
        ob_start();
        $usuario_usuarios = $_SESSION['usuario_usuarios'];
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
        $rp_capremci = new PlanCuentasModel();
        
        $columnas = " solicitud_prestamo.nombre_banco_cuenta_bancaria,
				solicitud_prestamo.tipo_cuenta_cuenta_bancaria,
				solicitud_prestamo.numero_cuenta_cuenta_bancaria,
				solicitud_prestamo.numero_cedula_datos_personales,
                solicitud_prestamo.tipo_pago_cuenta_bancaria";
        $tablas = " public.solicitud_prestamo";
        $where = "solicitud_prestamo.id_solicitud_prestamo='$id_solicitud'";
        $id = "solicitud_prestamo.id_solicitud_prestamo";
        
        $resultSoli = $db->getCondicionesDesc($columnas, $tablas, $where, $id);
        if( !( empty( $resultSoli ) ) )
        {
            if ($resultSoli[0]->tipo_pago_cuenta_bancaria == "Depósito") {
                
                $nombre_banco = $resultSoli[0]->nombre_banco_cuenta_bancaria;
                
                $columnas = "bankid";
                $tablas = "bancos";
                $where = "nombre_bancos='" . $nombre_banco . "'";
                $id = "bankid";
                $id_banco = $db->getCondicionesDesc($columnas, $tablas, $where, $id);
                $id_banco = $id_banco[0]->bankid;
                
                $numero_cuenta = $resultSoli[0]->numero_cuenta_cuenta_bancaria;
                $numero_cedula = $resultSoli[0]->numero_cedula_datos_personales;
                $tipo_cuenta = $resultSoli[0]->tipo_cuenta_cuenta_bancaria;
                
                $columnas = "id_participes";
                $tablas = " public.core_participes";
                $where = "cedula_participes='$numero_cedula'";
                $id = "id_participes";
                
                $id_participes = $rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
                $id_participes = $id_participes[0]->id_participes;
                
                if ($tipo_cuenta == 'Ahorros') {
                    $tipo_cuenta = 'AHORROS';
                } else {
                    $tipo_cuenta = 'CORRIENTE';
                }
                
                $ip_adress = $_SERVER['REMOTE_ADDR'];
                $columnas = "id_tipo_cuentas";
                $tablas = " public.core_tipo_cuentas";
                $where = "nombre_tipo_cuentas='$tipo_cuenta'";
                $id = "id_tipo_cuentas";
                
                $id_tipo_cuentas = $rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
                $id_tipo_cuentas = $id_tipo_cuentas[0]->id_tipo_cuentas;
                
                $funcion = "ins_core_participes_cuentas";
                /*
                 * $parametros="'$id_participes',
                 * '$id_banco',
                 * '$numero_cuenta',
                 * '$id_tipo_cuentas',
                 * 1,
                 * 'true',
                 * '$usuario_usuarios',
                 * '$ip_adress'";
                 */
                
                $parametros = $id_participes . ', ' . $id_banco . ', \'' . $numero_cuenta . '\',';
                $parametros .= $id_tipo_cuentas . ',\'true\' ,\'' . $usuario_usuarios . '\',\'' . $ip_adress . '\'';
                $rp_capremci->setFuncion($funcion);
                $rp_capremci->setParametros($parametros);
                $resultado = $rp_capremci->Insert();
                
                $columnas = "id_forma_pago";
                $tablas = " public.forma_pago";
                $where = "nombre_forma_pago='TRANSFERENCIA'";
                $id = "id_forma_pago";
                
                $id_forma_pagos = $rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
                $id_forma_pagos = $id_forma_pagos[0]->id_forma_pago;
                
                $where = "numero_creditos='" . $numero_creditos . "'";
                $tabla = "core_creditos";
                $colval = "id_forma_pago=" . $id_forma_pagos;
                $rp_capremci->UpdateBy($colval, $tabla, $where);
            } else {
                $columnas = "id_forma_pago";
                $tablas = " public.forma_pago";
                $where = "nombre_forma_pago='CHEQUE'";
                $id = "id_forma_pago";
                
                $id_forma_pagos = $rp_capremci->getCondiciones($columnas, $tablas, $where, $id);
                $id_forma_pagos = $id_forma_pagos[0]->id_forma_pago;
                
                $where = "numero_creditos='" . $numero_creditos . "'";
                $tabla = "core_creditos";
                $colval = "id_forma_pago=" . $id_forma_pagos;
                $rp_capremci->UpdateBy($colval, $tabla, $where);
            }
            
            $errores_cuentas = ob_get_clean();
            $errores_cuentas = trim($errores_cuentas);
        } else {
            $errores_cuentas = "NO SE PUDO CONSEGUIR LA INFO";
        }
        
        return $errores_cuentas;
    }
    
    public function GetAvaluoHipotecario()
    {
        session_start();
        $rp_capremci = new PlanCuentasModel();
        $monto_maximo = 0;
        
        $id_solicitud = $_POST['id_solicitud'];
        $tipo_credito_hipotecario = $_POST['tipo_credito_hipotecario'];
        $columnas = "valor_avaluo_core_documentos_hipotecario";
        $tablas = "core_documentos_hipotecario";
        $where = "id_solicitud_credito=" . $id_solicitud;
        $avaluo_credito = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        if (sizeof($avaluo_credito) > 0) {
            $avaluo_credito = $avaluo_credito[0]->valor_avaluo_core_documentos_hipotecario;
            if ($tipo_credito_hipotecario == 1) {
                $monto_maximo = $avaluo_credito * 0.8;
                if ($monto_maximo > 100000)
                    $monto_maximo = 100000;
            } else {
                $monto_maximo = $avaluo_credito * 0.5;
                if ($monto_maximo > 45000)
                    $monto_maximo = 45000;
            }
            $avaluo_credito = number_format((float) $avaluo_credito, 2, ".", "");
            $monto_maximo = number_format((float) $monto_maximo, 2, ".", "");
            $html = '<table>
        <tr>
        <td><font size="3">Avalúo del bien : ' . $avaluo_credito . '</font></td>
        </tr>
        <tr>
        <td><font size="3" id="monto_disponible2">Monto máximo a recibir : ' . $monto_maximo . '</font></td>
        </tr>
        <tr>
        <td>
        <span class="input-group-btn">
        <button  type="button" class="btn bg-olive" title="Cambiar Modalidad" onclick="TipoCredito()"><i class="glyphicon glyphicon-refresh"></i></button>
        <button  type="button" class="btn bg-olive" title="Escrituras" onclick="TipoCredito()"><i class="glyphicon glyphicon-book"></i></button>
        <button  type="button" class="btn bg-olive" title="Certificado" onclick="TipoCredito()"><i class="glyphicon glyphicon-check"></i></button>
        <button  type="button" class="btn bg-olive" title="Impuestos" onclick="TipoCredito()"><i class="fa fa-black-tie"></i></button>
        <button  type="button" class="btn bg-olive" title="Avaluo" onclick="TipoCredito()"><i class="glyphicon glyphicon-usd"></i></button>
        </span>
        </td>
        </tr>
        </table>';
        } else {
            $html = '<table>
        <tr>
        <td><font size="3">No hay avaluo registrado</font></td>
        </tr>
        <tr>
        <td><font size="3" id="monto_disponible2">Monto máximo a recibir : 0.00</font></td>
        </tr>
                
        </table>';
        }
        
        echo $html;
    }
    
    /**
     * *
     * fn que permite obtener valores de cuota de creditos a renovar
     *
     * @author dc 2020/05/11
     * @throws Exception
     */
    public function obtenerCuotaParticipe()
    {
        session_start();
        $creditos = new EstadoModel();
        $response = array();
        
        try {
            
            ob_start();
            $cedula_participe = $_POST['cedula_participe'];
            $tipo_credito = $_POST['tipo_credito'];
            $cuota_total = 0.00;
            
            if (! empty(error_get_last())) {
                throw new Exception("variables no definidas");
            }
            
            // busca los tipo creditos que puede renovar de acuerdo al tipo de credito recibido
            $columnas = "tcr.id_tipo_creditos_a_renovar";
            $tablas = "core_tipo_creditos_renovacion tcr
                INNER JOIN core_tipo_creditos tc ON tc.id_tipo_creditos = tcr.id_tipo_creditos";
            $where = "tcr.id_estado = 107
                AND tc.codigo_tipo_creditos = '" . $tipo_credito . "'";
            
            $_result_creditos_renovar = $creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
            
            if (! empty($_result_creditos_renovar)) {
                
                foreach ($_result_creditos_renovar as $res) {
                    
                    // buscar datos deacuerdo a la lista de creditos a renovar obtenidos
                    /*
                     $columnas = 'monto_otorgado_creditos, plazo_creditos, interes_creditos';
                     $tablas = 'core_creditos
                     INNER JOIN core_participes ON core_creditos.id_participes = core_participes.id_participes';
                     $where = "core_participes.cedula_participes='$cedula_participe'
                     AND core_creditos.id_estado_creditos=4
                     AND core_creditos.id_estatus=1
                     AND id_tipo_creditos=" . $res->id_tipo_creditos_a_renovar;
                     $_result_monto_credito = $creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
                     */
                    //consulta para obtener la ultima cuota cancelada del credito
                    /*$col1 = "cc.id_tabla_amortizacion, cc.total_valor_tabla_amortizacion";
                     $tab1 = 'core_participes aa
                     INNER JOIN core_creditos bb ON bb.id_participes = aa.id_participes
                     INNER JOIN core_tabla_amortizacion cc ON cc.id_creditos = bb.id_creditos';
                     $whe1 = "1 = 1
                     AND aa.id_estatus = 1
                     AND bb.id_estatus = 1
                     AND cc.id_estatus = 1
                     AND cc.id_estado_tabla_amortizacion = 2
                     AND aa.cedula_participes = '$cedula_participe'
                     AND bb.id_estado_creditos = 4
                     AND bb.id_tipo_creditos = ".$res->id_tipo_creditos_a_renovar;
                     $lim1 = " ORDER BY cc.fecha_tabla_amortizacion DESC LIMIT 1";
                     */
                    $col1 = " SUM(aa.total_valor_tabla_amortizacion) total_valor_tabla_amortizacion ";
                    $tab1 = " core_tabla_amortizacion aa
                        INNER JOIN core_creditos bb	ON bb.id_creditos = aa.id_creditos
                        INNER JOIN core_participes cc ON cc.id_participes = bb.id_participes
                        INNER JOIN (
                            SELECT MAX( sa.fecha_tabla_amortizacion ) fecha_cuota, sa.id_creditos
                            FROM core_tabla_amortizacion sa
                            WHERE sa.id_estatus = 1
                            AND sa.id_estado_tabla_amortizacion = 2
                            GROUP BY sa.id_creditos
                            )dd ON dd.id_creditos = aa.id_creditos AND dd.fecha_cuota = aa.fecha_tabla_amortizacion";
                    $whe1 = "1 = 1
                        AND aa.id_estatus = 1
                        and bb.id_estado_creditos = 4
                        and cc.id_estatus = 1
                        and cc.cedula_participes = '$cedula_participe'
                        and bb.id_tipo_creditos =".$res->id_tipo_creditos_a_renovar;
                    $lim1 = " ";
                    
                    $_result_monto_credito = $creditos->getCondicionesSinOrden( $col1, $tab1, $whe1, $lim1);
                    
                    if (! empty($_result_monto_credito)) {
                        
                        foreach ($_result_monto_credito as $res1) {
                            
                            // OBTENGO LA CUOTA MENSUAL QUE PAGA POR EL CREDITO
                            /*$tasa_interes = $res1->interes_creditos;
                             $tasa_interes = $tasa_interes / 100;
                             $interes_mensual = $tasa_interes / 12;
                             $valor_cuota = ($res1->monto_otorgado_creditos * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $res1->plazo_creditos));
                             $valor_cuota = round($valor_cuota, 2);*/
                            
                            // SUMO TODAS LAS CUOTAS DE LOS CREDITOS A RENOVAR
                            //$cuota_total += $valor_cuota;
                            $cuota_total    += $res1->total_valor_tabla_amortizacion;
                        } // fin foreach de recorrido
                    } // fin de validacion de si hay datos en $_result_monto_credito
                }
            }
            
            $response['estatus'] = "OK";
            $response['cuota_creditos'] = $cuota_total;
            
            $salida = ob_get_clean();
            if (! empty($salida)) {
                throw new Exception();
            }
        } catch (Exception $e) {
            
            $response['estatus'] = "ERROR";
            $response['mensaje'] = "error al determinar cuotas de creditos activos";
            $response['buffer'] = error_get_last();
        }
        
        echo json_encode($response);
    }
    
    // FUNCION PARA VERIFICAR EL VALOR MENSUAL DE CUOTAS DE CREDITOS QUE TIENE EL PARTICIPE
    public function cuotaParticipe()
    {
        session_start();
        $creditos = new EstadoModel();
        $cedula_participe = $_POST['cedula_participe'];
        $tipo_credito = $_POST['tipo_credito'];
        $cuota_total = 0.00;
        
        // BUSCO LOS TIPOS DE CREDITOS QUE SE PUEDEN RENOVAR
        
        $columnas = "tcr.id_tipo_creditos_a_renovar";
        $tablas = "core_tipo_creditos_renovacion tcr INNER JOIN core_tipo_creditos tc ON tc.id_tipo_creditos = tcr.id_tipo_creditos";
        $where = "tc.codigo_tipo_creditos='" . $tipo_credito . "' AND tcr.id_estado=107";
        $_result_creditos_renovar = $creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        if (! empty($_result_creditos_renovar)) {
            
            foreach ($_result_creditos_renovar as $res) {
                
                // CONSULTO MONTO OTORGADO POR EL TIPO DE CREDITO A RENOVAR
                $columnas = 'monto_otorgado_creditos, plazo_creditos, interes_creditos';
                $tablas = 'core_creditos INNER JOIN core_participes
                ON core_creditos.id_participes = core_participes.id_participes';
                $where = "core_participes.cedula_participes='$cedula_participe' AND core_creditos.id_estado_creditos=4
                AND core_creditos.id_estatus=1 AND id_tipo_creditos=" . $res->id_tipo_creditos_a_renovar;
                $_result_monto_credito = $creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
                
                if (! empty($_result_monto_credito)) {
                    
                    foreach ($_result_monto_credito as $res1) {
                        
                        // OBTENGO LA CUOTA MENSUAL QUE PAGA POR EL CREDITO
                        $tasa_interes = $res1->interes_creditos;
                        $tasa_interes = $tasa_interes / 100;
                        $interes_mensual = $tasa_interes / 12;
                        $valor_cuota = ($res1->monto_otorgado_creditos * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $res1->plazo_creditos));
                        $valor_cuota = round($valor_cuota, 2);
                        
                        // SUMO TODAS LAS CUOTAS DE LOS CREDITOS A RENOVAR
                        $cuota_total += $valor_cuota;
                    }
                }
            }
        }
        echo $cuota_total;
    }
    
    public function obtenerCuotaGarante()
    {
        ob_start();
        session_start();
        $response = array();
        $creditos = new EstadoModel();
        $cedula_participe = $_POST['cedula_participe'];
        $cuota_total = 0;
        $columnas = 'monto_otorgado_creditos, plazo_creditos, interes_creditos';
        $tablas = 'core_creditos INNER JOIN core_participes
        ON core_creditos.id_participes = core_participes.id_participes
        INNER JOIN core_tipo_creditos
        ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos';
        
        $where = "core_participes.cedula_participes='$cedula_participe' AND core_creditos.id_estado_creditos=4
        AND core_creditos.id_estatus=1";
        
        $id_credito = $creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        foreach ($id_credito as $res) {
            
            $tasa_interes = $res->interes_creditos;
            $tasa_interes = $tasa_interes / 100;
            $interes_mensual = $tasa_interes / 12;
            $valor_cuota = ($res->monto_otorgado_creditos * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $res->plazo_creditos));
            $valor_cuota = round($valor_cuota, 2);
            $cuota_total += $valor_cuota;
        }
        $salida = ob_get_clean();
        if (! empty($salida)) {
            $response['estatus'] = "ERROR";
            $response['buffer'] = error_get_last();
        } else {
            $response['estatus'] = "OK";
            $response['cuota_total'] = $cuota_total * (- 1);
        }
        
        echo json_encode($response);
    }
    
    public function cuotaGarante()
    {
        session_start();
        $creditos = new EstadoModel();
        $cedula_participe = $_POST['cedula_participe'];
        $cuota_total = 0;
        $columnas = 'monto_otorgado_creditos, plazo_creditos, interes_creditos';
        $tablas = 'core_creditos INNER JOIN core_participes
        ON core_creditos.id_participes = core_participes.id_participes
        INNER JOIN core_tipo_creditos
        ON core_creditos.id_tipo_creditos = core_tipo_creditos.id_tipo_creditos';
        
        $where = "core_participes.cedula_participes='$cedula_participe' AND core_creditos.id_estado_creditos=4
        AND core_creditos.id_estatus=1";
        
        $id_credito = $creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        foreach ($id_credito as $res) {
            $tasa_interes = $res->interes_creditos;
            $tasa_interes = $tasa_interes / 100;
            $interes_mensual = $tasa_interes / 12;
            $valor_cuota = ($res->monto_otorgado_creditos * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $res->plazo_creditos));
            $valor_cuota = round($valor_cuota, 2);
            $cuota_total += $valor_cuota;
        }
        
        echo $cuota_total * - 1;
    }
    
    public function ActualizarSolicitud($id_solicitud, $monto, $plazo, $id_credito)
    {
        ob_start();
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
        $colval = "id_estado_tramites=2, monto_datos_prestamo=" . $monto . ", plazo_datos_prestamo=" . $plazo . ", numero_creditos=" . $id_credito;
        $tabla = "solicitud_prestamo";
        $where = "id_solicitud_prestamo=" . $id_solicitud;
        $db->ActualizarBy($colval, $tabla, $where);
        
        return ob_get_clean();
    }
    
    public function genera_codigo()
    {
        session_start();
        $rp_capremci = new PlanCuentasModel();
        $tipo_creditos = $_POST['tipo_credito'];
        $columnas = "interes_tipo_creditos";
        $tablas = "core_tipo_creditos";
        $where = "codigo_tipo_creditos='" . $tipo_creditos . "'";
        
        $resultSet = $rp_capremci->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $interes_credito = $resultSet[0]->interes_tipo_creditos;
        
        $cadena = "1234567890";
        $longitudCadena = strlen($cadena);
        $codigo = "";
        $longitudPass = 5;
        for ($i = 1; $i <= $longitudPass; $i ++) {
            $pos = rand(0, $longitudCadena - 1);
            $codigo .= substr($cadena, $pos, 1);
        }
        $resultado = array();
        array_push($resultado, $codigo, $interes_credito);
        echo json_encode($resultado);
    }
        
    // PARA OBTENER SALDO ACTUAL
    public function devuelve_saldo_capital($id_creditos)
    {
        session_start();
        $creditos = new CoreCreditoModel();
        $saldo_credito = 0;
        
        $columnas_pag = "coalesce(sum(tap.saldo_cuota_tabla_amortizacion_pagos),0) as saldo";
        $tablas_pag = "core_creditos c
                        inner join core_tabla_amortizacion at on c.id_creditos=at.id_creditos
                        inner join core_tabla_amortizacion_pagos tap on at.id_tabla_amortizacion=tap.id_tabla_amortizacion
                        inner join core_tabla_amortizacion_parametrizacion tapa on tap.id_tabla_amortizacion_parametrizacion=tapa.id_tabla_amortizacion_parametrizacion";
        $where_pag = "c.id_creditos='$id_creditos' and c.id_estatus=1 and at.id_estatus=1 and tapa.tipo_tabla_amortizacion_parametrizacion=0";
        
        $resultPagos = $creditos->getCondicionesSinOrden($columnas_pag, $tablas_pag, $where_pag, "");
        
        if (! empty($resultPagos)) {
            
            $saldo_credito = $resultPagos[0]->saldo;
        }
        
        return $saldo_credito;
    }
    
    /**
     * *** BEGIN DC cambios nuevos *******
     */
    
    
    /**
     * ****************************** END CAMBIOS DC ******************************
     */
    
   
    /**
     * *dc 2020/05/21 *
     */
    public function InsertarSimulacionCredito()
    {
        session_start();
        ob_start();
        $mensage = "";
        $respuesta = true;
        $credito = new CoreTipoCreditoModel();
        
        $usuario    = $_SESSION['usuario_usuarios'];
        $monto_credito  = $_POST['monto_credito'];
        $tipo_credito   = $_POST['tipo_credito'];
        $con_garante    = $_POST['con_garante'];
        $cuota  = $_POST['cuota_credito'];
        $cedula_participe       = $_POST['cedula_participe'];
        $observacion_credito    = $_POST['observacion_credito'];
        $id_solicitud   = $_POST['id_solicitud'];
        $id_creditos_productos  = $_POST['id_creditos_productos'];
        
        if( $con_garante )
        {
            $id_garante = $_POST['cedula_garante'];
        }
        
        $interes_consecion = 0;
        $fecha_pago = date("Y-m-d");
        $hoy = date("Y-m-d");
        
        //obtener id e interes de tipo credito
        $columnas = "interes_tipo_creditos, id_tipo_creditos";
        $tablas = "core_tipo_creditos";
        $where = "codigo_tipo_creditos='" . $tipo_credito . "'";
        $resultSet = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $tasa_interes   = $resultSet[0]->interes_tipo_creditos;
        $id_tipo_creditos   = $resultSet[0]->id_tipo_creditos;
        
        $interes_credito    = $tasa_interes;
        $tasa_interes       = $tasa_interes / 100;
        $interes_mensual    = $tasa_interes / 12;
        //$plazo_dias = $cuota * 30;
        
        //calculo de cuota
        $valor_cuota = ($monto_credito * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $cuota));
        $valor_cuota = round($valor_cuota, 2);
        
        if ($tipo_credito == "PH")
        {
            $columnas = "valor_avaluo_core_documentos_hipotecario";
            $tablas = "core_documentos_hipotecario";
            $where = "id_solicitud_credito=" . $id_solicitud;
            $avaluo_credito = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
            $avaluo_credito = $avaluo_credito[0]->valor_avaluo_core_documentos_hipotecario;
            
            $resultAmortizacion = $this->tablaAmortizacionHipotecario($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_pago, $tasa_interes, $avaluo_credito);
        }else
        {
            $resultAmortizacion = $this->tablaAmortizacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_pago, $tasa_interes);
        }
        
        $columnas   = " id_participes";
        $tablas     = " core_participes";
        $where      = " id_estatus = 1 AND cedula_participes='" . $cedula_participe . "'";
        $id_participe = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $id_participe = $id_participe[0]->id_participes;
        
        $columnas   = "numero_consecutivos";
        $tablas     = "consecutivos";
        $where      = "nombre_consecutivos='CREDITO'";
        $numero_credito = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $numero_credito = $numero_credito[0]->numero_consecutivos;
        $numero_credito ++;
        
        $columnas   = " id_estado";
        $tablas     = " estado";
        $where      = " tabla_estado='tabla_core_creditos_garantias' AND nombre_estado='ACTIVO'";
        $id_estado = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $id_estado = $id_estado[0]->id_estado;
        
        //buscar parametros para desglose de amortizacion
        $datosDesglosePagos = $this->obtenerTipoPagosAmortizacion($tipo_credito);
        
        // cambiar numero de credito por numero solicitud
        $credito->beginTran();
        
        /* dc */
        //SINTAXERROR --va variables que no estan definidas
        $impuesto_exento_seguro     = 0;
        $base_calculo_participes    = 0;
        $id_comprobantes            = 'null';
        $id_forma_pago              = 'null';
        
        $plazo_creditos = $cuota;
        
        $monto_neto = 0;
        $insercion  = 1; //$mensajeInsercion='';
        
        foreach ($resultAmortizacion as $res)
        {
            
            //$mensajeInsercion .= "Numero Insersion ".$insercion;
            $insercion++;
            
            if( $res['interes_concesion'] != 0 )
            {
                $interes_consecion = $res['interes_concesion'];
                
                $monto_neto = $monto_credito - $interes_consecion;
                
                $funcion    = "cre_ins_core_creditos";
                $parametros = "'$numero_credito', '$id_participe', '$id_creditos_productos', '$monto_credito', '$monto_credito', '$hoy', '2', '$plazo_creditos', '$monto_neto','$numero_credito', '$id_tipo_creditos', '$observacion_credito', '1', '$usuario', '$interes_credito', '$impuesto_exento_seguro', '$hoy','$base_calculo_participes', $id_comprobantes,$id_forma_pago,'$valor_cuota' ";
                
                $credito->setFuncion($funcion);
                $credito->setParametros($parametros);
                $queryInsert = $credito->getconsultaPG($credito->getFuncion(), $credito->getParametros());
                $resultado = $credito->llamarconsultaPG($queryInsert);
                $id_creditos = $resultado[0];
                
                $fecha_pago = $res['fecha_pago'];
                $num_cuota  = $res['pagos_trimestrales'];
                $amortizacion   = $res['amortizacion'];
                $intereses      = $res['interes'];
                $saldo_inicial  = $res['saldo_inicial'];
                $desgravamen    = $res['desgravamen'];
                $incendios      = 0;
                $dividendo = $res['pagos'];
                
                if ($tipo_credito == "PH")
                {
                    $incendios = $res['seguro_incendios'];
                }
                
                $total_valor = $amortizacion + $intereses + $desgravamen + $incendios;
                
                $function   = "cre_ins_core_tabla_amortizacion";
                $params     = " '1', '$id_creditos', '$fecha_pago', '$num_cuota', '$amortizacion', '$intereses', '$dividendo',
                     '$saldo_inicial', '$total_valor', '$total_valor', '3', '1', '$tasa_interes', '$hoy' ";
                
                $queryInsert    = $credito->getconsultaPG($function, $params);
                $rsInsert       = $credito->llamarconsultaPG($queryInsert);
                $id_tabla_amortizacion = $rsInsert[0];
                
                //setear valores de amortizacion dc 2020/08/03
                $valores = array( 'capital'=> $amortizacion,
                    'interes'=>$intereses,
                    'desgravamen'=>$desgravamen,
                    'mora'=>0.00,
                    'incendios'=>$incendios);
                
                //trabajar con el desgloge de pagos-amortizacion
                $this->generarDesglosePagos( $datosDesglosePagos, $valores, $id_tabla_amortizacion);
                
                if ($con_garante == "true")
                {
                    $columnas = "id_participes";
                    $tablas = "core_participes";
                    $where = "cedula_participes='" . $id_garante . "'";
                    
                    $id_garante = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
                    $id_garante = $id_garante[0]->id_participes;
                    
                    $query = "INSERT INTO core_creditos_garantias
                       (id_creditos, id_participes, id_estado, usuario_usuarios)
                       VALUES(" . $id_creditos . ", " . $id_garante . ", " . $id_estado . ", '" . $usuario . "')";
                    
                    $credito->executeNonQuery($query);
                }
                
                #INSERTAR se realiza insercion primera cuota
                $this->ingresarRetencionPrimeraCuota($id_creditos, $res['interes_concesion'] );
                
                $errores_credito = ob_get_clean();
                $errores_credito = trim($errores_credito);
                
                if (empty($errores_credito))
                {
                    $query  = "INSERT INTO core_creditos_retenciones( monto_creditos_retenciones, id_creditos )
                        VALUES (" . $res['interes_concesion'] . ", " . $id_creditos . ")";
                    $credito->executeNonQuery($query);
                    
                } else
                {
                    $credito->endTran('ROLLBACK');
                    $respuesta = false;
                    $mensage = "ERROR Credito" . $errores_credito . "--" . $num_cuota;
                    break;
                }
                
                //termina if de interes de concesion diferente de cero
            } else
            {
                $fecha_pago     = $res['fecha_pago'];
                $num_cuota      = $res['pagos_trimestrales'];
                $amortizacion   = $res['amortizacion'];
                $intereses      = $res['interes'];
                $saldo_inicial  = $res['saldo_inicial'];
                $desgravamen    = $res['desgravamen'];
                $dividendo      = $res['pagos'];
                $incendios  = 0;
                
                if ($tipo_credito == "PH")
                {
                    $incendios = $res['seguro_incendios'];
                }
                
                $total_valor = $amortizacion + $intereses + $desgravamen + $incendios;
                
                $function   = "cre_ins_core_tabla_amortizacion";
                $params     = " '1', '$id_creditos', '$fecha_pago', '$num_cuota', '$amortizacion', '$intereses', '$dividendo',
                     '$saldo_inicial', '$total_valor', '$total_valor', '3', '1', '$tasa_interes', '$hoy' ";
                
                $queryInsert    = $credito->getconsultaPG($function, $params);
                $rsInsert       = $credito->llamarconsultaPG($queryInsert);
                $id_tabla_amortizacion = $rsInsert[0];
                
                //setear valores de amortizacion dc 2020/08/03
                $valores = array( 'capital'=> $amortizacion,
                    'interes'=>$intereses,
                    'desgravamen'=>$desgravamen,
                    'mora'=>0.00,
                    'incendios'=>$incendios);
                
                //trabajar con el desgloge de pagos-amortizacion
                $this->generarDesglosePagos( $datosDesglosePagos, $valores, $id_tabla_amortizacion);
                
                $errores = ob_get_clean();
                $errores = trim($errores);
                
                if (! (empty($errores)))
                {
                    $credito->endTran('ROLLBACK');
                    $respuesta = false;
                    $mensage = "ERROR credito" . $errores . "==" . $num_cuota;
                    break;
                }
                
            }
        }
        
        
        #INSERTAR se realiza insercion Reafiliacion
        if( $respuesta && $this->validarReAfiliacion($id_participe) )
        {
            #METODO insertar reafiliacion
            $resultado_ins_retenciones_aportes  = $this->ingresarRetencionesAportes($id_creditos, $id_participe, $hoy);
            if( $resultado_ins_retenciones_aportes != 1 || !empty(error_get_last()) )
            {
                $credito->endTran('ROLLBACK');
                $mensage = "ERROR al ingresar retenciones de aportes ".error_get_last()['message'];
                $respuesta = false;
            }
        }
        
        
        if( $respuesta )
        {
            $actualizacion_solicitud = $this->ActualizarSolicitud($id_solicitud, $monto_credito, $cuota, $numero_credito);
            $plan_cuentas = new PlanCuentasModel();
            $colval = "numero_consecutivos=" . $numero_credito;
            $tabla = "consecutivos";
            $where = "nombre_consecutivos='CREDITO'";
            $actualizacion_solicitud = trim($actualizacion_solicitud);
            if (empty($actualizacion_solicitud)) {
                ob_start();
                $plan_cuentas->ActualizarBy($colval, $tabla, $where);
                $actualizacion_consecutivo = ob_get_clean();
                $actualizacion_consecutivo = trim($actualizacion_consecutivo);
                if (empty($actualizacion_consecutivo)) {
                    $actualizar_cuentas = $this->ActualizarCuentasParticipes($id_solicitud, $numero_credito);
                    if (empty($actualizar_cuentas)) {
                        
                        $actualizar_info_participes = $this->ActualizarInfoParticipe($cedula_participe, $id_solicitud);
                        if (empty($actualizar_info_participes)) {
                            $credito->endTran('COMMIT');
                            
                            $mensage = "OK";
                        } else {
                            $credito->endTran('ROLLBACK');
                            $mensage = "ERROR 1 " . $actualizar_info_participes;
                        }
                    } else {
                        $credito->endTran('ROLLBACK');
                        $mensage = "ERROR 2 " . $actualizar_cuentas . " " . $id_solicitud . " " . $numero_credito;
                    }
                } else {
                    $credito->endTran('ROLLBACK');
                    $mensage = "ERROR 3 " . $actualizacion_consecutivo;
                }
            } else {
                echo "solicitud no aceptada";
                $credito->endTran('ROLLBACK');
                $mensage = "ERROR 4 " . $actualizacion_solicitud;
            }
        }
        
        echo $mensage;
    }
    
    /** dc 2020/08/20 **/
    public function insertarRenovacionCredito()
    {
        session_start();
        ob_start();
        $mensage = "";
        $total_retencion = 0;
        $respuesta = true;
        $credito = new CoreTipoCreditoModel();
        
        $usuario            = $_SESSION['usuario_usuarios'];
        $monto_credito      = $_POST['monto_credito'];
        $tipo_credito       = $_POST['tipo_credito'];
        $cuota              = $_POST['cuota_credito'];
        $cedula_participe   = $_POST['cedula_participe'];
        $observacion_credito= $_POST['observacion_credito'];
        $id_solicitud       = $_POST['id_solicitud'];
        $id_creditos_productos  = $_POST['id_creditos_productos'];
        
        $con_garante    = $_POST['con_garante'];
        
        if ($con_garante)
        {
            $id_garante = $_POST['cedula_garante'];
        }
        
        $columnas = "interes_tipo_creditos";
        $tablas = "core_tipo_creditos";
        $where = "codigo_tipo_creditos='" . $tipo_credito . "'";
        $resultSet = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $tasa_interes = $resultSet[0]->interes_tipo_creditos;
        
        $columnas = "id_tipo_creditos";
        $tablas = "core_tipo_creditos";
        $where = "codigo_tipo_creditos='" . $tipo_credito . "'";
        $id_tipo_creditos = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $id_tipo_creditos = $id_tipo_creditos[0]->id_tipo_creditos;
        
        $fecha_pago = date("Y-m-d");
        $interes_credito = $tasa_interes;
        
        $tasa_interes = $tasa_interes / 100;
        
        $interes_consecion = 0;
        
        $interes_mensual = $tasa_interes / 12;
        //$plazo_dias = $cuota * 30;
        
        $valor_cuota = ($monto_credito * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $cuota));
        $valor_cuota = round($valor_cuota, 2);
        
        if ($tipo_credito == "PH")
        {
            $columnas = "valor_avaluo_core_documentos_hipotecario";
            $tablas = "core_documentos_hipotecario";
            $where = "id_solicitud_credito=" . $id_solicitud;
            $avaluo_credito = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
            $avaluo_credito = $avaluo_credito[0]->valor_avaluo_core_documentos_hipotecario;
            
            $resultAmortizacion = $this->tablaAmortizacionRenovacionHipotecario(
                $monto_credito,
                $cuota,
                $interes_mensual,
                $valor_cuota,
                $fecha_pago,
                $tasa_interes,
                $avaluo_credito
                );
        }else
        {
            $resultAmortizacion = $this->tablaAmortizacionRenovacion(
                $monto_credito,
                $cuota,
                $interes_mensual,
                $valor_cuota,
                $fecha_pago,
                $tasa_interes
                );
        }
        
        $columnas = "id_participes";
        $tablas = "core_participes";
        $where = "cedula_participes='" . $cedula_participe . "'";
        $id_participe = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $id_participe = $id_participe[0]->id_participes;
        
        $columnas = "id_estado_creditos";
        $tablas = "core_estado_creditos";
        $where = "nombre_estado_creditos='En proceso de Renovacion'";
        $id_estado_renovacion = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $id_estado_renovacion = $id_estado_renovacion[0]->id_estado_creditos;
        
        $columnas = "numero_consecutivos";
        $tablas = "consecutivos";
        $where = "nombre_consecutivos='CREDITO'";
        $numero_credito = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $numero_credito = $numero_credito[0]->numero_consecutivos;
        $numero_credito ++;
        $hoy = date("Y-m-d");
        
        $columnas = "id_estado";
        $tablas = "estado";
        $where = "tabla_estado='tabla_core_creditos_garantias' AND nombre_estado='ACTIVO'";
        $id_estado = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $id_estado = $id_estado[0]->id_estado;
        
        //empieza transaccion
        $credito->beginTran();
        
        //SINTAXERROR --va variables que no estan definidas
        $impuesto_exento_seguro     = 0;
        $base_calculo_participes    = 0;
        $id_comprobantes            = 'null';
        $id_forma_pago              = 'null';
        $plazo_creditos             = $cuota;
        
        $monto_neto = $monto_credito - $interes_consecion;
        
        //inserta tabla core_creditos
        $funcion    = "cre_ins_core_creditos";
        $parametros = "'$numero_credito', '$id_participe', '$id_creditos_productos', '$monto_credito', '$monto_credito', '$hoy', '2', '$plazo_creditos', '$monto_neto','$numero_credito', '$id_tipo_creditos', '$observacion_credito', '1', '$usuario', '$interes_credito', '$impuesto_exento_seguro', '$hoy','$base_calculo_participes', $id_comprobantes,$id_forma_pago,'$valor_cuota' ";
        
        $credito->setFuncion($funcion);
        $credito->setParametros($parametros);
        $queryInsert = $credito->getconsultaPG($credito->getFuncion(), $credito->getParametros());
        $resultado = $credito->llamarconsultaPG($queryInsert);
        $id_creditos = $resultado[0];
        
        // BUSCAR CREDITOS A RENOVAR CON SU VALOR
        $colfun1    = " id_creditos_out, suma_capital_out, suma_seguro_out, suma_total_out";
        $tabfun1    = " cre_obtener_saldos_renovacion_creditos('$tipo_credito','$fecha_pago',$id_participe) ";
        $whefun1    = " 1 = 1";
        
        $rsConsulta1    = $credito->getCondicionesSinOrden($colfun1, $tabfun1, $whefun1, "" );
        
        $total_retencion = 0;
        foreach ( $rsConsulta1 as $res ){
            
            $id_creditos_renovar    = $res->id_creditos_out;
            $capital_renovar        = $res->suma_capital_out;
            $desgravamen_renovar    = $res->suma_seguro_out;
            $total_retencion        += $res->suma_total_out;
            
            $query = "INSERT INTO core_creditos_renovaciones
                    (id_creditos_renovado, id_creditos_nuevo, saldo_credito_renovado_creditos_renovaciones, seguro_desgravamen_creditos_renovaciones)
                VALUES ( $id_creditos_renovar," . $id_creditos . ",$capital_renovar," . $desgravamen_renovar . ")";
            $credito->executeNonQuery($query);
            
            $query = "UPDATE core_creditos SET id_estado_creditos=" . $id_estado_renovacion . " WHERE id_creditos=" . $id_creditos_renovar;
            $credito->executeNonQuery($query);
            
            #INSERTAR en la tabla migrada
            $datosRenovacion    = array();
            $datosRenovacion['id_creditos']         = $id_creditos;
            $datosRenovacion['id_creditos_renovar'] = $id_creditos_renovar;
            $datosRenovacion['saldo']               = ($capital_renovar + $desgravamen_renovar);
            $datosRenovacion['observacion']         = "Generación de proceso de renovación";
            $datosRenovacion['estado']              = 1;
            
            $respRenovacion = $this->ingresarRegistroRenovaciones($datosRenovacion);
            
            if( $respRenovacion['error'] )
            {
                echo $respRenovacion['mensaje'];
            }
        }
        
        #ACTUALUZAR consecutivo de credito
        //$query = "UPDATE consecutivos SET numero_consecutivos ='" . $numero_credito . "' WHERE nombre_consecutivos='CREDITO'";
        //$credito->executeNonQuery($query);
        
        $total_retencion = number_format((float) $total_retencion, 2, ".", "");
        $monto_neto = $monto_credito - $total_retencion;
        
        $query = "INSERT INTO core_creditos_retenciones( monto_creditos_retenciones, id_creditos) VALUES(" . $total_retencion . ", " . $id_creditos . ")";
        
        $credito->executeNonQuery($query);
        
        //AQUI ACTUALIZAR EL MONTO A RECIBIR
        $query = "UPDATE core_creditos SET monto_neto_entregado_creditos='" . $monto_neto . "' WHERE id_creditos=" . $id_creditos;
        $credito->executeNonQuery($query);
        
        if ($con_garante == "true")
        {
            $columnas = "id_participes";
            $tablas = "core_participes";
            $where = "cedula_participes='" . $id_garante . "'";
            
            $id_garante = $credito->getCondicionesSinOrden($columnas, $tablas, $where, "");
            $id_garante = $id_garante[0]->id_participes;
            
            $query = "INSERT INTO core_creditos_garantias
                   (id_creditos, id_participes, id_estado, usuario_usuarios)
                   VALUES(" . $id_creditos . ", " . $id_garante . ", " . $id_estado . ", '" . $usuario . "')";
            
            $credito->executeNonQuery($query);
        }
        
        //buscar parametros para desglose de amortizacion
        $datosDesglosePagos = $this->obtenerTipoPagosAmortizacion($tipo_credito);
        
        foreach ($resultAmortizacion as $res)
        {
            
            $fecha_pago = $res['fecha_pago'];
            $num_cuota = $res['pagos_trimestrales'];
            $amortizacion = $res['amortizacion'];
            $intereses = $res['interes'];
            $saldo_inicial = $res['saldo_inicial'];
            $desgravamen = $res['desgravamen'];
            $dividendo = $res['pagos'];
            
            $incendios  = 0.00;
            if ($tipo_credito == "PH")
            {
                $incendios = $res['seguro_incendios'];
            }
            
            $total_valor = $amortizacion + $intereses + $desgravamen;
            
            $function   = "cre_ins_core_tabla_amortizacion";
            $params     = " '1', '$id_creditos', '$fecha_pago', '$num_cuota', '$amortizacion', '$intereses', '$dividendo',
                     '$saldo_inicial', '$total_valor', '$total_valor', '3', '1', '$tasa_interes', '$hoy' ";
            
            $queryInsert    = $credito->getconsultaPG($function, $params);
            $rsInsert       = $credito->llamarconsultaPG($queryInsert);
            $id_tabla_amortizacion = $rsInsert[0];
            
            //setear valores de amortizacion dc 2020/08/03
            $valores = array( 'capital'=> $amortizacion,
                'interes'=>$intereses,
                'desgravamen'=>$desgravamen,
                'mora'=>0.00,
                'incendios'=>$incendios);
            
            //trabajar con el desgloge de pagos-amortizacion
            $this->generarDesglosePagos( $datosDesglosePagos, $valores, $id_tabla_amortizacion);
            
            $errores = ob_get_clean();
            $errores = trim($errores);
            
            if (! (empty($errores)))
            {
                $credito->endTran('ROLLBACK');
                $respuesta = false;
                $mensage = "ERROR" . $errores;
                break;
            }
        }
        
        if ($respuesta) {
            $actualizacion_solicitud = $this->ActualizarSolicitud($id_solicitud, $monto_credito, $cuota, $numero_credito);
            $plan_cuentas = new PlanCuentasModel();
            $actualizacion_solicitud = trim($actualizacion_solicitud);
            if (empty($actualizacion_solicitud)) {
                ob_start();
                $colval = "numero_consecutivos=" . $numero_credito;
                $tabla = "consecutivos";
                $where = "nombre_consecutivos='CREDITO'";
                $plan_cuentas->ActualizarBy($colval, $tabla, $where);
                $actualizacion_consecutivo = ob_get_clean();
                $actualizacion_consecutivo = trim($actualizacion_consecutivo);
                if (empty($actualizacion_consecutivo)) {
                    $actualizar_cuentas = $this->ActualizarCuentasParticipes($id_solicitud, $numero_credito);
                    if (empty($actualizar_cuentas)) {
                        
                        $actualizar_info_participes = $this->ActualizarInfoParticipe($cedula_participe, $id_solicitud);
                        if (empty($actualizar_info_participes)) {
                            $credito->endTran('COMMIT');
                            
                            $mensage = "OK";
                        } else {
                            $credito->endTran('ROLLBACK');
                            $mensage = "ERROR 1 " . $actualizar_info_participes;
                        }
                    } else {
                        $credito->endTran('ROLLBACK');
                        $mensage = "ERROR 2 " . $actualizar_cuentas . " " . $id_solicitud . " " . $numero_credito;
                    }
                } else {
                    $credito->endTran('ROLLBACK');
                    $mensage = "ERROR 3 " . $actualizacion_consecutivo;
                }
            } else {
                echo "solicitud no aceptada";
                $credito->endTran('ROLLBACK');
                $mensage = "ERROR 4 " . $actualizacion_solicitud;
            }
        }
        echo $mensage;
    }
    
    
    /**
     * dc 2020/05/21
     */
    public function obtenerSimulacionCredito()
    {
        //para pruebas
        //$_POST['monto_credito'] = 2500;
        //$_POST['tipo_credito']  = "ORD";
        //$_POST['plazo_credito'] = 36;
        //$_POST['renovacion_credito']    = 'true';
        //$_POST['id_solicitud']  = 1;
        //$_POST['avaluo_bien']   = 0;
        //$fecha_corte    ='2020-07-22';
        //termina pruebas
        ob_start();
        
        $response   = array();
        
        session_start();
        $cuotas = new PlanCuentasModel();
        
        $fecha_corte    = date('Y-m-d');
        $monto_credito  = $_POST['monto_credito'];
        $id_solicitud   = $_POST['id_solicitud'];
        
        if ($id_solicitud == 0)
        {
            // para simulador
            $avaluo_bien = $_POST['avaluo_bien'];
        } else
        {
            // para produccion
            $avaluo_bien = 0;
        }
        
        $cuota = $_POST['plazo_credito'];
        $tipo_credito = $_POST['tipo_credito'];
        $renovacion_credito = $_POST['renovacion_credito'];
        
        if ($tipo_credito == "PH" && $id_solicitud != 0)
        {
            // producion hipotecario
            $columnas = "valor_avaluo_core_documentos_hipotecario";
            $tablas = "core_documentos_hipotecario";
            $where = "id_solicitud_credito=" . $id_solicitud;
            $avaluo_credito = $cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
            $avaluo_credito = $avaluo_credito[0]->valor_avaluo_core_documentos_hipotecario;
            
        } else
        {
            // simulador
            $avaluo_credito = $avaluo_bien;
        }
        
        // obtengo la taza de interes del credito seleccionado
        $columnas = "interes_tipo_creditos";
        $tablas = "core_tipo_creditos";
        $where = "codigo_tipo_creditos='" . $tipo_credito . "'";
        
        $resultSet = $cuotas->getCondicionesSinOrden($columnas, $tablas, $where, "");
        $tasa_interes = $resultSet[0]->interes_tipo_creditos;
        $tasa_interes = $tasa_interes / 100;
        $interes_mensual = $tasa_interes / 12;
        
        $valor_cuota = ($monto_credito * $interes_mensual) / (1 - pow((1 + $interes_mensual), - $cuota));
        //$valor_cuota = round($valor_cuota, 2);
        
        if ($tipo_credito == "PH")
        {
            if ($renovacion_credito == "true")
            {
                $resultAmortizacion = $this->tablaAmortizacionRenovacionHipotecario($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes, $avaluo_credito);
            } else
            {
                $resultAmortizacion = $this->tablaAmortizacionHipotecario($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes, $avaluo_credito);
            }
        } else
        {
            if ($renovacion_credito == "true")
            {
                $resultAmortizacion = $this->tablaAmortizacionRenovacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes);
            } else
            {
                $resultAmortizacion = $this->tablaAmortizacion($monto_credito, $cuota, $interes_mensual, $valor_cuota, $fecha_corte, $tasa_interes);
            }
        }
        
        //trabajamos con el array obtenido de amortizacion
        
        $total_capital  = 0.00;
        $total_interes  = 0.00;
        $total_pagos    = 0.00;
        $total_desgravamen  = 0.00;
        $total_incendio = 0.00;
        
        foreach ($resultAmortizacion as $res)
        {
            $total_capital  += $res['amortizacion'];
            $total_interes  += $res['interes'];
            $total_desgravamen  += $res['desgravamen'];
            $total_pagos    += ( $res['pagos'] + $res['desgravamen'] );
            
            if ($tipo_credito == "PH")
            {
                $total_incendio += $res['seguro_incendios'];
            }
        }
        
        
        if ($tipo_credito == "PH")
        {
            $html = '<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Tabla de Amortización</h3>';
            
            if ($id_solicitud != 0)
            {
                $html .= '<button id="btn_guardar_simulacion_credito" class="btn btn-info pull-right" ><i class="glyphicon glyphicon-floppy-disk"></i> GUARDAR</button>';
                $html .= '<button id="btn_imprimir_simulacion_credito" class="btn btn-info pull-right" ><i class="glyphicon glyphicon-file"></i> IMPRIMIR</button>';
                $html .= '</div>
                    <table border="1" width="100%">
                         <tr style="color:white;" class="bg-olive">
                            <th width="5%">Cuota</th>
                            <th width="15%">Fecha</th>
                            <th width="13%">Capital</th>
                            <th width="13%">Interes</th>
                            <th width="13%">Seg. Desgravamen</th>
                            <th width="13%">Seg. Incendio</th>
                            <th width="13%">Cuota</th>
                            <th width="13%">Saldo</th>
                            <th width="2%"></th>
                         </tr>
                       </table>
                       <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                       <table border="1" width="100%">';
            }
            
            foreach ($resultAmortizacion as $res)
            {
                $html .= '<tr>';
                $html .= '<td width="5%" bgcolor="white"><font color="black">' . $res['pagos_trimestrales'] . '</font></td>';
                $html .= '<td width="15%" bgcolor="white" align="center"><font color="black">' . $res['fecha_pago'] . '</font></td>';
                $res['amortizacion'] = number_format((float) $res['amortizacion'], 2, ".", ",");
                $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $res['amortizacion'] . '</font></td>';
                $res['interes'] = number_format((float) $res['interes'], 2, ".", ",");
                $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $res['interes'] . '</font></td>';
                $cuota_pagar = $res['desgravamen'] + $res['pagos'];
                $res['desgravamen'] = number_format((float) $res['desgravamen'], 2, ".", ",");
                $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black" id="desgravamen' . $res['pagos_trimestrales'] . '">' . $res['desgravamen'] . '</font></td>';
                $res['seguro_incendios'] = number_format((float) $res['seguro_incendios'], 2, ".", ",");
                $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black" id="incendio' . $res['pagos_trimestrales'] . '">' . $res['seguro_incendios'] . '</font></td>';
                $cuota_pagar = number_format((float) $cuota_pagar, 2, ".", ",");
                $html .= '<td  width="13.2%" bgcolor="white" align="right"><font color="black" id="cuota_a_pagar' . $res['pagos_trimestrales'] . '">' . $cuota_pagar . '</font></td>';
                $res['saldo_inicial'] = number_format((float) $res['saldo_inicial'], 2, ".", ",");
                $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $res['saldo_inicial'] . '</font></td>';
                $html .= '</tr>';
            }
            
            $total_capital  = number_format( (float)$total_capital, 2, ".", "");
            $total_interes  = number_format( (float)$total_interes, 2, ".", "");
            $total_desgravamen  = number_format( (float)$total_desgravamen, 2, ".", "");
            $total_pagos    = number_format( (float)$total_pagos, 2, ".", "");
            
            $html .= '<tr>';
            $html .= '<td width="5%" bgcolor="white"><font color="black"></font></td>';
            $html .= '<td width="15%" bgcolor="white" align="center"><font color="black">Totales</font></td>';
            $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $total_capital . '</font></td>';
            $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $total_interes . '</font></td>';
            $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black">' . $total_desgravamen . '</font></td>';
            $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black" id="incendio' . $res['pagos_trimestrales'] . '">' . $total_incendio . '</font></td>';
            $html .= '<td width="13.2%" bgcolor="white" align="right"><font color="black">' . $total_pagos . '</font></td>';
            $html .= '<td width="13.4%" bgcolor="white" align="right"><font color="black"></font></td>';
            $html .= '</tr>';
            
            $html .= '</table></div>';
            
        } else
        {
            $html = '<div class="box box-solid bg-olive">
            <div class="box-header with-border">
            <h3 class="box-title">Tabla de Amortización</h3>';
            
            if($id_solicitud != 0)
            {
                $html .= '<button id="btn_guardar_simulacion_credito" class="btn btn-info pull-right" ><i class="glyphicon glyphicon-floppy-disk"></i> GUARDAR</button>';
                $html .= '<button id="btn_imprimir_simulacion_credito" class="btn btn-info pull-right" ><i class="glyphicon glyphicon-file"></i> IMPRIMIR</button>';
                $html .= '</div>
                     <table border="1" width="100%">
                             <tr style="color:white;" class="bg-olive">
                                <th width="5%">Cuota</th>
                                <th width="18%" >Fecha</th>
                                <th width="15%">Capital</th>
                                <th width="15%">Interes</th>
                                <th width="15%">Seg. Desgravamen</th>
                                <th width="15%">Cuota</th>
                                <th width="15%">Saldo</th>
                                <th width="2%"></th>
                             </tr>
                           </table>
                           <div style="overflow-y: scroll; overflow-x: hidden; height:200px; width:100%;">
                     <table border="1" width="100%">';
            }
            
            foreach ($resultAmortizacion as $res)
            {
                $index      = $res['pagos_trimestrales'];
                $fecha      = $res['fecha_pago'];
                $capital    = number_format( (float)$res['amortizacion'], 2, ".", "");
                $interes    = number_format( (float)$res['interes'], 2, ".", "");
                $desgravamen    = number_format( (float)$res['desgravamen'], 2, ".", ",");
                $pagos      = $res['desgravamen'] + $res['pagos'];
                $pagos      = number_format( (float)$pagos, 2, ".", ",");
                $saldo      = number_format( (float)$res['saldo_inicial'], 2, ".", ",");
                
                $html .= '<tr>';
                $html .= '<td width="5%" bgcolor="white"><font color="black">' . $index . '</font></td>';
                $html .= '<td width="18%" bgcolor="white" align="center"><font color="black">' . $fecha . '</font></td>';
                $html .= '<td width="15.2%" bgcolor="white" align="right"><font color="black">' . $capital . '</font></td>';
                $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black">' . $interes. '</font></td>';
                $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black" id="desgravamen' . $index . '">' . $desgravamen . '</font></td>';
                $html .= '<td  width="15.4%" bgcolor="white" align="right"><font color="black" id="cuota_a_pagar' . $index . '">' . $pagos . '</font></td>';
                $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black">' . $saldo . '</font></td>';
                $html .= '</tr>';
            }
            
            $total_capital  = number_format( (float)$total_capital, 2, ".", "");
            $total_interes  = number_format( (float)$total_interes, 2, ".", "");
            $total_desgravamen  = number_format( (float)$total_desgravamen, 2, ".", "");
            $total_pagos    = number_format( (float)$total_pagos, 2, ".", "");
            
            $html .= '<tr>';
            $html .= '<td width="5%" bgcolor="white"><font color="black"></font></td>';
            $html .= '<td width="18%" bgcolor="white" align="center"><font color="black">Totales</font></td>';
            $html .= '<td width="15.2%" bgcolor="white" align="right"><font color="black">' . $total_capital . '</font></td>';
            $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black">' . $total_interes . '</font></td>';
            $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black">' . $total_desgravamen . '</font></td>';
            $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black">' . $total_pagos . '</font></td>';
            $html .= '<td width="15.4%" bgcolor="white" align="right"><font color="black"></font></td>';
            $html .= '</tr>';
            
            $html .= '</table></div>';
            
        }
        
        $salida = ob_get_clean();
        if( !empty( $salida ) )
        {
            $response['estatus']    = "ERROR";
            $response['buffer']     = $salida;
        }else
        {
            $response['estatus']    = "OK";
            $response['html']     = $html;
        }
        
        echo json_encode($response);
    }
    
    /** dc 2020/07/17 **/
    function devuelveSegDesgravamen(float $saldo_inicial)
    {
        $desgravamen = ((0.163 / 1000) * $saldo_inicial) * 1.04;
        return $desgravamen;
    }
    /** end dc 2020/07/17 **/
    
    
    /** dc 2020/07/30 **/
    function devuelveFormulaDesgravamen()
    {
        $model  = new ModeloModel();
        
        $col1   = " expresion_formulas";
        $tab1   = " core_formulas
            INNER JOIN estado ON core_formulas.id_estado = estado.id_estado";
        $whe1   = " descripcion_formulas='seguro_de_desgravamen' AND estado.nombre_estado='ACTIVO' AND estado.tabla_estado='core_formulas'";
        $rsFormula  = $model->getCondicionesSinOrden($col1, $tab1, $whe1, "");
        
        return $rsFormula[0]->expresion_formulas;
    }
    /** end dc 2020/07/03 **/
    
    /** dc 2020/08/01 **/
    public function obtenerTipoPagosAmortizacion($tipo_credito){
        
        $model = new ModeloModel();
        
        $col1   = " aa.id_tabla_amortizacion_parametrizacion, aa.tipo_tabla_amortizacion_parametrizacion,
            aa.orden_tabla_amortizacion_parametrizacion, aa.descripcion_tabla_amortizacion_parametrizacion";
        $tab1   = "  core_tabla_amortizacion_parametrizacion aa
            INNER JOIN core_tipo_creditos bb ON bb.id_tipo_creditos = aa.id_tipo_creditos";
        $whe1   = " 1 = 1
            AND bb.codigo_tipo_creditos='$tipo_credito'
            AND aa.id_estado=114";
        $id1    = " aa.tipo_tabla_amortizacion_parametrizacion";
        
        $rsConsulta1    = $model->getCondiciones($col1, $tab1, $whe1, $id1);
        
        return $rsConsulta1 ?: null;
    }
    /** end dc 2020/08/01 **/
    
    /** dc 2020/08/03 **/
    public function generarDesglosePagos( array $datos, array $valores, int $id_amortizacion){
        
        $model  = new ModeloModel();
        $parametros = $datos;
        
        foreach ( $parametros as $res)
        {
            $tipo_pago  = $res->descripcion_tabla_amortizacion_parametrizacion;
            $id_pago    = $res->id_tabla_amortizacion_parametrizacion;
            
            if( trim($tipo_pago, " " )  == 'CAPITAL' )
            {
                //echo "ID es --> $id_pago aqui inserto el pago capital ",$datos['capital'],"<br>";
                $query = "INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_pago, $id_amortizacion, '" . $valores['capital'] . "',
                            '" . $valores['capital'] . "', 1)";
                $model->executeNonQuery($query);
            }
            
            if( trim( $tipo_pago, " " )  == 'INTERES' )
            {
                //echo "ID es --> $id_pago aqui inserto el pago interes ",$datos['interes'],"<br>";
                $query = "INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_pago, $id_amortizacion, '" . $valores['interes'] . "',
                            '" . $valores['interes'] . "', 1)";
                $model->executeNonQuery($query);
            }
            
            if( trim( $tipo_pago, " " )  == 'MORA' )
            {
                //echo "ID es --> $id_pago aqui inserto el pago mora ",$datos['mora'],"<br>";
                $query = "INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_pago, $id_amortizacion, '0.00','0.00', 1)";
                $model->executeNonQuery($query);
            }
            
            if( trim( $tipo_pago, " " )  == 'SEGURO DE DESGRAVAMEN' )
            {
                //echo "ID es --> $id_pago aqui inserto el pago desgavamen ",$datos['desgravamen'],"<br>";
                $query = "INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_pago, $id_amortizacion, '" . $valores['desgravamen'] . "','" . $valores['desgravamen'] . "', 1)";
                $model->executeNonQuery($query);
            }
            
            if( trim( $tipo_pago, " " )  == 'SEGURO DE INCENDIOS' )
            {
                //echo "ID es --> $id_pago aqui inserto el pago incendios ",$datos['incendios'],"<br>";
                $query = "INSERT INTO core_tabla_amortizacion_pagos
                    (id_tabla_amortizacion_parametrizacion, id_tabla_amortizacion, valor_pago_tabla_amortizacion_pagos,
                    saldo_cuota_tabla_amortizacion_pagos, id_estatus)
                    VALUES ($id_pago, $id_amortizacion, '" . $valores['incendios'] . "','" . $valores['incendios'] . "', 1)";
                $model->executeNonQuery($query);
            }
        }
    }
    /** end dc 2020/08/03 **/
    
    /** dc 2020/09/11 **/
    /***
     * @desc funcion determinar si el participe pidio una reafiliacion -- numero cuotas
     * @param int $id_participes
     */
    public function validarReAfiliacion(int $id_participes)
    {
        $creditos   = new CreditosModel();
        
        $fecha  = date('Y-m-d');
        
        $col1   = " id_historico_jerarquia, numero_pedido_historico_jerarquia";
        $tab1   = " public.core_historico_jerarquia ";
        $whe1   = " id_tipo_historico_jerarquia = 7 AND id_estatus = 1
            AND ( '$fecha' BETWEEN fecha_historico_jerarquia
            AND ( DATE_TRUNC('DAY',fecha_historico_jerarquia) + ' 10 day '::INTERVAL  )::DATE )
            AND id_participes = $id_participes";
        $rsConsulta1    = $creditos->getCondicionesSinOrden($col1, $tab1, $whe1, "");
        
        return sizeof($rsConsulta1) > 0 ? true : false;
    }
    /** end dc 2020/09/11 **/
    
    /** dc 2020/09/14 **/
    public function ingresarRetencionPrimeraCuota($id_creditos, $valor_retencion)
    {
        $creditos   = new CreditosModel();
        
        $sqlInsert  = "INSERT INTO core_creditos_retencion_primera_cuota(id_creditos, valor_creditos_retencion_primera_cuota)
VALUES( $id_creditos, $valor_retencion)";
        
        $resultado  = $creditos->executeNonQuery($sqlInsert);
        
        return $resultado;
    }
    /** end dc 2020/09/14 **/
    
    /** dc 2020/09/14 **/
    public function ingresarRetencionesAportes($id_creditos, $id_participes, $fecha)
    {
        $creditos   = new CreditosModel();
        
        $funcion  = "cre_ins_retenciones_aportes";
        $parametros = "$id_creditos, $id_participes, '$fecha'";
        $sqlFuncion = $creditos->getconsultaPG($funcion, $parametros);
        
        $resultado  = $creditos->llamarconsultaPG($sqlFuncion);
        
        return $resultado[0];
    }
    /** end dc 2020/09/14 **/
    
    /** dc 2020/09/21 **/
    /***
     * @desc permite ingresar datos a la tabla core_creditos_a_pagar_renovaciones mediante un array como parametro
     * @param array $params
     * @return boolean[]|mixed[]|boolean[]|string[]
     */
    public function ingresarRegistroRenovaciones(array $params)
    {
        $creditos   = new CreditosModel();
        
        $sqlInsertRenovacion = "INSERT INTO public.core_creditos_a_pagar_renovaciones (
            id_creditos, id_creditos_renovaciones, saldo_a_la_fecha_creditos_a_pagar_renovaciones,
            fecha_creditos_a_pagar_renovaciones, observacion_creditos_a_pagar_renovaciones, id_estatus,	id_transacciones
            ) VALUES(".$params['id_creditos'].",".$params['id_creditos_renovar'].",".$params['saldo'].",
            NOW()::TIMESTAMP,'".$params['observacion']."',".$params['estado'].",null)";
        
        $creditos->executeNonQuery($sqlInsertRenovacion);
        
        if( !empty(error_get_last()) )
        {
            return array('error'=>true,'mensaje'=>error_get_last()['message']);
        }else
        {
            return array('error'=>false,'mensaje'=>"OK");
        }
        
    }
    /** end dc 2020/09/21 **/
    
    /** dc 2020/09/28 **/
    /***
     * @desc busca la cuenta individual de una cedula
     * @param string $cedula
     * @return number
     */
    public function obtenerCuentaIndividual(string $cedula)
    {
        $creditos   = new CreditosModel();
        ob_start();
        $cuenta_individual  = 0.00;
        // AQUI OBTENGO TOTAL DE CUENTA INDIVIDUAL
        $columnas = "COALESCE(SUM(valor_personal_contribucion),0) + COALESCE(SUM(valor_patronal_contribucion),0) AS total";
        $tablas = "core_contribucion aa
            INNER JOIN core_participes bb ON bb.id_participes  = aa.id_participes";
        $where = "aa.id_estatus=1 AND bb.cedula_participes = '$cedula'";
        $totalCtaIndividual = $creditos->getCondicionesSinOrden($columnas, $tablas, $where, "");
        
        $salida = ob_get_clean();
        if( !empty( trim($salida) ) || !empty(error_get_last()) )
        {
            $cuenta_individual = 0.00;
        }else{
            $cuenta_individual = $totalCtaIndividual[0]->total;
        }
        
        return $cuenta_individual;
    }
    /** end dc 2020/09/28 **/
    
    /** dc 2020/09/28 **/
    /***
     * @desc AQUI OBTENGO TOTAL DE CUENTA INDIVIDUAL
     * @param string $cedula
     * @param array $fecha
     * @return NULL|object[]
     */
    public function obtenerAportesParticipes(string $cedula, array $fecha)
    {
        $creditos   = new CreditosModel();
        
        // AQUI OBTENGO TOTAL DE CUENTA INDIVIDUAL
        $col1   = " TO_CHAR(c.fecha_registro_contribucion, 'MM') AS mes, SUM(c.valor_personal_contribucion) AS aporte";
        $tab1   = " core_contribucion c INNER JOIN core_participes p ON c.id_participes = p.id_participes";
        $whe1   = " p.id_estatus = 1 AND c.id_contribucion_tipo = 1 AND c.id_estatus = 1
            AND ( c.fecha_registro_contribucion BETWEEN '".$fecha['desde']."' AND '".$fecha['hasta']."')
            AND p.cedula_participes = '$cedula' ";
        $grupo  = " TO_CHAR(c.fecha_registro_contribucion, 'MM')";
        $having = " SUM(c.valor_personal_contribucion) > 0";
        $rsConsulta = $creditos->getCondiciones_Grupo_Having($col1, $tab1, $whe1, $grupo, $having);
                
        return $rsConsulta ?? null;
    }
    /** end dc 2020/09/28 **/
    
    /** dc 2020/09/28 **/
    public function obtenerSaldosCreditos(string $codigo_tipo_creditos)
    {
        
    }
    /** end dc 2020/09/28 **/
                
}

?>