<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of metadatosServices
 *
 * @author pwilson
 */
class metadatosServices implements MetadatosMetadatosInterface
{


    /**
     * Constructor de la interfaz, destinado a reservar para objetos de tipo model
     *
     * @return IEstructura
     */
    function __construct()
    {
        $this->estructura = new EstructuraModel();

    }

    /**
     * Devuelve un arreglo con los datos de la estructura
     * si en $idEstructura tiene el valor de "Estructuras"
     * devuelve los que son raices
     *
     * @param int $idEstructura
     * @return array
     */
    function DameEstructura($idEstructura)
    {
        $arraydata = $this->estructura->getEstructuraId($idEstructura);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }

    /**
     * Buscar todos los hijos de una estructura
     * el parametro es false entonce devuelve las raices
     *
     * @param int $idPadre
     * @return array
     */
    function DameHijosEstructura($idPadre)
    {
        $arraydata = $this->estructura->getHijos($idPadre);
        $result = ZendExt_ClassStandard::ArrayObject($arraydata);
        return $result;
    }
    /*public function endTransaction() {
        $doctrineManager = Doctrine_Manager::getInstance();
        $doctrineManager->setCurrentConnection($this->activeconn);
    }

    public function startTransaction() {
        $doctrineManager = Doctrine_Manager::getInstance();
	try {
		$conn = $doctrineManager->getConnection('metadatos');
	} catch (Doctrine_Manager_Exception $e) {
		$transactionManager = ZendExt_Aspect_TransactionManager::getInstance();
		$configApp = new ZendExt_App_Config();
		$configApp->addBdToConfig('metadatos', 'metadatos/');
		$conn = $transactionManager->openConections('metadatos');
	}
	$activeconn = $doctrineManager->getCurrentConnection()->getName();
	$doctrineManager->setCurrentConnection('metadatos');
        $this->activeconn=$activeconn;        
    }*/

}

?>
