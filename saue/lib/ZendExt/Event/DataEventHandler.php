<?php

class ZendExt_Event_DataEventHandler {

    private function createTrace($pArray) {
        $pRecord = $pArray [0];
        $pId = $pArray [1];
        $table = $pRecord->getTable()->tableName;
        $result = explode('.', $table);
        $schema = $result[0];
        $tabla = $result[1];
        
        if ($schema != 'mod_traza') {
            $register = Zend_Registry::getInstance();
            $global = ZendExt_GlobalConcept::getInstance();

            $user = $global->Perfil->usuario;
            $idestructura = $global->Estructura->idestructura;
            $pEstructura = $global->Estructura->denominacion;
            $ip_host = $this->devolverIp();
            $idRol = $register->session->idrol;
            $pRol = $register->session->rol;
            $idDominio = $register->session->perfil['iddominio'];
            $pDominio = $register->session->perfil['dominio'];

            $frontController = Zend_Controller_Front::getInstance();
            if ($frontController->getRequest())
                $action = $frontController->getRequest()->getActionName();
            else
                $action = 'index';

            $id = null;

            foreach ($pId as $key => $value) {
                $id = $value;
                break;
            }

            $dataTrace = new ZendExt_Trace_Container_Data($schema, $tabla, $id, -1, $action, $ip_host, $idRol, $idDominio, $user, $idestructura, $pRol, $pDominio, $pEstructura);
            return $dataTrace;
        }

        return false;
    }

    function handleInsert($pArray) {
        $trace = $this->createTrace($pArray);

        if ($trace) {
            $trace->setIdOperacion(1);
            $instance = ZendExt_Trace :: getInstance();
            $instance->handle($trace);
        }
    }

    function handleUpdate($pArray) {
        $trace = $this->createTrace($pArray);

        if ($trace) {
            $trace->setIdOperacion(2);
            $instance = ZendExt_Trace :: getInstance();
            $instance->handle($trace);
        }
    }

    function handleDelete($pArray) {
        $trace = $this->createTrace($pArray);

        if ($trace) {
            $trace->setIdOperacion(3);
            $instance = ZendExt_Trace :: getInstance();
            $instance->handle($trace);
        }
    }

    private function devolverIp() {
        if ($_SERVER) {
            if ($_SERVER['HTTP_X_FORWARDED_FOR'])
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else {
                if ($_SERVER['HTTP_CLIENT_IP'])
                    $realip = $_SERVER['HTTP_CLIENT_IP'];
                else
                    $realip = $_SERVER['REMOTE_ADDR'];
            }
        }
        else {
            if (getenv("HTTP_X_FORWARDED_FOR"))
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            elseif (getenv("HTTP_CLIENT_IP"))
                $realip = getenv("HTTP_CLIENT_IP");
            else
                $realip = getenv("REMOTE_ADDR");
        }
        return $realip;
    }

}

?>
