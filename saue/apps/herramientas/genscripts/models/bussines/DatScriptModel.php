<?php

class DatScriptModel extends ZendExt_Model {

    public function DatScriptModel() {
        parent::ZendExt_Model();
    }

    private function ObtenerDSN() {
        $modulesconfig = ZendExt_FastResponse::getXML('modulesconfig');
        $bdconfig = $modulesconfig->conn;
        $gestor = $bdconfig['gestor'];
        $usuario = $bdconfig['usuario'];
        $RSA = new ZendExt_RSA();
        $password = $RSA->decrypt($bdconfig['password'], '85550694285145230823', '99809143352650341179');
        $host = $bdconfig['host'];
        $port = $bdconfig['port'];
        $basedatos = $bdconfig['bd'];
        $connStr = "$gestor://$usuario:$password@$host:$port/$basedatos";

        return $connStr;
    }

    public function insertar($script) {
        $connStr = $this->ObtenerDSN();
        $pp = Doctrine_Manager::getInstance()->openConnection($connStr);        
        $dat = DatScript::ExistScript($script->nombre_script, $pp);
        
        if ($dat == NULL) {
            $script->save($pp);
            return true;
        } else {
            return false;
        }
    }

    public function modificar($script) {
        $script->save();
    }

    public function eliminar($id) {
        $script = Doctrine::getTable('DatScript')->find($id);
        $script->delete();
    }

    public function cargarScript() {
        $connStr = $this->ObtenerDSN();
        $pp = Doctrine_Manager::getInstance()->openConnection($connStr);
        $datos = DatScript::AllScript($pp);

        return $datos;
    }

    public function obtenerDefinicionScript($idscript) {
        $connStr = $this->ObtenerDSN();
        $pp = Doctrine_Manager::getInstance()->openConnection($connStr);
        $script = Doctrine::getTable('DatScript')->find($idscript);

        return $script->definicionsql;
    }

    function Header($version_script, $tipo) {
        $script = "----------------------V$version_script----------------------\n";
        $script = $script . "/*\n";
        $script = $script . "-------  CENTRO DE SOLUCIONES DE GESTIÓN  -------\n";
        $script = $script . "-------     Subdirección de tecnología    -------\n";
        $script = $script . "-------                                   -------\n";
        $script = $script . "-------SCRIPT de INSTALACIÓN de $tipo-------\n";
        $script = $script . "-------                                   -------\n";
        $script = $script . "*/\n";
        $script = $script . "-------------------------------------------------\n";
        $script = $script . "---------------------------------------------------------------------------------------------\n";
        $script = $script . "/*\n";
        $script = $script . "--Reglas de confidencialidad                                                               --\n";
        $script = $script . "--Clasificación: Clasificado.                                                              --\n";
        $script = $script . "--Forma de distribución: Script SQL.                                                       --\n";
        $script = $script . "--Este documento contiene información propietaria del CENTRO DE SOLUCIONES DE GESTIÓN      --\n";
        $script = $script . "--y es emitido confidencialmente para un propósito específico.                             --\n";
        $script = $script . "--El que recibe el documento asume la custodia y control, comprometiéndose a no reproducir,--\n";
        $script = $script . "--divulgar, difundir o de cualquier manera hacer de conocimiento público su contenido,     --\n";
        $script = $script . "--excepto para cumplir el propósito para el cual se ha generado.                           --\n";
        $script = $script . "--Las reglas son aplicables a todo este documento.                                         --\n";
        $script = $script . "*/\n";
        $script = $script . "---------------------------------------------------------------------------------------------\n\n";
        $script = $script . "--comienza ta transacción--\n";
        $script = $script . "BEGIN;\n";
        $script = $script . "    --Propiedades de la BD\n";
        $script = $script . "    SET client_encoding = \"UTF8\";\n";
        $script = $script . "    SET standard_conforming_strings = off;\n";
        $script = $script . "    SET check_function_bodies = false;\n";
        $script = $script . "    SET client_min_messages = warning;\n";
        $script = $script . "    SET escape_string_warning = off;\n\n\n";

        return $script;
    }

