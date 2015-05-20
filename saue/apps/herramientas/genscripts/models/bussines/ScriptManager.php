<?php

/**
 * @author René Bauta Camejo
 */
class ScriptManager {

    function Tables($tablas, $schema, $text) {
        $cont = 0;
        $array_return = array();
        foreach ($tablas as $var) {
            $arreglo_auxiliar['leaf'] = true;
            $arreglo_auxiliar['text'] = $var['tablename'];
            $arreglo_auxiliar['id'] = $schema . '^' . $var['tablename'];
            if ($text != 'Datos')
                $arreglo_auxiliar['checked'] = false;
            $array_return[$cont] = $arreglo_auxiliar;
            $cont++;
        }
        return $array_return;
    }

    function Functions($funciones, $schema) {
        $cont = 0;
        $array_return = array();
        $iguales = array();
        for ($i = 0; $i < count($funciones); $i++) {
            for ($j = $i + 1; $j < count($funciones); $j++) {
                if ($funciones[$i]['proname'] == $funciones[$j]['proname'])
                    $iguales[$i] = $funciones[$i]['proname'];
            }
        }
        foreach ($funciones as $var) {
            $arreglo_auxiliar['leaf'] = true;
            if (in_array($var['proname'], $iguales)) {
                $arreglo_auxiliar['text'] = $var['proname'] . ' -----> ' . $var['oid'];
                $arreglo_auxiliar['id'] = $schema . '^' . $var['proname'] . '^' . $var['oid'];
            } else {
                $arreglo_auxiliar['text'] = $var['proname'];
                $arreglo_auxiliar['id'] = $schema . '^' . $var['proname'];
            }
            $arreglo_auxiliar['checked'] = false;
            $array_return[$cont] = $arreglo_auxiliar;
            $cont++;
        }
        return $array_return;
    }

    function Triggers($disparadores, $schema) {
        $cont = 0;
        $array_return = array();
        $iguales = array();
        for ($i = 0; $i < count($disparadores); $i++) {
            for ($j = $i + 1; $j < count($disparadores); $j++) {
                if ($disparadores[$i]['trigger_name'] == $disparadores[$j]['trigger_name'])
                    $iguales[$i] = $disparadores[$i]['trigger_name'];
            }
        }
        foreach ($disparadores as $var) {
            $arreglo_auxiliar['leaf'] = true;
            if (in_array($var['trigger_name'], $iguales))
                $arreglo_auxiliar['text'] = $var['trigger_name'] . ' -----> ' . $var['event_object_table'];
            else
                $arreglo_auxiliar['text'] = $var['trigger_name'];
            $arreglo_auxiliar['id'] = $schema . '^' . $var['trigger_name'] . '^' . $var['event_object_table'];
            $arreglo_auxiliar['checked'] = false;
            $array_return[$cont] = $arreglo_auxiliar;
            $cont++;
        }
        return $array_return;
    }

    function Indexs($indices, $schema) {
        $cont = 0;
        $array_return = array();
        foreach ($indices as $var) {
            $arreglo_auxiliar['leaf'] = true;
            $arreglo_auxiliar['text'] = $var['indexname'];
            $arreglo_auxiliar['id'] = $schema . '^' . $var['indexname'];
            $arreglo_auxiliar['checked'] = false;
            $array_return[$cont] = $arreglo_auxiliar;
            $cont++;
        }
        return $array_return;
    }

    function Sequences($secuencias, $schema) {
        $cont = 0;
        $array_return = array();
        foreach ($secuencias as $var) {
            $arreglo_auxiliar['leaf'] = true;
            $arreglo_auxiliar['text'] = $var['sequence_name'];
            $arreglo_auxiliar['id'] = $schema . '^' . $var['sequence_name'];
            $arreglo_auxiliar['checked'] = false;
            $array_return[$cont] = $arreglo_auxiliar;
            $cont++;
        }
        return $array_return;
    }

