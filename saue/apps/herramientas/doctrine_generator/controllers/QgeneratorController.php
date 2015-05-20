<?php

/**
 * @package Doctrine Generator
 * @copyright ERP Cuba
 * @author Omar Antonio Díaz Peña
 * @version 1.0-0
 */
class QgeneratorController extends ZendExt_Controller_Secure {

    /**
     * @var Project
     */
    private $_project;

    private function gen_cache_id() {
        $str = "project_{$_SERVER ['REMOTE_ADDR']}";
        return str_replace('.', '_', $str);
    }

    function init() {
        parent::init();
        $cache = ZendExt_Cache::getInstance();
        $this->_project = $cache->load($this->gen_cache_id());
    }

    function qgeneratorAction() {
        $this->render();
    }

    public function loadactionsAction() {
        $t = null;

        $t->id = 1;
        $t->act = 'SELECT';
        $types[] = $t;
        $t = null;
        $t->id = 2;
        $t->act = 'DELETE';
        $types[] = $t;
        $t = null;
        $t->id = 2;
        $t->act = 'UPDATE';
        $types[] = $t;
        $t = null;
        echo json_encode(array('data' => $types));
        
    }

    /* function loadClassesAction() {
      $result = array();
      $tables = $this->_project->get_tables();

      foreach ($tables as $table) {
      $item->clas = $table->get_classname();
      $item->table = $table->get_name();
      $result[] = $item;
      $item = null;
      }
      echo json_encode(array('data' => $result));
      } */

    public function loadrealationsAction() {
        $table = $this->_project->find_table($this->getRequest()->getPost('table'));
        echo json_encode(array('data' => $table));
    }

    public function loadCamposAction() {
        $table = $this->_project->find_table($this->getRequest()->getPost('table'));
        echo json_encode(array('fields' => $table->_component_to_array('_fields')));
    }

    function loadAction() {
        $classname = $this->getRequest()->getPost('clase');
        echo json_encode($this->_project->find_table($classname)->to_array());
    }

    function loadclassesAction() {

        $result = array();
        $tables = $this->_project->get_tables();

        foreach ($tables as $table) {
            $item->clas = $table->get_classname();
            $item->table = $table->get_name();
            $result[] = $item;
            $item = null;
        }

        echo json_encode(array('data' => $result));
    }

    public function loadfieldsAction() {
        $tablea = $this->_project->find_table($this->getRequest()->getPost('table'))->_component_to_array('_fields');
        $aliasa = $this->_project->find_table($this->getRequest()->getPost('table'))->get_alias();
        if ($this->getRequest()->getPost('table1')) {
            $table1 = $this->_project->find_table($this->getRequest()->getPost('table1'))->_component_to_array('_fields');
            $alias1 = $this->_project->find_table($this->getRequest()->getPost('table1'))->get_alias();
        }

        $table = array();
        foreach ($tablea as $t) {
            $temp = $t['name'];
            $t['name'] = $aliasa . "." . $temp;
            $table[] = $t;
        }

        foreach ($table1 as $t) {
            $temp = $t['name'];
            $t['name'] = $alias1 . "." . $temp;
            $table[] = $t;
        }
        echo json_encode(array('fields' => $table));
    }

    public function loadfieldscAction() {
        $table = $this->_project->find_table($this->getRequest()->getPost('table'));
        // print_r($table);die;
        echo json_encode(array('fields' => $table->_component_to_array('_fields')));
    }

    public function loadfieldsvAction() {
        $table = $this->_project->find_table($this->getRequest()->getPost('table'));
        echo json_encode(array('fields' => $table->_component_to_array('_fields')));
    }

    function loadtreeAction() {
        $result = array();
        $tables = $this->_project->get_tables();
        $nombre = "Repository";
        if ($this->_project->get_version() == "DoctrineV1")
            $nombre = "Base";
        $i = 1;
        foreach ($tables as $table) {
            $item->id = $i;
            $item->text = $table->get_classname();
            $item->leaf = true;

            $item1->id = $i . '-r';
            $item1->text = $table->get_classname() . $nombre;
            $item1->leaf = true;

            $item2->id = $i . '-m';
            $item2->text = $table->get_classname() . "Model";
            $item2->leaf = true;

            $result[] = $item;
            $result[] = $item1;
            $result[] = $item2;
            $item = null;
            $item1 = null;
            $item2 = null;
            $i++;
        }
        //print_r(array('nodes' => $result));die;
        echo json_encode($result);
    }

    function loadtreeFunctionsAction() {
        $node = $this->_request->getPost('node');
        $result = array();
        $tables = $this->_project->get_tables();
        $nombre = "";
        if ($this->_project->get_version() == "DoctrineV2")
            $nombre = "Repository";
        $i = 1;
        $hijos = array();
        $item1 = array();
        $item = array();
        if ($node > 0) {
            $j = 1;
            while ($j <= $node) {
                foreach ($tables as $table) {
                    if ($j == $node) {
                        foreach ($table->get_functions() as $function) {
                            $item1['id'] = $l . '-f';
                            $item1['text'] = $function->get_name();
                            $item1['leaf'] = true;

                            $hijos[] = $item1;
                            $item1 = null;
                            $l++;
                        }
                    }
                    $j++;
                }
            }
            echo json_encode($hijos);
            return;
        } else {
            foreach ($tables as $table) {
                $item['id'] = $i;
                $item['text'] = $table->get_classname() . $nombre;
                $item['leaf'] = false;
                $result[] = $item;
                $item = null;
                $i++;
            }
         
//            $cant=count($result);
       //print_r($a);die('fin');
////            if(<1){
////                $this->showMessage("No hay elementos para mostar.");
////                return;
////            }
            echo json_encode($result);
            
            return;
        }
    }