    function Defautl($esquemas) {
        $script = "";
        foreach ($esquemas as $esquema) {
            $script = $script . "    CREATE SCHEMA $esquema;\n";
            if ($esquema == "mod_datosmaestros") {
                $script = $script . "\n";
                $script = $script . "SET search_path = mod_datosmaestros, pg_catalog;";
                $script = $script . "    --Tabla nom servidor\n";
                $script = $script . "    CREATE TABLE nom_servidor\n";
                $script = $script . "    (\n";
                $script = $script . "        idservidor numeric(19,0)\n";
                $script = $script . "    ) WITHOUT OIDS;\n\n";
                $script = $script . "    --Tabla nom secuencias\n";
                $script = $script . "    CREATE TABLE nom_secuenciasgeneral\n";
                $script = $script . "    (\n";
                $script = $script . "        nombresecuencia character varying,\n";
                $script = $script . "        valor_inicial numeric(19,0),\n";
                $script = $script . "        valor_incrementar numeric(10,0),\n";
                $script = $script . "        valor_final numeric(19,0),\n";
                $script = $script . "        nombreesquema character varying(50)\n";
                $script = $script . "    ) WITHOUT OIDS;\n\n";
                $script = $script . "    --Llave primaria de nom_secuencia\n";
                $script = $script . "    ALTER TABLE ONLY nom_secuenciasgeneral\n";
                $script = $script . "    ADD CONSTRAINT nom_secuencias_nombresecuenciageneral_key PRIMARY KEY (nombresecuencia, nombreesquema);\n\n";
                $script = $script . "    --Llave primaria de nom_servidor\n";
                $script = $script . "    ALTER TABLE ONLY nom_servidor\n";
                $script = $script . "    ADD CONSTRAINT nom_servidor_idservidor_key PRIMARY KEY (idservidor);\n\n";
                $script = $script . "    --Datos del servidor\n";
                $script = $script . "    --Aqui se le pone el id del servidor es decir en numero de despliegue\n";
                $script = $script . "    INSERT INTO mod_datosmaestros.nom_servidor(idservidor) VALUES (?);\n\n";
                $script = $script . "    --Función para la creación de las secuencias generales\n";
                $script = $script . "    CREATE OR REPLACE FUNCTION ft_creacionsecuenciasact () RETURNS trigger AS\n";
                $script = $script . "        \$body$\n";
                $script = $script . "             DECLARE\n\n";
                $script = $script . "             v_servidor numeric;\n\n";
                $script = $script . "             BEGIN\n\n";
                $script = $script . "			select ser.idservidor into v_servidor\n";
                $script = $script . "                     from mod_datosmaestros.nom_servidor ser;\n\n";
                $script = $script . "                     EXECUTE \"CREATE SEQUENCE \" || NEW.nombreesquema || \".\" || NEW.nombresecuencia ||\n";
                $script = $script . "					 \" INCREMENT \" || NEW.valor_incrementar || \" MINVALUE \" || \n";
                $script = $script . "                                     rpad(v_servidor::VARCHAR,(length(v_servidor::VARCHAR) + NEW.valor_inicial)::INTEGER, \"0\") \n";
                $script = $script . "					  || \" MAXVALUE \" || rpad(v_servidor::VARCHAR,(length(v_servidor::VARCHAR) + NEW.valor_final)::INTEGER, \"9\") \n";
                $script = $script . "					  || \" START \" || rpad(v_servidor::VARCHAR,(length(v_servidor::VARCHAR) + NEW.valor_inicial)::INTEGER, \"0\") || \";\";\n\n";
                $script = $script . "			RETURN new;\n\n";
                $script = $script . "             END;\n";
                $script = $script . "        \$body$\n";
                $script = $script . "    LANGUAGE \"plpgsql\";\n\n";
                $script = $script . "    --Trigger para la creación de las secuencias generales\n";
                $script = $script . "    CREATE TRIGGER t_creacionsecuenciasact AFTER INSERT\n";
                $script = $script . "    ON nom_secuenciasgeneral FOR EACH ROW\n";
                $script = $script . "    EXECUTE PROCEDURE ft_creacionsecuenciasact();\n\n";
                $script = $script . "    --Insercción de datos para la generación de secuencias generales\n";
                $script = $script . "    SET search_path = mod_datosmaestros, pg_catalog;\n\n";
                $script = $script . "    INSERT INTO nom_secuenciasgeneral VALUES (\"sec_version_seq\", 14, 1, 14, \"mod_datosmaestros\");\n";
                $script = $script . "    INSERT INTO nom_secuenciasgeneral VALUES (\"sec_idconfiguracion_seq\", 14, 1, 14, \"mod_datosmaestros\");\n";
            }
        }

        $script = $script . "\n";

        return $script;
    }

