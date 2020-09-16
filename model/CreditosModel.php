<?php
class CreditosModel extends ModeloBase{
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
		$this->table="core_creditos";
	
		parent::__construct($this->table);
	}
	
    public function Insert(){
    
    	$query = "SELECT ".$this->funcion."(".$this->parametros.")";
    
    	$resultado=$this->enviarFuncion($query);
    		
    		
    	return  $resultado;
    }
    
    public function llamafuncion(){
        
        $query = "SELECT ".$this->funcion."(".$this->parametros.")";
        $resultado = null;
        
        $resultado=$this->llamarconsulta($query);
        
        return  $resultado;
    }
    
    public function llamafuncionPG(){
        
        $query = "SELECT ".$this->funcion."(".$this->parametros.")";
        $resultado = null;
        
        $resultado=$this->llamarconsultaPG($query);
        
        return  $resultado;
    }
    
    public function obtenerIdEstado($nombre)
    {
        $query  = " SELECT id_estado_creditos FROM core_estado_creditos WHERE id_estatus = 1 AND nombre_estado_creditos = '$nombre' LIMIT 1";
        
        $resultado  = $this->enviaquery($query);
        
        return $resultado[0]->id_estado_creditos ?? 0;
        
    }
}
?>
