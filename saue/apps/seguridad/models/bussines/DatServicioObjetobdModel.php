<?php

class DatServicioObjetobdModel extends ZendExt_Model {

    public function DatServicioObjetobdModel() {
        parent::ZendExt_Model();
    }

    public function insertar($obj) {
        $obj->save();
    }

    public function modificar($instance) {

        $instance->save();
    }

    public function eliminar($instance) {
        $instance->delete();
    }
    
    public function ExtraerConexionesRepetidas($datosConexion) {
        $cont = 0;
        $contenidos = array();
        $newdatosConexion = array();

        foreach ($datosConexion as $value) {
            $elementobuscar['idbd'] = $value['idbd'];
            $elementobuscar['idesquema'] = $value['idesquema'];
            $elementobuscar['idservidor'] = $value['idservidor'];
            $elementobuscar['idgestor'] = $value['idgestor'];
            $elementobuscar['idrolesbd'] = $value['idrolesbd'];

            if (!in_array($elementobuscar, $contenidos)) {
                $contenidos[$cont] = $elementobuscar;
                $newdatosConexion[] = $value;
                $cont++;
            }
        }

        return $newdatosConexion;
    }    
    
    function VerifyConnection($dsn) {
        $c = explode("@", $dsn);
        $ig = explode(":", $c[1]);
        $ip = $ig[0];
        if (PHP_OS == "Linux") {
            $str = exec("ping -c1 -W2 $ip", $input, $result);
        } else {
            $str = exec("ping -n 1 -w 1 $ip", $input, $result);
        }
        if ($result == 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function TratarCriterioBusqueda($objetoName) {
        $inserted = "";
        for ($i = 0; $i < strlen($objetoName); $i++) {
            if ($objetoName[$i] == "_") {
                $inserted.='!';
            }
            $inserted.=$objetoName[$i];
        }
        return $inserted;
    }
    
    function accion_privilegio($arrayidaccion, $idservicio, $idobjetobd) {
        $accion_privilegio = array();
        $sevicio_objeto = DatServicioObjetobd::getById($idservicio, $idobjetobd);
        $privilegio = $sevicio_objeto->privilegios;
        foreach ($arrayidaccion as $idaccion) {
            $accion_privilegio[] = array(
                "idaccion" => $idaccion,
                "privilegios" => $privilegio
            );
        }
        return $accion_privilegio;
    }
    
    function ArrayPermisosToString($arrayPermisos) {
        $permisos = "";
        $ListOfPrivileges = array();
        $ListOfPrivileges['SEL'] = "r";
        $ListOfPrivileges['INS'] = "a";
        $ListOfPrivileges['UPD'] = "w";
        $ListOfPrivileges['DEL'] = "d";
        $ListOfPrivileges['REF'] = "x";
        $ListOfPrivileges['TRIG'] = "t";
        $ListOfPrivileges['EXEC'] = "X";
        $ListOfPrivileges['USG'] = "U";
        foreach ($arrayPermisos as $value) {
            if ($value == "OWN") {
                $permisos = "rawdxt";
                break;
            } else {
                $permisos.=$ListOfPrivileges[$value];
            }
        }
        return $permisos;
    }
    
     function CombinarPermisos($permisosHay, $permisosPoner) {
        $permisos = array();
        $permisos['r'] = false;
        $permisos['a'] = false;
        $permisos['w'] = false;
        $permisos['d'] = false;
        $permisos['x'] = false;
        $permisos['t'] = false;
        $permisos['X'] = false;
        $permisos['U'] = false;
        for ($index = 0; $index < strlen($permisosHay); $index++)
            $permisos[$permisosHay[$index]] = true;
        for ($index1 = 0; $index1 < count($permisosPoner); $index1++)
            $permisos[$permisosPoner[$index1]] = true;
        $combinado = "";
        foreach ($permisos as $key => $permiso) {
            if ($permiso)
                $combinado.=$key;
        }

        return $combinado;
    }
    
     
}