    function SqlSequenceCreate($checked, $conn) {
        $exist = $conn->ExistNomSecGeneral();
        if ($exist[0]['tablename'] != null) {
            foreach ($checked as $var) {
                if (stripos($var[0], '^sequences') == strlen($var[0]) - 10) {
                    $schema = preg_split("/\^/", $var[0]);
                    $script = $script . "\nSET search_path = mod_datosmaestros, pg_catalog;\n\n";
                    foreach ($var[1] as $obj) {
                        $info = $conn->SelectSequenceInfo($obj);
                        if ($exist[0]['tablename'] != null && $schema[0] != "mod_datosmaestros") {
                            $server = $conn->SelectServerId();
                            $server = $server[0]['idservidor'];
                            $length_server = $this->lengthInt($server);
                            $length_start = $this->lengthInt($info[0]['start_value']);
                            $length_start = $length_start - $length_server;
                            $length_max = $this->lengthInt($info[0]['maximum_value']);
                            $length_max = $length_max - $length_server;
                            $script = $script . "INSERT INTO nom_secuenciasgeneral VALUES ('" . $info[0]['sequence_name'] . "', ";
                            $script = $script . $length_start;
                            $script = $script . ", ";
                            $script = $script . $info[0]['increment'];
                            $script = $script . ", ";
                            $script = $script . $length_max;
                            $script = $script . ", '" . $info[0]['sequence_schema'] . "');\n";
                        }
                    }
                }
            }
        } else {
            foreach ($checked as $var) {
                if (stripos($var[0], '^sequences') == strlen($var[0]) - 10) {
                    $schema = preg_split("/\^/", $var[0]);
                    if ($schema[0] != "mod_datosmaestros") {
                        $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                        foreach ($var[1] as $obj) {
                            $info = $conn->SelectSequenceInfo($obj);
                            $script = $script . "CREATE SEQUENCE " . $info[0]['sequence_schema'] . "." . $info[0]['sequence_name'] . "\n";
                            $script = $script . "    INCREMENT " . $info[0]['increment'] . "\n";
                            $script = $script . "    MINVALUE " . $info[0]['minimum_value'] . "\n";
                            $script = $script . "    MAXVALUE " . $info[0]['maximum_value'] . "\n";
                            $script = $script . "    START " . $info[0]['start_value'] . "\n";
                            $script = $script . "    CACHE 1;\n\n";
                        }
                    }
                }
            }
        }
        return $script;
    }

    function SqlTableCreate($checked, $conn) {
        foreach ($checked as $var) {
            if (stripos($var[0], '^tables') == strlen($var[0]) - 7) {
                $schema = preg_split("/\^/", $var[0]);
                $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                foreach ($var[1] as $obj) {
                    $table = preg_split("/\^/", $obj);
                    if ($table[0] != "mod_datosmaestros" && $table[1] != "nom_servidor" && $table[1] != "nom_secuenciasgeneral") {
                        $script = $script . "CREATE TABLE " . $table[1] . "\n";
                        $info = $conn->SelectTableInfo($obj);
                        $script = $script . "(\n";
                        foreach ($info as $value) {
                            $script = $script . "    " . $value['column_name'] . " " . $value['data_type'];
                            if ($value['numeric_precision'])
                                $script = $script . "(" . $value['numeric_precision'] . ",0)";
                            if ($value['column_default'])
                                $script = $script . " DEFAULT " . $value['column_default'];
                            if ($value['is_nullable'] == "NO"){
                                if ($value != $info[count($info)-1])
                                    $script = $script . " NOT NULL,\n";
                                else
                                    $script = $script . " NOT NULL\n";
                            }
                            if ($value['is_nullable'] == "YES"){
                                if ($value != $info[count($info)-1])
                                    $script = $script . ",\n";
                                else
                                    $script = $script . "\n";
                            }
                        }
                        $script = $script . "\n);\n\n";
                    }
                }
            }
        }
        return $script;
    }

