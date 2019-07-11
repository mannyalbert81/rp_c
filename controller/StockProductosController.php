<?php

class StockProductosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    
    public function index(){
        
      
        
        session_start();
        
        $activosfdetalle=new ActivosFijosDetalleModel();
      
       
        $oficina=new OficinaModel();
        $resultOfi=$oficina->getAll("nombre_oficina");
       
        $tipoactivos=new TipoActivosModel();
        $resultTipoac=$tipoactivos->getAll("nombre_tipo_activos_fijos");
        
        
        
        
            
        $resultEdit = "";
        
        $resultSet = null;
       
        if (isset(  $_SESSION['nombre_usuarios']) )
        {
            
            $nombre_controladores = "StockProductos";
            $id_rol= $_SESSION['id_rol'];
            $resultPer = $activosfdetalle->getPermisosVer("   controladores.nombre_controladores = '$nombre_controladores' AND permisos_rol.id_rol = '$id_rol' " );
            
            if (!empty($resultPer))
            {
                if (isset ($_GET["id_activos_fijos_detalle"])   )
                {
                    
                  
                        
                    $_id_activos_fijos = $_GET["id_activos_fijos"];
                        $columnas = "
                                      activos_fijos.id_activos_fijos, 
                                      tipo_activos_fijos.id_tipo_activos_fijos, 
                                      tipo_activos_fijos.nombre_tipo_activos_fijos, 
                                      activos_fijos.nombre_activos_fijos, 
                                      activos_fijos.codigo_activos_fijos, 
                                      activos_fijos.valor_activos_fijos, 
                                      activos_fijos.depreciacion_mensual_activos_fijos, 
                                      activos_fijos_detalle.id_activos_fijos_detalle,
                                      activos_fijos_detalle.anio_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_enero_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_febrero_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_marzo_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_abril_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_mayo_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_junio_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_julio_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_agosto_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_septiembre_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_octubre_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_noviembre_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_diciembre_depreciacion_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_depreciacion_acumulada_anio_activos_fijos_detalle, 
                                      activos_fijos_detalle.valor_a_depreciar_siguiente_anio_activos_fijos_detalle, 
                                      activos_fijos_detalle.creado, 
                                      activos_fijos_detalle.modificado
                                    
                                    ";
                        
                        $tablas   = " public.activos_fijos, 
                                      public.oficina, 
                                      public.tipo_activos_fijos, 
                                      public.estado, 
                                      public.usuarios";
                        $where    = " public.activos_fijos, 
                                      public.activos_fijos_detalle, 
                                      public.tipo_activos_fijos, 
                                      AND activos_fijos_detalle.id_activos_fijos_detalle, = '$_id_activos_fijos_detalle'";
                        $id       = "activos_fijos.id_activos_fijos";
                        
                        $resultEdit = $activosfdetalle->getCondiciones($columnas ,$tablas ,$where, $id);
                        
                    
                    
                }
                
                
                $this->view_Inventario("StockProductos",array(
                    "resultSet"=>$resultSet, 
                    "resultEdit" =>$resultEdit
                    
                    
                    
                ));
                
                
                
            }
            else
            {
                $this->view_Inventario("Error",array(
                    "resultado"=>"No tiene Permisos de Acceso a La depreciación de Activos Fijos"
                    
                ));
                
                exit();
            }
            
        }
        else{
            
            $this->redirect("Usuarios","sesion_caducada");
            
        }
        
    }
    
     
    
    public function stock_productos(){
        session_start();
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
            $datos_empresa['USUARIOEMPRESA']=(isset($_SESSION['usuario_usuarios']))?$_SESSION['usuario_usuarios']:'';
        }
        
        //NOTICE DATA
        $datos_cabecera = array();
        $datos_cabecera['USUARIO'] = (isset($_SESSION['nombre_usuarios'])) ? $_SESSION['nombre_usuarios'] : 'N/D';
        $datos_cabecera['FECHA'] = date('Y/m/d');
        $datos_cabecera['HORA'] = date('h:i:s');
        
        
        $productos=new SaldoProductosModel();
        $datos_reporte = array();
        
        //////retencion detalle
        
        $columnas = " productos.id_productos, 
                      productos.codigo_productos, 
                      productos.marca_productos, 
                      productos.nombre_productos, 
                      productos.descripcion_productos, 
                      productos.ult_precio_productos, 
                      saldo_productos.entradas_f_saldo_productos, 
                      saldo_productos.salidas_f_saldo_productos, 
                      saldo_productos.saldos_f_saldo_productos, 
                      saldo_productos.precio_costo_saldo_productos";
        
        $tablas = "  public.productos, 
                     public.saldo_productos";
        $where= "saldo_productos.id_productos = productos.id_productos";
        $id="productos.id_productos";
        
        $productos_detalle = $productos->getCondiciones($columnas, $tablas, $where, $id);
        
        $html='';
        
        
        $html.='<table class="info" style="width:98px;" border=1>';
        $html.='<tr>';
        $html.='<th colspan="2" style=" text-align: center; font-size: 11px;">Código</th>';
        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Marca</th>';
        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Nombre</th>';
        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Descripción</th>';
        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Entradas</th>';
        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Salidas</th>';
        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Disponible</th>';
        $html.='<th colspan="2" style="text-align: center; font-size: 11px;">Precio Costo</th>';
        $html.='</tr>';
        
        
        
        
        foreach ($productos_detalle as $res)
        {
            
            $html.='<tr >';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->codigo_productos.'</td>';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->marca_productos.'</td>';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->nombre_productos.'</td>';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;">'.$res->descripcion_productos.'</td>';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;"align="center";>'.(int)$res->entradas_f_saldo_productos.'</td>';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;"align="center";>'.(int)$res->salidas_f_saldo_productos.'</td>';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;"align="center";>'.(int)$res->saldos_f_saldo_productos.'</td>';
            $html.='<td colspan="2" style="text-align: center; font-size: 11px;" align="right";>'.$res->precio_costo_saldo_productos.'</td>';
            
            
            $html.='</td>';
            $html.='</tr>';
        }
        
        $html.='</table>'; 
        
        $datos_reporte['DETALLE_PRODUCTOS']= $html;
        
        $this->verReporte("StockProductos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
        
        
    }
    
    
   
}
?>