    function loadunionsAction() {
        $action = array();
        $item->id = 1;
        $item->un = 'AND';
        $action[] = $item;
        $item = null;
        $item->id = 2;
        $item->un = 'OR';
        $action[] = $item;
        echo json_encode(array('data' => $action));
    }

    function loadmodesAction() {
        $action = array();
        $item->id = 1;
        $item->mod = 'ASC';
        $action[] = $item;
        $item = null;
        $item->id = 2;
        $item->mod = 'DESC';
        $action[] = $item;
        echo json_encode(array('data' => $action));
    }

    function loadvisibAction() {
        $action = array();
        $item->id = 1;
        $item->visib = 'public';
        $action[] = $item;
        $item = null;
        $item->id = 2;
        $item->visib = 'static';
        $action[] = $item;
        $item = null;
        $item->id = 3;
        $item->visib = 'private';
        $action[] = $item;
        echo json_encode(array('data' => $action));
    }

    function loadoperandosAction() {
        $action = array();
        $item->id = 1;
        $item->op = '=';
        $action[] = $item;
        $item = null;
        $item->id = 2;
        $item->op = '>';
        $action[] = $item;
        $item = null;
        $item->id = 3;
        $item->op = '<';
        $action[] = $item;
        $item = null;
        $item->id = 4;
        $item->op = '<>';
        $action[] = $item;
        $item = null;
        $item->id = 5;
        $item->op = 'LIKE';
        $action[] = $item;
        echo json_encode(array('data' => $action));
    }
   /* public savedinamicAction(){
$str = "temp_{$_SERVER ['REMOTE_ADDR']}";
$cache = ZendExt_Cache::getInstance();
$_tempfu = $cache->load(str_replace('.', '_', $str));

$cache->save(str_replace('.', '_', $str), $this->gen_cache_id());

}
public function adddinamicAction(){

    $obj->visible = $this->getRequest()->getPost('visible');
    $obj->name = $this->getRequest()->getPost('name');
    $obj->table = $this->getRequest()->getPost('action');
    $obj->params=$this->getRequest()->getPost('params');
    $obj->fields=$this->getRequest()->getPost('fields');
    $obj->join=$this->getRequest()->getPost('join');
    $obj->visib = $this->getRequest()->getPost('visib');

    $table = $this->getRequest()->getPost('table');

    $fs = $this->_project->find_table($table)->get_functions();

}*/
    public function addfunctionAction() {
        $id = $this->getRequest()->getPost('id');
        $name = $this->getRequest()->getPost('name');
        $table = $this->getRequest()->getPost('table');
        $visib = $this->getRequest()->getPost('visib');

        $_function = new Funcion();
        $_function->set_id($id);
        $_function->set_name($name);
        $_function->set_visibility($visib);
        $fs = $this->_project->find_table($table)->get_functions();
        $flag = true;
        foreach ($fs as $function) {
            if ($function->get_id() == $id)
                $flag = false;
        }
        if ($flag) {
            $this->_project->find_table($table)->set_function($_function);
            $this->save();
        }
        // print_r($this->_project->find_table('mod_seguridad.seg_datos_reconocimiento')->get_functions());
        //$this->showMessage("Echo");
    }

    public function remfunctionAction() {
        $id = $this->getRequest()->getPost('id');
        $table = $this->getRequest()->getPost('table');

        $fs = $this->_project->find_table($table)->get_functions();

        foreach ($fs as $index => $tr) {
            if ($tr->get_id() == $id) {
                $this->_project->find_table($table)->rem_function($index);
                break;
            }
        }
        $this->save();
    }

    function save() {
        $cache = ZendExt_Cache::getInstance();
        $cache->save($this->_project, $this->gen_cache_id());
    }

    public function loadversionAction() {
        $version = $this->_project->get_version();
        echo json_encode($version);
    }

    public function loadaliasAction() {
        $table = $this->getRequest()->getPost('clase');
        $alias = $this->_project->find_table($table)->get_alias();
        echo json_encode($alias);
    }

    public function loadnamespaceAction() {
        $table = $this->getRequest()->getPost('clase');
        $namespace = $this->_project->find_table($table)->get_namespace();
        echo json_encode($namespace);
    }

    public function setnamespaceAction() {
        $table = $this->getRequest()->getPost('table');
        $_namespace = $this->getRequest()->getPost('namespace');

        $this->_project->find_table($table)->set_namespace($_namespace);
          $this->save();
        echo "success";
    }

    public function loadcheckAction() {
        $table = $this->getRequest()->getPost('clase');
        //print_r($table);die;
        $fs = $this->_project->find_table($table)->get_functions();
        $result = array();
        foreach ($fs as $value) {
            $result[]['id'] = $value->get_id();
        }
        echo json_encode($result);
    }

}
