<?php

class ReporteDescuentosController extends ControladorBase{
    
    public function __construct() {
        parent::__construct();
    }
    
     
    
    public function reporte_creditos(){
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
        
        $id_entidad=9;
        
        $descuentosaportes=new DescuentosAportesModel();
        $datos_reporte = array();
        
        //////retencion detalle
        
        $columnas = "  core_descuentos_registrados_cabeza.id_descuentos_registrados_cabeza, 
  core_descuentos_registrados_detalle_aportes.year_descuentos_registrados_detalle_aportes, 
  core_descuentos_registrados_detalle_aportes.mes_descuentos_registrados_detalle_aportes, 
  core_participes.cedula_participes, 
  core_participes.nombre_participes, 
  core_participes.apellido_participes, 
  core_descuentos_registrados_detalle_aportes.aporte_personal_descuentos_registrados_detalle_aportes, 
  core_descuentos_registrados_detalle_aportes.aporte_patronal_descuentos_registrados_detalle_aportes, 
  core_descuentos_registrados_detalle_aportes.rmu_descuentos_registrados_detalle_aportes, 
  core_descuentos_registrados_detalle_aportes.liquido_descuentos_registrados_detalle_aportes, 
  core_descuentos_registrados_detalle_aportes.multas_descuentos_registrados_detalle_aportes, 
  core_descuentos_registrados_detalle_aportes.antiguedad_descuentos_registrados_detalle_aportes,
  core_descuentos_registrados_detalle_aportes.procesado_descuentos_registrados_detalle_aportes, 
  core_entidad_patronal.id_entidad_patronal, 
  core_entidad_patronal.nombre_entidad_patronal";
        
        $tablas = "  public.core_descuentos_registrados_detalle_aportes, 
  public.core_descuentos_registrados_cabeza, 
  public.core_participes, 
  public.core_entidad_patronal";
        $where= " core_descuentos_registrados_detalle_aportes.id_participes = core_participes.id_participes AND
  core_descuentos_registrados_cabeza.id_descuentos_registrados_cabeza = core_descuentos_registrados_detalle_aportes.id_descuentos_registrados_cabeza AND
  core_entidad_patronal.id_entidad_patronal = core_descuentos_registrados_detalle_aportes.id_entidad_patronal AND core_entidad_patronal.id_entidad_patronal=9 
  AND core_descuentos_registrados_detalle_aportes.year_descuentos_registrados_detalle_aportes=2020 AND core_descuentos_registrados_detalle_aportes.mes_descuentos_registrados_detalle_aportes=1";
        $id="core_participes.apellido_participes";
        
        $desceuntos_aportes_personales = $descuentosaportes->getCondiciones($columnas, $tablas, $where, $id);
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 1 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "ENERO";
            
        }
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 2 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "FEBRERO";
            
        }
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 3 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "MARZO";
            
        } 
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 4 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "ABRIL";
            
        } 
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 5 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "MAYO";
            
        }
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 6 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "JUNIO";
            
        } 
        
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 7 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "JULIO";
            
        } 
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 8 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "AGOSTO";
            
        } 
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 9 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "SEPTIEMBRE";
            
        } 
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 10 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "OCTUBRE";
            
        }
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 11 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "NOVIEMBRE";
            
        } 
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 12 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "DICIEMBRE";
            
        } 
        
        
        
        
        $datos_reporte['ENTIDAD_PATRONAL']=$desceuntos_aportes_personales[0]->nombre_entidad_patronal;
        $datos_reporte['ANIO']=$desceuntos_aportes_personales[0]->year_descuentos_registrados_detalle_aportes;
        $datos_reporte['MES']=$desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes;
        
        $html='';
        
        
        $html.='<table class="1" border=1>';
        $html.='<tr>';
        $html.='<th>N°</th>';
        $html.='<th>Cédula</th>';
        $html.='<th>Nombre</th>';
        $html.='<th>Aporte Personal</th>';
        $html.='<th>Aporte Patronal</th>';
        $html.='<th>RMU</th>';
        $html.='<th>Líquido</th>';
        $html.='<th>Multas</th>';
        $html.='<th>Antiguedad</th>';
        $html.='<th>Procesado</th>';
       
        $html.='</tr>';
        
        
        
        $i=0;
        foreach ($desceuntos_aportes_personales as $res)
        {
            
            if($res->procesado_descuentos_registrados_detalle_aportes == "t" ){
                
                $res->procesado_descuentos_registrados_detalle_aportes= "SI";
                
            } else
            
            {
                
                $res->procesado_descuentos_registrados_detalle_aportes= "NO";
            }
            
            
            
            $i++;
            $html.='<tr >';
            $html.='<td>'.$i.'</td>';
            $html.='<td align="left";>'.$res->cedula_participes.'</td>';
            $html.='<td align="left";>'.$res->apellido_participes.'&nbsp;'.$res->nombre_participes.'</td>';
            $html.='<td align="right";>'.$res->aporte_personal_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->aporte_patronal_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->rmu_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->liquido_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->multas_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="center";>'.$res->procesado_descuentos_registrados_detalle_aportes.'</td>';
            
            
            $html.='</td>';
            $html.='</tr>';
        }
        
        
        
        
        
        
        
        $html.='</table>'; 
        
        $datos_reporte['DETALLE_DESCUENTOS_APORTES']= $html;
        
        
        
        $this->verReporte("ReporteAportesRecibidos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
        
        
    }
    
    
    public function reporte_descuentos_recibios(){
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
        
        $id_entidad=9;
        
        $descuentosaportes=new DescuentosAportesModel();
        $datos_reporte = array();
        
        //////retencion detalle
        
        $columnas = "  core_descuentos_registrados_cabeza.id_descuentos_registrados_cabeza,
  core_descuentos_registrados_detalle_aportes.year_descuentos_registrados_detalle_aportes,
  core_descuentos_registrados_detalle_aportes.mes_descuentos_registrados_detalle_aportes,
  core_participes.cedula_participes,
  core_participes.nombre_participes,
  core_participes.apellido_participes,
  core_descuentos_registrados_detalle_aportes.aporte_personal_descuentos_registrados_detalle_aportes,
  core_descuentos_registrados_detalle_aportes.aporte_patronal_descuentos_registrados_detalle_aportes,
  core_descuentos_registrados_detalle_aportes.rmu_descuentos_registrados_detalle_aportes,
  core_descuentos_registrados_detalle_aportes.liquido_descuentos_registrados_detalle_aportes,
  core_descuentos_registrados_detalle_aportes.multas_descuentos_registrados_detalle_aportes,
  core_descuentos_registrados_detalle_aportes.antiguedad_descuentos_registrados_detalle_aportes,
  core_descuentos_registrados_detalle_aportes.procesado_descuentos_registrados_detalle_aportes,
  core_entidad_patronal.id_entidad_patronal,
  core_entidad_patronal.nombre_entidad_patronal";
        
        $tablas = "  public.core_descuentos_registrados_detalle_aportes,
  public.core_descuentos_registrados_cabeza,
  public.core_participes,
  public.core_entidad_patronal";
        $where= " core_descuentos_registrados_detalle_aportes.id_participes = core_participes.id_participes AND
  core_descuentos_registrados_cabeza.id_descuentos_registrados_cabeza = core_descuentos_registrados_detalle_aportes.id_descuentos_registrados_cabeza AND
  core_entidad_patronal.id_entidad_patronal = core_descuentos_registrados_detalle_aportes.id_entidad_patronal AND core_entidad_patronal.id_entidad_patronal=9
  AND core_descuentos_registrados_detalle_aportes.year_descuentos_registrados_detalle_aportes=2020 AND core_descuentos_registrados_detalle_aportes.mes_descuentos_registrados_detalle_aportes=1";
        $id="core_participes.apellido_participes";
        
        $desceuntos_aportes_personales = $descuentosaportes->getCondiciones($columnas, $tablas, $where, $id);
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 1 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "ENERO";
            
        }
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 2 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "FEBRERO";
            
        }
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 3 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "MARZO";
            
        }
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 4 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "ABRIL";
            
        }
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 5 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "MAYO";
            
        }
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 6 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "JUNIO";
            
        }
        
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 7 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "JULIO";
            
        }
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 8 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "AGOSTO";
            
        }
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 9 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "SEPTIEMBRE";
            
        }
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 10 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "OCTUBRE";
            
        }
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 11 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "NOVIEMBRE";
            
        }
        
        
        if($desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes == 12 ){
            
            $desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes= "DICIEMBRE";
            
        }
        
        
        
        
        $datos_reporte['ENTIDAD_PATRONAL']=$desceuntos_aportes_personales[0]->nombre_entidad_patronal;
        $datos_reporte['ANIO']=$desceuntos_aportes_personales[0]->year_descuentos_registrados_detalle_aportes;
        $datos_reporte['MES']=$desceuntos_aportes_personales[0]->mes_descuentos_registrados_detalle_aportes;
        
        $html='';
        
        
        $html.='<table class="1" border=1>';
        $html.='<tr>';
        $html.='<th>N°</th>';
        $html.='<th>Cédula</th>';
        $html.='<th>Nombre</th>';
        $html.='<th>Garantizado</th>';
        $html.='<th>Aporte Patronal</th>';
        $html.='<th colspan="5">PQ-CREDITO 2X1</th>';
        $html.='<th colspan="5">PQ-CREDITO EMER.</th>';
        $html.='<th colspan="6">PQ-CREDITO HIPO.</th>';
        $html.='<th colspan="5">PQ-CREDITO ORD.</th>';
        $html.='<th>CUOTA CARGARDA</th>';
        $html.='<th>CUOTA ARCHIVO</th>';
        $html.='<th>ID_CXP</th>';
        $html.='<th>ESTADO CXP</th>';
        $html.='<th>Procesado</th>';
        $html.='<th>Observ.</th>';
        $html.='</tr>';
        
        $html.='<tr>';
        $html.='<th></th>';
        $html.='<th></th>';
        $html.='<th></th>';
        $html.='<th></th>';
        $html.='<th></th>';
        $html.='<th>Capital</th>';
        $html.='<th>Interes</th>';
        $html.='<th>Mora</th>';
        $html.='<th>Seg. Degrav.</th>';
        $html.='<th>Total</th>';
        
        $html.='<th>Capital</th>';
        $html.='<th>Interes</th>';
        $html.='<th>Mora</th>';
        $html.='<th>Seg. Degrav.</th>';
        $html.='<th>Total</th>';
        
        $html.='<th>Capital</th>';
        $html.='<th>Interes</th>';
        $html.='<th>Mora</th>';
        $html.='<th>Seg. Degrav.</th>';
        $html.='<th>Seg. incendios.</th>';
        $html.='<th>Total</th>';
        
        $html.='<th>Capital</th>';
        $html.='<th>Interes</th>';
        $html.='<th>Mora</th>';
        $html.='<th>Seg. Degrav.</th>';
        $html.='<th>Total</th>';
        
        $html.='<th></th>';
        $html.='<th>Cuota</th>';
        $html.='<th></th>';
        $html.='<th></th>';
        $html.='<th></th>';
        $html.='<th></th>';
        $html.='</tr>';
        
        
        
        $i=0;
        foreach ($desceuntos_aportes_personales as $res)
        {
            
            if($res->procesado_descuentos_registrados_detalle_aportes == "t" ){
                
                $res->procesado_descuentos_registrados_detalle_aportes= "SI";
                
            } else
            
            {
                
                $res->procesado_descuentos_registrados_detalle_aportes= "NO";
            }
            
            
            
            $i++;
            $html.='<tr >';
            $html.='<td>'.$i.'</td>';
            $html.='<td align="left";>'.$res->cedula_participes.'</td>';
            $html.='<td align="left";>'.$res->apellido_participes.'&nbsp;'.$res->nombre_participes.'</td>';
            $html.='<td align="right";>'.$res->aporte_personal_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->aporte_patronal_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->rmu_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->liquido_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->multas_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            $html.='<td align="right";>'.$res->antiguedad_descuentos_registrados_detalle_aportes.'</td>';
            
            $html.='</td>';
            $html.='</tr>';
        }
        
        
    
        
        
        $html.='</table>';
        
        $datos_reporte['DETALLE_DESCUENTOS_APORTES']= $html;
        
        
        
        $this->verReporte("ReporteDescuentosRecibidos", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
        
        
    }
    
    
   
}
?>