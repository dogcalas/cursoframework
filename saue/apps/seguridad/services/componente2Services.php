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
class seguridadServices implements SeguridadSeguridadInterface {

    /**
     * verifyAccessEntity
     * Verifica si un usuario tiene acceso a una entidad.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identity - Identificador de la entidad a la que accedió el usuario.
     * @return integer
     */
    public function verifyAccessEntity($certificate, $identity) {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);
        $idusuario = $datos[0]->idusuario;
        $roles = SegRol::obtenerrolesusuarioentidad($idusuario, $identity)->toArray();
        if (count($roles)) {
            $datosrol['idrol'] = $roles[0]['id'];
            $datosrol['denominacion'] = $roles[0]['text'];
            return $datosrol;
        }
        else
            return '0';
    }
/**
     * verifyAccessEntity
     * Verifica si un usuario tiene acceso a una entidad.
     *
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identity - Identificador de la entidad a la que accedió el usuario.
     * @return integer
     */
    public function insertUser($certificate, $identity) {
      //  Trabajar aqui
        $objcertificado = new SegU();
        $datos = $objcertificado->verificarcertificado($certificate);
        $idusuario = $datos[0]->idusuario;
        $roles = SegRol::obtenerrolesusuarioentidad($idusuario, $identity)->toArray();
        if (count($roles)) {
            $datosrol['idrol'] = $roles[0]['id'];
            $datosrol['denominacion'] = $roles[0]['text'];
            return $datosrol;
        }
        else
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
    public function getSystems($certificate, $identidad) {
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
        }
        else
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
    public function getSystemsFunctions($certificate, $idsistema, $identidad) {
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
                        $sistemafunArr[$contador]['referencia'] = 'http://' . $externa[0]['externa'] . '/' . $valor['referencia'];
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
        }
        else
            throw new ZendExt_Exception('SGIS003');
    }

    /**
     * Obtener los modulos para el portal tipo escritorio.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la entidad a la que accedió el usuario.
     * @return array - Arreglo de modulos. 
     */
    public function getSystemsFunctionsDesktopModules($certificate, $identidad) {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);
        if (count($datos)) {
            $idusuario = $datos[0]->idusuario;
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario, $identidad);
            $sistemas = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol)->toArray();
            if (count($sistemas)) {
                foreach ($sistemas as $mdf)
                    $resultado[] = $this->subsistemasfunc($mdf, $rolusuarioentidad[0]->idrol);
                return $resultado;
            }
            else
                return array();
        }
        else
            throw new ZendExt_Exception('SGIS003');
    }

    private function subsistemasfunc($raiz, $idrol) {
        $result = array('icono' => $raiz['icono'], 'id' => $raiz['idsistema'], 'text' => $raiz['denominacion'], 'externa' => $raiz['externa'], 'menu' => $this->funcionalidades($raiz['idsistema'], $idrol, $raiz['externa']), 'servidores' => $this->servidores($raiz['idsistema']));
        $sistemashijos = DatSistema::cargarsistemahijos($raiz['idsistema'], $idrol);
        if (count($sistemashijos)) {
            foreach ($sistemashijos as $hijo) {
                if ($raiz['idsistema'] != $hijo['idsistema']) {
                    $result['menu'][] = $this->subsistemasfunc($hijo, $idrol);
                }
            }
            return $result;
        }
        else
            return $result;
    }

    private function funcionalidades($idsistema, $idrol, $externa) {
        $funcsistema = DatFuncionalidad::obtenerFuncionalidades($idsistema, $idrol);
        $arrayFuncionalidades = array();
        foreach ($funcsistema as $key => $funcionalidades) {
            $arrayFuncionalidades[$key]['text'] = $funcionalidades->text;
            $arrayFuncionalidades[$key]['id'] = $funcionalidades->idfuncionalidad;
            $arrayFuncionalidades[$key]['index'] = $funcionalidades->index;
            if ($externa)
                $arrayFuncionalidades[$key]['referencia'] = 'http://' . $externa . '/' . $funcionalidades->referencia;
            else
                $arrayFuncionalidades[$key]['referencia'] = '../../../' . $funcionalidades->referencia;
        }
        return $arrayFuncionalidades;
    }

    /**
     * Obtener los modulos para el portal tipo escritorio.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la entidad a la que accedió el usuario.
     * @return array - Arreglo de modulos. 
     */
    public function getSystemsDesktopModules($certificate, $identidad) {
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
            }
            else
                return array();
        }
        else
            throw new ZendExt_Exception('SGIS003');
    }

    private function subsistemas($raiz, $idrol) {
        $result = array('icono' => $raiz['icono'], 'id' => $raiz['idsistema'], 'text' => $raiz['denominacion'], 'externa' => $raiz['externa'], 'servidores' => $this->servidores($raiz['idsistema']));
        $sistemashijos = DatSistema::cargarsistemahijos($raiz['idsistema'], $idrol);
        if (count($sistemashijos)) {
            foreach ($sistemashijos as $hijo) {
                if ($raiz['idsistema'] != $hijo['idsistema']) {
                    $result['menu'][] = $this->subsistemas($hijo, $idrol);
                }
            }
            return $result;
        }
        else
            return $result;
    }

    private function servidores($idsistema) {
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
    public function deleteUserCertificates($user_id) {
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
    public function ChangePassword($usuario, $oldpass, $newpass) {
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
