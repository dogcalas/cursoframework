<?php

/**
 * @package Doctrine Generator
 * @copyright ERP Cuba
 * @author Omar Antonio Díaz Peña
 * @version 1.0-0
 */
class MainController extends ZendExt_Controller_Secure {

    function init() {
        parent::init(false);
    }

    function mainAction() {
        $this->render();
    }

    private function gen_cache_id() {
        $str = "project_{$_SERVER ['REMOTE_ADDR']}";
        return str_replace('.', '_', $str);
    }

    function loaddbmsAction() {
        $dbms_path = str_replace('controllers' . DIRECTORY_SEPARATOR . 'MainController.php', 'models' . DIRECTORY_SEPARATOR . 'bussines', __FILE__);
        $classes = ClassLoader::getClassesImplements($dbms_path, 'Driver');

        $data = array();

        foreach ($classes as $class) {
            $item->dbms = $class->getName();
            $data[] = $item;
            $item = null;
        }

        $json->data = $data;
        echo json_encode($json);
    }

    function loadversionAction() {
        $dbms_path = str_replace('controllers' . DIRECTORY_SEPARATOR . 'MainController.php', 'models' . DIRECTORY_SEPARATOR . 'bussines', __FILE__);
        $classes = ClassLoader::getClassesImplements($dbms_path, 'DoctrineVersion');

        $data = array();

        foreach ($classes as $class) {
            $item->version = $class->getName();
            $data[] = $item;
            $item = null;
        }

        $json->data = $data;
        echo json_encode($json);
    }

    function addtableAction() {
        $cache = ZendExt_Cache :: getInstance();
        $project = $cache->load($this->gen_cache_id());
        $project->get_connection()->connect();
        $name = $this->getRequest()->getParam('name');
        $_alias="tn_";
        $_alias .= $this->getRequest()->getParam('num');
        $table = new Table();
        $table->set_name($name);
        $table->set_alias($_alias);
        $table->set_fields($project->get_connection()->get_driver()->get_fields($name));
        $project->add_table($table);
        $project->get_connection()->set_doctrine(NULL);

        $cache->save($project, $this->gen_cache_id());
    }

    function addrelationsAction() {
        $cache = ZendExt_Cache :: getInstance();
        $project = $cache->load($this->gen_cache_id());
        $project->get_connection()->connect();

        $name = $this->getRequest()->getParam('name');
        $table = $project->find_table($name);

        $relations = $project->get_connection()->get_driver()->get_relations($name);

        foreach ($relations as $relation) {
            if ($project->table_exists($relation->get_foreign_table())) {
                $table->add_relation($relation);
            }
        }

        $project->get_connection()->set_doctrine(NULL);

        $cache->save($project, $this->gen_cache_id());
    }

    function revertrelationAction() {
        $cache = ZendExt_Cache :: getInstance();
        $project = $cache->load($this->gen_cache_id());
        $project->get_connection()->connect();

        $table = $project->find_table($this->getRequest()->getParam('name'));

        if ($table) {
            $relations = $table->get_relations();

            foreach ($relations as $relation) {
                $inverse = $relation->construct_inverse_relation($table);
                $inverse->set_inverse($relation);
                $relation->set_inverse($inverse);

                $inverse_table = $project->find_table($relation->get_foreign_table());

                if ($inverse_table && !$inverse_table->find_relation($inverse))
                    $inverse_table->add_relation($inverse);
            }
        }

        $project->get_connection()->set_doctrine(NULL);
        $cache->save($project, $this->gen_cache_id());
    }

    function createprojectAction() {
        $version = $this->getRequest()->getPost('version');
        $cache = ZendExt_Cache :: getInstance();
        $project = $cache->load($this->gen_cache_id());

        if ($project) {
            $project->set_name($this->getRequest()->getPost('project'));
            $project->set_mapper(new $version());
            $project->set_version($version);
            $project->set_path(sys_get_temp_dir() . DIRECTORY_SEPARATOR . $project->get_name());
            $cache->save($project, $this->gen_cache_id());
        } else
            ZendExt_MessageBox::show('Vuelva a conectarse.', ZendExt_MessageBox::ERROR);
    }

    function openAction() {

        $adapter = new Zend_File_Transfer_Adapter_Http();
        $adapter->setDestination(sys_get_temp_dir());
        $adapter->receive();

        $file = $adapter->getFileName();

        $file1 = explode('.', $file);
        if ($file1[count($file1) - 1] != 'dg') {
            ZendExt_MessageBox::show('Archivo inválido.', ZendExt_MessageBox::ERROR);
        }

        $project = unserialize(file_get_contents($file));


        if ($project instanceof Project) {
            $cache = ZendExt_Cache :: getInstance();
            $cache->save($project, $this->gen_cache_id());
        }
    }

    function comprobarproyectoAction() {
        $cache = ZendExt_Cache::getInstance();
        $project = $cache->load($this->gen_cache_id());
        if ($project instanceof Project)
            echo "{'succes':true}";
        else
            echo "{'succes':false}";
        //ZendExt_MessageBox::show('No hay un proyecto creado o abierto.', ZendExt_MessageBox::ERROR);
    }

    function downloadAction() {
        $cache = ZendExt_Cache::getInstance();
        $project = $cache->load($this->gen_cache_id());

        $serialized = serialize($project);
        file_put_contents($project->get_path() . '.dg', $serialized);
        ZendExt_Download :: force_download($project->get_name() . '.dg', file_get_contents($project->get_path() . '.dg'));
    }

    function connectAction() {
        $driver = $this->getRequest()->getPost('dbms');

        $conn = new Connection($this->getRequest()->getPost('host'), $this->getRequest()->getPost('port'), $this->getRequest()->getPost('db'), $this->getRequest()->getPost('user'), $this->getRequest()->getPost('passwd'), null);

        $d = new $driver($conn);
        $conn->set_driver($d);

        $project = new Project();
        $project->set_connection($conn);
        $cache = ZendExt_Cache :: getInstance();
        $cache->save($project, $this->gen_cache_id());

        try {
            if (!$conn->is_connected()) {
                $conn->connect();
            }

            $tables = $conn->getTables($this->getRequest()->getPost('db'));
            $tmp = array();

            foreach ($tables as $t) {
                $tt->table_name = $t->get_name();
                $tmp[] = $tt;
                $tt = null;
            }

            echo json_encode(array('data' => $tmp));
        } catch (Exception $e) {
            //ZendExt_MessageBox :: show ($e->getMessage(), ZendExt_MessageBox :: ERROR);
            ZendExt_MessageBox::show('Debe verificar los datos introducidos.', ZendExt_MessageBox::ERROR);
        }
    }

}
