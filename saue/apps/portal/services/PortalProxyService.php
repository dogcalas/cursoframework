<?php
class PortalProxyService {

	public function buscarUsuarioByAlias($alias)
	{
		try
		{
        		return Modulo::buscarUsuarioByAlias($alias);
		}
        	catch (Exception $e)
        	{
			throw new ZendExt_Exception('EP001', $e);
        	}
	}
	
	public function ObtenerSubsitemaURI($uri, $identidad)
	{
		$subsistemas = array();
		$subsistemas['metadatos'] = array(1, 'Estructura y Composición', 'metadatos');
		$subsistemas['portal'] = array(96, 'Portal', 'portal');;
		$subsistemas['seguridad'] = array(97, 'Seguridad', 'seguridad');
		$subsistemas['traza'] = array(98, 'Traza', 'traza');
		$subsistemas['instalador'] = array(99, 'Instalador', 'instalador');
		
		$standard = new stdClass();
		$standard->idsubsistema = $subsistemas[$uri->nivel1][0];
		$standard->uri = $subsistemas[$uri->nivel1][2];
		$standard->denominacion = $subsistemas[$uri->nivel1][1];
		$standard->idestado = 0;
		$standard->estado = '';
		$standard->version = 0;
		$standard->crc = 0;
		
		return $standard;
	}
}