    function lengthInt($val) {
        $cont = 1;
        $length = 1;
        while ($val / (10 * $cont) >= 1) {
            $length++;
            $cont*=10;
        }
        return $length;
    }

    function SqlPkCreate($checked, $conn) {
        $script = "";
        foreach ($checked as $var) {
            if (stripos($var[0], '^tables') == strlen($var[0]) - 7) {
                $schema = preg_split("/\^/", $var[0]);
                $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                foreach ($var[1] as $obj) {
                    $table = preg_split("/\^/", $obj);
                    if ($table[0] != "mod_datosmaestros" && $table[1] != "nom_servidor" && $table[1] != "nom_secuenciasgeneral") {
                        $script = $script . "ALTER TABLE ONLY " . $table[1] . "\n";
                        $info = $conn->SelectPkInfo($obj);
                        if (count($info) > 1) {
                            $script = $script . "    ADD CONSTRAINT " . $info[0]['constraint_name'] . " PRIMARY KEY (";
                            for ($i = 0; $i < count($info); $i++) {
                                if ($i == count($info) - 1)
                                    $script = $script . $info[$i]['column_name'];
                                else
                                    $script = $script . $info[$i]['column_name'] . ", ";
                            }
                            $script = $script . ");\n\n";
                        }
                        else {
                            for ($i = 0; $i < count($info); $i++) {
                                $script = $script . "    ADD CONSTRAINT " . $info[$i]['constraint_name'] . " PRIMARY KEY (" . $info[$i]['column_name'] . ");\n\n";
                            }
                        }
                    }
                }
            }
        }
        return $script;
    }

    function SqlRestricCreate($checked, $conn) {
        $script = "";
        foreach ($checked as $var) {
            if (stripos($var[0], '^tables') == strlen($var[0]) - 7) {
                $schema = preg_split("/\^/", $var[0]);
                $info = $conn->RestricForSchema($schema[0]);
                if (count($info) > 0)
                    $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                foreach ($var[1] as $obj) {
                    $table = preg_split("/\^/", $obj);
                    if ($table[0] != "mod_datosmaestros" && $table[1] != "nom_servidor" && $table[1] != "nom_secuenciasgeneral") {
                        $info = $conn->RestricForSchemaTable($table[0], $table[1]);
                        if (count($info) > 0) {
                            $script = $script . "ALTER TABLE ONLY " . $table[1] . "\n";
                            if (count($info) > 1) {
                                $script = $script . "    ADD CONSTRAINT " . $info[0]['constraint_name'] . " UNIQUE(";
                                for ($i = 0; $i < count($info); $i++) {
                                    if ($i == count($info) - 1)
                                        $script = $script . $info[$i]['column_name'];
                                    else
                                        $script = $script . $info[$i]['column_name'] . ", ";
                                }
                                $script = $script . ");\n\n";
                            }
                            else {
                                for ($i = 0; $i < count($info); $i++) {
                                    $script = $script . "    ADD CONSTRAINT " . $info[$i]['constraint_name'] . " UNIQUE(" . $info[$i]['column_name'] . ");\n\n";
                                }
                            }
                        }
                    }
                }
            }
        }
        return $script;
    }

    function SqlIndexCreate($checked, $conn) {
        $script = "";
        foreach ($checked as $var) {
            if (stripos($var[0], '^index') == strlen($var[0]) - 6) {
                $schema = preg_split("/\^/", $var[0]);
                $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                foreach ($var[1] as $obj) {
                    $index = preg_split("/\^/", $obj);
                    $info = $conn->IndexDefForSchema($index[0], $index[1]);
                    $script = $script . $info[0]['indexdef'] . ";\n\n";
                }
            }
        }
        return $script;
    }
    
