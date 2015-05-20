<?php

class InstrospectorModel extends ZendExt_Model{
    function  __construct() {
        parent::ZendExt_Model();
    }

    function getMethodData ($pCheckeable, $pPath, $pMethods, &$pResult) {
            if (sizeof($pMethods)) {
                try {
                    $method = $pMethods [sizeof($pMethods) - 1];
                    unset ($pMethods [sizeof($pMethods) - 1]);

                    if ($pCheckeable)
                    $item->checked = false;
                    $item->id = $method->getName ();
                    $item->text = $method->getName ();
                    $item->path = $pPath;
                    $item->leaf = true;
                    $item->type = 'method';
                                   // $image = explode('aplicaciones', $_SERVER['HTTP_REFERER']) ;
                    //$item->icon = $image[0].'comun/frameworks/ExtJS/idioma/es/temas/default/images/iconos/metodos.png';

                    $pResult[] = $item; $item = null;
                    InstrospectorModel :: getMethodData($pCheckeable, $pPath, $pMethods, $pResult);
                }

                catch (Exception $e) {
                    $item->path = $pPath;

                    $pResult[] = $item; $item = null;
                    InstrospectorModel :: getMethodData($pCheckeable, $pPath, $pMethods, $pResult);
                }
            }
        }

    function getClassesByFile ($pFile) {
        require_once ($pFile);
        @$file = new Zend_Reflection_File($pFile);

        $result = array ();

        foreach ($file->getClasses() as $class) {
            $item->text = $class->getName ();
            $item->path = $pFile;
            $item->type = 'class';
           // $image = explode('aplicaciones', $_SERVER['HTTP_REFERER']) ;
            //$item->icon = $image[0].'comun/frameworks/ExtJS/idioma/es/temas/default/images/iconos/class.png';
            $item->id = $class->getName ();
            $result[] = $item;

            $item = null;
        }

            return $result;
		
        }

    function getMethodsByClass ($pCheck, $pClass, $pPath) {
        require_once ($pPath);
        @$class = new Zend_Reflection_Class ($pClass);

        $result = array ();
        $methods = $class->getMethods ();
        $own_methods = array ();

        foreach ($methods as $method)
            if ($method->getDeclaringClass()->getName() == $class->getName())
                $own_methods [] = $method;

        InstrospectorModel::getMethodData($pCheck,
                                $pPath,
                                $own_methods,
                                $result);

        return $result;
    }

    function getFilesByPath ($pPath, $pOnlyFolders=false) {
        $nodes = InstrospectorModel::directory_map ($pPath, true);
        $result = array (); $element = null;
        
        foreach ($nodes as $node) {
           if (! $pOnlyFolders) {
               if (strpos($node, '.php')) {
                   $node = str_replace('.php', '',$node);
                    $element->text = $node;
                    $element->path = $pPath.DIRECTORY_SEPARATOR.$node;
                    $element->type = 'file';
					 $element->id = $node;
                    $element->leaf = false;
                  //  $image = explode('aplicaciones', $_SERVER['HTTP_REFERER']) ;
                   // $element->icon = $image[0].'comun/frameworks/ExtJS/idioma/es/temas/default/images/iconos/file.png';
               } 
           } else {
               if (is_dir($pPath.'/'.$node)) {
                   $element->text = $node;
				   $element->id = $node;
                   $element->type = 'folder';
                   $element->path = $pPath.'/'.$node;
               }
           }

           if ($element != null)
               $result[] = $element;
           $element = null;
        }

        return $result;
    }

    function getClassData ($pClass) {
        try{
            @$class = new Zend_Reflection_Class ($pClass);

            $tags = array ('author', 'version', 'copyright');
            $result = null;

            $result->clase = $pClass;
            $result->description = $class->getDocblock()->getShortDescription();

            foreach ($tags as $tag)
                if ($class->getDocblock()->hasTag($tag))
                    $result->$tag = substr($class->getDocblock()->getTag($tag)->getDescription (), 0,
                    strlen($class->getDocblock()->getTag($tag)->getDescription ()) - 1);


           return $result;
       }

       catch (Zend_Exception $e) {
            $result->description = 'No especificado';
            $result->author = 'No especificado';
            $result->version = 'No especificado';
            $result->copyright = 'No especificado';

            return $result;
       }
    }

    function getParamsByClassAndMethod ($pClass, $pMethod)  {
         
        try {
          $result = array ();
         @$class = new Zend_Reflection_Class ($pClass);
           $method = $class->getMethod ($pMethod);
            $params = $method->getParameters();
             foreach ($params as $param) {
                $item->param = $param->getName();
                $item->type = "<undefined>";
                try{
                    if($param->getType())
                        $item->type = $param->getType();
                }
                catch(Exception $ee){

                }
                 $result[] = $item;
                $item = null;
            }
          return $result;
          
        }

        catch (Exception $e) {
            return array ();
        }
    }

    function getReturnByClassAndMethod ($pClass, $pMethod) {
        try {
            @$reflection_class = new Zend_Reflection_Class($pClass);

            return $reflection_class->getMethod ($pMethod)->getReturn()->getType ();
        }

        catch (Exception $e) {
           return 'void';
        }
    }

    function getDocblockByClassAndMethod ($pClass, $pMethod) {
        @$reflection_class = new Zend_Reflection_Class ($pClass);
        return $reflection_class->getMethod ($pMethod)->getDocblock();
    }

    function getClassnameByPath ($pPath) {
        $class_name = substr($pPath,strrpos($pPath, '/') + 1);
        return substr($class_name, 0, strpos($class_name, '.'));
    }

    function directory_map($source_dir, $top_level_only = FALSE){
        if ($fp = @opendir($source_dir))
        {
            $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            $filedata = array();

            while (FALSE !== ($file = readdir($fp)))
            {
                if (strncmp($file, '.', 1) == 0)
                {
                    continue;
                }

                if ($top_level_only == FALSE && @is_dir($source_dir.$file))
                {
                    $temp_array = array();

                    $temp_array = directory_map($source_dir.$file.DIRECTORY_SEPARATOR);

                    $filedata[$file] = $temp_array;
                }
                else
                {
                    $filedata[] = $file;
                }
            }

            closedir($fp);
            return $filedata;
        }
    }
}
?>
