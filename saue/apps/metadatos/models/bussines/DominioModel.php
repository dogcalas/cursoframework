<?php
class  DominioModel extends ZendExt_Model
{

    public function DominioModel()
    {
        parent::ZendExt_Model();
        $this->instance = new NomDominio();
    }

    /**
     * Insertar dominio con muneros binarios modificado por Raciel Garrido Torres
     *
     * @param string $nombre
     * @param string $descripcion
     * @param array $arrayEstructuras
     * @return int
     */

    public function InsertarDominio($nombre, $descripcion, $arrayEstructuras)
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');

        $idDominio = $this->buscaridproximo();
        $idpadre = $this->global->Perfil->iddominio;
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $conn->beginTransaction();
        $conn->execute("INSERT INTO mod_estructuracomp.nom_dominio
						(iddominio,denominacion,descripcion,dominio,idpadre) values
						($idDominio,'$nombre','$descripcion','{1}', '$idpadre') ");
        $conn->commit();
        $this->ActualizarBinarios($conn, $idDominio, $arrayEstructuras);
        return $idDominio;
    }


    /**
     * Modificar dominio (Moficicado por Raciel Garrido Torres para el trabajo con binarios)
     *
     * @param int $iddominio
     * @param string $nombre
     * @param string $descripcion
     * @param array $arrayEstructuras
     * @return bool
     */
    public function ModificarDominio($iddominio, $nombre, $descripcion, $arrayEstructuras)
    {


        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $sqlActualiza = "UPDATE   mod_estructuracomp.nom_dominio
						   	SET 
									dominio      = '{1}' ,
									denominacion = '$nombre' ,
									descripcion  = '$descripcion'
							WHERE   iddominio    = '$iddominio' ";
        $conn->execute($sqlActualiza);
        //$this->LimpiarBinarios($conn, $iddominio);
        $this->ActualizarBinarios($conn, $iddominio, $arrayEstructuras);
        return true;
    }

