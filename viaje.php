<?php

class Viaje {
    // Atributos
    private $idViaje;
    private $destino;
    private $cantMaxPasajeros;
    private $objResponsable;
    private $colPasajeros;
    private $importe;
    private $objEmpresa;
    private $mensajeOperacion;

    // Método constructor
    public function __construct() {
        $this->idViaje = 0;
        $this->destino = "";
        $this->cantMaxPasajeros = 0;
        $this->objResponsable = null;
        $this->colPasajeros = [];
        $this->importe = 0;
        $this->objEmpresa = null;
    }

    // Método cargar
    public function cargar($destino, $cantMaxPasajeros, $objResponsable, $importe, $objEmpresa) {
        $this->setDestino($destino);
        $this->setCantMaxPasajeros($cantMaxPasajeros);
        $this->setObjResponsable($objResponsable);
        $this->setImporte($importe);
        $this->setObjEmpresa($objEmpresa);
    }

    // Métodos de acceso
    public function setIdViaje($idViaje) {
        $this->idViaje = $idViaje;
    }

    public function getIdViaje() {
        return $this->idViaje;
    }

    public function setDestino($destino) {
        $this->destino = $destino;
    }

    public function getDestino() {
        return $this->destino;
    }

    public function setCantMaxPasajeros($cantMaxPasajeros) {
        $this->cantMaxPasajeros = $cantMaxPasajeros;
    }

    public function getCantMaxPasajeros() {
        return $this->cantMaxPasajeros;
    }

    public function setObjResponsable($objResponsable) {
        $this->objResponsable = $objResponsable;
    }

    public function getObjResponsable() {
        return $this->objResponsable;
    }

    public function setColPasajeros($colPasajeros) {
        $this->colPasajeros = $colPasajeros;
    }

    public function getColPasajeros() {
        return $this->colPasajeros;
    }

    public function setImporte($importe) {
        $this->importe = $importe;
    }

    public function getImporte() {
        return $this->importe;
    }

    public function setMensajeOperacion($mensajeOperacion) {
		$this->mensajeOperacion = $mensajeOperacion;
	}

    public function getMensajeOperacion(){
		return $this->mensajeOperacion;
	}

    public function setObjEmpresa($objEmpresa) {
		$this->objEmpresa = $objEmpresa;
    }

    public function getObjEmpresa() {
		return $this->objEmpresa;
    }

    // Método toString
    public function __toString() {
        $cadena = "Código del viaje: " . $this->getIdViaje() . "\n";
        $cadena .= "Destino: " . $this->getDestino() . "\n";
        $cadena .= "Cantidad máxima de pasajeros: " . $this->getCantMaxPasajeros() . "\n";
        $cadena .= "Datos del responsable: " . $this->getObjResponsable() . "\n";
        $cadena .= "Importe: " . $this->getImporte() . "\n";
        $cadena .= "Colección de pasajeros:\n" . "-----------------------------------\n";
        $arrayPasajeros = $this->getColPasajeros();
        for ($i = 0; $i < count($arrayPasajeros); $i++) {
            $unPasajero = $arrayPasajeros[$i];
            $cadena .= "Pasajero N°" . ($i+1) . ":\n";
            $cadena .= $unPasajero . "-----------------------------------\n";
        }
        return $cadena;
    }

    // Métodos de consulta
    public function buscar($idViaje){
        $base = new BaseDatos();
        // Crea consulta
        $consultaViaje = "SELECT * FROM viaje WHERE idviaje=".$idViaje;
        $resp = false;
        if($base->Iniciar()){
            if($base->Ejecutar($consultaViaje)){
                if($fila = $base->Registro()){
                    // Crea objetos auxiliar
                    $unResponsable = new Responsable();
                    $unaEmpresa = new Empresa();
                    // Setea el viaje
                    $this->setIdViaje($idViaje);
                    $destino = ($fila['vdestino']);
                    $cantPasajeros= ($fila['vcantmaxpasajeros']);
                    $importe = ($fila['vimporte']);
                    // El Buscar devuelve un rnumeroempleado el cual es directamente buscado por el obj auxiliar creado y luego set
                    $objResponsable = $unResponsable->buscar($fila['rnumeroempleado']);
                    $objEmpresa = $unaEmpresa->buscar($fila['idempresa']);
                    $this->cargar($destino, $cantPasajeros, $objResponsable, $importe, $objEmpresa);
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
	    $arregloViajes = null;
		$base=new BaseDatos();
		$consultaViajes="Select * from viaje ";
		if ($condicion!=""){
		    $consultaViajes=$consultaViajes.' where '.$condicion;
		}
		$consultaViajes.=" order by idviaje ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViajes)){				
				$arregloViajes= array();
				while($fila=$base->Registro()){
				    $objEmpresa = new Empresa();
                    $objResponsable = new Responsable();
                    $idViaje = $fila['idviaje'];
                    $destino = $fila['vdestino'];
                    $cantMaxPasajeros = $fila['vcantmaxpasajeros'];
                    $objEmpresa->buscar($fila['idempresa']);
                    $objResponsable->buscar($fila['rnumeroempleado']);
                    $importe = $fila['vimporte'];
                    
                    // Seteo el objeto viaje
                    $viajeAux = new Viaje();
                    $viajeAux->cargar($destino,$cantMaxPasajeros,$objResponsable,$importe,$objEmpresa);
                    $viajeAux->setIdViaje($idViaje);
                    array_push($arregloViajes,$viajeAux);	
				}							
		 	} else {
		 		$this->setmensajeoperacion($base->getError());		 		
			}
		} else {
		 	$this->setmensajeoperacion($base->getError());		 	
		}	
		return $arregloViajes;
	}

    public function insertar(){
        $base = new BaseDatos();
        $resp= false;
        $consultaInsertar = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) 
                VALUES ('" . $this->getDestino() . "'," . $this->getCantMaxPasajeros() . "," . $this->getObjEmpresa()->getId() . ","  . $this->getObjResponsable()->getNumEmpleado() . "," . $this->getImporte() . ")";
        if ($base->Iniciar()) {
            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setIdViaje($id);
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
        $consultaModificar = "UPDATE viaje SET vdestino='".$this->getDestino()."',vcantmaxpasajeros='".$this->getCantMaxPasajeros()."',idempresa=".$this->getObjEmpresa()->getId().",rnumeroempleado=".$this->getObjResponsable()->getNumEmpleado().",vimporte=".$this->getImporte()." WHERE idviaje = ".$this->getIdViaje();
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
                $consultaBorrar = "DELETE FROM viaje WHERE idviaje=".$this->getIdViaje();
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

}
?>