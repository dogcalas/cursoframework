<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author pwilson
 */
interface componente3Interface {

    /**
     * Verifica si un usuario tiene acceso a una entidad.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identity - Identificador de la entidad a la que accedió el usuario.
     * @return integer
     */
    public function verificarAccessEntity($certificate, $identity);
     /**
     * Funcion para cambiar el password a un usuario.
     * 
     * @param string $usuario - nombre de usuario.
     * @param string $oldpass - Contrase�a vieja.
     * @param string $newpass - Nueva contrase�a.
     * @return integer - entero. 
     */
    public function CambiarPassword($usuario, $oldpass, $newpass);

    /**
     * Brinda el servicio de listar todos los sistemas a los que tiene acceso un usuario en una entidad.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la estructura.
     * @return json | null - Los sistemas de la entidad.
     */
    public function dameSystems($certificate, $identidad);

    /**
     * Elimina los certificados asociados a un usuario
     * 
     * @param string $user_id /id del usuario registrado en la session
     */
    public function eliminarUserCertificates($user_id);

    /**
     * Carga sistemas, subsistemas y funcionalidades.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $idsistema - Identificador del sistema.
     * @param integer $identidad - Identificador de la estructura.
     * @return json | null - Los subsistemas de un sistema o las funcionalidades de un subsistema.
     */
    public function dameSystemsFunctions($certificate, $idsistema, $identidad);

    /**
     * Obtener los modulos para el portal tipo escritorio.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la entidad a la que accedió el usuario.
     * @return array - Arreglo de modulos. 
     */
    public function dameSystemsFunctionsDesktopModules($certificate, $identidad);
}

?>
