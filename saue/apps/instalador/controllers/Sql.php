<?php

include ('Script.php');

class Sql {

    private $_scripts, $_scripts_instalables;

    public function __construct() {
        $this->_scripts = array();
        $this->_scripts_instalables = array();
    }

    public function getScripts() {
        return $this->_scripts;
    }

    public function getScriptsInstalables() {

        return $this->_scripts_instalables;
    }

    public function encontrar_scripts($direccion, $sistemas) {
        $this->_scripts = $this->cargar_script($direccion);
        $arrayestructura = array();
        $arrayroles = array();
        $arrayestructuradep = array();
        $arrayscript = array();
        $arraydatos = array();
        $arraydatosdep = array();
        $arraypermisos = array();
        $arraypermisosdep = array();
        foreach($sistemas as $pos=>$value)
        {
            //$existe = $this->existesistemaenarray($sistemas, $pos);
           // if($existe){
                foreach($this->_scripts[$value] as $objet){
                    $nombre = $objet->getNombre();
                    $partes = explode("-", $nombre);
                    if($partes[0]==1){
                            $arrayroles[]=$objet;
                    }
                    if($partes[0]==2){
                        $cad = strpos($nombre, "(");
                        if(!$cad){
                            if($nombre != "2-ddmm.sql"){
                                $esta = $this->existescriptarray($arrayestructura,$nombre);
                                if(!$esta)
                                    $arrayestructura []= $objet;
                           }
                        }
                        else
                        {
                            $esta =$this->existescriptarray($arrayestructuradep,$nombre);
                                if(!$esta)
                                    $arrayestructuradep[]=$objet;
                        }
                    }
                    if($partes[0]==3){
                        $cad = strpos($nombre, "(");
                        if(!$cad){
                            $esta =$this->existescriptarray($arraydatos,$nombre);
                            if(!$esta)
                                $arraydatos []= $objet;
                        }
                        else{
                            $esta =$this->existescriptarray($arraydatosdep,$nombre);
                            if(!$esta)
                                $arraydatosdep[]= $objet;
                        }
                    }
                    elseif($partes[0]==4){
                        $cad = strpos($nombre, "(");
                        if(!$cad){
                            $esta =$this->existescriptarray($arraypermisos,$nombre);
                            if(!$esta)
                            $arraypermisos []= $objet;
                        }
                        else{
                            $esta =$this->existescriptarray($arraypermisosdep,$nombre);
                            if(!$esta)
                                $arraypermisosdep[]= $objet;
                        }
                    }
                }
           // }
        }
        //echo'<pre>';print_r($arrayroles);die;
        //$arrayroles = array_unique($arrayroles);
        $arraestructura = array_merge($arrayestructura,$arrayestructuradep);
        $arraydatos = array_merge($arraydatos,$arraydatosdep);
        $arraypermisos = array_merge($arraypermisos, $arraypermisosdep);

        $arrayscript= array_merge($arrayscript,$arrayroles);
        $arrayscript= array_merge($arrayscript,$arraestructura);
        $arrayscript= array_merge($arrayscript,$arraydatos);
        $arrayscript= array_merge($arrayscript,$arraypermisos);

        $this->_scripts_instalables =$arrayscript;
        return $arrayscript; //
    }

    public function existesistemaenarray($sistemas, $pos)
    {
        foreach($sistemas as $val){
            if($val == $pos)
                return true;
        }
        return false;
    }

    public function existescriptarray($arrayscript, $script)
    {
        foreach($arrayscript as $val){
            if(trim($val->getNombre()) == trim($script)){
                return true;
            }
        }
        return false;
    }

