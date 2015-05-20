<?php

/**
 * ZendExt_SAML_Generate *
 * Clase que genera los id y la fecha de los xml en la comunicación SAML*
 *@ Daniel Enrique López Méndez * 
 *@Seguridad* (módulo)
 *@Autenticacion (submódulo)*
 *@copyright ERP Cuba*
 *@version 1.0*/
 
class ZendExt_SAML_Generate
    {

        function _construct()
        {

        }

        function samlCreateId() {
            $rndChars = 'abcdefghijklmnop123456789';
            $rndId = '';
            $numero = rand(1, 10000);
            for ($i = 0; $i < 40; $i++ )
                $rndId .= $rndChars[rand(0,strlen($rndChars)-1)];
            return 'Token:'.$rndId;
        }

        function samlGetDateTime($timestamp) {
            return gmdate('d-m-Y\TH:i:s\Z', $timestamp);
        	}

    }
?>