    function ScriptEstructura($schemas, $nodo) {
        $cont = 0;
        $idSchema = array();
        foreach ($schemas as $var) {
            $idSchema[$cont] = (string) $var['table_schema'];
            $cont++;
        }
        $cont = 0;
        $array_return = array();
        while ($cont < count($idSchema)) {

            if ($nodo == $idSchema[$cont]) {
                $arreglo_auxiliar['leaf'] = false;
                $arreglo_auxiliar['text'] = 'Tablas';
                $arreglo_auxiliar['id'] = $nodo . '^tables';
                $array_return[0] = $arreglo_auxiliar;
                $arreglo_auxiliar['leaf'] = false;
                $arreglo_auxiliar['text'] = 'Funciones';
                $arreglo_auxiliar['id'] = $nodo . '^functions';
                $array_return[1] = $arreglo_auxiliar;
                $arreglo_auxiliar['leaf'] = false;
                $arreglo_auxiliar['text'] = 'Disparadores';
                $arreglo_auxiliar['id'] = $nodo . '^triggers';
                $array_return[2] = $arreglo_auxiliar;
                $arreglo_auxiliar['leaf'] = false;
                $arreglo_auxiliar['text'] = 'Indices';
                $arreglo_auxiliar['id'] = $nodo . '^index';
                $array_return[3] = $arreglo_auxiliar;
                $arreglo_auxiliar['leaf'] = false;
                $arreglo_auxiliar['text'] = 'Tipos de dato';
                $arreglo_auxiliar['id'] = $nodo . '^types';
                $array_return[4] = $arreglo_auxiliar;
                $arreglo_auxiliar['leaf'] = false;
                $arreglo_auxiliar['text'] = 'Secuencias';
                $arreglo_auxiliar['id'] = $nodo . '^sequences';
                $array_return[5] = $arreglo_auxiliar;
            }
            $cont++;
        }
        return $array_return;
    }

    function ScriptPermisos($schemas, $nodo) {
        $cont = 0;
        $idSchema = array();
        foreach ($schemas as $var) {
            $idSchema[$cont] = (string) $var['table_schema'];
            $cont++;
        }
        $cont = 0;
        $array_return = array();
        while ($cont < count($idSchema)) {

            if ($nodo == $idSchema[$cont]) {
                $arreglo_auxiliar['leaf'] = false;
                $arreglo_auxiliar['text'] = 'Tablas';
                $arreglo_auxiliar['id'] = $nodo . '^tables';
                $array_return[0] = $arreglo_auxiliar;
                $arreglo_auxiliar['leaf'] = false;
                $arreglo_auxiliar['text'] = 'Funciones';
                $arreglo_auxiliar['id'] = $nodo . '^functions';
                $array_return[1] = $arreglo_auxiliar;
                $arreglo_auxiliar['leaf'] = false;
                $arreglo_auxiliar['text'] = 'Tipos de Datos';
                $arreglo_auxiliar['id'] = $nodo . '^types';
                $array_return[2] = $arreglo_auxiliar;
                $arreglo_auxiliar['leaf'] = false;
                $arreglo_auxiliar['text'] = 'Secuencias';
                $arreglo_auxiliar['id'] = $nodo . '^sequences';
                $array_return[3] = $arreglo_auxiliar;
            }
            $cont++;
        }
        return $array_return;
    }

    function HeadersScEst($tipo) {
        $script = "";
        $script = "\n-------------------------------------------------------------------------------\n" .
                "---------------------------Creación de $tipo------------------------------\n" .
                "-------------------------------------------------------------------------------\n";
        return $script;
    }

    function HeadersScPer($tipo) {
        $script = "";
        $script = "\n-------------------------------------------------------------------------------\n" .
                "---------------------------$tipo------------------------------\n" .
                "-------------------------------------------------------------------------------\n";
        return $script;
    }

    function HeadersScDat($schema, $table) {
        $script = $script . "\n------------------------------------------------------------------------\n";
        $script = $script . "----------Insertando datos en $schema.$table\n";
        $script = $script . "------------------------------------------------------------------------\n";
        return $script;
    }

    function Footer() {
        $script = $script . "\n--termina la transacción--\n";
        $script = $script . "COMMIT;";

        return $script;
    }

    function SalvarScript($paquete, $script, $tipo) {
        try {
            $dir = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'herramientas' . DIRECTORY_SEPARATOR . 'genscripts' . DIRECTORY_SEPARATOR . $paquete;
            $nombre_archivo = "$tipo-$paquete.sql";
            if (!is_dir($dir)) {
                mkdir($paquete, 0777);
            }
            if (!file_exists($dir . DIRECTORY_SEPARATOR . $nombre_archivo)) {
                $archivo = fopen($dir . DIRECTORY_SEPARATOR . $nombre_archivo, 'a');
            } else {
                unlink($dir . DIRECTORY_SEPARATOR . $nombre_archivo);
                $archivo = fopen($dir . DIRECTORY_SEPARATOR . $nombre_archivo, 'a');
            }
            fwrite($archivo, $script);
            fclose($archivo);
        } catch (Exception $exc) {
            echo "No se pudo salvar el script, verifíque la información.";
        }
    }

    function Types($types, $schema) {
        $cont = 0;
        $array_return = array();
        foreach ($types as $var) {
            $arreglo_auxiliar['leaf'] = true;
            $arreglo_auxiliar['text'] = $var['object_name'];
            $arreglo_auxiliar['id'] = $schema . '^' . $var['object_name'];
            $arreglo_auxiliar['checked'] = false;
            $array_return[$cont] = $arreglo_auxiliar;
            $cont++;
        }
        return $array_return;
    }

}

?>