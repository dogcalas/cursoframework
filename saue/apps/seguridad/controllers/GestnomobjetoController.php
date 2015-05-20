<?php

class GestnomobjetoController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestnomobjetoAction() {
        $this->render();
    }

    function insertarnomobjetoAction() {

        $objeto = new NomObjetospermisos();
        $objeto->nombreobjeto = $this->_request->getPost('nombreobjeto');
        $objeto->descripcion = $this->_request->getPost('descripcion');
        $objeto->idobj=$this->determinarIdObjetoXNombre($objeto->nombreobjeto);
        $model = new NomObjetospermisosModel();
        $model->insertarnomobjeto($objeto);       
        echo"{'codMsg':1,'mensaje':perfil.etiquetas.lbMsgAddObj}";
    }
    
    function modificaromobjetoAction() {
        $id = $this->_request->getPost('id');
        $objbd = $this->_request->getPost('nombreobjeto');
        $descripcion = $this->_request->getPost('descripcion');
        $objeto_mod = Doctrine::getTable('NomObjetospermisos')->find($id);
        $objeto_mod->descripcion = $descripcion;
        if($objbd!=null){
        $objeto_mod->nombreobjeto = $objbd;
        $objeto_mod->idobj=$this->determinarIdObjetoXNombre($objeto_mod->nombreobjeto);
        }
        $model = new NomObjetospermisosModel();
        $model->modificarnomobjeto($objeto_mod);
       echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgModObj}";
    }
    
    function eliminarobjetoAction() {

        $model = new NomObjetospermisosModel();
        $objeto = Doctrine::getTable('NomObjetospermisos')->find($this->_request->getPost('id'));
        $model->eliminarnomobjeto($objeto);
       echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbMsgDelObj}";
    }

    function cargarnomobjetosAction() {
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
        $datosobjetos = NomObjetospermisos::cargarnomobjetos($limit, $start);
        $canfilas = count($datosobjetos);
        $datos = $datosobjetos->toArray();
        $result = array('cantidad_filas' => $canfilas, 'datos' => $datos);
        echo json_encode($result);
    }
    
    function getObjetosBdAction() {
        $datos = array(
            0 => array("nombreobjeto" => "Tablas","idobj"=>18),
            1 => array("nombreobjeto" => "Vistas","idobj"=>19),
            2 => array("nombreobjeto" => "Secuencias","idobj"=>20),
            3 => array("nombreobjeto" => "Funciones","idobj"=>21),
            4 => array("nombreobjeto" => "Servicios","idobj"=>22)
            );
        $objetosCargados=array();
        $cargar=array();
        $objetosCargados=NomObjetospermisos::buscarnomobjetos($objeto,0,5);
        foreach ($datos as $obj) {
            if($this->EsCargado($objetosCargados, $obj['idobj']))
                 $cargar[]=$obj;   
        }
        echo json_encode($cargar);
    }
    
    //------------------Funciones Privadas----------------------------
    private function determinarIdObjetoXNombre($nombre){
        $datos = array("Tablas"=>18,"Vistas"=>19,"Secuencias"=>20,"Funciones"=>21,"Servicios"=>22);
        return $datos[$nombre];
    }
    
    private function EsCargado($objetosCargados,$idobj){
        foreach ($objetosCargados as $objbd) {
            if($objbd->idobj==$idobj)
                return false;
        }
        return true;
    }

}

?>