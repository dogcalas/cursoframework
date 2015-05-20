<?php


class PgClass extends BasePgClass
{
    public function setUp()
    {
    parent::setUp();
    $this->hasOne('PgNamespace',array('local'=>'relnamespace','foreign'=>'oid'));    
    }
}