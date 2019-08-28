<?php



class TipoComprobantesModel extends ModeloBase{
	
	private $table;
	private $where;
	private $funcion;
	private $parametros;
	
	public function getWhere() {
		return $this->where;
	}
	
	public function setWhere($where) {
		$this->where = $where;
	}
	
	public function getFuncion() {
		return $this->funcion;
	}
	
	
	public function setFuncion($funcion) {
		$this->funcion = $funcion;
	}
	
	
	
	public function getParametros() {
		return $this->parametros;
	}
	
	
	public function setParametros($parametros) {
		$this->parametros = $parametros;
	}
	
	
	
	
	public function __construct(){
		$this->table="tipo_comprobantes";

		
		parent::__construct($this->table);
	}
	

	public function Insert(){
		
		$query = "SELECT ".$this->funcion."(".$this->parametros.")";
		
		$resultado=$this->enviarFuncion($query);
			
			
		return  $resultado;
	}
	
	//dc 2019-08-27
	public function llamafuncionPG(){
	    
	    $query = "SELECT ".$this->funcion."(".$this->parametros.")";
	    $resultado = null;
	    
	    $resultado=$this->llamarconsultaPG($query);
	    
	    return  $resultado;
	}
	
	//dc 2019-08-28
	public function getTipoComprobanteByNombre($nTipoComprobante){
	    
	    $query = "SELECT id_tipo_comprobantes,nombre_tipo_comprobantes FROM tipo_comprobantes WHERE nombre_tipo_comprobantes = '$nTipoComprobante'";
	    
	    $resultado=$this->enviaquery($query);
	    
	    return  $resultado;
	    
	}
	
}
?>