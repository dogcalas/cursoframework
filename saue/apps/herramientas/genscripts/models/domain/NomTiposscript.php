<?php

class NomTiposscript extends BaseNomTiposscript
{

    public function setUp()
    {
        parent :: setUp ();
        $this->hasMany ('DatScript', array ('local' => 'id_tiposcript', 'foreign' => 'id_tiposcript'));
    }
}

