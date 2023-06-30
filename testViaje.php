<?php
include_once 'db.php';
include_once 'empresa.php';
include_once 'pasajero.php';
include_once 'responsableV.php';
include_once 'viaje.php';

$objEmpresa = new Empresa();
$objResponsable = new Responsable();
$objViaje = new Viaje();
$objPasajero = new Pasajero();
$opcionElegida=0;

while ($opcionElegida != 17){
    echo "----------Menú de empresa----------\n";
    echo "1- Insertar los datos de la empresa\n";
    echo "2- Modificar los datos de la empresa\n";
    echo "3- Eliminar los datos de la empresa\n";
    echo "\n----------Menú de responsable----------\n";
    echo "4- Insertar los datos de un responsable\n";
    echo "5- Modificar los datos de un responsable\n";
    echo "6- Eliminar los datos de un responsable\n";
    echo "\n----------Menú de Viaje----------\n";
    echo "7- Insertar los datos de un viaje\n";
    echo "8- Modificar los datos de un viaje\n";
    echo "9- Eliminar los datos de un viaje\n";
    echo "\n----------Menú de pasajeros----------\n";
    echo "10- Insertar los datos de pasajero\n";
    echo "11- Modificar los datos de un pasajero de un viaje\n";
    echo "12- Eliminar los datos de un pasajero de un viaje\n";
    echo "\n----------Mostrar datos----------\n";
    echo "13- Mostrar la empresa\n";
    echo "14- Mostrar un responsable\n";
    echo "15- Mostrar un viaje\n";
    echo "16- Mostrar pasajero de un viaje\n";
    echo "\n----------Salir----------\n";
    echo "17- Salir\n";
    echo "Elija una opción: ";
    $opcionElegida = trim(fgets(STDIN));
    echo "********************************\n";
    $listaResponsable = $objResponsable->listar(1);
    $listaEmpresa = $objEmpresa->listar(1);
    if (count($listaEmpresa) > 0) {
        $objEmpresa = $listaEmpresa[0];
    }
    $listaViajes = $objViaje->listar(1);
    $listaPasajeros = $objPasajero->listar(1);

    switch ($opcionElegida) {
        case 1:
            $listaEmpresa = $objEmpresa->listar(1);
            if (count($listaEmpresa)>0){
                $objEmpresa = $listaEmpresa[0];
                echo "ERROR: Ya hay una empresa, usa '2' para modificar los datos.\n";
            } else {
                echo "\nIngrese el nombre de la empresa: ";
                $nombreEmpresa = trim(fgets(STDIN));
                echo "\nIngrese la dirección de la empresa: ";
                $direccionEmpresa = trim(fgets(STDIN));
                $objEmpresa->cargar($nombreEmpresa, $direccionEmpresa);
                if ($objEmpresa->insertar()) {
                    echo "\nLa empresa ha sido cargada con éxito.\n";
                } else {
                    echo "\nNo se pudo agregar la empresa debido al siguiente error: " . $objEmpresa->getMensajeOperacion()."\n";
                }
            }
            sleep(3);
            break;

        case 2:
            if (count($listaEmpresa) > 0) {
                echo "Ingrese el nuevo nombre de la empresa: ";
                $nombreEmpresa = trim(fgets(STDIN));
                echo "Ingrese la nueva dirección de la empresa: ";
                $direccionEmpresa = trim(fgets(STDIN));
                $objEmpresa->setNombre($nombreEmpresa);
                $objEmpresa->setDireccion($direccionEmpresa);
                if ($objEmpresa->modificar()) {
                    echo "La empresa ha sido correctamente modificada.\n";
                } else {
                    echo "No se pudo realizar la modificación debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                }
            } else {
                echo "No hay ninguna empresa que modificar. Pruebe agregando una con la opción '1'.\n";
            }
            sleep(3);
            break;

        case 3:
            if (count($listaEmpresa)>0) {
                if (count($listaViajes)>0){
                    echo "ERROR: Hay viajes vinculados a esta empresa. Elimine los viajes antes de continuar.\n";
                } else {
                    if ($objEmpresa->eliminar()) {
                        echo "La empresa ha sido correctamente eliminada.\n";
                    } else {
                        echo "No se pudo realizar la eliminación de la empresa debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                    }
                }
            } else {
                echo "ERROR: No hay empresas para eliminar. Pruebe agregando una con la opción '1'.\n";
            }
            sleep(3);
            break;

        case 4:
            echo "\nIngrese el número de licencia: ";
            $numLicencia = trim(fgets(STDIN));
            echo "\nIngrese el nombre del responsable: ";
            $nombre = trim(fgets(STDIN));
            echo "\nIngrese el apellido del responsable: ";
            $apellido = trim(fgets(STDIN));
            $objResponsable->cargar($numLicencia, $nombre, $apellido);
            if ($objResponsable->insertar()) {
                echo "\nEl responsable ha sido correctamente cargado.\n";
            } else {
                echo "\nNo se pudo agregar al responsable debido al siguiente error: " . $objResponsable->getMensajeOperacion()."\n";
            }
            sleep(3);
            break;

        case 5:
            foreach ($listaResponsable as $unResponsable){
                echo "Nº RESPONSABLE: ". $unResponsable->getNumEmpleado(). " | NOMBRE Y APELLIDO: ".$unResponsable->getNombre(). " ". $unResponsable->getApellido()."\n";
            }
            echo "\nIngrese el número de empleado del responsable que desea modificar: ";
            $numEmpleado = trim(fgets(STDIN));
            if ($objResponsable->buscar($numEmpleado)){
                echo "\nIngrese el nuevo número de licencia: ";
                $numLicencia = trim(fgets(STDIN));
                echo "\nIngrese el nuevo nombre del responsable: ";
                $nuevoNombre = trim(fgets(STDIN));
                echo "\nIngrese el nuevo apellido del responsable: ";
                $nuevoApellido= trim(fgets(STDIN));
                $objResponsable->cargar($numLicencia, $nuevoNombre, $nuevoApellido);
                if ($objResponsable->modificar()) {
                    echo "\nEl responsable ha sido correctamente modificadado.\n";
                } else {
                    echo "\nNo se pudo realizar la modificación del responsable debido al siguiente error: " . $objResponsable->getMensajeOperacion();
                }
            } else {
                echo "\nNo existe ningún responsable con el número de empleado: " . $numEmpleado. "\n";
            }
            sleep(3);
            break;

        case 6:
            if (count($listaResponsable)>0){
                foreach ($listaResponsable as $unResponsable){
                    echo "Nº RESPONSABLE: ". $unResponsable->getNumEmpleado(). " | NOMBRE Y APELLIDO: ".$unResponsable->getNombre(). " ". $unResponsable->getApellido()."\n";
                }
                echo "\nIngrese el número de empleado del responsable que desea eliminar: ";
                $numEmpleado = trim(fgets(STDIN));
                //Pregunto si ese empleado está en algún viaje
                $listaAux = $objViaje->listar("rnumeroempleado=".$numEmpleado);
                if (count($listaAux)>0){
                    $unViajeAux = $listaAux[0];
                    if ($numEmpleado==$unViajeAux->getObjResponsable()->getNumEmpleado()){
                        echo "ERROR: No se puede eliminar un pasajero que esté vinculado a un viaje. Elimine los viajes antes de eliminar al responsable.\n";
                    }
                } else {
                    if ($objResponsable->buscar($numEmpleado)) {
                        if ($objResponsable->eliminar()) {
                            echo "\nEl responsable ha sido correctamente eliminado.\n";
                        } else {
                            echo "\nNo se pudo realizar la eliminación del responsable debido al siguiente error: " . $objEmpresa->getMensajeOperacion()."\n";
                        }
                    } else {
                        echo "\nNo existe ningún responsable con el número de empleado: " . $numEmpleado."\n";
                    }
                }
            } else {
                echo "ERROR. No existen responsables para eliminar, prueba agregando uno con la siguiente opción '4'.\n";
            }
            sleep(3);
            break;

        case 7:
            if (count($listaEmpresa)>0){
                if (count($listaResponsable)>0){
                    foreach($listaResponsable as $unResponsable){
                        echo "Nº RESPONSABLE: ". $unResponsable->getNumEmpleado().  " | NOMBRE Y APELLIDO: ". $unResponsable->getNombre(). " ". $unResponsable->getApellido()."\n";
                    }
                    echo "\nIngrese el número de empleado del responsable: ";
                    $numEmpleadoResponsableViaje = trim(fgets(STDIN));
                    if ($objResponsable->buscar($numEmpleadoResponsableViaje)){
                        echo "\nIngrese el destino: ";
                        $destinoViaje = trim(fgets(STDIN));
                        echo "\nIngrese la cantidad máxima de pasajeros: ";
                        $cantMaxPasajerosViaje = trim(fgets(STDIN));
                        echo "\nIngrese el importe: ";
                        $importeViaje = trim(fgets(STDIN));
                        $objViaje->cargar($destinoViaje, $cantMaxPasajerosViaje, $objResponsable, $importeViaje, $objEmpresa);
                        if ($objViaje->insertar()){
                            echo "El viaje ha sido cargado con éxito.\n";
                        } else {
                            echo "No se pudo agregar el viaje debido al siguiente error: " . $objEmpresa->getMensajeOperacion()."\n";
                        }
                    } else {
                        echo "ERROR. No existen responsables con ese Nro de empleado. Ingrese uno válido.\n";
                    }
                } else {
                    echo "ERROR. No existen responsables para añadir al viaje. Pruebe agregando uno con la opción '4'.\n";
                }
                $unaColViajes = $objEmpresa->getColViajes();
            } else {
                echo "ERROR. No existen ninguna empresa. Pruebe agregando una con la opción '1'.\n";
            }
            sleep(3);
            break;

        case 8:
            if (count($listaEmpresa)>0){
                if (count($listaViajes)>0){
                    if (count($listaResponsable)>0){
                        foreach ($listaViajes as $unViaje){
                            echo "ID VIAJE: ".$unViaje->getIdViaje()." | DESTINO: ".$unViaje->getDestino(). " | IMPORTE: ".$unViaje->getImporte()."\n";
                        }
                        echo "\nIngrese el ID del viaje que desea modificar: ";
                        $idViaje = trim(fgets(STDIN));
                        echo "--------------------------------\n";
                        if ($objViaje->buscar($idViaje)) {
                            $objViaje->setColPasajeros($objPasajero->listar("idviaje=".$idViaje));
                            foreach ($listaResponsable as $unResponsable){
                                echo "\nNúmero responsable: ". $unResponsable->getNumEmpleado(). " | Nombre y apellido: ".$unResponsable->getNombre(). " ". $unResponsable->getApellido()."\n";
                            }
                            echo "\nIngrese el número de empleado del nuevo responsable: ";
                            $numEmpleadoResponsableViaje = trim(fgets(STDIN));
                            echo "-----------------------------------\n";
                            if ($objResponsable->buscar($numEmpleadoResponsableViaje)) {
                                echo "\nIngrese la nueva cantidad máxima de pasajeros: ";
                                $cantMaxPasajerosViaje = trim(fgets(STDIN));
                                if (count($objViaje->getColPasajeros())<$cantMaxPasajerosViaje){
                                    echo "\nIngrese el nuevo destino del viaje: ";
                                    $destinoViaje = trim(fgets(STDIN));
                                    echo "\nIngrese el nuevo importe: ";
                                    $importeViaje = trim(fgets(STDIN));
                                    $objViaje->cargar($destinoViaje, $cantMaxPasajerosViaje, $objResponsable, $importeViaje, $objEmpresa);
                                    if ($objViaje->modificar()) {
                                        echo "\nEl viaje ha sido correctamente modificado.\n";
                                    } else {
                                        echo "\nNo se pudo realizar la modificación del viaje debido al siguiente error: " . $objResponsable->getMensajeOperacion()."\n";
                                    }
                                } else {
                                    echo "ERROR: No puedes ingresar esa cantidad máxima de pasajeros porque es menor a la cantidad de pasajeros cargados\n";
                                }
                            } else {
                                echo "\nNo existe ningún responsable con el número de empleado: " . $numEmpleadoResponsableViaje."\n";
                            }
                        } else {
                            echo "\nNo existe ningun viaje con el ID: " . $idViaje."\n";
                        }
                    } else {
                        echo "ERROR: No existen viajes para modificar. Pruebe agregando uno con la opción '7'\n";
                    }
                } else {
                    echo "ERROR: No existe ningún viaje. Pruebe agregando uno con la opción '7'.\n";
                }
            } else {
                echo "ERROR: No existen empresas que contengan viajes. Pruebe agregando una con la opción '1'.\n";
            }
            sleep(3);
            break;

        case 9:
            if (count($listaViajes)>0){
                foreach ($listaViajes as $unViaje){
                    echo "ID VIAJE: ".$unViaje->getIdViaje()." | DESTINO: ".$unViaje->getDestino(). " | IMPORTE: ".$unViaje->getImporte()."\n";
                }
                echo "\nIngrese el ID del viaje que desea borrar: ";
                $idViaje = trim(fgets(STDIN));
                $listaAux = $objPasajero->listar("idviaje=".$idViaje);
                if (count($listaAux)>0){
                    $unPasajeroAux = $listaAux[0];
                    if ($idViaje == $unPasajeroAux->getObjViaje()->getIdViaje()){
                        echo "ERROR: No se puede eliminar este viaje porque está vinculado a un pasajero. Elimine los pasajeros antes de eliminar el viaje.\n";
                    }
                } else {
                    if ($objViaje->buscar($idViaje)) {
                        if ($objViaje->eliminar()) {
                            echo "El viaje ha sido correctamente eliminado.\n";
                        } else {
                            echo "\nNo se pudo realizar la eliminación del viaje debido al siguiente error: " . $objEmpresa->getMensajeOperacion()."\n";
                        }
                    } else {
                        echo "\nNo existe ningún viaje con la ID: " . $idViaje."\n";
                    }
                }
            } else {
                echo "ERROR. No existen viajes para eliminar. Pruebe agregando uno con la opción '7'\n";
            }
            sleep(3);
            break;

        case 10:
            if (count($listaViajes)>0){
                echo "A qué viaje quiere ir?\n";
                $cantViajes = count($listaViajes);
                foreach ($listaViajes as $unViaje){
                    echo "ID: " . $unViaje->getIdViaje() . " Destino: " . $unViaje->getDestino()."\n";
                }
                echo "Ingrese el ID del viaje: ";
                $viajeElegido = trim(fgets(STDIN));
                /*echo "Ingrese un valor entre 1 y " . $cantViajes.": ";
                $viajeElegido = trim(fgets(STDIN))-1;*/
                if ($objViaje->buscar($viajeElegido)){
                    if (count($objViaje->getColPasajeros()) < $objViaje->getCantMaxPasajeros()){
                        echo "Ingrese el número de documento: ";
                        $documentoPasajero = trim(fgets(STDIN));
                        echo "Ingrese el nombre: ";
                        $nombrePasajero = trim(fgets(STDIN));
                        echo "Ingrese el apellido: ";
                        $apellidoPasajero = trim(fgets(STDIN));
                        echo "Ingrese el número de teléfono: ";
                        $nroTelefono = trim(fgets(STDIN));
                        $objPasajero->cargar($nombrePasajero, $apellidoPasajero, $documentoPasajero, $nroTelefono, $unViaje);
        
                        // Verifica que el pasajero este en el viaje
                        $encontroPasajero = false;
                        $pasajerosViaje = $unViaje->getColPasajeros();
                        $i=0;
                        while ($i < count($pasajerosViaje) && !$encontroPasajero) {
                            $unPasajero = $pasajerosViaje[$i];
                            if ($unPasajero->getNDocumento() == $documentoPasajero) {
                                $encontroPasajero = true; 
                            }
                            $i++;
                        }
                        if (!$encontroPasajero){
                            if ($objPasajero->insertar()) {
                                echo "El pasajero ha sido cargado con éxito.\n";
                            } else {
                                echo "No se pudo agregar el pasajero debido al siguiente error: " . $objEmpresa->getMensajeOperacion()."\n";
                            }
                        } else {
                            echo "ERROR: El pasajero ya está cargado.\n";
                        }
                    } else {
                        echo "ERROR: Este viaje no tiene cupo.\n";
                    }
                } else {
                    echo "ERROR: El valor ingresado es inválido. Porfavor, ingrese uno correcto.\n";
                }
            } else {
                echo "ERROR: No existen viajes cargados. Pruebe usando la opción '7'\n";
            }
            sleep(3);
            break;

        case 11:
            if (count($listaEmpresa)>0){
                if (count($listaViajes)>0){
                    foreach ($listaViajes as $unViaje){
                        echo "ID: " . $unViaje->getIdViaje() . " Destino: " . $unViaje->getDestino()."\n";
                    }
                    echo "Ingrese el ID del viaje que quiera ver: ";
                    $idAux = trim(fgets(STDIN));
                    echo "-----------------------------------\n";
                    if ($objViaje->buscar($idAux)){
                        $objViaje->setColPasajeros($objPasajero->listar("idviaje=".$idAux));
                        if (count($listaPasajeros)>0){
                            foreach ($listaPasajeros as $unPasajero){
                                echo "Nº DOCUMENTO: ". $unPasajero->getNDocumento(). " | NOMBRE Y APELLIDO: ".$unPasajero->getNombre()." ".$unPasajero->getApellido()."\n";
                            }
                            echo "Ingrese el Nro de documento del pasajero a modificar: ";
                            $nDoc = trim(fgets(STDIN));
                            if ($objPasajero->buscar($nDoc)){
                                echo "Ingrese el nuevo nombre: ";
                                $nombreNuevo = trim(fgets(STDIN));
                                echo "Ingrese el nuevo apellido: ";
                                $apellidoNuevo = trim(fgets(STDIN));
                                echo "Ingrese el nuevo nro telefono: ";
                                $telefonoNuevo = trim(fgets(STDIN));
                                $objPasajero->cargar($nombreNuevo, $apellidoNuevo, $nDoc, $telefonoNuevo, $objPasajero->getObjViaje());
                                $objPasajero->modificar();
                                echo "El pasajero ha sido modificado con éxito.\n";
                            } else {
                                echo "ERROR: No existe un pasajero con ese número de documento. Porfavor, ingrese uno válido.\n";
                            }
                        } else {
                            echo "ERROR: No existen pasajeros para eliminar. Pruebe agregando uno con la opción '10'.\n";
                        }
                    } else {
                        echo "ERROR: No existe el ID del viaje. Porfavor ingrese un ID de viaje válido.\n";
                    }
                } else {
                    echo "ERROR: No hay viajes que contengan pasajeros. Prueba agregando uno con la opción '7'.\n";
                }
            } else {
                echo "ERROR: No hay empresas que contengan viajes. Prueba agregando una con la opción '1'.\n";
            }
            sleep(3);
            break;

        case 12:
            if (count($listaEmpresa)>0){
                if (count($listaViajes)>0){
                    foreach ($listaViajes as $unViaje){
                        echo "ID: " . $unViaje->getIdViaje() . " Destino: " . $unViaje->getDestino()."\n";
                    }
                    echo "Ingrese el ID del viaje que quiera ver: ";
                    $idAux = trim(fgets(STDIN));
                    echo "-----------------------------------\n";
                    if ($objViaje->buscar($idAux)){
                        $objViaje->setColPasajeros($objPasajero->listar("idviaje=".$idAux));
                        if (count($listaPasajeros)>0){
                            foreach ($listaPasajeros as $unPasajero){
                                echo "Nº DOCUMENTO: ". $unPasajero->getNDocumento(). " | NOMBRE Y APELLIDO: ".$unPasajero->getNombre()." ".$unPasajero->getApellido()."\n";
                            }
                            echo "Ingrese el Nro de documento del pasajero para eliminar: ";
                            $nDoc = trim(fgets(STDIN));
                            if ($objPasajero->buscar($nDoc)){
                                if ($objPasajero->eliminar()) {
                                    echo "El pasajero ha sido eliminado correctamente.\n";
                                } else {
                                    echo "No se pudo realizar la eliminación del pasajero debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                                }                    
                            } else {
                                echo "ERROR: No existen pasajeros con ese número de documento. Ingrese uno válido.\n";
                            }
                        } else {
                            echo "ERROR: No existen pasajeros para eliminar. Pruebe agregando uno con la opción '10'.\n";
                        }
                    } else {
                        echo "ERROR: No existe el ID del viaje. Porfavor ingrese un ID de viaje válido.\n";
                    }
                } else {
                    echo "ERROR: No hay viajes que contengan pasajeros. Prueba agregando uno con la opción '7'.\n";
                }
            } else {
                echo "ERROR: No hay empresas que contengan viajes. Prueba agregando una con la opción '1'.\n";
            }
            sleep(3);
            break;
        case 13:
            if (count($listaEmpresa)>0){
                echo $objEmpresa;
            } else {
                echo "ERROR: No existen empresas para mostrar. Pruebe agregando una con la opción '1'.\n";
            }
            sleep(5);
            break;
        case 14:
            if (count($listaResponsable)>0){
                foreach($listaResponsable as $unResponsable){
                    echo "Nº RESPONSABLE: ". $unResponsable->getNumEmpleado().  " | NOMBRE Y APELLIDO: ". $unResponsable->getNombre(). " ". $unResponsable->getApellido()."\n";
                }
                echo "\nIngrese el Nro responsable que quieras ver: ";
                $nroAux = trim(fgets(STDIN));
                echo "-----------------------------------\n";
                if ($objResponsable->buscar($nroAux)){
                    echo $objResponsable;
                } else {
                    echo "ERROR: No existe el Nro del responsable. Intente ingresar uno válido.\n";
                }
            } else {
                echo "ERROR: No existen responsables para mostrar. Pruebe agregando uno con la opción '4'.\n";
            }
            sleep(5);
            break;
        case 15:
            if (count($listaEmpresa)>0){
                if (count($listaViajes)>0){
                    foreach ($listaViajes as $unViaje){
                        echo "ID: " . $unViaje->getIdViaje() . " Destino: " . $unViaje->getDestino()."\n";
                    }
                    echo "Ingrese el ID del viaje que quiera ver: ";
                    $idAux = trim(fgets(STDIN));
                    echo "-----------------------------------\n";
                    if ($objViaje->buscar($idAux)){
                        $objViaje->setColPasajeros($objPasajero->listar("idviaje=".$idAux));
                        echo $objViaje;
                    } else {
                        echo "ERROR: No existe el ID del viaje. Porfavor ingrese un ID de viaje válido.\n";
                    }
                } else {
                    echo "ERROR: No existen viajes para mostrar. Pruebe agregando uno con la opción '7'.\n";
                }
            } else {
                echo "ERROR: No existe empresa que contenga viajes. Pruebe agregando una con la opción '1'.\n";
            }
            sleep(5);
            break;
        case 16:
            if (count($listaEmpresa)>0){
                if (count($listaViajes)>0){
                    if (count($listaPasajeros)>0){
                        foreach ($listaViajes as $unViaje){
                            echo "ID: " . $unViaje->getIdViaje() . " Destino: " . $unViaje->getDestino()."\n" . "-----------------------------------\n";
                        }
                        echo "Ingrese el ID del viaje para mostrar sus pasajeros: ";
                        $idAux = trim(fgets(STDIN));
                        echo "-----------------------------------\n";
                        if ($objViaje->buscar($idAux)){
                            foreach ($listaPasajeros as $unPasajero){
                                echo "Nº DOCUMENTO: ". $unPasajero->getNDocumento(). " | NOMBRE Y APELLIDO: ".$unPasajero->getNombre()." ".$unPasajero->getApellido()."\n";
                            }
                            echo "-----------------------------------\n";
                            echo "Ingrese el Nro de documento del pasajero para mostrarlo: ";
                            $nDoc = trim(fgets(STDIN));
                            if ($objPasajero->buscar($nDoc)){
                                echo $objPasajero . "-----------------------------------\n";
                            } else {
                                echo "ERROR: No existe el Nº de documento. Porfavor ingrese un número válido.\n";
                            }
                        } else {
                            echo "ERROR: No existe ID del viaje. Porfavor ingrese un número válido.\n";
                        }
                    } else {
                        echo "ERROR: No existen pasajeros para mostrar. Pruebe ingresando uno con la opción '10'.\n";
                    }
                } else {
                    echo "ERROR: No existen viajes para mostrar. Pruebe ingresando uno con la opción '7'.\n";
                }
            } else {
                echo "ERROR: No existe una empresa. Pruebe agregando una con la opción '1'.";
            }
            sleep(5);
            break;
        default:
            if ($opcionElegida<1 || $opcionElegida>17){
                echo "ERROR: Ingrese una opción válida.\n";
                echo "-----------------------------------\n";
            }
            sleep(3);
            break;
    }
}

?>