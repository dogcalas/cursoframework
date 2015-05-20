<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of seguridadServices
 *
 * @author pwilson
 */
class seguridadServices implements SeguridadSeguridadInterface
{

    /**
     * verifyAccessEntity
     * Verifica si un usuario tiene acceso a una entidad.
     *
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identity - Identificador de la entidad a la que accedió el usuario.
     * @return integer
     */
    public function verifyAccessEntity($certificate, $identity)
    {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);
        $idusuario = $datos[0]->idusuario;
        $roles = SegRol::obtenerrolesusuarioentidad($idusuario, $identity)->toArray();
        if (count($roles)) {
            $datosrol['idrol'] = $roles[0]['id'];
            $datosrol['denominacion'] = $roles[0]['text'];

            $objcertificado = Doctrine::getTable('SegCertificado')->find($datos[0]->idcertificado);
            $objcertificado->rol = $datosrol['denominacion'];
            $integrator = ZendExt_IoC::getInstance();
            $estructura = $integrator->metadatos->DameEstructura($identity);
            $objcertificado->entidad = $estructura[0]->denominacion;
            $objcertificado->save();
            $_SESSION['denominacion_transaction'] = $datosrol['denominacion'];
            return $datosrol;
        } else
            return '0';
    }

    /**
     * getSystems
     * Brinda el servicio de listar todos los sistemas a los que tiene acceso un usuario en una entidad.
     *
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la estructura.
     * @return json | null - Los sistemas de la entidad.
     */
    public function getSystems($certificate, $identidad)
    {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);
        if (count($datos)) {
            $idusuario = $datos[0]->idusuario;
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario, $identidad);
            $rolusuarioentidad = $this->arregloBidimensionalToUnidimensionalRoles($rolusuarioentidad);

            $sistemaObj = DatSistema::obtenersistemas($rolusuarioentidad);
            $sistemaArr = array();
            foreach ($sistemaObj as $key => $sistema) {
                $sistemaArr[$key]['id'] = $sistema->idsistema;
                $sistemaArr[$key]['icono'] = ($sistema->icono) ? $sistema->icono : 'falta';
                $sistemaArr[$key]['titulo'] = $sistema->denominacion;
                $sistemaArr[$key]['externa'] = $sistema->externa;
            }
            return $sistemaArr;
        } else
            throw new ZendExt_Exception('SGIS003');
    }

    /**
     * Carga sistemas, subsistemas y funcionalidades.
     *
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $idsistema - Identificador del sistema.
     * @param integer $identidad - Identificador de la estructura.
     * @return json | null - Los subsistemas de un sistema o las funcionalidades de un subsistema.
     */
    public function getSystemsFunctions($certificate, $idsistema, $identidad)
    {
        $tmpidsistema = explode('_', $idsistema);
        if (count($tmpidsistema) >= 1)
            $idsistema = $tmpidsistema[0];
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);
        $idusuario = $datos[0]->idusuario;
        $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario, $identidad);
        $rolusuarioentidad = $this->arregloBidimensionalToUnidimensionalRoles($rolusuarioentidad);
        $idrol = $rolusuarioentidad;
        $acceso = DatSistema::verificarrolsistema($idrol, $idsistema);
        if (count($acceso)) {
            $sistemas = DatSistema::cargarsistemahijos($idsistema, $idrol);
            $externa = DatSistema::buscarservidorweb($idsistema);
            $contador = 0;
            $sistemafunArr = array();
            if (count($sistemas)) {
                foreach ($sistemas as $valores => $valor) {
                    $sistemafunArr[$contador]['id'] = $valor['id'] . '_' . $idsistema;
                    $sistemafunArr[$contador]['idsistema'] = $valor['id'];
                    $sistemafunArr[$contador]['externa'] = $valor['externa'];
                    $sistemafunArr[$contador]['icono'] = $valor['icono'];
                    $sistemafunArr[$contador]['text'] = $valor['text'];
                    $sistemafunArr[$contador]['servidores'] = $this->servidores($valor['id']);
                    $contador++;
                }
            }
            $funcionalidad = DatFuncionalidad::obtenerFuncionalidades($idsistema, $idrol);
            if (count($funcionalidad)) {
                foreach ($funcionalidad as $valores => $valor) {
                    $sistemafunArr[$contador]['id'] = $valor['id'] . '_' . $idsistema;
                    $sistemafunArr[$contador]['idfuncionalidad'] = $valor['id'];
                    if ($externa[0]->externa)
                        $sistemafunArr[$contador]['referencia'] = $valor['referencia'];
                    else
                        $sistemafunArr[$contador]['referencia'] = '../../../' . $valor['referencia'];
                    $sistemafunArr[$contador]['icono'] = $valor['icono'];
                    $sistemafunArr[$contador]['text'] = $valor['text'];
                    $sistemafunArr[$contador]['leaf'] = true;
                    $contador++;
                }
            }
            if (count($sistemafunArr))
                return $sistemafunArr;
            else
                return array();
        } else
            throw new ZendExt_Exception('SGIS003');
    }

    function arregloUnidimensionalRoles($rolusuarioentidad)
    {
        $array = array();
        foreach ($rolusuarioentidad as $rol)
            $array[] = $rol['idrol'];
        return $array;
    }

    private function esSistemaSauxe($idsistema, $arraysistema)
    {
        foreach ($arraysistema as $valor) {
            if ($valor == $idsistema)
                return true;
        }
        return false;
    }

    /**
     * Obtener los modulos para el portal tipo escritorio.
     *
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la entidad a la que accedió el usuario.
     * @return array - Arreglo de modulos.
     */
    public function getSystemsFunctionsDesktopModules($certificate, $identidad)
    {
        $resultado = array();

        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);        
        if (count($datos)) {
            $idusuario = $datos[0]->idusuario;
            $objentidaduserrol = new DatEntidadSegUsuarioSegRol();
            $array = $objentidaduserrol->obtenerrolusuarioentidad($idusuario, $identidad);            
            $arrayroles = $this->arregloBidimensionalToUnidimensional($array);            
            //echo'<pre>';print_r($identidad);die;
            $sistemas = DatSistema::arraysistemasDominio($arrayroles)->toArray();
            if (count($sistemas)) {
                foreach ($sistemas as $mdf) {
                    //if (!$this->esSistemaSauxe($mdf['idsistema'], $arraysistema)) {
                    $resultado[] = $this->subsistemasfunc($mdf, $arrayroles, $identidad, $idusuario);
                    //}
                }
               
                return $resultado;
            } else
                return array();
        } else
            throw new ZendExt_Exception('SGIS003');
    }

    private function arregloBidimensionalToUnidimensional($arrayroles)
    {
        $array = array();
        foreach ($arrayroles as $rol)
            $array[] = $rol['idrol'];
        return $array;
    }


    private function subsistemasfunc($raiz, $idrol, $identidad, $idusuario)
    {
        $result = array('icono' => $raiz['icono'], 'id' => $raiz['idsistema'], 'text' => $raiz['denominacion'], 'externa' => $raiz['externa'], 'menu' => $this->funcionalidades($raiz['idsistema'], $idrol, $raiz['externa'], $identidad, $idusuario) /*, 'servidores' => $this->servidores($raiz['idsistema'])*/);
         
        $sistemashijos = DatSistema::arraySistemasHijosDadoArrayRoles($raiz['idsistema'], $idrol);
        
        if (count($sistemashijos)) {
            foreach ($sistemashijos as $hijo) {
                if ($raiz['idsistema'] != $hijo['idsistema']) {
                    $result['menu'][] = $this->subsistemasfunc($hijo, $idrol, $identidad, $idusuario);
                }
            }
            return $result;
        } else
            return $result;
    }

    private function funcionalidades($idsistema, $idrol, $externa, $identidad, $idusuario)
    {
        
        $funcsistema = DatFuncionalidad::obtenerFuncionalidadesDadoArrayRoles($idsistema, $idrol);

        $funcionalidadesRest = DatFuncionalidadesRestringidasUsuarioEntidadRol:: obtenerFunResDadoArrayRoles($idsistema, $identidad, $idusuario, $idrol);

        if (count($funcionalidadesRest))
            $funcionalidadesRest = $this->arregloUnidimensionalFuncionalidades($funcionalidadesRest);
        //echo'<pre>';print_r($funcionalidadesRest);die;
        $arrayFuncionalidades = array();
        foreach ($funcsistema as $key => $funcionalidades) {
            if (!count($funcionalidadesRest) || !count(array_intersect($funcionalidadesRest, array($funcionalidades->idfuncionalidad)))) {
                $arrayFuncionalidades[$key]['text'] = $funcionalidades->text;
                $arrayFuncionalidades[$key]['id'] = $funcionalidades->idfuncionalidad;
                $arrayFuncionalidades[$key]['index'] = $funcionalidades->index;
                $arrayFuncionalidades[$key]['acceso'] = $funcionalidades->descripcion;
                if ($externa)
                    $arrayFuncionalidades[$key]['referencia'] = 'http://' . $externa . '/' . $funcionalidades->referencia;
                else
                    $arrayFuncionalidades[$key]['referencia'] = '../../../' . $funcionalidades->referencia;
            }
        }
        return $arrayFuncionalidades;
    }

    function arregloUnidimensionalFuncionalidades($arrayFuncionalidades)
    {
        $array = array();
        foreach ($arrayFuncionalidades as $est)
            $array[] = $est['idfuncionalidad'];
        return $array;
    }

    /**
     * Obtener los modulos para el portal tipo escritorio.
     *
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la entidad a la que accedió el usuario.
     * @return array - Arreglo de modulos.
     */
    public function getSystemsDesktopModules($certificate, $identidad)
    {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);
        if (count($datos)) {
            $idusuario = $datos[0]->idusuario;
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario, $identidad);
            $sistemas = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol)->toArray(true);
            if (count($sistemas)) {
                foreach ($sistemas as $mdf)
                    $resultado[] = $this->subsistemas($mdf, $rolusuarioentidad[0]->idrol);
                return $resultado;
            } else
                return array();
        } else
            throw new ZendExt_Exception('SGIS003');
    }

    private function subsistemas($raiz, $idrol)
    {
        $result = array('icono' => $raiz['icono'], 'id' => $raiz['idsistema'], 'text' => $raiz['denominacion'], 'externa' => $raiz['externa'], 'servidores' => $this->servidores($raiz['idsistema']));
        $sistemashijos = DatSistema::cargarsistemahijos($raiz['idsistema'], $idrol);
        if (count($sistemashijos)) {
            foreach ($sistemashijos as $hijo) {
                if ($raiz['idsistema'] != $hijo['idsistema']) {
                    $result['menu'][] = $this->subsistemas($hijo, $idrol);
                }
            }
            return $result;
        } else
            return $result;
    }

    private function servidores($idsistema)
    {
        return array(); /*         * *ARREGLAR** */
        $obj = new DatSistemaDatServidores();
        $esquemassistema = $obj->obtenersistemacompleto($idsistema);
        foreach ($esquemassistema as $valor1) {
            $arrayresult['idsistema'] = $valor1['idsistema'];
            $arrayresult['denominacion'] = $valor1['denominacion'];
            if (isset($valor1['DatServidor'][0]))
                $arrayresult['servidor'] = $valor1['DatServidor'][0]['denominacion'];
            else
                $arrayresult['servidor'] = '';
            if (isset($valor1['DatGestor'][0]))
                $arrayresult['gestor'] = $valor1['DatGestor'][0]['gestor'];
            else
                $arrayresult['gestor'] = '';
            $arraybd = array();
            foreach ($valor1['DatBd'] as $bd) {
                $arraybd[] = $bd['denominacion'];
                foreach ($valor1['DatEsquema'] as $esquemas) {
                    $arrayesquemas[] = $esquemas['denominacion'];
                }
                $arrayresult['esquemas'] = $arrayesquemas;
            }
            $arrayresult['bd'] = $arraybd;
        }
        return $arrayresult;
    }

    /**
     * Elimina los certificados asociados a un usuario
     *
     * @param string $user_id /id del usuario registrado en la session
     */
    public function deleteUserCertificates($user_id)
    {
        $segCertificado = new SegCertificado();
        $certificados = $segCertificado->existecertificado($user_id);
        foreach ($certificados as $certificado) {
            Doctrine::getTable('SegCertificado')->find($certificado['idcertificado'])->delete();
        }
        return;
    }

    /**
     * Funcion para cambiar el password a un usuario.
     *
     * @param string $usuario - nombre de usuario.
     * @param string $oldpass - Contrase�a vieja.
     * @param string $newpass - Nueva contrase�a.
     * @return integer - entero.
     */
    public function ChangePassword($usuario, $oldpass, $newpass)
    {
        $verificar = SegUsuario::verificarpass($usuario);
        $oldpass = md5($oldpass);
        if ($verificar[0]->contrasenna == $oldpass) {
            if ($this->verificarpass($newpass)) {
                $objusuario = Doctrine::getTable('SegUsuario')->find($verificar[0]->idusuario);
                $objusuario->contrasenna = md5($newpass);
                $objusuario->contrasenabd = md5($newpass);
                $objusuario->save();
                return 1;
            } else {
                throw new ZendExt_Exception('SEG020');
            }
        } else {
            throw new ZendExt_Exception('SEG013');
        }
    }

    /* public function endTransaction() {
      $doctrineManager = Doctrine_Manager::getInstance();
      $doctrineManager->setCurrentConnection($this->activeconn);
      }

      public function startTransaction() {
      $doctrineManager = Doctrine_Manager::getInstance();
      try {
      $conn = $doctrineManager->getConnection('seguridad');
      } catch (Doctrine_Manager_Exception $e) {
      $transactionManager = ZendExt_Aspect_TransactionManager::getInstance();
      $configApp = new ZendExt_App_Config();
      $configApp->addBdToConfig('seguridad', 'seguridad/');
      $conn = $transactionManager->openConections('seguridad');
      }
      $activeconn = $doctrineManager->getCurrentConnection()->getName();
      $doctrineManager->setCurrentConnection('seguridad');
      $this->activeconn=$activeconn;
      } */
}

?>
