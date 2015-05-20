<?php
/**
 * File containing the ezcWorkflowConditionIsInteger class.
 *
 * @package Workflow
 * @version 1.3.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Condition that evaluates to true if the evaluated value is an integer.
 *
 * Typically used together with ezcWorkflowConditionVariable to use the
 * condition on a workflow variable.
 *
 * <code>
 * <?php
 * $condition = new ezcWorkflowConditionVariable(
 *   'variable name',
 *   new ezcWorkflowConditionIsInteger
 * );
 * ?>
 * </code>
 *
 * @package Workflow
 * @version 1.3.2
 */
class ezcWorkflowConditionIsInteger extends ezcWorkflowConditionType
{
    /**
     * Evaluates this condition and returns true if $value is an integer or false if not.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     * @ignore
     */
    public function evaluate( $value )
    {
        $isInt = is_int( $value );
        if (!$isInt){
            if ($this->isValidInt($value)) {
                $value = intval($value);
                
                //Por si acaso...
                return $this->evaluate($value);
            }else return false;
        }
        return $isInt;
    }

    /**
     * Returns a textual representation of this condition.
     *
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return 'integer';
    }
    
    private function isValidInt($intAsString){
        if (is_string($intAsString)) {
            for ($i = 0; $i < strlen($intAsString); $i++) {
                if ($intAsString[$i] < '0' && $intAsString[$i] > '9') {
                    return false;
                }
            }
            return true;
        }  else {
            return false;
        }
    }
}
?>
