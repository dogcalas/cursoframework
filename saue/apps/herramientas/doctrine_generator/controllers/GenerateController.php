<?php
    /**
	 * @package Doctrine Generator
	 * @copyright ERP Cuba
	 * @author Omar Antonio Díaz Peña
	 * @version 1.0-0
	 */

	class GenerateController extends ZendExt_Controller_Secure {
        /**
         * 
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

        function generateAction () {
            $mapper = $this->_project->get_mapper ();
            $tables = $this->_project->get_tables ();
            $path = $this->_project->get_path () . '/';
            
            $mapper->generate ($path, $tables);
            $file = new ZendExt_File ();
            $recursive = $file->tree ($path);

            $zip = new ZipArchive ();
            $zip->open ($path . $this->_project->get_name() .'.zip', ZIPARCHIVE::CREATE);

            foreach ($recursive as $item) {
                $just_local = substr ($item, strpos ($item, $this->_project->get_name ()));

                if (is_dir ($item))
                    $zip->addEmptyDir ($just_local);
                else
                    $zip->addFile ($item, $just_local);
            }

            $zip->close ();

            $obj->file = $path . $this->_project->get_name() .'.zip';
            echo json_encode ($obj);
        }
    	
        function descargarAction () {
            $path = $this->_project->get_path () . '/';

            ZendExt_Download :: force_download($this->_project->get_name() .'.zip',
                                               file_get_contents($path . $this->_project->get_name() .'.zip'));
        }
	}
?>
