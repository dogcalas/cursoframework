<?php
    class ZendExt_Metadata_Models_MetadataModel extends ZendExt_Model {

        private $_data;
        private $_confgrid;
        private $_ciar;
        private $_module;



        public function  __construct() {
                parent :: ZendExt_Model ();
                $this->_manager = new ZendExt_Metadata_Sql_Manager();
                $this->_data->items = array();
                $this->_confgrid->rd = array();
                $this->_confgrid->cm = array();

                $ciar = new ActiveRecordFactory();
                $this->_ciar = $ciar->Factory();
				$doctrineManager = Doctrine_Manager::getInstance();
                $this->_module = $doctrineManager->getConnectionName($this->conn);

        }

        public function obtenerConfigGridPanel($idtabla) {
            $this->_manager->setCurrentConnection('xmetacomponent');

            $result = $this->obtenerCamposDadoTabla($idtabla);

            for ($i=0 ; $i<count($result); $i++)
            {
                            $tabla = $result[$i]->DatComponente->NomTipoComponente->tabla;

                            if(isset($result[$i]->DatComponente))
                            {
                                            if($result[$i]->DatComponente->NomTipoComponente->idtipocomponente == 5)
                                            {
                                                $this->_confgrid-> rd [] = array('name' => "{$result[$i]->DatTabla->esquema}_{$result[$i]->DatTabla->tabla}_{$result[$i]->denominacion}");
                                                $this->_confgrid-> rd [] = array('name' => "{$result[$i]->DatComponente->$tabla->DatRemoto->Origen->Origen->DatTabla->tabla}_{$result[$i]->DatComponente->$tabla->DatRemoto->campo_denominacion}");
                                                $this->_confgrid-> cm [] = array('header' => $result[$i]->DatComponente->etiqueta, 'dataIndex' =>"{$result[$i]->DatComponente->$tabla->DatRemoto->Origen->Origen->DatTabla->tabla}_{$result[$i]->DatComponente->$tabla->DatRemoto->campo_denominacion}");
                                            }
                                            else
                                            {
                                                $this->_confgrid-> rd [] = array('name' => "{$result[$i]->DatTabla->esquema}_{$result[$i]->DatTabla->tabla}_{$result[$i]->denominacion}");
                                                $this->_confgrid-> cm [] = array('header' => $result[$i]->DatComponente->etiqueta, 'dataIndex' =>  "{$result[$i]->DatTabla->esquema}_{$result[$i]->DatTabla->tabla}_{$result[$i]->denominacion}");
                                            }
                            }
                            elseif($result[$i]->llave_primaria)
                                            $this->_confgrid-> rd [] = array('name' =>"{$result[$i]->DatTabla->esquema}_{$result[$i]->DatTabla->tabla}_{$result[$i]->denominacion}");
            }

            $this->_manager->setCurrentConnection($this->_module);
            return $this->_confgrid;
        }

        public function obtenerConfigFormulario($idtabla)
        {
            $this->_manager->setCurrentConnection('xmetacomponent');

            
            $result = $this->obtenerCamposDadoTabla($idtabla);

            for ($i=0 ; $i<count($result); $i++)
            {
                if(isset($result[$i]->DatComponente->NomTipoComponente->tabla))
                {
                    $tabla = $result[$i]->DatComponente->NomTipoComponente->tabla;
                    $objTabla = $result[$i]->DatComponente->$tabla;

                    switch($result[$i]->DatComponente->NomTipoComponente->idtipocomponente)
                    {
                            case 1 : {
                                        $this->_data -> items [] = array('xtype' => 'numberfield','id' => $result[$i]->idcampo,'name' => "{$result[$i]->DatTabla->esquema}_{$result[$i]->DatTabla->tabla}_{$result[$i]->denominacion}",'regex' => '',
                                        'anchor' => '95%','allowBlank' => ($result[$i]->permite_nulo == 1)?true:false,'fieldLabel' => $result[$i]->DatComponente->etiqueta,
                                        'maxLength' => $result[$i]->longitud, 'allowDecimals'=>($objTabla->decimal == 1) ? true : false,'decimalPrecision' => $objTabla->precision);
                                     }continue;
                            case 2 : {
                                        $check -> items [] = array('xtype' => 'checkbox','id' => $result[$i]->idcampo,'name' => "{$result[$i]->DatTabla->esquema}_{$result[$i]->DatTabla->tabla}_{$result[$i]->denominacion}",'regex' => '',
                                        'anchor' => '95%','allowBlank' =>($result[$i]->permite_nulo == 1)?true:false,'fieldLabel' => $result[$i]->DatComponente->etiqueta);
                                     }continue;
                            case 3 : {
                                        $this->_data -> items [] = array('xtype' => 'datefield','id' => $result[$i]->idcampo,'name' => "{$result[$i]->DatTabla->esquema}_{$result[$i]->DatTabla->tabla}_{$result[$i]->denominacion}",
                                        'anchor' => '95%','allowBlank' => ($result[$i]->permite_nulo == 1)?true:false,'readOnly' => false,'fieldLabel' => $result[$i]->DatComponente->etiqueta,
                                        'maxValue' => $objTabla->fecha_fin, 'minValue' => $objTabla->fecha_inicio,'format'=>'d/m/y');
                                     }continue;
                            case 4 : {
                                        $this->_data -> items [] = array('xtype' => 'textfield','id' => $result[$i]->idcampo,'name' => "{$result[$i]->DatTabla->esquema}_{$result[$i]->DatTabla->tabla}_{$result[$i]->denominacion}",
                                        'regex' => "",'anchor' => '95%',
                                        'allowBlank' => ($result[$i]->permite_nulo == 1)?true:false,'fieldLabel' => $result[$i]->DatComponente->etiqueta, 'maxLength'=> $result[$i]->longitud);
                                     }continue;
                            case 5 : {
                                        $filtrado     = ($objTabla->filtrado == 1) ? true : false;
                                        $valuefield   = $objTabla->DatRemoto->Origen->Origen->denominacion;
                                        $displayfield = $objTabla->DatRemoto->campo_denominacion;
                                        $tablaOrigen  = $objTabla->DatRemoto->Origen->Origen->DatTabla;

                                        $result_query = $this->_ciar ->select($displayfield." as ".$tablaOrigen->tabla."_".$displayfield ." , ".$valuefield)
                                                                     ->from("{$tablaOrigen->esquema}.{$tablaOrigen->tabla}")
                                                                     ->get()
                                                                     ->result_array();

                                        $this->_data -> items [] = array('xtype' => 'combo','id' => $result[$i]->idcampo,'hiddenName' => "{$result[$i]->DatTabla->esquema}_{$result[$i]->DatTabla->tabla}_{$result[$i]->denominacion}",
                                        'anchor' => '95%', 'mode'=>'local', 'triggerAction' => 'all', 'allowBlank' => ($result[$i]->permite_nulo == 1)?true:false,'fieldLabel' => $result[$i]->DatComponente->etiqueta,
                                        'emptyText' => '[Seleccionar]', 'typeAhead' => $filtrado, 'valueField' => $valuefield, 'displayField' => "{$tablaOrigen->tabla}_{$displayfield}", 
                                        'store'=>"new Ext.data.JsonStore({fields:['{$valuefield}','{$tablaOrigen->tabla}_{$displayfield}'], data: ".json_encode($result_query)."})");
                                     }continue;
                            case 6 : {
                                        $this->_data -> items [] = array('xtype' => 'textarea','id' => $result[$i]->idcampo,'name' => "{$result[$i]->DatTabla->esquema}_{$result[$i]->DatTabla->tabla}_{$result[$i]->denominacion}",'regex' => '',
                                        'anchor' => '95%','allowBlank' => ($result[$i]->permite_nulo == 1)?true:false,'fieldLabel' => $result[$i]->DatComponente->etiqueta);
                                     }continue;
                    }
                }

                    if($result[$i]->llave_primaria)
                         $this->_data -> items [] = array('xtype' => 'hidden', 'id' => $result[$i]->idcampo,'name' => "{$result[$i]->DatTabla->esquema}_{$result[$i]->DatTabla->tabla}_{$result[$i]->denominacion}");
            }
			
			$this->obtenerComponentesCompuestos($idtabla);
			
            $this->_manager->setCurrentConnection($this->_module);
            return $this->_data;


			/*if($check->items)
				$dat->items = array_merge($dat->items,$check->items);*/
        }
		
		public function obtenerComponentesCompuestos($idtabla) {
		
			$this->_manager->setCurrentConnection('xmetacomponent');
			$compcompuestos = DatComponenteCompuesto::obtenerComponentesCompuestos($idtabla);
			
			foreach ($compcompuestos as $componente)
            {
                $this->_manager->setCurrentConnection('xmetacomponent');
                if(isset($componente->NomTipoComponenteCompuesto->tabla))
                {
                    $tablacomp    = $componente->NomTipoComponenteCompuesto->tabla;
                    $objTablaComp = $componente->$tablacomp;

                    switch($componente->idtipo_componente_compuesto)
                    {
                       case 1: {
                                $idtabladetalle = $objTablaComp->idtabladetalle;
                                $alias = Doctrine::getTable('DatTabla')->find($idtabladetalle)->alias;
                                $this->_data -> items [] = array('xtype' => 'meta-grid','idtabla'=>$idtabladetalle,'idcomponente'=>$componente->idcomponente_compuesto, 'alias'=>$alias,'config'=>$this->obtenerConfigGridPanel($idtabladetalle));
                        }continue;
                        case 2: {
                                $idtabladetalle = $objTablaComp->idtabladetalle;
                                $alias = Doctrine::getTable('DatTabla')->find($idtabladetalle)->alias;
                                $this->_data -> items [] = array('xtype' => 'meta-tree','idtabla'=>$idtabladetalle,'idcomponente'=>$componente->idcomponente_compuesto, 'alias'=>$alias);
                        }continue;
						case 3: {
								$idtabladetalle = $objTablaComp->idtabladetalle;
								$tabladetalle = Doctrine::getTable('DatTabla')->find($idtabladetalle);
								$valuefield   = $tabladetalle->esquema."_".$tabladetalle->tabla."_id".substr ($tabladetalle->tabla, 4, strlen($tabladetalle->tabla)-4);
								$displayfield = $tabladetalle->esquema."_".$tabladetalle->tabla."_".$componente->DatComboCompuesto->displayfield;
								$this->_data -> items [] = array('xtype' => 'meta-combo','anchor' => '95%', 'mode'=>'remote', 'triggerAction' => 'all','fieldLabel' => $tabladetalle->alias,
								'emptyText' => '[Seleccionar]', 'displayField' => $displayfield, 'valueField'=>$valuefield, 'fields'=>array($displayfield,$valuefield),'idtabla'=>$idtabladetalle,
								'idcomponente'=>$componente->idcomponente_compuesto);
								}continue;
                    }
                }
            }
			
			$this->_manager->setCurrentConnection($this->_module);
			return $this->_data; 
		}



    public function insertarEnMetadatos($pIdtabla,$arrData = array(),$arrValues = array())
    {
        $this->_manager->setCurrentConnection('xmetacomponent');

        $table  = Doctrine :: getTable('DatTabla')->find($pIdtabla);
        $_data = array();        

        foreach ($table->DatCampo as $field)
        {
            if($arrData["{$table->esquema}_{$table->tabla}_{$field->denominacion}"])
                $_data[$field->denominacion] = $arrData["{$table->esquema}_{$table->tabla}_{$field->denominacion}"];
            if($field->DatComponente->idtipocomponente == 2)
                $_data[$field->denominacion] = ($arrData["{$table->esquema}_{$table->tabla}_{$field->denominacion}"] == 'on') ? 1 : 0;
        }

        foreach($arrValues as $key=>$value)
             $_data[$key] = $value;
    
        if($table->arbol == 1 && $arrData['idnodopadre'] != 0)
                $_data["id".substr ($table->tabla, 4, strlen($table->tabla)-4)."padre"] = $arrData['idnodopadre'];
		
        $this->_ciar->insert($table->esquema.".".$table->tabla,$_data);

        unset($_data);

        $arrData['llave_master'] = "id".substr ($table->tabla, 4, strlen($table->tabla)-4);
		$arrData['idmaster'] = $this->_ciar->insert_id();
		
		if($this->insertarComponentesCompuestos($arrData)) {
			$this->_manager->setCurrentConnection($this->_module);
			return true;
		}
    }

    public function modificarEnMetadatos($pIdtabla,$arrData)
	{
            $this->_manager->setCurrentConnection('xmetacomponent');

            $table  = Doctrine :: getTable('DatTabla')->find($pIdtabla);
            $_data = array();

            foreach ($table->DatCampo as $field)
            {
                if(!$field->llave_primaria)
                {
                    if($arrData["{$table->esquema}_{$table->tabla}_{$field->denominacion}"])
                        $_data[$field->denominacion] = $arrData["{$table->esquema}_{$table->tabla}_{$field->denominacion}"];
                    if($field->DatComponente->idtipocomponente == 2)
                        $_data[$field->denominacion] = ($arrData["{$table->esquema}_{$table->tabla}_{$field->denominacion}"] == 'on') ? 1 : 0;
                }
            }

            $this->_ciar->update($table->esquema.".".$table->tabla,$_data, array("id".substr ($table->tabla, 4, strlen($table->tabla)-4) => $arrData["{$table->esquema}_{$table->tabla}_id".substr ($table->tabla, 4, strlen($table->tabla)-4)]));
			
			$arrData['llave_master'] = "id".substr ($table->tabla, 4, strlen($table->tabla)-4);
			$arrData['idmaster'] = $arrData["{$table->esquema}_{$table->tabla}_id".substr ($table->tabla, 4, strlen($table->tabla)-4)];
			
			if($this->insertarComponentesCompuestos($arrData)) {
				$this->_manager->setCurrentConnection($this->_module);
				return true;
			}
	}

	public function eliminarEnMetadatos($pIdtabla,$arrData)
	{
		$table  = Doctrine :: getTable('DatTabla')->find($pIdtabla);

		$this->_ciar->delete($table->esquema.".".$table->tabla,array("id".substr ($table->tabla, 4, strlen($table->tabla)-4) => $arrData["{$table->esquema}_{$table->tabla}_id".substr ($table->tabla, 4, strlen($table->tabla)-4)]));
		return true;
	}        


    public function obtenerCamposDadoTabla($idtabla)
    {
        $q = Doctrine_Query :: create ();
        return $q->from ('DatCampo f')
                     ->leftJoin('f.DatComponente cp')
                     ->select ('f.idcampo, f.denominacion, f.longitud, cp.etiqueta, ct.tabla, f.llave_primaria')
                     ->where ('f.idtabla = ?', $idtabla)
                     ->orderBy('cp.orden')
                     ->setHydrationMode(Doctrine :: HYDRATE_RECORD)
                     ->execute();
    }
    
    public function obtenerInfoTabla($idtable,$cond = array())
    {
        //Obtengo y cambio la conexion a la de metadatos
        $this->_manager->setCurrentConnection('xmetacomponent');

        //Si me envian el alias para la tabla pongo el alias sino por default el alias es meta
        //para eso garantizar que el alias no sea utilizado despues para otra tabla
        $alias = ($cond['alias']) ? $cond['alias']: 'meta';

        //Obtengo el objeto tabla dado el idtabla
        $table = Doctrine::getTable('DatTabla')->find($idtable);
        
        if(!$table) throw new Exception("La tabla no existe en metadatos.");

        //Recorro cada campo de la tabla
        foreach($table->DatCampo as $field) {
            //Si es tipo de componente es Nomenclador ( Combobox )
			if($field->DatComponente->idtipocomponente == 5)
                {
                    //Obtengo la tabla de configuracion del componente Combo
                    $tabla     = $field->DatComponente->NomTipoComponente->tabla;
                    //Obtengo el campo origen del nomenclador que seria el valuefield
                    $fieldOrig = $field->DatComponente->$tabla->DatRemoto->Origen->Origen;
                    //Obtengo el objeto tabla origen del nomenclador
                    $tableOrig = $fieldOrig->DatTabla;
                    //Obtengo la denominacion de los campos de la tabla origen y los inserto en el array de campos
                    $arrData->campos [] = "{$alias}_{$tableOrig->idtabla}.{$fieldOrig->denominacion} as {$table->esquema}_{$table->tabla}_{$field->denominacion}";
                    $arrData->campos [] = "{$alias}_{$tableOrig->idtabla}.{$field->DatComponente->$tabla->DatRemoto->campo_denominacion} as {$tableOrig->tabla}_{$field->DatComponente->$tabla->DatRemoto->campo_denominacion}";
                    //Realizo un inner join con el Active Record entre la tabla origen y la tabla destino por los campos que especifico
                    //el que creo el campo de tipo nomenclador
                    $this->_ciar = $this->_ciar->join("{$tableOrig->esquema}.{$tableOrig->tabla} {$alias}_{$tableOrig->idtabla}","{$alias}_{$tableOrig->idtabla}.{$fieldOrig->denominacion} = {$alias}.{$field->denominacion}",'inner');
                }
                else
                    //Sino es de tipo nomenclador
                    //Obtengo la denominacion del campo y lo inserto en el array de campos
                    $arrData->campos [] = "{$alias}.{$field->denominacion} as {$table->esquema}_{$table->tabla}_{$field->denominacion}";
            }
			
			$arrData->campos [] = "{$alias}.id".substr ($table->tabla, 4, strlen($table->tabla)-4)." as id";
            
			//Si la tabla tiene estructura arbolea
                if($table->arbol) {
                        $arrData->campos [] = "{$alias}.denominacion as text";
                        $arrData->campos [] = "CAST(({$alias}.ordender - {$alias}.ordenizq) = 1 as INTEGER) as leaf";
						//Obtengo el nodo padre que se envia como parametro de cual
                        //se desea obtener sus hijos
                        $idpadre = $cond['node'];
                        //Realizo una condicion con el Activo record
                        //Si envian como parametro el idpadre selecciono los nodos que su idpadre sea igual al enviado
                        //sino selecciono los nodos raices del arbol es decir su id es igual al idpadre
                        $where = (!$idpadre) ? "{$alias}.id".substr ($table->tabla, 4, strlen($table->tabla)-4)." = {$alias}.id".substr ($table->tabla, 4, strlen($table->tabla)-4)."padre" : "{$alias}.id".substr ($table->tabla, 4, strlen($table->tabla)-4)."padre = $idpadre AND id".substr ($table->tabla, 4, strlen($table->tabla)-4)." <> $idpadre";
                        $this->_ciar = $this->_ciar->where($where);
                    }
            //Realizo la seleccion de los campo anteriormente obtenidos y que se encuentran en el array de campos
            //para conformar el select le doy un implode con la ',' para separlos
            $this->_ciar->select(implode(',', $arrData->campos))
            //Conformo el from con el objeto obtenido de la tabla
                         ->from("{$table->esquema}.{$table->tabla} {$alias}");
            //Obtengo y devuelvo la conexion por la del modulo que solicita el active record
            $this->_manager->setCurrentConnection($this->_module);
            //Retorno el objeto active record conformado ya con la tabla recibida por parametro
            return $this->_ciar;
   }
   
   public function obtenerDataTablaCompuesta($values, $idcomponente, $record) {
		$this->_manager->setCurrentConnection('xmetacomponent');
		$componente = Doctrine::getTable('DatComponenteCompuesto')->find($idcomponente);
        $tablacomp  = $componente->NomTipoComponenteCompuesto->tabla;
		$tabladet   = Doctrine::getTable('DatTabla')->find($componente->$tablacomp->idtabladetalle);
		$tablagen   = Doctrine::getTable('DatTabla')->find($componente->$tablacomp->idtablacompuesta);
		$tablamas	= Doctrine::getTable('DatTabla')->find($componente->idtablamaster);
		
		$iddetalle = $tabladet->esquema."_".$tabladet->tabla."_id".substr ($tabladet->tabla, 4, strlen($tabladet->tabla)-4);
		$idmaster  = "id".substr ($tablamas->tabla, 4, strlen($tablamas->tabla)-4);
		$idtablamaster = $tablamas->esquema."_".$tablamas->tabla."_".$idmaster;
		
		for($i = 0; $i< count($values); $i++)
		{
			$values[$i]["checked"] = ($this->getCountTable($tablagen,"id".substr ($tabladet->tabla, 4, strlen($tabladet->tabla)-4)." = ".$values[$i][$iddetalle]." AND $idmaster = ".$record->$idtablamaster)>0) ? true : false;
		}
		
		$this->_manager->setCurrentConnection($this->_module);
		return  $values;
		
   }
   
   public function getCountTable($tabla, $cond = '') {
		$this->_manager->setCurrentConnection('xmetacomponent');
		if(is_numeric($tabla))$tabla = Doctrine::getTable('DatTabla')->find($tabla);
		if($cond) $this->_ciar = $this->_ciar->where($cond);
		$total = ($this->_ciar->select("id".substr ($tabla->tabla, 4, strlen($tabla->tabla)-4)." as total")
					          ->from("{$tabla->esquema}.{$tabla->tabla} {$alias}")
					          ->count_all_results());
		$this->_manager->setCurrentConnection($this->_module);
		return $total;
   }
   
   public function insertarComponentesCompuestos($values) {
	
		$_data = array();
		if(isset($values['valuesR'])) {
			foreach (json_decode(stripslashes($values['valuesR'])) as $key=>$value)
			{
				$_data[$values['llave_master']]  = $values['idmaster'];
				$componente = Doctrine::getTable('DatComponenteCompuesto')->find($key);
				$tablacomp  = $componente->NomTipoComponenteCompuesto->tabla;
				$tabladet   = Doctrine::getTable('DatTabla')->find($componente->$tablacomp->idtabladetalle);
				$tablagen   = Doctrine::getTable('DatTabla')->find($componente->$tablacomp->idtablacompuesta);
				foreach($value->checkeds as $ch)
				{
					$id = $tabladet->esquema."_".$tabladet->tabla."_id".substr ($tabladet->tabla, 4, strlen($tabladet->tabla)-4);
					if($ch->$id)
						$_data["id".substr ($tabladet->tabla, 4, strlen($tabladet->tabla)-4)] = $ch->$id;
					
					$this->_ciar->insert($tablagen->esquema.".".$tablagen->tabla,$_data);
				}
				foreach($value->uncheckeds as $unch)
				{
					$id = $tabladet->esquema."_".$tabladet->tabla."_id".substr ($tabladet->tabla, 4, strlen($tabladet->tabla)-4);
					$iddetalle = "id".substr ($tabladet->tabla, 4, strlen($tabladet->tabla)-4);
					$this->_ciar->where("$iddetalle = ".$unch->$id." AND ".$values['llave_master']." = ".$values['idmaster']);
					$this->_ciar->delete($tablagen->esquema.".".$tablagen->tabla);
				}
				unset($_data);
			}
		}
		return true;
   }

}
?>
