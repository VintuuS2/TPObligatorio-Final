<?php

class Responsable {
    // Atributos
    private $numEmpleado;
    private $numLicencia;
    private $nombre;
    private $apellido;
    private $mensajeOperacion;

    // Método constructor
    public function __construct() {
        $this->numEmpleado = 0;
        $this->numLicencia = 0;
        $this->nombre = "";
        $this->apellido = "";
    }

    public function cargar($numLicencia, $nombre, $apellido) {
        $this->setNumLicencia($numLicencia);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
    }

    // Métodos de acceso
    public function setNumEmpleado($numEmpleado) {
        $this->numEmpleado = $numEmpleado;
    }

	public function getNumEmpleado() {
        return $this->numEmpleado;
    }

    public function setNumLicencia($numLicencia) {
        $this->numLicencia = $numLicencia;
    }

	public function getNumLicencia() {
        return $this->numLicencia;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

	public function getNombre() {
        return $this->nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

	public function getApellido() {
        return $this->apellido;
    }

    public function setMensajeOperacion($mensajeOperacion){
		$this->mensajeOperacion = $mensajeOperacion;
	}

	public function getMensajeOperacion(){
		return $this->mensajeOperacion;
	}

    /** Busca los datos de un responsable
	 * @param INT $dni
	 * @return BOOLEAN, true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($numEmpleado){
		$base = new BaseDatos();
		$consultaEmpleado = "SELECT * FROM responsable WHERE rnumeroempleado=".$numEmpleado;
		$resp = false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaEmpleado)){
				if($fila = $base->Registro()){
                    $this->setNumEmpleado($numEmpleado);
					$this->setNumLicencia($fila['rnumerolicencia']);
					$this->setNombre($fila['rnombre']);
					$this->setApellido($fila['rapellido']);
					$resp = true;
				}
		 	} else {
		 		$this->setMensajeOperacion($base->getError());
			}
		} else {
		 	$this->setMensajeOperacion($base->getError());
	    }		
		return $resp;
	}

	public function listar($condicion){
	    $arregloResponsables = null;
		$base=new BaseDatos();
		$consultaResponsable="SELECT * FROM responsable ";
		if ($condicion!=""){
		    $consultaResponsable=$consultaResponsable.' WHERE '.$condicion;
		}
		$consultaResponsable.=" order by rnumeroempleado ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaResponsable)){				
				$arregloResponsables= array();
				while($fila=$base->Registro()){
				    $numEmpleado=$fila['rnumeroempleado'];
					$numLicencia=$fila['rnumerolicencia'];
					$resNombre=$fila['rnombre'];	
					$resApellido=$fila['rapellido'];	
					$responsableAux = new Responsable();
					$responsableAux->cargar($numLicencia,$resNombre,$resApellido);
					$responsableAux->setNumEmpleado($numEmpleado);
					array_push($arregloResponsables,$responsableAux);	
				}							
		 	} else {
		 		$this->setmensajeoperacion($base->getError());		 		
			}
		} else {
		 	$this->setmensajeoperacion($base->getError());		 	
		}	
		return $arregloResponsables;
	}

    public function insertar(){
        $base = new BaseDatos();
        $resp = false;
        //Consulta que inserta los valores internos del objeto en la db
        $consultaInsertar = "INSERT INTO responsable(rnumerolicencia,rnombre,rapellido) 
                VALUES (".$this->getNumLicencia().",'".$this->getNombre()."','".$this->getApellido()."');";
        if ($base->Iniciar()) {
            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setNumEmpleado($id);
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
		$consultaModificar="UPDATE responsable SET rnumerolicencia=".$this->getNumLicencia()."
                           ,rnombre='".$this->getNombre()."',rapellido='".$this->getApellido()."' WHERE rnumeroempleado=".$this->getNumEmpleado();
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
				$consultaBorrar="DELETE FROM responsable WHERE rnumeroempleado=".$this->getNumEmpleado();
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
        $cadena = "Número de empleado: " . $this->getNumEmpleado() . "\n";
        $cadena .= "Número de licencia: " . $this->getNumLicencia() . "\n";
        $cadena .= "Nombre: " . $this->getNombre() . "\n";
        $cadena .= "Apellido: " . $this->getApellido() . "\n";
        return $cadena;
    }
}
?>