    function SqlTypesCreate($checked, $conn) {
        foreach ($checked as $var) {
            if (stripos($var[0], '^types') == strlen($var[0]) - 6) {
                $schema = preg_split("/\^/", $var[0]);
                $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";

                foreach ($var[1] as $obj) {
                    $typename = preg_split("/\^/", $obj);
                    $info = $conn->SelectAllDataTypes($schema[0], $typename[1]);
                    if ($typename[1] == $info[$cont]['object_name']) {
                        $script = $script . "CREATE TYPE " . $typename[0] . "." . $typename[1] . " AS(\n";

                        for ($i = 0; $i < count($info); $i++) {
                            
                        }

                        while ($typename[1] == $info[$cont]['object_name']) {
                            if ($info[$cont]['numeric_precision']) {
                                $script = $script . "(" . $info[$cont]['numeric_precision'];
                                if ($info[$cont]['numeric_scale'] != null)
                                    $script = $script . "," . $info[$cont]['numeric_scale'] . ")";
                                if ($info[$cont]['numeric_scale'] == null)
                                    $script = $script . ")";
                            }
                        }


                        foreach ($info as $value) {
                            $script = $script . "    " . $value['column_name'] . " " . $value['data_type'];
                            if ($value['numeric_precision'])
                                $script = $script . "(" . $value['numeric_precision'] . ",0)";
                            if ($value['column_default'])
                                $script = $script . " DEFAULT " . $value['column_default'];
                            if ($value['is_nullable'] == "NO")
                                $script = $script . " NOT NULL,\n";
                            if ($value['is_nullable'] == "YES")
                                $script = $script . ",\n";
                        }
                        $script = $script . "\n);\n\n";
                    }
                }
            }
        }
    }
    
    function SqlFkCreate($checked, $conn) {
        $script = "";
        foreach ($checked as $var) {
            if (stripos($var[0], '^tables') == strlen($var[0]) - 7) {
                $schema = preg_split("/\^/", $var[0]);
                $info = $conn->FkForSchema($schema[0]);
                if (count($info) > 0) {
                    $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                    foreach ($info as $este) {
                        $script = $script . "ALTER TABLE ONLY " . $este['table_name'] . "\n";
                        $constraint_name = $este['constraint_name'];
                        $script = $script . "    ADD CONSTRAINT " . $este['constraint_name'] . " FOREIGN KEY (" . $este['column_name'] . ")\n";

                        $references = $conn->SelectReferences($constraint_name);
                        $script = $script . "    REFERENCES " . $references[0]['constraint_schema'] . "." . $references[0]['table_name'] . "(" . $references[0]['column_name'] . ")";
                        $constraint = $conn->SelectRuleForCOnstraintName($constraint_name);
                        if ($constraint[0]['match_option'])
                            $script = $script . " MATCH " . $constraint[0]['match_option'] . "\n";
                        else
                            $script = $script . "\n";
                        $script = $script . "    ON UPDATE " . $constraint[0]['update_rule'] . " ON DELETE " . $constraint[0]['delete_rule'] . ";\n\n";
                    }
                }
            }
        }
        return $script;
    }

    function SqlFunctionCreate($checked, $conn) {
        $script = "";
        $version = $conn->SelectVersion();
        $version = substr($version[0]['version'], 11, 5);
        $version = (float) $version;
        foreach ($checked as $var) {
            if (stripos($var[0], '^functions') == strlen($var[0]) - 10) {
                $schema = preg_split("/\^/", $var[0]);
                $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                foreach ($var[1] as $obj) {
                    $function = preg_split("/\^/", $obj);
                    if ($version >= 9.0 && $function[0] != "mod_datosmaestros" && $function[1] != "ft_creacionsecuenciasact") {
                        $info = $conn->SelectFunctionDef9($function[1]);
                        $script = $script . $info[0]['pg_get_functiondef'];
                    } else if ($function[0] != "mod_datosmaestros" && $function[1] != "ft_creacionsecuenciasact") {
                        $info = $conn->SelectFunctionDef($function[1]);
                        $script = $script . "CREATE OR REPLACE FUNCTION " . $function[0] . "." . $function[1] . "(";

                        $proargnames = $info[0]['proargnames'];
                        $proargnames = preg_split("/}/", $proargnames);
                        $proargnames = preg_split("/{/", $proargnames[0]);
                        $proargnames = preg_split("/,/", $proargnames[1]);

                        $proargtypes = $info[0]['proargtypes'];
                        $proargtypes = preg_split("/ /", $proargtypes);

                        for ($i = 0; $i < count($proargtypes); $i++) {
                            ;
                            $argument = $conn->SelectTypeForArg($proargtypes[$i]);
                            $argument = json_encode($argument[0]['typname']);
                            $argument = preg_split("/\"/", $argument);
                            $script = $script . "$proargnames[$i] " . $argument[1];
                            if ($i != count($proargtypes) - 1)
                                $script = $script . ", ";
                        }
                        $script = $script . ")\n";
                        $script = $script . "  RETURNS SETOF ";
                        $return = $info[0]['prorettype'];
                        $return1 = $conn->SelectTypeForReturn($return);
                        $script = $script . $return1[0]['typname'] . " AS\n";
                        $script = $script . "\$BODY$\n";
                        $script = $script . $info[0]['prosrc'] . ";\n";
                        $script = $script . "\$BODY$\n";
                        $script = $script . "  LANGUAGE 'plpgsql' ;\n\n";
                    }
                }
            }
        }
        return $script;
    }