    public function cargar_script($source_dir){
        return  $this->directory_map($source_dir);
    }
    public function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
    {
        if ($fp = @opendir($source_dir))
        {
            $filedata	= array();
            $new_depth	= $directory_depth - 1;
            $source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

            while (FALSE !== ($file = readdir($fp)))
            {
                // Remove '.', '..', and hidden files [optional]
                if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.'))
                {
                    continue;
                }

                if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
                {
                    $filedata[$file] = $this->directory_map($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
                }
                else
                {
                    $f = new Script($file, $source_dir);
                    $filedata[] = $f;
                }
            }

            closedir($fp);
            return $filedata;
        }

        return FALSE;
    }

    public function buscar_scripts($dir) {
        $_temp = array();
        if (is_dir($dir)) {
            if ($gd = opendir($dir)) {
                while ($file = readdir($gd)) {
                    if ($file != '.' && $file != '..') {
                        if (is_dir($dir . '/' . $file) && file_exists($dir . '/' . $file)) 
			            {
                            $temp_ []= $this->buscar_scripts($dir . '/' . $file);
                            foreach ($temp_ as $t){
                                $_temp[] = $t;
                            }
                        } 
                        else
                        {
                            $_propiedades = pathinfo($dir . '/' . $file);
                            $_nombre = $_propiedades['basename'];

                      if(isset($_propiedades['extension']))
                                if (is_file($dir . '/' . $file) && !($_nombre[0] == '.') && $_propiedades['extension'] == 'sql')
                                {
                                    $_temp[] = new Script($file, $dir);
                                }
                        }
                    }

                }
                closedir($gd);
            }
        }
        return $_temp;
    }

    private function scripts_sin_dependencias($esquemas, $tipo_script) {

        $lista = array();
        foreach ($esquemas as $esquema){
            foreach ($this->_scripts as $script) {
                foreach($script as $datos){
                    $nombrescript = $datos->getNombre();
                    $element_nombre = $this->limpiar_nombre_script($nombrescript);
                    $bug = explode("-", $element_nombre);
                    $_long = count($bug);

                    if ($bug[0] == $tipo_script && $_long < 3 && $bug[1] == trim($esquema)) {
                        $lista[] = $script;
                        break;
                    }

                }
            }
        }
        return $lista;
    }

    private function scripts_con_dependencias($esquemas, $tipo_script) {
        $lista = array();
        foreach ($this->_scripts as $elemento) {
            foreach($elemento as $datos){
                $_nombre = $this->limpiar_nombre_script($datos->getNombre());
                $bug = explode("-", $_nombre);
                $element_long = count($bug);

                if ($bug[0] == $tipo_script && $element_long > 2 && $this->dependencias_cumplidas($esquemas, $bug))
                    $lista[] = $elemento;
            }
        }
        return $lista;
    }

    private function limpiar_nombre_script($nombre) {
        $nombre_limpio = "";
        $reemplazar = array(".sql", "(", ")");

        $nombre_limpio = str_replace($reemplazar, "", $nombre);
        return $nombre_limpio;
    }

    private function dependencias_cumplidas($esquemas, $dependencias) {
        $bandera = false;
        for ($i = 1; $i < count($dependencias); $i++) {
            $bandera = false;
            $cant = 0;
            foreach ($esquemas as $esquema) {
                $cant++;
                if (trim($esquema) == $dependencias[$i]) {
                    $bandera = true;
                    break;
                } elseif (count($esquemas) <= $cant && !$bandera)
                    return false;
            }
        }
        return $bandera;
    }

    public function scripts_instalables($esquemas) {

        $this->_scripts_instalables = array_merge($this->_scripts_instalables, $this->scripts_sin_dependencias($esquemas, "2"));
        $this->_scripts_instalables = array_merge($this->_scripts_instalables, $this->scripts_con_dependencias($esquemas, "2"));
        $this->_scripts_instalables = array_merge($this->_scripts_instalables, $this->scripts_sin_dependencias($esquemas, "1"));
        $this->_scripts_instalables = array_merge($this->_scripts_instalables, $this->scripts_con_dependencias($esquemas, "1"));
        $this->_scripts_instalables = array_merge($this->_scripts_instalables, $this->scripts_sin_dependencias($esquemas, "3"));
        $this->_scripts_instalables = array_merge($this->_scripts_instalables, $this->scripts_con_dependencias($esquemas, "3"));
        $this->_scripts_instalables = array_merge($this->_scripts_instalables, $this->scripts_sin_dependencias($esquemas, "4"));
        $this->_scripts_instalables = array_merge($this->_scripts_instalables, $this->scripts_con_dependencias($esquemas, "4"));
        return $this->_scripts_instalables;
    }

}
?>
