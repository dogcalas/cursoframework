<?php

/**
 * @access public
 */
class DoctrineV1 extends DoctrineVersion
{
    public function gen_base($pTable)
    {
        $class = new Zend_CodeGenerator_Php_Class();
        $class->setName('Base' . $pTable->get_classname());

        $class->setAbstract(true);
        //Si no existe herencia en Doctrine entonces heredo de Record
        //si no entonces heredo del padre
        if (!$pTable->get_parent_table())
            $class->setExtendedClass('Doctrine_Record');
        else
            $class->setExtendedClass($pTable->get_parent_table());

        $method = new Zend_CodeGenerator_Php_Method();
        $method->setVisibility('public');
        $method->setName('setTableDefinition');

        $body = '$this->setTableName(\'' . $pTable->get_name() . '\');';
        $body .= "\n";

        $fields = $pTable->get_fields();

        foreach ($fields as $field) {
            $null = ($field->get_null()) ? 'true' : 'false';
            $pk = ($field->get_primary_key()) ? 'true' : 'false';

            $body .= '$this->hasColumn(';
            $body .= "'{$field->get_name()}', ";
            $body .= "'{$field->get_type()}', null, array (";
            $body .= "'notnull' => $null";
            $body .= ",'primary' => $pk";

            if ($field->get_sequence()) {
                if (strpos($field->get_sequence(), 'nextval'))
                    $body .= ", 'sequence' => '{$field->get_sequence()}'";
                else
                    $body .= ", 'default' => '{$field->get_default()}'";
            }

            $body .= '));';
            $body .= "\n";
        }

        $method->setBody($body);

        $class->setMethod($method);

        $file = new Zend_CodeGenerator_Php_File ();
        $file->setClass($class);

        return $file;
    }

    function gen_model($pTable)
    {
        $class = new Zend_CodeGenerator_Php_Class();
        $class->setName($pTable->get_classname() . 'Model');
        $class->setExtendedClass('ZendExt_Model');

        $file = new Zend_CodeGenerator_Php_File ();
        $file->setClass($class);
        return $file;
    }

    function gen_domain($pTable)
    {
        $class = new Zend_CodeGenerator_Php_Class();
        $class->setName($pTable->get_classname());
        $class->setExtendedClass('Base' . $pTable->get_classname());

        $method = new Zend_CodeGenerator_Php_Method();
        $method->setVisibility('public');
        $method->setName('setUp');

        $body = 'parent :: setUp ();';
        $relations = $pTable->get_relations();

        foreach ($relations as $relation) {
            if ($relation->get_mappeable())

                $body .= "\n{$relation->toString()}";
        }

        $method->setBody($body);

        $class->setMethod($method);
        $_functions = $pTable->get_functions();

        if ($_functions) {

            foreach ($_functions as $_function) {

                $method = new Zend_CodeGenerator_Php_Method();
                $method->setVisibility($_function->get_visibility());
                $method->setName($_function->get_name());

                $param = $parameter = new Zend_CodeGenerator_Php_Parameter();
                $param->setName("arr");
                $method->setParameter($param);

                $_name = "body_" . $_function->get_name();
                $fparam = array();
                $fparam['table'] = $pTable->get_classname();

                $bodym = StoreV1::$_name($fparam);

                $method->setBody($bodym);

                $class->setMethod($method);
            }
        }
        $file = new Zend_CodeGenerator_Php_File ();
        $file->setClass($class);
        return $file;
    }

    public function generate($pPath, $pTables)
    {
        $file = new ZendExt_File ();
        $file->mkdir($pPath);
        $file->mkdir($pPath . '/domain');
        $file->mkdir($pPath . '/bussines');
        $file->mkdir($pPath . '/domain/generated');

        foreach ($pTables as $pTable) {
            $base = $this->gen_base($pTable);
            $model = $this->gen_model($pTable);
            $domain = $this->gen_domain($pTable);

            file_put_contents($pPath . '/domain/generated/Base' . $pTable->get_classname() . '.php', $base->generate());
            file_put_contents($pPath . '/bussines/' . $pTable->get_classname() . 'Model.php', $model->generate());
            file_put_contents($pPath . '/domain/' . $pTable->get_classname() . '.php', $domain->generate());
        }

    }
}

?>