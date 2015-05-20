<?php

class HistoryController extends ZendExt_Controller_Secure {

<<<<<<< .mine
    function init() {
        parent::init();
        $this->_history = new ZendExt_History_ADTHistory ( );
    }
=======
		if (count ( $hist ) > 0)
			
		$result = $this->_history->CreateHistorial ( $hist, $user );                
>>>>>>> .r6020

    public function historyAction() {
        $this->render();
    }

<<<<<<< .mine
    public function especificacionAction() {
        $this->render();
    }

    public function inicialAction() {
        $schema = "";
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        if ($this->_request->getPost('schema'))
            $schema = $this->_request->getPost('schema');
        $tables = $this->_history->Tables($limit, $start, $schema);
        if (!$schema || $schema == 'todo')
            $schema = "";
        $cantidad = $this->_history->CountTables($schema);

        echo json_encode(array('total' => $cantidad [0] ['cantidad'], 'datos' => $tables));
    }

    function crearhistorialAction() {

        $hist = json_decode(stripslashes($this->_request->getPost('datos')));

        if (count($hist) > 0)
            $user = $this->global->Perfil->usuario;
        // print_r($hist);die;
        $result = $this->_history->CreateHistorial($hist, $user);

        if (count($result) > 0)
            $this->showMessage('Verifique las tabla(s) para historial.');
        else
            $this->showMessage('Se ha realizado el historial correctamente.');
    }

    function cargarhistorialAction() {
        $array = array();
        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $h = $this->_history->getHistorial($limit, $start);
        $cantidad = $this->_history->Count('dat_historial');

        if (count($h)) {

            foreach ($h as $index => $datos) {
                $array [$index] ['id_historial'] = $datos ['id_historial'];
                $array [$index] ['table_name'] = $datos ['tabla'];
                $array [$index] ['fecha'] = $datos['fecha'];
                $array [$index] ['creado'] = $datos['usuario'];
                $array [$index] ['esquema'] = $datos['esquema'];
                $array [$index] ['hora'] = $datos['hora'];
            }
            echo json_encode(array('total' => $cantidad [0] ['cantidad'], 'datos' => $array));
        } else {
            echo json_encode(array('total' => 0, 'datos' => array()));
        }
    }

    function eliminarhistorialAction() {
        $hist = json_decode(stripslashes($this->_request->getPost('datos')));
        $result = $this->_history->DropHistorial($hist);

        if (count($result) > 0) {
            $this->showMessage('No se han podido eliminar alguna(s) tabla(s) del historial. Presencia de datos');
        } else {
            $this->showMessage('Se han eliminado la tabla(s) del historial correctamente');
        }
    }

    function cargarschemaAction() {
        $all = array('table_schema' => 'todo');
        $result = $this->_history->Schema();
        $result [] = $all;
        echo json_encode(array('datos' => $result));
    }

    function configridAction() {
        $result = $result = array('grid' => array('columns' => array()));
        $table = $this->_request->getPost('table');
        $fields = $this->_history->FieldTable($table, false);
        foreach ($fields as $field) {
            $campo = ucwords($field ['column_name']);
            $result ['grid'] ['columns'] [] = array('header' => $campo, 'width' => 70, 'sortable' => true, 'dataIndex' => $field ['column_name']);
            $result ['grid'] ['campos'] [] = $field ['column_name'];
        }
        echo json_encode($result);
    }

    function cargartablasAction() {
        $array = array();
        $h = $this->_history->getHistorial("", "");
        foreach ($h as $index => $i) {
            $array [$index] ['table_name'] = $i ['table'];
        }
        echo json_encode(array('datos' => $array));
    }

    function cargargriddatosAction() {
        $table = $this->_request->getPost('table');
        $limit = $this->_request->getPost('limit');
        $offset = $this->_request->getPost('start');
        $datos = $this->_history->TableAll($table, $limit, $offset);
        $cantidad = $this->_history->Count($table);
        echo json_encode(array('cantidad' => $cantidad [0] ['cantidad'], 'datos' => $datos));
    }

=======
		if (count ( $h )) {
			
			foreach ( $h as $index =>$datos)
				{
				//$tabla = $this->_history->Table ( $t ['table'] );
                                $array [$index] ['id_historial'] = $datos ['id_historial'];
				$array [$index] ['table_name'] = $datos ['tabla'];
				$array [$index] ['fecha'] = $datos['fecha'];
				$array [$index] ['creado'] = $datos['usuario'];
				$array [$index] ['esquema'] = $datos['esquema'];
                                $array [$index] ['hora'] = $datos['hora'];
			}
			echo json_encode ( array ('total' => $cantidad [0] ['cantidad'], 'datos' => $array ) );
		} else {
			echo json_encode ( array ('total' => 0, 'datos' => array () ) );
		}
	}
	function eliminarhistorialAction() {
		$hist = json_decode ( stripslashes ( $this->_request->getPost ( 'datos' ) ) );
		$result = $this->_history->DropHistorial ( $hist );
		if (count ( $result ) > 0) {
			$this->showMessage ( 'No se han podido eliminar alguna(s) tabla(s) del historial. Presencia de datos' );
		} else {
			$this->showMessage ( 'Se han eliminado la tabla(s) del historial correctamente' );
		}
	}
	function cargarschemaAction() {
		$all = array ('table_schema' => 'todo' );
		$result = $this->_history->Schema ();
		$result [] = $all;
		echo json_encode ( array ('datos' => $result ) );
	}
	function configridAction() {
		$result = $result = array ('grid' => array ('columns' => array () ) );
		$table = $this->_request->getPost ( 'table' );
		$fields = $this->_history->FieldTable ( $table ,false);
		foreach ( $fields as $field ) {
			$campo = ucwords ( $field ['column_name'] );
			$result ['grid'] ['columns'] [] = array ('header' => $campo, 'width' => 70, 'sortable' => true, 'dataIndex' => $field ['column_name'] );
			$result ['grid'] ['campos'] [] = $field ['column_name'];
		}
		echo json_encode ( $result );
	}
	function cargartablasAction() {
		$array = array ();
		$h = $this->_history->getHistorial ( "", "" );
		foreach ( $h as $index => $i ) {
			$array [$index] ['table_name'] = $i ['table'];
		}
		echo json_encode ( array ('datos' => $array ) );
	}
	function cargargriddatosAction() {
		$table = $this->_request->getPost ( 'table' );
		$limit = $this->_request->getPost ( 'limit' );
		$offset = $this->_request->getPost ( 'start' );
		$datos = $this->_history->TableAll ( $table, $limit, $offset );
		$cantidad = $this->_history->Count ( $table );
		echo json_encode ( array ('cantidad' => $cantidad [0] ['cantidad'], 'datos' => $datos ) );
	}
	
	
>>>>>>> .r6020
}

?>
