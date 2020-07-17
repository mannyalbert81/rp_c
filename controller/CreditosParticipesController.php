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

    public function index001()
    {
        session_start();
        $estado = new EstadoModel();
        $id_rol = $_SESSION['id_rol'];

        $this->view_Credito("BuscarParticipes", array(
            "result" => ""
        ));
    }

    public function index1()
    {
        session_start();

        if (isset($_SESSION['id_usuarios'])) {

            if (isset($_GET["cedula_participe"]) && isset($_GET["id_solicitud"])) {

                $cedula_participe = $_GET['cedula_participe'];
                $id_solicitud = $_GET['id_solicitud'];

                $this->view_Credito("BuscarParticipes", array(
                    "result" => ""
                ));

                // EXECUTO METODO InfoSolicitud DE JAVA SCRIPT BuscarParticipes
                echo '<script type="text/javascript">', 'InfoSolicitud("' . $cedula_participe . '", ' . $id_solicitud . ');', '</script>';
            }
        } else {

            $this->redirect("Usuarios", "sesion_caducada");
        }
    }

    public function InfoSolicitud()
    {
        session_start();
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();
        $response = array();

        try {
            ob_start();
            $id_solicitud = $_POST['id_solicitud'];

            $columnas = " solicitud_prestamo.destino_dinero_datos_prestamo,
					  solicitud_prestamo.nombre_banco_cuenta_bancaria,
					  solicitud_prestamo.tipo_cuenta_cuenta_bancaria,
					  solicitud_prestamo.numero_cuenta_cuenta_bancaria,
					  solicitud_prestamo.tipo_pago_cuenta_bancaria,
				      tipo_creditos.nombre_tipo_creditos";

            $tablas = "public.solicitud_prestamo INNER JOIN public.tipo_creditos
                     ON solicitud_prestamo.id_tipo_creditos=tipo_creditos.id_tipo_creditos";

            $where = "solicitud_prestamo.id_solicitud_prestamo=" . $id_solicitud;

            $resultSet = $db->getCondiciones($columnas, $tablas, $where);

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
            if (! empty($salida)) {
                throw new Exception("");
            }

            $response['html'] = $html;
            $response['data'] = $data;
        } catch (Exception $e) {
            $html = '<h3 class="titulo">DATOS NO ENCONTRADOS</h3>';
            $response['html'] = $html;
            $response['mensaje'] = "Causa problable solicitud no encoontrada";
            $response['buffer'] = error_get_last();
        }

        echo json_encode($response);
    }

    public function InfoSolicitud001()
    {
        session_start();
        require_once 'core/DB_Functions.php';
        $db = new DB_Functions();

        // ESTE ES EL METODO PARA CARGAR EL MODAL DE INFORMACION DE LA SOLICITUD

        $id_solicitud = (isset($_POST['id_solicitud'])) ? $_POST['id_solicitud'] : 0;

        if ($id_solicitud > 0) {

            $columnas = " solicitud_prestamo.destino_dinero_datos_prestamo,
					  solicitud_prestamo.nombre_banco_cuenta_bancaria,
					  solicitud_prestamo.tipo_cuenta_cuenta_bancaria,
					  solicitud_prestamo.numero_cuenta_cuenta_bancaria,
					  solicitud_prestamo.tipo_pago_cuenta_bancaria,
				      tipo_creditos.nombre_tipo_creditos";

            $tablas = "public.solicitud_prestamo INNER JOIN public.tipo_creditos
                     ON solicitud_prestamo.id_tipo_creditos=tipo_creditos.id_tipo_creditos";

            $where = "solicitud_prestamo.id_solicitud_prestamo=" . $id_solicitud;

            $resultSet = $db->getCondiciones($columnas, $tablas, $where);

            $html = '<div id="info_solicitud_participe" class="small-box bg-teal">
               <div class="inner">
              <table width="100%">
              <tr>
              <td colspan="2" align="center">
                <font size="4"><b>Información de Solicitud<b></font>
              </td>
              </tr>
              <tr>
              <td width="50%">
                <font size="3" id="tipo_credito_solicitud">Tipo Crédito : ' . $resultSet[0]->nombre_tipo_creditos . '</font>
              </td>
              <td width="50%">
                <font size="3">Destino Dinero : ' . $resultSet[0]->destino_dinero_datos_prestamo . '</font>
              </td>
              <tr>
              <td width="50%">
                <font size="3">Nombre Banco : ' . $resultSet[0]->nombre_banco_cuenta_bancaria . '</font>
              </td>
              <td width="50%">
                <font size="3">Tipo Cuenta : ' . $resultSet[0]->tipo_cuenta_cuenta_bancaria . '</font>
               </td>
              <tr>
              <td width="50%">
                <font size="3">Número Cuenta : ' . $resultSet[0]->numero_cuenta_cuenta_bancaria . '</font>
               </td>
              <td width="50%">
                <font size="3">Tipo de Pago: ' . $resultSet[0]->tipo_pago_cuenta_bancaria . '</font>
                </td>
                </tr>
                </table>
               </div>
               </div>';

            echo $html;
        } else {

            echo "NO VINO EL ID SOLICITUD POR POST";
        }
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

    public function dateDifference($date_1, $date_2, $differenceFormat = '%y Años, %m Meses')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
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
            AND coalesce( aa.mora_tabla_amortizacion, 0) > 0
            AND bb.id_estado_creditos = 4
            AND ( aa.fecha_tabla_amortizacion BETWEEN '".$fechasValidacion['desde']."' AND '".$fechasValidacion['hasta']."' )
            AND cc.cedula_participes = '".$cedula_participes."'";
        $order  = " ORDER BY aa.id_creditos DESC, aa.fecha_tabla_amortizacion DESC";
        $rsConsulta1    = $participes->getCondicionesSinOrden($col1, $tab1, $whe1, $order);
        
        
        if( empty($rsConsulta1) )
        {
            $response['error']      = false;
            $response['mensaje']    = ""; 
        }else
        {
            $response['error']      = true;
            $response['mensaje']    = "Existen cuotas en Mora";
        }
        $salida = ob_get_contents();
        if( !empty( $salida ) )
        {
            $response = array();
            $response['estatus'] = "ERROR";
            $response['buffer']  = $salida;
        }else
        {
            $response['estatus'] = "OK";
        }
        
        echo json_encode( $response );
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
    
    public function verCode()
    {
        $valor_cuota = ( 440 * 0.0075) / (1 - pow((1 + 0.0075), - 12));
        $valor_cuota = round($valor_cuota, 2);
        echo "VALOR CUOTA --> ",$valor_cuota,"<br>";
        $desgravamen = ( ( 0.16/1000) * 31.88 ) * 1.04;
        echo floor( $desgravamen *100 ) / 100 ;
        echo "<br>";
        
        $valoruno = floor(3.3 * 100)/100;
        echo "VALOR DE UNO  --> ",$valoruno,"<br>";
        
        $desgravamen2 = ( ( 0.16/1000) * 440 ) * 1.04;
        $desgravamen2 = floor( $desgravamen2 *100 ) / 100 ;
        echo "DESGRAVAMEN 2  --> ",$desgravamen2,"<br>";
        
        echo "************************** VALIDAR CUOTA DE CREDITO ********************* <br>";
        try {
            $valor_cuota = ( 440 * 0.0075 ) / (1 - pow((1 + 0.0075 ), - 0 ));
        }catch( DivisionByZeroError $e){
            $valor_cuota = 0;
        }catch(ErrorException $e) {
            $valor_cuota = 0;
        }
        echo " VALOR CUOTA --> ",$valor_cuota;
        
        echo "<br> /*************************************************//";
        var_dump($this->getFechasUltimas3Cuotas());
        
        echo "<br> /*************************************************//<br>";
        $tasa_interes = 9;
        $tasa_interes = $tasa_interes / 100;
        $interes_mensual = $tasa_interes / 12;
        $valor_cuota = ( 11220 * $interes_mensual ) / ( 1 - pow( ( 1 + $interes_mensual), - 72 ) );
        $valor_cuota = round($valor_cuota, 2);
        echo "AQUI LA CUOTA -->",$valor_cuota;
        
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
        
}

?>