    function SqlTriggerCreate($checked, $conn) {
        $script = "";
        foreach ($checked as $var) {
            if (stripos($var[0], '^triggers') == strlen($var[0]) - 9) {
                $schema = preg_split("/\^/", $var[0]);
                $info = $conn->SelectTriggerForSchema($schema[0]);
                if (count($info) > 0)
                    $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                foreach ($var[1] as $obj) {
                    $trigger = preg_split("/\^/", $obj);
                    if ($trigger[0] != "mod_datosmaestros" && $trigger[1] != "t_creacionsecuenciasact") {
                        $info = $conn->SelectTriggerForSchemaTriggernameEvent($trigger[0], $trigger[1], $trigger[2]);
                        foreach ($info as $este) {
                            $trigger_name = $este['trigger_name'];
                            $action_timing = $este['action_timing'];
                            $event_manipulation = $este['event_manipulation'];
                            $event_object_schema = $este['event_object_schema'];
                            $event_object_table = $este['event_object_table'];
                            $action_orientation = $este['action_orientation'];
                            $action_statement = $este['action_statement'];
                            $script = $script . "CREATE TRIGGER " . $trigger_name . "\n" .
                                    "    " . $action_timing . " " . $event_manipulation . " ON " . $event_object_schema . "." . $event_object_table . "\n" .
                                    "    FOR EACH " . $action_orientation . "\n" .
                                    "    " . $action_statement . "\n\n";
                        }
                    }
                }
            }
        }
        return $script;
    }

    function SqlGrantAlterSchema($checked) {
        $script = "";
        $esquemas = array();
        $j = 0;
        foreach ($checked as $var) {
            $schema = preg_split("/\^/", $var[0]);
            if (!in_array($schema[0], $esquemas)) {
                $esquemas[$j] = $schema[0];
                $j++;
            }
        }
        foreach ($esquemas as $esquema) {
            $script = $script . "GRANT USAGE ON SCHEMA $esquema TO sauxe;\n";
        }
        foreach ($esquemas as $esquema) {
            $script = $script . "ALTER SCHEMA $esquema OWNER TO sauxe;\n";
        }

        $script = $script . "\n";
        return $script;
    }

    function SqlAlterFunction($checked, $conn) {
        $script = "";
        foreach ($checked as $var) {
            if (stripos($var[0], '^functions') == strlen($var[0]) - 10) {
                $schema = preg_split("/\^/", $var[0]);
                $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                foreach ($var[1] as $obj) {
                    $function = preg_split("/\^/", $obj);
                    $functions = $conn->SelectArqTypes($function[1]);
                    foreach ($functions as $value) {
                        $script = $script . "ALTER FUNCTION " . $schema[0] . "." . $value['proname'] . "(";
                        if ($value['proargtypes'] != null) {
                            $proargtypes = preg_split("/ /", $value['proargtypes']);
                            for ($i = 0; $i < count($proargtypes); $i++) {
                                $arg = $conn->SelectTypeName($proargtypes[$i]);
                                $argument = json_encode($arg[0]['typname']);
                                $argumen = preg_split("/\"/", $argument);
                                $script = $script . $argumen[1];
                                if ($i != count($proargtypes) - 1)
                                    $script = $script . ", ";
                            }
                        }
                        $script = $script . ") TO sauxe;\n";
                    }
                }
                $script = $script . "\n";
            }
        }
        return $script;
    }

