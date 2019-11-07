<?php
class ReporteDesafiliacionController extends ControladorBase{
    public function index(){
        session_start();
      
        $this->view_Credito("RevisionCreditos",array(
        ));
    }
    
  
    
    
    public function ReporteDesafiliacion($param) {
        
        
        session_start();
        $entidades = new EntidadesModel();
        $ccomprobantes = new CComprobantesModel();
        $dcomprobantes = new DComprobantesModel();
        $tipo_comprobantes = new TipoComprobantesModel();
        $entidades = new EntidadesModel();
        $tipo_comprobante=new TipoComprobantesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
        $rp_capremci= new PlanCuentasModel();
        $id_reporte=$_GET['id_reporte'];
        
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
        
       
        
        //TABLA
        
    
        $FECHA = "06/09/2019";

        
        $datos_reporte = array();
        
       
        
        $html='';
        
            $html.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
            
            $html.= "<tr>";
            $html.='<th style="text-align: center; font-size: 11px;">FECHA DE PAGO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">No. DOCUMENTO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">IDENTIFICACIÓN</th>';
            $html.='<th style="text-align: center; font-size: 11px;">APELLIDOS DEL SOCIO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">SOCIO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">TOTAL INGRESOS</th>';
            $html.='<th style="text-align: center; font-size: 11px;">VALOR A DEVOLVER</th>';
            $html.='<th style="text-align: center; font-size: 11px;">TIPO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">ESTADO DE SOLICITUD</th>';
            $html.='<th style="text-align: center; font-size: 11px;">FORMA DE PAGO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">NOMBRE DEL BANCO</th>';
            $html.='<th style="text-align: center; font-size: 11px;">No. DE CUENTA</th>';
            $html.='<th style="text-align: center; font-size: 11px;">TIPO DE CUENTA</th>';
            $html.='<th style="text-align: center; font-size: 11px;">TRANSFERENCIA</th>';
            $html.='<th style="text-align: center; font-size: 11px;">CELULAR</th>';
            $html.='</tr>';
            
            $html.= "<tr>";
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.= "</tr>";
          
            $html.= "<tr>";
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">Total:</td>';
            $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.='<td style="text-align: center; font-size: 11px;"></td>';
            $html.= "</tr>";
            
        $html.='</table>';
        
        $html.='<table class="3" cellspacing="0" style="width:100px;" border="1" >';
        
        $html.= "<tr>";
        $html.='<th colspan="4" style="text-align: center; font-size: 11px;">RESUMEN TOTAL DE LA OPERACIÓN</th>';
        $html.= "</tr>";
        
        $html.= "<tr>";
        $html.='<th style="text-align: center; font-size: 11px;">FORMA DE PAGO</th>';
        $html.='<th style="text-align: center; font-size: 11px;">BANCO</th>';
        $html.='<th style="text-align: center; font-size: 11px;">TOTAL TRANSACCIONES</th>';
        $html.='<th style="text-align: center; font-size: 11px;">TOTAL</th>';
        $html.='</tr>';
        
        $html.= "<tr>";
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='</tr>';
        
        $html.= "<tr>";
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;">Total:</td>';
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;"></td>';
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;"></td>';
        $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='</tr>';
        
        $html.='</table>';
       
     
    
        $datos_reporte['DETALLE_DESAFILIACION']= $html;
        
        
        
        
        
        $this->verReporte("ReporteDesafiliacion", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
        
        
        

    }
    
    public function ReporteLiquidacion($param) {
        
        
        session_start();
        $entidades = new EntidadesModel();
        $ccomprobantes = new CComprobantesModel();
        $dcomprobantes = new DComprobantesModel();
        $tipo_comprobantes = new TipoComprobantesModel();
        $entidades = new EntidadesModel();
        $tipo_comprobante=new TipoComprobantesModel();
        //PARA OBTENER DATOS DE LA EMPRESA
        $datos_empresa = array();
        $rsdatosEmpresa = $entidades->getBy("id_entidades = 1");
        $rp_capremci= new PlanCuentasModel();
        $id_reporte=$_GET['id_reporte'];
        
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
        
        
        
        //TABLA
        
        
        $FECHA = "06/09/2019";
        
        
        $datos_reporte = array();
        
        
        
        $html='';
        
        $html.='<table class="1" cellspacing="0" style="width:100px;" border="1" >';
        
        $html.= "<tr>";
        $html.='<th style="text-align: center; font-size: 11px;">FECHA DE PAGO</th>';
        $html.='<th style="text-align: center; font-size: 11px;">No. DOCUMENTO</th>';
        $html.='<th style="text-align: center; font-size: 11px;">IDENTIFICACIÓN</th>';
        $html.='<th style="text-align: center; font-size: 11px;">APELLIDOS DEL SOCIO</th>';
        $html.='<th style="text-align: center; font-size: 11px;">NOMBRES DEL SOCIO</th>';
        $html.='<th style="text-align: center; font-size: 11px;">TOTAL INGRESOS</th>';
        $html.='<th style="text-align: center; font-size: 11px;">TOTAL EGRESOS</th>';
        $html.='<th style="text-align: center; font-size: 11px;">VALOR A DEVOLVER</th>';
        $html.='<th style="text-align: center; font-size: 11px;">TIPO</th>';
        $html.='<th style="text-align: center; font-size: 11px;">ESTADO DE SOLICITUD</th>';
        $html.='<th style="text-align: center; font-size: 11px;">FORMA DE PAGO</th>';
        $html.='<th style="text-align: center; font-size: 11px;">NOMBRE DEL BANCO</th>';
        $html.='<th style="text-align: center; font-size: 11px;">No. DE CUENTA</th>';
        $html.='<th style="text-align: center; font-size: 11px;">TIPO DE CUENTA</th>';
        $html.='<th style="text-align: center; font-size: 11px;">TRANSFERENCIA</th>';
        $html.='</tr>';
        
        $html.= "<tr>";
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.= "</tr>";
        
        $html.= "<tr>";
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.= "</tr>";
        
        $html.= "<tr>";
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;">Total:</td>';
        $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.='<td style="text-align: center; font-size: 11px;"></td>';
        $html.= "</tr>";
        
        $html.='</table>';
        
        $html.='<table class="3" cellspacing="0" style="width:100px;" border="1" >';
        
        $html.= "<tr>";
        $html.='<th colspan="4" style="text-align: center; font-size: 11px;">RESUMEN TOTAL DE LA OPERACIÓN</th>';
        $html.= "</tr>";
        
        $html.= "<tr>";
        $html.='<th style="text-align: center; font-size: 11px;">FORMA DE PAGO</th>';
        $html.='<th style="text-align: center; font-size: 11px;">BANCO</th>';
        $html.='<th style="text-align: center; font-size: 11px;">TOTAL TRANSACCIONES</th>';
        $html.='<th style="text-align: center; font-size: 11px;">TOTAL</th>';
        $html.='</tr>';
        
        $html.= "<tr>";
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='</tr>';
        
        $html.= "<tr>";
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;">Total:</td>';
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;"></td>';
        $html.='<td class="htexto1" style="text-align: center; font-size: 11px;"></td>';
        $html.='<td class="htexto2" style="text-align: center; font-size: 11px;">'.$FECHA.'</td>';
        $html.='</tr>';
        
        $html.='</table>';
        
        
        
        
        
        $datos_reporte['DETALLE_DESAFILIACION']= $html;
        
        
        
        
        
        $this->verReporte("ReporteLiquidacion", array('datos_empresa'=>$datos_empresa, 'datos_cabecera'=>$datos_cabecera, 'datos_reporte'=>$datos_reporte));
        
        
        
        
    }
    
    
   
}

?>