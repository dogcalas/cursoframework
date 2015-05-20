<?php

/**
 * @access public
 */
class DoctrineV2 extends DoctrineVersion {

//en talla    
    public function gen_Entity($pTable) {
        $class = new ZendExt_CodeGenerator_Php_Class();
        $class->setName($pTable->get_classname());

        $namespace = $pTable->get_namespace() . "\\models\\Entity;\n\n";
        $class->setNamespace($namespace);
        $docblockclass .= "{$pTable->get_classname()}\n\n";
        $docblockclass .= "@Table(name=\"{$pTable->get_name()}\")\n"
        . "@Entity(repositoryClass=\"{$pTable->get_namespace()}\\models\\Repository\\{$pTable->get_classname()}Repository\") ";
        $class->setDocblock($docblockclass);
        

        $fields = $pTable->get_fields();
        foreach ($fields as $field) {
            $null = ($field->get_null()) ? 'true' : 'false';
            $docblockProperty = "";
            $docblockMethodGet = "";
            $docblockMethodSet = "";
            $bodySet = "";
            $bodyGet = "";

            if ($field->get_type() == "numeric") {
                $field->set_type('decimal');
            }
            if ($field->get_type() == "character varying") {
                $field->set_type('string');
            }
            if ($field->get_type() == "bit") {
                $field->set_type('boolean');
            }
            $length = "";
            if ($field->get_type() == "String") {
                $length.=" length=255,";
            }
            $docblockProperty .= "@var {$field->get_type()}\n\n";
            $docblockProperty .= "@Column(name=\"{$field->get_name()}\", type=\"{$field->get_type()}\",{$length} nullable=\"{$null}\")\n";
            if ($field->get_primary_key()) {
                $docblockProperty .="@Id\n";
            }
            if ($field->get_sequence()) {
                $docblockProperty .= "@GeneratedValue(strategy=\"SEQUENCE\")\n";
                $docblockProperty .= "@SequenceGenerator(sequenceName=\" {$field->get_sequence()}\", allocationSize=\"{$field->get_length()}\", initialValue=\"1\")\n";
            }

            $property = new ZendExt_CodeGenerator_Php_Property();
            $property->setDocblock($docblockProperty);
            $property->setName($field->get_name());
            $property->setVisibility("private");
  
            $class->setProperty($property);


            $docblockMethodSet .= "Set {$field->get_name()}\n\n";
            $docblockMethodSet .= "@param {$field->get_type()} \${$field->get_name()}\n";
            $docblockMethodSet .= "@return {$class->getName()}\n";

            $methodSet = new ZendExt_CodeGenerator_Php_Method();
            $methodSet->setDocblock($docblockMethodSet);
            $methodSet->setVisibility('public');
            $methodSet->setName("set{$field->get_name()}");
            $parameter = new ZendExt_CodeGenerator_Php_Parameter();
            $parameter->setName("{$field->get_name()}");
            $methodSet->setParameter($parameter);

            $bodySet .= "\$this->\${$field->get_name()} = {$parameter->generate()};\n";
            $bodySet .= "return \$this;";

            $methodSet->setBody($bodySet);
            $class->setMethod($methodSet);

            $docblockMethodGet .= "Get {$field->get_name()}\n\n";
            $docblockMethodGet .= "@return {$field->get_type()}\n";

            $bodyGet .= "return \$this->{$field->get_name()};";

            $methodGet = new ZendExt_CodeGenerator_Php_Method();
            $methodGet->setDocblock($docblockMethodGet);
            $methodGet->setVisibility('public');
            $methodGet->setName("get{$field->get_name()}");

            $methodGet->setBody($bodyGet);
            $class->setMethod($methodGet);
        }

        $file = new ZendExt_CodeGenerator_Php_File ();
        $file->setClass($class);

        return $file;
    }

