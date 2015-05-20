<?php
/**
 * File containing the ezcWorkflowConditionIsLessThan class.
 *
 * @package Workflow
 * @version 1.3.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Condition that evaluates to true if the provided value is less than the reference value.
 *
 * Typically used together with ezcWorkflowConditionVariable to use the
 * condition on a workflow variable.
 *
 * <code>
 * <?php
 * $condition = new ezcWorkflowConditionVariable(
     'variable name',
 *   new ezcWorkflowConditionIsLessThan( $comparisonValue )
 * );
 * ?>
 * </code>
 *
 * @package Workflow
 * @version 1.3.2
 */
class ezcWorkflowConditionIsLessThan extends ezcWorkflowConditionComparison
{
    /**
     * @var mixed
     */
    protected $operator = '<';

    /**
     * Evaluates this condition with $value and returns true if $value is less than
     * the reference value or false if not.
     *
     * @param  mixed $value
     * @return boolean true when the condition holds, false otherwise.
     * @ignore
     */
    public function evaluate( $value )
    {
        return $value < $this->value;
    }
}
?>
