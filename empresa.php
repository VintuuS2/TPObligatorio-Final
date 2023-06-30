<?php

use Empresa as GlobalEmpresa;

class Empresa {
    // Atributos
    private $id;
    private $nombre;
    private $direccion;
	private $colViajes = array();
    private $mensajeOperacion;

    public function __construct() {
        $this->id = 0;
        $this->nombre = "";
        $this->direccion = "";
    }

    public function cargar($nombre, $direccion) {
        $this->setNombre($nombre);
        $this->setDireccion($direccion);
    }

    public function setId($id) {
        $this->id = $id;
    }

	public function getId() {
        return $this->id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

	public function getNombre() {
        return $this->nombre;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

	public function getDireccion() {
        return $this->direccion;
    }

	function setColViajes($colViajes){
		$this->colViajes = $colViajes;
	}

	function getColViajes(){
		return $this->colViajes;
	}

    public function setMensajeOperacion($mensajeOperacion){
		$this->mensajeOperacion = $mensajeOperacion;
	}

	public function getMensajeOperacion(){
		return $this->mensajeOperacion;
	}

	/** Busca los datos de una persona por una ID
	 * @param INT $idEmpresa
	 * @return BOOLEAN true en caso de encontrar los datos, false en caso contrario 
	 */		
	function buscar($idEmpresa){
		$base=new BaseDatos();
		$consultaEmpresa="Select * from empresa where idempresa=".$idEmpresa;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresa)){
				if($fila=$base->Registro()){
				    $this->setId($idEmpresa);
				    $this->setNombre($fila['enombre']);
					$this->setDireccion($fila['edireccion']);
					$resp= true;
				}							
		 	} else {
		 		$this->setmensajeoperacion($base->getError());		 		
			}
		} else {
		 	$this->setmensajeoperacion($base->getError());		 	
		}		
		return $resp;
	}

	public function listar($condicion){
	    $arregloEmpresas = null;
		$base=new BaseDatos();
		$consultaEmpresas="Select * from empresa ";
		if ($condicion!=""){
		    $consultaEmpresas=$consultaEmpresas.' where '.$condicion;
		}
		$consultaEmpresas.=" order by idempresa ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpresas)){				
				$arregloEmpresas= array();
				while($fila=$base->Registro()){
				    $idEmpresa=$fila['idempresa'];
					$eNombre=$fila['enombre'];
					$eDireccion=$fila['edireccion'];	
					$this->cargar($eNombre,$eDireccion);
					$this->setId($idEmpresa);
					array_push($arregloEmpresas,$this);	
				}							
		 	} else {
		 		$this->setmensajeoperacion($base->getError());		 		
			}
		} else {
		 	$this->setmensajeoperacion($base->getError());		 	
		}	
		return $arregloEmpresas;
	}

    public function insertar(){
		$base = new BaseDatos();
		$resp = false;
		$consultaInsertar = "INSERT INTO empresa(enombre,edireccion) 
				VALUES ('".$this->getNombre()."','".$this->getDireccion()."')";
		if ($base->Iniciar()) {
			if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setId($id);
			    $resp = true;
			} else {
	    		$this->setMensajeOperacion($base->getError());			
			}
		} else {
			$this->setMensajeOperacion($base->getError());
		}
		return $resp;
	}
	
	public function modificar(){
	    $resp = false; 
	    $base = new BaseDatos();
		$consultaModificar = "UPDATE empresa SET enombre='".$this->getNombre()."'
                           ,edireccion='".$this->getDireccion()."' WHERE idempresa=".$this->getId();
		if ($base->Iniciar()) {
			if ($base->Ejecutar($consultaModificar)) {
			    $resp = true;
			} else {
				$this->setMensajeOperacion($base->getError());
			}
		} else {
			$this->setMensajeOperacion($base->getError());
		}
		return $resp;
	}
	
	public function eliminar(){
		$base = new BaseDatos();
		$resp = false;
		if ($base->Iniciar()) {
				$consultaBorrar="DELETE FROM empresa WHERE idempresa=".$this->getId();
				if ($base->Ejecutar($consultaBorrar)) {
				    $resp = true;
				} else {
					$this->setMensajeOperacion($base->getError());
				}
		} else {
			$this->setMensajeOperacion($base->getError());
		}
		return $resp; 
	}

    // Método toString
    public function __toString() {
        $cadena = "ID: " . $this->getId() . "\n";
        $cadena .= "Nombre: " . $this->getNombre() . "\n";
        $cadena .= "Dirección: " . $this->getDireccion() . "\n";
        return $cadena;
    }
}
?>