<?php
class ExcepcionesProxyService {
    
    public function GetExcepciones($idsistema)
    {
	$a = new ExcepcionModel();
        return $a->servicio($idsistema);
    }
}
