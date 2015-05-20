<?php
class ServicioExcepcionModel extends ZendExt_Model
{
    public function servicio($idsistema)
    {
        $dirsistema =  $this->integrator->seguridad->getRutasistema($idsistema);
        $dirsistema = str_replace('/', DIRECTORY_SEPARATOR, $dirsistema);
        $direccion = $dirsistema.DIRECTORY_SEPARATOR."comun".DIRECTORY_SEPARATOR."recursos".DIRECTORY_SEPARATOR."xml".DIRECTORY_SEPARATOR."exception.xml";

        /*if (!file_exists($direccion))
        {
            throw new ZendExt_Exception();
        }*/
        $xml = simplexml_load_file($direccion);
        $excepciones = $xml->children();
        $cantf = count($excepciones);
        $arrayExcepciones = array();

        for($cont=0; $cont<$cantf; $cont++){
            $item = array();
            $item['nombre']= (string)($excepciones[$cont]->attributes()->$nombre);
            $item['codigo']= $excepciones[$cont]->getName();
            $item['tipo']= (string)$excepciones[$cont]->tipo;
            $item['mensaje']= (string)($excepciones[$cont]->es->mensaje);
            $item['descripcion']= (string)($excepciones[$cont]->es->descripcion);
            $arrayExcepciones[] = $item;
        }
        return $arrayExcepciones;
    }
}
?>
