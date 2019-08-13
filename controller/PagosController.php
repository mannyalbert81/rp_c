<?php

class PagosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        
        $entidad = new CoreEntidadPatronalModel();
        
        session_start();
        
        if(empty( $_SESSION)){
            
            $this->redirect("Usuarios","sesion_caducada");
            return;
        }
        
        $nombre_controladores = "GenerarCheque";
        $id_rol= $_SESSION['id_rol'];
        $resultPer = $entidad->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
        
        if (empty($resultPer)){
            
            $this->view("Error",array(
                "resultado"=>"No tiene Permisos de Acceso Pagos"
                
            ));
            exit();
        }
        
        $rsEntidad = $entidad->getBy(" 1 = 1 ");
        
        
        $this->view_tesoreria("Pagos",array(
            "resultSet"=>$rsEntidad
            
        ));
        
        
    }
    
    public function indexconsulta(){
        
        
        $busqueda = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : "";
        if(!isset($_POST['peticion'])){
            echo 'sin conexion';
            return;
        }
        
        $page = (isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
        
        $cuentasPagar = new CuentasPagarModel();
        
        $queryFormasPago = "SELECT id_forma_pago, nombre_forma_pago FROM forma_pago";
        $rsFormaPago = $cuentasPagar->enviaquery($queryFormasPago);
        
        $columnas = " l.id_lote, l.nombre_lote, l.id_usuarios, cp.id_cuentas_pagar, cp.descripcion_cuentas_pagar, 
                    cp.fecha_cuentas_pagar, cp.id_forma_pago, pr.id_proveedores, pr.nombre_proveedores,
                    fp.nombre_forma_pago";
        
        $tablas = "tes_lote l
            INNER JOIN tes_cuentas_pagar cp
            ON l.id_lote = cp.id_lote
            INNER JOIN proveedores pr
            ON pr.id_proveedores = cp.id_proveedor
            INNER JOIN estado e
            ON e.id_estado = cp.id_estado
            LEFT JOIN forma_pago fp
            ON fp.id_forma_pago = cp.id_forma_pago";
        
        $where = " 1=1 AND e.nombre_estado = 'GENERADO' ";
        
        //para los parametros de where 
        if(!empty($busqueda)){
            
            $where .= "AND ( nombre_lote = '$busqueda' OR pr.identificacion_proveedores like '$busqueda%' )";
        }
        
        $id = "cp.id_cuentas_pagar";
        
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
        
        if( $cantidad > 0 ){
            
            //$html.='<div class="pull-left" style="margin-left:11px;">';
            //$html.='<span class="form-control"><strong>Registros: </strong>'.$cantidad.'</span>';
            //$html.='<input type="hidden" value="'.$cantidad.'" id="total_query" name="total_query"/>' ;
            //$html.='</div>';
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<section style="height:280px; overflow-y:scroll;">';
            $html.= "<table id='tabla_productos' class='tablesorter table table-striped table-bordered dt-responsive nowrap'>";
            $html.= "<thead>";
            $html.= "<tr>";
            $html.='<th style="text-align: left;  font-size: 12px;"></th>';
            $html.='<th style="text-align: left;  font-size: 12px;">LOTE</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">USUARIO</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">DESCRIPCION</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">FECHA</th>';
            $html.='<th style="text-align: left;  font-size: 12px;">BENEFICIARIO</th>';
            $html.='<th colspan="2" style="text-align: left;  font-size: 12px;">PAGOS</th>';
            
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
                $html.='<td style="font-size: 11px;">'.$res->id_lote.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->id_usuarios.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->descripcion_cuentas_pagar.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->fecha_cuentas_pagar.'</td>';
                $html.='<td style="font-size: 11px;">'.$res->nombre_proveedores.'</td>';
                
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
                        $html.='<td ></td>';
                    }
                    
                    if($res->nombre_forma_pago == 'TRANSFERENCIA'){
                        
                        $html.='<td width="3%" style="font-size:80%;">';
                        $html.='<a class="btn btn-sm btn-info" title="Realizar Transferencia"  href="index.php?controller=Transferencias&action=index&id_cuentas_pagar='.$res->id_cuentas_pagar.'">';
                        $html.='<i class="glyphicon glyphicon-transfer"></i></a></td>';
                    }else{
                        $html.='<td ></td>';
                    }
                }
                
                $html.='</tr>';
                
            }
            
            
            $html.='</tbody>';
            $html.='</table>';
            $html.='</section></div>';
            $html.='<div class="table-pagination pull-right">';
            $html.=''. $this->paginate("index.php", $page, $tpages, $adjacents,"").'';
            $html.='</div>';
            
            
            
        }else{
            $html.='<div class="col-lg-12 col-md-12 col-xs-12">';
            $html.='<div class="alert alert-warning alert-dismissable" style="margin-top:40px;">';
            $html.='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $html.='<h4>Aviso!!!</h4> <b> No hay cuentas por Pagar</b>';
            $html.='</div>';
            $html.='</div>';
        }
        
        //array de datos
        $respuesta = array();
        $respuesta['html'] = $html;
        $respuesta['valores'] = array('cantidad'=>$cantidad);
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