    function SqlGrantFunction($checked, $conn) {
        $script = "";
        foreach ($checked as $var) {
            if (stripos($var[0], '^functions') == strlen($var[0]) - 10) {
                $schema = preg_split("/\^/", $var[0]);
                $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                foreach ($var[1] as $obj) {
                    $function = preg_split("/\^/", $obj);
                    $functions = $conn->SelectArqTypes($function[1]);
                    foreach ($functions as $value) {
                        $script = $script . "GRANT EXECUTE ON FUNCTION " . $schema[0] . "." . $value['proname'] . "(";
                        if ($value['proargtypes'] != null) {
                            $proargtypes = preg_split(" ", $value['proargtypes']);
                            for ($i = 0; $i < count($proargtypes); $i++) {
                                $argument = $conn->SelectTypeName($proargtypes[$i]);
                                $argumen = json_encode($argument[0]['typname']);
                                $arg = preg_split("\"", $argumen);
                                $script = $script . $arg[1];
                                if ($i != count($proargtypes) - 1)
                                    $script = $script . ", ";
                            }
                        }
                        $script = $script . ") TO sauxe;\n";
                    }
                }
                $script = $script . "\n";
            }
        }
        return $script;
    }

    function SqlGrantSequences($checked) {
        $script = "";
        foreach ($checked as $var) {
            if (stripos($var[0], '^sequences') == strlen($var[0]) - 10) {
                $schema = preg_split("/\^/", $var[0]);
                $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                $sequence = preg_split("/\^/", $var[1][0]);
                $script = $script . "GRANT ALL ON TABLE " . $sequence[0] . "." . $sequence[1] . " TO sauxe;\n";
                foreach ($var[1] as $obj) {
                    $sequence = preg_split("/\^/", $obj);
                    $script = $script . "GRANT ALL ON TABLE " . $sequence[0] . "." . $sequence[1] . " TO sauxe;\n";
                }
                $script = $script . "\n";
            }
        }
        return $script;
    }

    function SqlGrantTables($checked) {
        $script = "";
        foreach ($checked as $var) {
            if (stripos($var[0], '^tables') == strlen($var[0]) - 7) {
                $schema = preg_split("/\^/", $var[0]);
                $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                foreach ($var[1] as $obj) {
                    $table = preg_split("/\^/", $obj);
                    $script = $script . "GRANT ALL ON TABLE " . $table[0] . "." . $table[1] . " TO sauxe;\n";
                }
                $script = $script . "\n";
            }
        }
        return $script;
    }

    function SqlAlterTablesSequences($checked) {
        $script = "";
        foreach ($checked as $var) {
            if (stripos($var[0], '^tables') == strlen($var[0]) - 7) {
                $schema = preg_split("/\^/", $var[0]);
                $script = $script . "\nSET search_path = $schema[0], pg_catalog; \n\n";
                foreach ($var[1] as $obj) {
                    $table = preg_split("/\^/", $obj);
                    $script = $script . "ALTER TABLE " . $table[0] . "." . $table[1] . " OWNER TO sauxe;\n";
                }
            }
            if (stripos($var[0], '^sequences') == strlen($var[0]) - 10) {
                $schema1 = preg_split("/\^/", $var[0]);
                if ($schema1[0] != $schema[0])
                    $script = $script . "\nSET search_path = $schema1[0], pg_catalog; \n\n";
                foreach ($var[1] as $obj) {
                    $sequence = preg_split("/\^/", $obj);
                    $script = $script . "ALTER TABLE " . $sequence[0] . "." . $sequence[1] . " OWNER TO sauxe;\n";
                }
            }
        }
        $script = $script . "\n";
        return $script;
    }

    function SqlInsertData($var, $schema, $table, $conn) {
        $script = "";
        foreach ($var[1] as $row) {
            $pk = preg_split("/=>/", $row);
            $data = $conn->SelectWhereAllData($schema, $table, $pk);
            $cont = 0;
            $campos = "";
            $valores = "";
            foreach ($data[0] as $key => $value) {
                if (!is_int($key)) {
                    if ($cont < count($data[0]) - 2) {
                        $campos = $campos . "$key, ";
                        $valores = $valores . "'$value', ";
                    } elseif ($cont == count($data[0]) - 2) {
                        $campos = $campos . "$key";
                        $valores = $valores . "'$value'";
                    }
                }
                $cont++;
            }
            $script = $script . "INSERT INTO $schema.$table ($campos) VALUES ($valores); \n";
        }
        return $script;
    }

}

