<?php

class PagosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        
        $entidad = new CoreEntidadPatronalModel();
        
        session_start();
        
        if(empty( $_SESSION['usuario_usuarios'])){
            
            $this->redirect("Usuarios","sesion_caducada");
            exit();
        }else{
            
            $nombre_controladores = "PagosCXP";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $entidad->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );            
            if (empty($resultPer)){
                
                $this->view("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso Pagos"
                    
                ));
                exit();
                
            }else{
                
                $rsEntidad = $entidad->getBy(" 1 = 1 ");                
                
                $this->view_tesoreria("Pagos",array(
                    "resultSet"=>$rsEntidad
                    
                ));
                exit();                
            }
            
        }
        
        
    }
    
    public function indexconsulta(){
        
        
        $busqueda = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : "";
        if(!isset($_POST['peticion'])){
            echo 'sin conexion';
            return;
        }
        
        $page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
        
        $cuentasPagar = new CuentasPagarModel();
        
        $columnas = "aa.id_cuentas_pagar, aa.descripcion_cuentas_pagar,aa.fecha_cuentas_pagar, aa.origen_cuentas_pagar, aa.total_cuentas_pagar, aa.saldo_cuenta_cuentas_pagar,
                	bb.id_lote, bb.nombre_lote, cc.id_proveedores,cc.nombre_proveedores, cc.identificacion_proveedores, ee.id_usuarios, ee.nombre_usuarios,
                	ff.id_forma_pago, ff.nombre_forma_pago";
        
        $tablas = "tes_cuentas_pagar aa
                INNER JOIN tes_lote bb
                ON aa.id_lote = bb.id_lote
                INNER JOIN proveedores cc
                ON aa.id_proveedor = cc.id_proveedores
                INNER JOIN estado dd
                ON aa.id_estado = dd.id_estado
                INNER JOIN usuarios ee
                ON bb.id_usuarios = ee.id_usuarios
                LEFT JOIN forma_pago ff
                ON aa.id_forma_pago = ff.id_forma_pago";
        
        $where = " 1=1 AND dd.nombre_estado = 'GENERADO' ";
        
        //para los parametros de where 
        if(!empty($busqueda)){
            
            $where .= "AND ( bb.nombre_lote = '$busqueda' OR cc.identificacion_proveedores like '$busqueda%' )";
        }
        
        $id = "aa.id_cuentas_pagar";
        
        //para obtener cantidad         
        $rsResultado = $cuentasPagar->getCantidad("1", $tablas, $where, $id);        
        
        $cantidad = 0;
        $html = "";
        $per_page = 10; //la cantidad de registros que desea mostrar
        $adjacents  = 9; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        
        if(!is_null($rsResultado) && !empty($rsResultado) && count($rsResultado)>0){
            $cantidad = $rsResultado[0]->total;
        }
        
        $limit = " LIMIT   '$per_page' OFFSET '$offset'";
        
        $resultSet = $cuentasPagar->getCondicionesPag( $columnas, $tablas, $where, $id, $limit);
        
        $tpages = ceil($cantidad/$per_page);
        $_nombre_tabla = "tbl_lista_cuentas_pagar";
        
        if( $cantidad > 0 ){
           
            $html.= "<table id='$_nombre_tabla' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">LOTE</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">ORIGEN</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">GENERADO POR</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">DESCRIPCION</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">FECHA</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">BENEFICIARIO</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">MONTO/VALOR</th>';
            $html.='<th >CHEQUE</th>';
            $html.='<th >TRANSFERENCIA</th>';            
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            
            $i=0;
            
            //print_r($resultSet); die();
            
            foreach ($resultSet as $res)
            {
                $i++;
                $html.='<tr>';
                $html.='<td style="font-size: 11px;">'.$i.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_lote.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->origen_cuentas_pagar.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_usuarios.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->descripcion_cuentas_pagar.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->fecha_cuentas_pagar.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_proveedores.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->saldo_cuenta_cuentas_pagar.'</td>';
                
                if($res->id_forma_pago == null || $res->id_forma_pago == "" ){
                    $html.='<td width="3%" style="font-size:80%;">';
                    $html.='<a class="btn btn-sm btn-info" title="Generar Cheque" href="index.php?controller=GenerarCheque&action=indexCheque&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                    $html.='<i class="fa fa-money"></i></a></td>';
                    $html.='<td width="3%" style="font-size:80%;">';
                    $html.='<a class="btn btn-sm btn-info" title="Realizar Transferencia"  href="index.php?controller=Transferencias&action=index&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                    $html.='<i class="glyphicon glyphicon-transfer"></i></a></td>';
                }else{
                
                    if($res->nombre_forma_pago != 'TRANSFERENCIA'){
                        
                        $html.='<td width="3%" style="font-size:80%;">';
                        $html.='<a class="btn btn-sm btn-info" title="Generar Cheque" href="index.php?controller=GenerarCheque&action=indexCheque&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                        $html.='<i class="fa fa-money"></i></a></td>';
                    }else{
                        $html.='<td width="3%" ></td>';
                    }
                    
                    if($res->nombre_forma_pago == 'TRANSFERENCIA'){
                        
                        $html.='<td width="3%" style="font-size:80%;">';
                        $html.='<a class="btn btn-sm btn-info" title="Realizar Transferencia"  href="index.php?controller=Transferencias&action=index&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                        $html.='<i class="glyphicon glyphicon-transfer"></i></a></td>';
                    }else{
                        $html.='<td width="3%" ></td>';
                    }
                }
                
                $html.='</tr>';
                
            }
            
            
            $html.='</tbody>';
            $html.='</table>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate("index.php", $page, $tpages, $adjacents,"buscaCuentasPagar").'';
            $html.='</div>';
            
            
            
        }else{
            
            $html.= "<table id='$_nombre_tabla' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">LOTE</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">ORIGEN</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">GENERADO POR</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">DESCRIPCION</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">FECHA</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">BENEFICIARIO</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">MONTO/VALOR</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">CHEQUE</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">TRANSFERENCIA</th>'; 
            $html.='</tr>';
            $html.='</thead>';
            $html.='<tbody>';
            $html.='</tbody>';
            $html.='</table>';
        }
        
        //array de datos
        $respuesta = array();
        $respuesta['tabla_datos'] = $html;
        $respuesta['valores'] = array('cantidad'=>$cantidad);
        $respuesta['nombre_tabla'] = $_nombre_tabla;
        echo json_encode($respuesta);
    }
    
    public function paginate($reload, $page, $tpages, $adjacents, $funcion = "") {
        
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out = '<ul class="pagination pagination-large">';
        
        // previous label
        
        if($page==1) {
            $out.= "<li class='disabled'><span><a>$prevlabel</a></span></li>";
        } else if($page==2) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(1)'>$prevlabel</a></span></li>";
        }else {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page-1).")'>$prevlabel</a></span></li>";
            
        }
        
        // first label
        if($page>($adjacents+1)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>1</a></li>";
        }
        // interval
        if($page>($adjacents+2)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // pages
        
        $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
        $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
        for($i=$pmin; $i<=$pmax; $i++) {
            if($i==$page) {
                $out.= "<li class='active'><a>$i</a></li>";
            }else if($i==1) {
                $out.= "<li><a href='javascript:void(0);' onclick='$funcion(1)'>$i</a></li>";
            }else {
                $out.= "<li><a href='javascript:void(0);' onclick='$funcion(".$i.")'>$i</a></li>";
            }
        }
        
        // interval
        
        if($page<($tpages-$adjacents-1)) {
            $out.= "<li><a>...</a></li>";
        }
        
        // last
        
        if($page<($tpages-$adjacents)) {
            $out.= "<li><a href='javascript:void(0);' onclick='$funcion($tpages)'>$tpages</a></li>";
        }
        
        // next
        
        if($page<$tpages) {
            $out.= "<li><span><a href='javascript:void(0);' onclick='$funcion(".($page+1).")'>$nextlabel</a></span></li>";
        }else {
            $out.= "<li class='disabled'><span><a>$nextlabel</a></span></li>";
        }
        
        $out.= "</ul>";
        return $out;
    }
    
   
    function validaMetodoPago(){
        
        $_id_cuentas_pagar = $_POST['id_cuentas_pagar'];
        
    }
    
   
}
    

?>