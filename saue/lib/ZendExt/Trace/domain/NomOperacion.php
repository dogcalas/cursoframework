<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class NomOperacion extends BaseNomOperacion
{
    function setUp()
    {
        $this->hasOne('HisDato', array('local' => 'idoperacion',
            'foreign' => 'idoperacion'));
    }

}

?>