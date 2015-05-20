<?php
/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */
	class SegCertificadoModel extends ZendExt_Model
	  {
	   public function SegCertificadoModel()
		   {
		      parent::ZendExt_Model('seguridad');
		   }
	public function insertarcertificado($objCertificado)
	{
			$objCertificado->fecha = date ('d/m/Y');
                        $objCertificado->hora= date ('H:i:s');
                        $objCertificado->idsession=session_id();
                        $objCertificado->save();		
			$dm = Doctrine_Manager::getInstance();
			$conn = $dm->getCurrentConnection();
			$conn->commit();
	}	

	public function modificarcertificado($objCertificado)
	{
		$objCertificado->save();
	}
	
	public function modificarcertificadoServicio($objCertificado) {
		$objCertificado->save();
		$this->conn->commit();
		$q = Doctrine_Query::create();
		$certifArr = $q->select('s.valor')->from('SegCertificado s')->where('s.valor = ?', $objCertificado->valor)->execute();
		if (count($certifArr))
			return  $certifArr[0]->valor;
		return 0;
	}
}













?>