    /** -----------------------------------------
     * Eliminar un campo de la base de datos
     *
     * @param int $pCampo
     * @return bool
     */
    public function EliminarDominio($iddominio)
    {
        try {
            $query = Doctrine_Query::create();
            // eliminar el campo
            $result = $query->delete('iddominio')
                ->from('NomDominio')
                ->where("iddominio = '$iddominio'")
                ->execute();
            return $result != 0;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());
            return false;
        }
    }

    public function BuscarDomios($limit, $start, $arrayResult)
    {
        $q = Doctrine_Query::create();
        $resultTotal = $q->select('count(iddominio) as cant')->from('NomDominio')->whereIn('iddominio', $arrayResult)->execute();
        $result = $q->select('d.iddominio, d.denominacion, d.seguridad, d.descripcion')
            ->from('NomDominio d')
            ->whereIn('d.iddominio', $arrayResult)
            ->limit($limit)
            ->offset($start)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return array('cant' => $resultTotal[0]->cant, 'datos' => $result);
    }

    public function cargarComboDominioBuscar($filtroDominio)
    {
        $q = Doctrine_Query::create();
        $resultTotal = $q->select('count(iddominio) as cant')->from('NomDominio')->whereIn('iddominio', $filtroDominio)->execute();
        $result = $q->select('d.iddominio, d.denominacion, d.descripcion')
            ->from('NomDominio d')
            ->whereIn('d.iddominio', $filtroDominio)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return array('cant' => $resultTotal[0]->cant, 'datos' => $result);
    }

    public function BuscarIdDominioHijos()
    {
        $iddominio = $this->global->Perfil->iddominio;
        $q = Doctrine_Query::create();
        $result = $q->select('d.iddominio')
            ->from('NomDominio d')
            ->where('d.idpadre = ?', $iddominio)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    public function BuscarIdDominioPadre()
    {
        $iddominio = $this->global->Perfil->iddominio;
        $q = Doctrine_Query::create();
        $result = $q->select('d.iddominio')
            ->from('NomDominio d')
            ->where('d.iddominio = ?', $iddominio)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return $result;
    }

    function BuscarDominios($limit, $start)
    {
        $idpadre = $this->global->Perfil->iddominio;
        $q = Doctrine_Query::create();
        $resultTotal = $q->select('count(iddominio) as cant')->from('NomDominio')->where("idpadre= ? and iddominio <> idpadre", $idpadre)->execute();
        $result = $q->select('d.iddominio, d.denominacion, d.seguridad, d.descripcion')
            ->from('NomDominio d')
            ->where("d.idpadre= ? and d.iddominio <> d.idpadre", $idpadre)
            ->limit($limit)
            ->offset($start)
            ->setHydrationMode(Doctrine :: HYDRATE_ARRAY)
            ->execute();
        return array('cant' => $resultTotal[0]->cant, 'datos' => $result);
    }

    /** -----------------------------------------
     * Buscar el proximo id a escribir
     *
     * @return int
     */
    public function buscaridproximo()
    {

        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $query = Doctrine_Query::create($conn);
        // eliminar el campo
        $result = $query->select('max(a.iddominio) as maximo')
            ->from('NomDominio a')
            ->execute()
            ->toArray();

        $proximo = isset($result[0]['maximo']) ? $result[0]['maximo'] + 1 : 1;
        return $proximo;
    }

    public function BuscarIdDominioSeguridad()
    {
        try {
            $query = Doctrine_Query::create();
            $result = $query->select('a.iddominio')
                ->from('NomDominio a')
                ->where('a.seguridad = 1')
                ->execute()
                ->toArray();
            return $result[0]['iddominio'];
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                die(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }

    function cargarArbolDominios($iddominio)
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $iddominiousuario = $this->global->Perfil->iddominio;
        if ($iddominio)
            $where = "idpadre = $iddominio and idpadre <> iddominio";
        else {
            if ($iddominiousuario == 1)
                $where = "idpadre = iddominio and iddominio = $iddominiousuario";
            else
                $where = "iddominio = $iddominiousuario";
        }
        $sql = "SELECT
				  dominio.iddominio AS id,
				  dominio.iddominio,
				  dominio.denominacion AS text,
				  false as checked,
				  dominio.rgt - dominio.lft = 1 as leaf
				  FROM mod_estructuracomp.nom_dominio dominio												
				  where $where";
        $arbolDominio = $conn->fetchAll($sql);
        return $arbolDominio;
    }

    function buscarArbolHijosDominio($arrayPadres)
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $primero = true;
        foreach ($arrayPadres as $id) {
            if ($primero) {
                $sqlWhere = "((dominio.lft > (select d.lft from nom_dominio d where d.iddominio = $id)
				and dominio.lft < (select d.rgt from nom_dominio d where d.iddominio = $id))";
                $primero = false;
            } else {
                $sqlWhere .= "or (dominio.lft > (select d.lft from nom_dominio d where d.iddominio = $id)
				and dominio.lft < (select d.rgt from nom_dominio d where d.iddominio = $id))";
            }
        }
        if ($sqlWhere)
            $sqlWhere .= ')';
        $sql = "select distinct dominio.iddominio,
				dominio.denominacion				
				from nom_dominio dominio
				where
				$sqlWhere";
        $arreglo = $conn->fetchAll($sql);
        return $arreglo;
    }

    function arregloEstructuras($iddominio)
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $arregloPermiso = $conn->fetchAll('select idfila
												FROM mod_estructuracomp.nom_filaestruc fila 
													
												where 
												 fila.dominiofila[' . $iddominio . '] = 1 :: bit
												');
        $elementos = array();
        foreach ($arregloPermiso as $idfila) {
            $elementos[] = $idfila['idfila'];
        }
        return $elementos;
    }

    function buscarArbolHijosEstructuras($arrayPadres)
    {
        $iddominio = $this->global->Perfil->iddominio;
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $primero = true;
        foreach ($arrayPadres as $id) {
            if ($primero) {
                $sqlWhere = "and ((fila.lft > (select f.lft from mod_estructuracomp.nom_filaestruc f where f.idfila = $id)
				and fila.lft < (select f.rgt from mod_estructuracomp.nom_filaestruc f where f.idfila = $id))";
                $primero = false;
            } else {
                $sqlWhere .= "or (fila.lft > (select f.lft from mod_estructuracomp.nom_filaestruc f where f.idfila = $id)
				and fila.lft < (select f.rgt from mod_estructuracomp.nom_filaestruc f where f.idfila = $id))";
            }
        }
        if ($sqlWhere)
            $sqlWhere .= ')';
        $sql = "select distinct fila.idfila as idestructura
				from mod_estructuracomp.nom_filaestruc fila
				where
				fila.dominiofila[ '$iddominio'] = 1 :: bit
				$sqlWhere";
        $arreglo = $conn->fetchAll($sql);
        return $arreglo;
    }

    function buscarHijosEstructuras($iddominiopermiso = false, $idestructura = false, $checked = false, $checkear = false, $arrayTiposEAV = false)
    {
        $iddominio = $this->global->Perfil->iddominio;
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        // verificar el dominio con el cual se va a trabajar
        $iddominio = $iddominio ? $iddominio : 1;
        $checkear = $checkear ? '1' : '0';
        if ($iddominiopermiso)
            $checked = ($checked) ? "(select(nom_filaestruc.dominiofila['$iddominiopermiso'] = 1 :: bit)
								from  mod_estructuracomp.nom_filaestruc
								where 
								 nom_filaestruc.idfila = fila.idfila) as checked," : '';
        else
            $checked = ($checked) ? 'false as checked,' : '';
        $SQLwhere = ($idestructura) ? "fila.idpadre='$idestructura' AND fila.idfila<>fila.idpadre " : "fila.idfila=fila.idpadre";
        if ($arrayTiposEAV == 1) { //Externas
            $case = "estructura.denominacion as text";
            $join = "inner join mod_estructuracomp.dat_estructura estructura on (fila.idfila = estructura.idestructura )";
        } elseif ($arrayTiposEAV == 2) { //Internas
            $case = "estructuraop.denominacion as text";
            $join = "inner join mod_estructuracomp.dat_estructuraop estructuraop on (fila.idfila = estructuraop.idestructuraop )";
        } else { //Ambas
            $case = "case when estructura.denominacion <> '' then estructura.denominacion
						when estructuraop.denominacion <> '' then estructuraop.denominacion 
					end as text";
            $join = "left join mod_estructuracomp.dat_estructura estructura on (fila.idfila = estructura.idestructura )
					 left join mod_estructuracomp.dat_estructuraop estructuraop on (fila.idfila = estructuraop.idestructuraop )";
        }
        $a = "select idfila as id,
											$checked
											fila.dominiofila[$iddominio] = 0 :: bit as unchecked,
											fila.idpadre as _parent,
											fila.rgt - fila.lft = 1 as leaf,
											$case
											FROM mod_estructuracomp.nom_filaestruc fila
											$join
											
											where
											$SQLwhere
											
											and (fila.dominiofila[$iddominio] = 1 :: bit or (select count(distinct f.idfila)
												from mod_estructuracomp.nom_filaestruc f
												where
												(f.lft > (select f1.lft from mod_estructuracomp.nom_filaestruc f1 where f1.idfila = fila.idfila)
												and f.lft < (select f1.rgt from mod_estructuracomp.nom_filaestruc f1 where f1.idfila = fila.idfila))

												and f.dominiofila[$iddominio] = 1 :: bit) > 0)
												
												";

        $arregloPermiso = $conn->fetchAll($a);
        return $arregloPermiso;

        // buscar todas las estructuras pertenecientes al dominio asignado

    }

    function buscarHijosEstructurasDadoArray($iddominio, $idestructura = false, $checked = false, $arrayIdestructura = array())
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        if (count($arrayIdestructura))
            $checked = ($checked) ? "case when idfila in (" . implode(',', $arrayIdestructura) . ") then true else false end as checked," : '';
        else
            $checked = ($checked) ? 'false as checked,' : '';
        $SQLwhere = ($idestructura) ? "fila.idpadre='$idestructura' AND fila.idfila<>fila.idpadre " : "fila.idfila=fila.idpadre";
        $case = "case when estructura.denominacion <> '' then estructura.denominacion
					when estructuraop.denominacion <> '' then estructuraop.denominacion 
				 end as text";
        $sqlNuevo = "select fila.idfila as id,fila.idpadre as _parent,
					$checked
					fila.dominiofila[$iddominio] = 0 :: bit as unchecked,
					fila.lft as _lft,fila.rgt as _rgt,
					(fila.rgt - fila.lft = 1 ) as leaf,
					$case
					FROM 
					mod_estructuracomp.nom_filaestruc fila
					LEFT JOIN  mod_estructuracomp.dat_estructura estructura ON ( estructura.idestructura = fila.idfila)
					LEFT JOIN  mod_estructuracomp.dat_estructuraop estructuraop ON ( estructuraop.idestructuraop = fila.idfila)
					where 
					$SQLwhere
					AND (fila.dominiofila[$iddominio] = 1 :: bit
					OR 
						(
							select count (otros.idfila) > 0
							FROM
							mod_estructuracomp.nom_filaestruc otros 
							where
							$SQLwhere
							AND otros.dominiofila[$iddominio] = 1 :: bit
							AND otros.lft > fila.lft 
							AND otros.lft < fila.rgt
							  
						))
					"; //die($sqlNuevo);
        $arregloPermiso = $conn->fetchAll($sqlNuevo);
        return $arregloPermiso;

    }

    function buscarIdEstructurasDominio($iddominio, $arrayIdestructura, $tipoEAV)
    {
        if (!$iddominio)
            $iddominio = $this->global->Perfil->iddominio;
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        if ($tipoEAV == 1) { //Externas
            $case = "estructura.denominacion as text";
            $join = "inner join mod_estructuracomp.dat_estructura estructura on (fila.idfila = estructura.idestructura )";
        } elseif ($tipoEAV == 2) { //Internas
            $case = "estructuraop.denominacion as text";
            $join = "inner join mod_estructuracomp.dat_estructuraop estructuraop on (fila.idfila = estructuraop.idestructuraop )";
        } else { //Ambas
            $case = "case when estructura.denominacion <> '' then estructura.denominacion
						when estructuraop.denominacion <> '' then estructuraop.denominacion 
					end as text";
            $join = "left join mod_estructuracomp.dat_estructura estructura on (fila.idfila = estructura.idestructura )
					 left join mod_estructuracomp.dat_estructuraop estructuraop on (fila.idfila = estructuraop.idestructuraop )";
        }
        if (count($arrayIdestructura))
            $where = "and idfila in (" . implode(',', $arrayIdestructura) . ")";
        else $where = '';
        $sql = "select distinct fila.idfila, fila.idfila as idestructura,
				$case
				from mod_estructuracomp.nom_filaestruc fila
				$join
				
				where
				fila.dominiofila[$iddominio] = 1 :: bit
				$where";

        return $conn->fetchAll($sql);
    }

    function cantIdEstructurasDominio($iddominio, $arrayIdestructura, $tipoEAV)
    {
        if (!$iddominio)
            $iddominio = $this->global->Perfil->iddominio;
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        if ($tipoEAV == 1) //Externas
            $join = "inner join mod_estructuracomp.dat_estructura estructura on (fila.idfila = estructura.idestructura )";
        elseif ($tipoEAV == 2) //Internas
            $join = "inner join mod_estructuracomp.dat_estructuraop estructuraop on (fila.idfila = estructuraop.idestructuraop )";
        else //Ambas
            $join = "left join mod_estructuracomp.dat_estructura estructura on (fila.idfila = estructura.idestructura )
					 left join mod_estructuracomp.dat_estructuraop estructuraop on (fila.idfila = estructuraop.idestructuraop )";
        if (count($arrayIdestructura))
            $where = "and idfila in (" . implode(',', $arrayIdestructura) . ")";
        else $where = '';
        $sql = "select count(fila.idfila) as cant
				from mod_estructuracomp.nom_filaestruc fila
				$join
				where
				 fila.dominiofila[$iddominio] = 1 :: bit  
				$where ";
        return $conn->fetchAll($sql);
    }

    /** --------------------------------------------
     * Borrar la instancia vieja que poseia el modelo
     *
     */
    public function Instancia()
    {
        $this->instance = null;
        $this->instance = new NomDominio();
    }


    /**
     * Anadir una estructura al dominio
     *
     * @param int $iddominio
     * @param int $dominio
     * @return bool
     */
    public function anadirEstructura($iddominio, $idestructura)
    {

        $this->instance = Doctrine::getTable('NomDominio')->find($iddominio);

        $elementos = $this->arregloEstructuras($iddominio);
        if (array_search($idestructura, $elementos) !== false) {
            return false;
        }

        $this->instance->dominiostring = $this->instance->dominiostring . ',' . $idestructura;
        try {
            $this->instance->save();

            return true;
        } catch (Doctrine_Exception $ee) {
            //if(DEBUG_ERP)
            echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    public function ExtraerEstructura($iddominio, $idestructura)
    {
        $this->instance = Doctrine::getTable('NomDominio')->find($iddominio);

        $elementos = $this->arregloEstructuras($iddominio);
        if (($posicion = array_search($idestructura, $elementos)) !== false) {
            unset($elementos[$posicion]);
            $this->instance->dominiostring = implode(',', $elementos);

        } else
            return false;
        try {
            $this->instance->save();
            return true;
        } catch (Doctrine_Exception $ee) {
            if (DEBUG_ERP)
                echo(__FILE__ . ' ' . __LINE__ . ' ' . $ee->getMessage());

            return false;
        }
    }


    public function perteneceEstructura($iddominio, $idestructura)
    {
        ///$this->instance = $this->conn->getTable('NomDominio')->find($iddominio);

        $elementos = $this->arregloEstructuras($iddominio);
        if (array_search($idestructura, $elementos) !== false) {
            return true;
        }

        return false;
    }

    public function ListarEstructuras($iddominio)
    {
        return array();
    }

    public function ListarEstructurasDadoArrayId($elementos)
    {
        return array();
    }

    public function ListarEstructurasExternasDadoArrayId($elementos)
    {
        return array();
    }


    public function ListarEstructuraSinCheked($iddominio)
    {
        return array();
    }

    function DominiosPorEstructura($idestructura)
    {
        $mg = ZendExt_Aspect_TransactionManager::getInstance();
        $conn = $mg->getConnection('metadatos');
        $arregloPermiso = $conn->fetchAll('select dominio.*
											FROM mod_estructuracomp.nom_filaestruc fila ,
											mod_estructuracomp.nom_dominio dominio 
												
											where 
											fila.idfila = ' . $idestructura . '
											AND fila.dominiofila[dominio.iddominio] = 1 :: bit  
											');
        return $arregloPermiso;

    }

    public function DatosDominioDadoID($iddominio)
    {

        $q = Doctrine_Query::create();
        $result = $q->from('NomDominio d ')
            ->where("d.iddominio = ?", $iddominio)
            ->execute();
        $resultado = $result->toArray();
        return $resultado;

    }

    public function ObtenerEstructurasNoFormales($iddominio)
    {
        return array();
    }


    public function ObtenerEstructurasPorIdOrgano($iddominio, $idorgano)
    {
        return array();
    }

    public function ActualizarBinarios($conn, $idDominio, $arrayEstructuras)
    {
        // -- insercion de los padres nuevos
        if (count($arrayEstructuras['padres']) > 0) {
            // buscar los izquierdos y derechos
            $filas = $conn->fetchAll("SELECT lft,rgt FROM
    					mod_estructuracomp.nom_filaestruc f
    					where

    					f.idfila in (" . implode(',', $arrayEstructuras['padres']) . ") 
    					 
    					");
            $i = 1;
            $cantidad = count($filas);
            $sqlQHERE = '  ';

            foreach ($filas as $fila) {
                if ($i == $cantidad)
                    $sqlQHERE .= " ( lft > {$fila['lft']} AND rgt < {$fila['rgt']} ) ";
                else
                    $sqlQHERE .= " ( lft > {$fila['lft']} AND rgt < {$fila['rgt']} ) OR";
                $i++;
            }

            /*$sqlActualiza	= "update
                        mod_estructuracomp.nom_filaestruc

                        set  dominiofila[$idDominio] = 0::bit
                        where dominiofila[$idDominio] = 1::bit  AND
                            ($sqlQHERENOT)
                        ;";
            //echo $sqlActualiza;
            $conn->execute($sqlActualiza);*/
            $sqlActualiza = "update
    					mod_estructuracomp.nom_filaestruc 
    					 
    					set  dominiofila[$idDominio] = 1::bit 
    					where dominiofila[$idDominio] = 0::bit  AND ( $sqlQHERE ) 
    					
    					";
            //die($sqlActualiza);
            $conn->execute($sqlActualiza);

        }
        // --  todo lo que esta marcado
        if (count($arrayEstructuras['estructuras']) > 0) {

            $sdqlActualiza = "update mod_estructuracomp.nom_filaestruc
    					set  dominiofila[$idDominio] = 1::bit 
    					WHERE 
    					dominiofila[$idDominio] = 0::bit   AND idfila in (" . implode(',', $arrayEstructuras['estructuras']) . ") 
    					; ";
            $conn->execute($sdqlActualiza);
            //echo $sdqlActualiza;
        }
        // --  todas las que no esten marcadas eliminarlas
        if (count($arrayEstructuras['arrayEntidadesEliminar']) > 0) { // estructuras a eliminar

            $sdqlActualiza = "update mod_estructuracomp.nom_filaestruc
    					set  dominiofila[$idDominio] = 0::bit 
    					WHERE 
    					dominiofila[$idDominio] = 1::bit   AND idfila in (" . implode(',', $arrayEstructuras['arrayEntidadesEliminar']) . ")
    					  ";
            $conn->execute($sdqlActualiza);
            //echo $sdqlActualiza;
        }

        // -- Arreglo de los padres que se van a eliminar (elimina todos los hijos de las entidades marcadas)
        if (count($arrayEstructuras['arrayPadresEliminar']) > 0) {
            // buscar los izquierdos y derechos
            $filas = $conn->fetchAll("SELECT lft,rgt FROM
    					mod_estructuracomp.nom_filaestruc f
    					where

    					f.idfila in (" . implode(',', $arrayEstructuras['arrayPadresEliminar']) . ") 
    					 
    					");
            $i = 1;
            $cantidad = count($filas);
            $sqlQHERE = '  ';

            foreach ($filas as $fila) {
                if ($i == $cantidad)
                    $sqlQHERE .= " ( lft > {$fila['lft']} AND rgt < {$fila['rgt']} ) ";
                else
                    $sqlQHERE .= " ( lft > {$fila['lft']} AND rgt < {$fila['rgt']} ) OR";
                $i++;
            }

            /*$sqlActualiza	= "update
                        mod_estructuracomp.nom_filaestruc

                        set  dominiofila[$idDominio] = 0::bit
                        where dominiofila[$idDominio] = 1::bit  AND
                            ($sqlQHERENOT)
                        ;";
            //echo $sqlActualiza;
            $conn->execute($sqlActualiza);*/
            $sqlActualiza = "update
    					mod_estructuracomp.nom_filaestruc 
    					 
    					set  dominiofila[$idDominio] = 0::bit 
    					where dominiofila[$idDominio] = 1::bit  AND ( $sqlQHERE ) 
    					
    					";
            //	die($sqlActualiza);
            $conn->execute($sqlActualiza);
        }

        // die();
    }

    public function LimpiarBinarios($conn, $idDominio)
    {

        $conn->execute("update mod_estructuracomp.nom_filaestruc
    					set  dominiofila[$idDominio] = 0::bit 
    					
    					");
    }

    //cantidad de dominios
    public function cantidadDominios()
    {
        $query = Doctrine_Query::create();
        $dominios = $query->select('count(iddominio) as cant')->from('NomDominio')->where('iddominio <> 1')->execute();
        return $dominios[0]->cant;

    }


}

?>
