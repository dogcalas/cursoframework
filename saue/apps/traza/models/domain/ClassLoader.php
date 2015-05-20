<?php
    class ClassLoader {
        static function getClassesImplements ($pPath, $pClass) {
            $result = array ();

            $file_manager = new ZendExt_File ($pPath);
            $files = $file_manager->ls ();

            foreach ($files as $file) {
                if (eregi (".php$", $file)) {
                    require_once ($file);
                    $refFile = new Zend_Reflection_File($file);
                    $refClass = $refFile->getClass();

                    if ($refClass->getParentClass()) {
                        if ($refClass->getParentClass()->getName() == $pClass) {
                            $result[] = $refClass;
                        }
                    }   
                }
            }

            return $result;
        }
    }
?>
