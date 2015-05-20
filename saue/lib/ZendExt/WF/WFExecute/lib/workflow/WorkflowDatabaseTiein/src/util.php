<?php
/**
 * File containing the ezcWorkflowDatabaseUtil class.
 *
 * @package WorkflowDatabaseTiein
 * @version 1.3.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * Utility methods for WorkflowDatabaseTiein.
 *
 * @package WorkflowDatabaseTiein
 * @version 1.3.1
 * @access private
 */
abstract class ezcWorkflowDatabaseUtil
{
    /**
     * Wrapper for serialize() that returns an empty string
     * for empty arrays and null values.
     *
     * @param  mixed $var
     * @return string
     */
    public static function serialize( $var )
    {
        $var = serialize( $var );

        if ( $var == 'a:0:{}' || $var == 'N;' )
        {
            return '';
        }

        $serialized = base64_encode( $var );
        return $serialized;
    }

    /**
     * Wrapper for unserialize().
     *
     * @param  string $serializedVar
     * @param  mixed  $defaultValue
     * @return mixed
     */
    public static function unserialize( $serializedVar, $defaultValue = array() )
    {
		
        if ( !empty( $serializedVar ) )
        {
			//print_r("asd");die;
            return unserialize( base64_decode( $serializedVar ) );
        }
        else
        {
			//print_r($defaultValue);die;
            return $defaultValue;
        }
    }
}
?>
