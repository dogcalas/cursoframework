<?php
/**
 * File containing the ezcWorkflowConditionType class.
 *
 * @package Workflow
 * @version 1.3.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Abstract base class for type conditions.
 *
 * @package Workflow
 * @version 1.3.2
 */
abstract class ezcWorkflowConditionType implements ezcWorkflowCondition
{
    public function toString(){
        return $this->__toString();
    }
}
?>