    public function gen_Proxy($pTable) {
        $class = new ZendExt_CodeGenerator_Php_Class();
        $namespace = $pTable->get_namespace() . "\\models\\Proxy;\n\n";
        $class->setNamespace($namespace);
        $class->setName($pTable->get_classname() . "Proxy");

        $class->setExtendedClass("\\".$pTable->get_namespace()."\\models\Entity\\" . $pTable->get_classname());
        $array = array('\Doctrine\ORM\Proxy\Proxy');
        $class->setImplementedInterfaces($array);

        $property1 = new ZendExt_CodeGenerator_Php_Property();
        $property1->setName("_entityPersister");
        $property1->setVisibility("private");

        $class->setProperty($property1);

        $property = new ZendExt_CodeGenerator_Php_Property();
        $property->setName("_identifier");
        $property->setVisibility("private");

        $class->setProperty($property);

        $methodConstruc = new ZendExt_CodeGenerator_Php_Method();
        $methodConstruc->setVisibility('public');
        $methodConstruc->setName('__construct');
        $parameterConstruc = new ZendExt_CodeGenerator_Php_Parameter();
        $parameterConstruc->setName("entityPersister");
        $methodConstruc->setParameter($parameterConstruc);

        $parameterConstruc1 = new ZendExt_CodeGenerator_Php_Parameter();
        $parameterConstruc1->setName("identifier");
        $methodConstruc->setParameter($parameterConstruc1);

        $bodyConstruc.="\$this->_entityPersister = \$entityPersister;\n\$this->_identifier = \$identifier;\n";

        $methodConstruc->setBody($bodyConstruc);
        $class->setMethod($methodConstruc);

        $methodLoad = new ZendExt_CodeGenerator_Php_Method();
        $methodLoad->setVisibility('public');
        $methodLoad->setName('__load');

        $bodyLoad .= "if (!\$this->__isInitialized__ && \$this->_entityPersister){\n \$this->__isInitialized__ = true; \n if (\$this->_entityPersister->load(\$this->_identifier, \$this) === null) { \n throw new \Doctrine\ORM\EntityNotFoundException();\n }\n unset(\$this->_entityPersister, \$this->_identifier);\n}";
        $bodyLoad .= "\n";

        $methodLoad->setBody($bodyLoad);
        $class->setMethod($methodLoad);

        $fields = $pTable->get_fields();
        foreach ($fields as $field) {

            $bodySet = "";
            $bodyGet = "";

            $methodSet = new ZendExt_CodeGenerator_Php_Method();
            $methodSet->setVisibility('public');
            $methodSet->setName("set{$field->get_name()}");
            $parameter = new ZendExt_CodeGenerator_Php_Parameter();
            $parameter->setName("{$field->get_name()}");
            $methodSet->setParameter($parameter);

            $bodySet .= "\$this->__load();\n";
            $bodySet .= "return parent::{$methodSet->getName()}({$parameter->generate()});";

            $methodSet->setBody($bodySet);
            $class->setMethod($methodSet);

            $methodGet = new ZendExt_CodeGenerator_Php_Method();
            $methodGet->setVisibility('public');
            $methodGet->setName("get{$field->get_name()}");

            $bodyGet .= "\$this->__load();\n";
            $bodyGet .= "return parent::{$methodSet->getName()}();";

            $methodGet->setBody($bodyGet);
            $class->setMethod($methodGet);
            $sleep.=",' {$field->get_name()}' ";
        }

        $methodSleep = new ZendExt_CodeGenerator_Php_Method();
        $methodSleep->setVisibility('public');
        $methodSleep->setName("__sleep");

        $bodySleep = "return array('__isInitialized__' {$sleep});";

        $methodSleep->setBody($bodySleep);

        $class->setMethod($methodSleep);

        $methodclone = new ZendExt_CodeGenerator_Php_Method();
        $methodclone->setVisibility('public');
        $methodclone->setName("__clone");

        $bodyclone .="if (!\$this->__isInitialized__ && \$this->_entityPersister) {\n";
        $bodyclone .="\$this->__isInitialized__ = true;\n";
        $bodyclone .="\$class = \$this->_entityPersister->getClassMetadata();\n";
        $bodyclone .="\$original = \$this->_entityPersister->load(\$this->_identifier);\n";
        $bodyclone .="if (\$original === null) {\n";
        $bodyclone .="throw new \Doctrine\ORM\EntityNotFoundException();\n   }\n";
        $bodyclone .="foreach (\$class->reflFields AS \$field => \$reflProperty) {\n";
        $bodyclone .="\$reflProperty->setValue(\$this, \$reflProperty->getValue(\$original)); \n }\n";
        $bodyclone .="unset(\$this->_entityPersister, \$this->_identifier);\n}\n";

        $methodclone->setBody($bodyclone);
        $class->setMethod($methodclone);

        $file = new ZendExt_CodeGenerator_Php_File ();
        $file->setClass($class);

        return $file;
    }

//en talla
    function gen_Model($pTable) {
        $class = new ZendExt_CodeGenerator_Php_Class();
        $class->setName($pTable->get_classname() . 'Model');
        $class->setExtendedClass('ZendExt_Model');
        $method = new ZendExt_CodeGenerator_Php_Method();
        $method->setVisibility('public');
        $method->setName($pTable->get_classname());

        $bodym = "parent::ZendExt_Model();";

        $method->setBody($bodym);

        $class->setMethod($method);
        $file = new ZendExt_CodeGenerator_Php_File ();
        $file->setClass($class);
        return $file;
    }

