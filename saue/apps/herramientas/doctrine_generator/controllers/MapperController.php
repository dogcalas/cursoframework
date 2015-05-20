<?php
	/**
	 * @package Doctrine Generator
	 * @copyright ERP Cuba
	 * @author Omar Antonio Díaz Peña
	 * @version 1.0-0
	 */

    class MapperController extends ZendExt_Controller_Secure {
        /**
         * @var Project
         */
    	private $_project;

        private function gen_cache_id () {
            $str = "project_{$_SERVER ['REMOTE_ADDR']}";
            return str_replace('.', '_', $str);
        }

        function init () {
            parent :: init();

            $cache = ZendExt_Cache::getInstance();
            $this->_project = $cache->load ($this->gen_cache_id ());
        }

        function loadclassesAction () {
            $result = array (); $tables = $this->_project->get_tables ();

            foreach ($tables as $table) {
                $item->clas = $table->get_classname ();
                $item->table = $table->get_name ();
                $result[] = $item; $item = null;
            }

            echo json_encode(array ('data' => $result));
        }

        public function loadtypesAction () {
            $t = null;
            
            $t->type = 'Tiene un(a)';
            $t->clas = 'HasOne';
            $types[] = $t; $t = null;
            $t->type = 'Tiene muchos(as)';
            $t->clas = 'HasMany';
            $types[] = $t;

            echo json_encode(array ('types' => $types));
        }

        public function loadfieldsAction () {
            $table = $this->_project->find_table ($this->getRequest()->getPost ('table'));
            echo json_encode (array ('fields' => $table->_component_to_array ('_fields')));
        }

        function loadAction () {
            $classname = $this->getRequest()->getPost ('clase');
            echo json_encode ($this->_project->find_table ($classname)->to_array ());
        }

        function updaterelationAction () {
            //Obtengo la tabla a actualizar
            $table = $this->_project->find_table ($this->getRequest()->getPost ('clase'));

            //Obtengo las relaciones
            $relations = $table->get_relations ();

            //Obtengo la relación en cuestion
            $relation = $relations[$this->getRequest()->getPost ('row')];

//            debug ($relations);

            //Solo se le hacen cambios a las HasMany
            if (! $relation instanceof HasMany)
            {
                ZendExt_MessageBox::show('Este tipo de relaciones no admiten cambios.', ZendExt_MessageBox::ERROR);
                return;
            }

            //Se le pone la tabla intermedia
            $relation->set_intermediate_table ($relation->get_foreign_table ());

            //Se le pone la tabla foranea
            $relation->set_foreign_table ($this->getRequest()->getPost ('value'));

            //Se actualiza el campo foraneo con el campo de llave primaria de esa tabla
            $relation->set_foreign_field($this->_project->find_table($this->getRequest()->getParam('value'))->get_pk_field ()->get_name ());

            //Se crea la relacion inversa
            $inverse_relation = new HasMany();

            //La tabla inversa es la local
            $inverse_relation->set_foreign_table ($table->get_name ());

            //Idem
            $inverse_relation->set_local_field ($relation->get_foreign_field ());

            //Idem
            $inverse_relation->set_foreign_field ($relation->get_local_field ());

            //Idem
            $inverse_relation->set_intermediate_table ($relation->get_intermediate_table ());

            $inverse_table = $this->_project->find_table ($relation->get_foreign_table ());

            //Se elimina la relación vieja
            $inverse_table->add_relation ($inverse_relation);

            foreach ($inverse_table->get_relations ()  as $index =>$rr) {
                if ($rr->get_foreign_table() == $relation->get_intermediate_table()) {
                    $inverse_table->rem_relation ($index); break;
                }
            }

            $tmp_table = $this->_project->find_table($relation->get_intermediate_table());
            $tmp_relations = $tmp_table->get_relations ();

            foreach ($tmp_relations as $index => $tr) {
                if ($tr->get_foreign_table () == $table || $tr->get_foreign_table () == $relation->get_foreign_table()) {
                    $tmp_table->rem_relation ($index); break;
                }
            }
            
            $this->save (); echo json_encode($table->to_array ());
        }

        function updateparentAction () {
            $table = $this->_project->find_table ($this->getRequest()->getPost ('table'));
            $table->set_parent_table ($this->getRequest()->getPost ('parent'));
            
            $relations = $table->get_relations ();
            $inverse_name = '';
            
            foreach ($relations as $relation) {
                if (Table :: classname ($relation->get_foreign_table ()) == $this->getRequest()->getPost ('parent')) {
                    $relation->set_mappeable (! $relation->get_mappeable ());
                    $inverse_name = $relation->get_foreign_table ();
                }
            }
            
            $inverse_table = $this->_project->find_table($inverse_name);

            if ($inverse_table) {
                $inverse_relations = $inverse_table->get_relations ();

                foreach ($inverse_relations as $relation) {
                    if ($relation->get_foreign_table () == $table->get_name ()) {
                        $relation->set_mappeable (! $relation->get_mappeable ());
                    }
                }
            }

            $this->save ();

            echo json_encode($table->to_array ());
        }
        
     function addAction() {
        	$type = $this->getRequest()->getPost ('type');
        	
        	$relation = new $type ();
        	$relation->set_foreign_table ($this->getRequest()->getPost ('ft'));
        	$relation->set_foreign_field ($this->getRequest()->getPost ('ff'));
        	$relation->set_local_field ($this->getRequest()->getPost ('lf'));
        	
        	if ($relation instanceof  HasMany) {
        		if (! $this->getRequest()->getPost ('it'))
        			$inverse = new HasOne ();
        		else 
        			$inverse = new HasMany();
        	} else $inverse = new HasMany (); 
        	
        	$inverse->set_foreign_field($relation->get_local_field ());
        	$inverse->set_foreign_table($this->getRequest()->getPost ('lt'));
        	$inverse->set_local_field($relation->get_foreign_field());
        	
        	
        	if ($this->getRequest()->getPost ('it')) {
        		$relation->set_intermediate_table($this->getRequest()->getPost ('it'));
        		$inverse->set_intermediate_table($this->getRequest()->getPost ('it'));
        	} 
        	
        	$table = $this->_project->find_table($this->getRequest()->getPost ('lt'));
        	$relations = $table->get_relations (); $relations[] = $relation; $table->set_relations ($relations);
        	        	
        	$inverse_table = $this->_project->find_table($this->getRequest()->getPost ('ft'));
        	$relations = $inverse_table->get_relations (); $relations[] = $inverse; $inverse_table->set_relations ($relations);
        	
        	$this->save ();

                echo json_encode($table->to_array ());
        }
        
        function remAction () {
        	$table = $this->_project->find_table($this->getRequest()->getPost ('table'));
        	$relations = $table->get_relations ();
			$relation = $relations [$this->getRequest()->getPost ('index')];
			
			$relation->set_mappeable (false); $this->save (); echo json_encode ($table->to_array ());
        }

        function save () {
            $cache = ZendExt_Cache::getInstance();
            $cache->save ($this->_project, $this->gen_cache_id ());
        }
    }
?>