    function gen_Repository($pTable) {
        $class = new ZendExt_CodeGenerator_Php_Class();

        $namespace = $pTable->get_namespace() . "\\models\\Repository;\n";
        $class->setNamespace($namespace);
        $use = "Doctrine\ORM\EntityRepository; \n";
        $class->setUse($use);
        $dockbloc = "
 {$pTable->get_classname()}Repository
 
 Esta clase ha sido generada por el Generador de Doctrine de Sauxe.
 QGenerator
 @author Inoelkis Velazquez Osorio";

        $class->setDocblock($dockbloc);
        $class->setName($pTable->get_classname() . "Repository");
        $class->setExtendedClass("EntityRepository");

        $_functions = $pTable->get_functions();

        if ($_functions) {
            foreach ($_functions as $_function) {

                $method = new ZendExt_CodeGenerator_Php_Method();
                $method->setVisibility($_function->get_visibility());
                $method->setName($_function->get_name());

                $param = $parameter = new ZendExt_CodeGenerator_Php_Parameter();
                $param->setName("arr");
                $method->setParameter($param);

                $param1 = $parameter = new ZendExt_CodeGenerator_Php_Parameter();
                $param1->setName("_em");
                $method->setParameter($param1);

                $_name = "body_" . $_function->get_name();
                $fparam = array();
                $fparam['table'] = $pTable->get_namespace() . "\\models\\Entity\\" . $pTable->get_classname();

                $bodym = StoreV2::$_name($fparam);

                $method->setBody($bodym);

                $class->setMethod($method);
            }
        }

        $file = new ZendExt_CodeGenerator_Php_File ();
        $file->setClass($class);

        return $file;
    }

    public function generate($pPath, $pTables) {
        $file = new ZendExt_File ();
        $file->mkdir($pPath);
        $file->mkdir($pPath . '/bussines');
        $file->mkdir($pPath . '/Proxy');
        $file->mkdir($pPath . '/Repository');
        $file->mkdir($pPath . '/Entity');

        foreach ($pTables as $pTable) {
            $base = $this->gen_Entity($pTable);
            $proxi = $this->gen_Proxy($pTable);
            $model = $this->gen_Model($pTable);
            $repository = $this->gen_Repository($pTable);

            file_put_contents($pPath . '/bussines/' . $pTable->get_classname() . 'Model.php', $model->generate());
            file_put_contents($pPath . '/Entity/' . $pTable->get_classname() . '.php', $base->generate());

            file_put_contents($pPath . '/Proxy/' . $pTable->get_classname() . 'Proxy.php', $proxi->generate());

            file_put_contents($pPath . '/Repository/' . $pTable->get_classname() . 'Repository.php', $repository->generate());
        }
    }

}

?>
