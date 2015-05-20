<?php

class DatImagenesUsuarioModel extends ZendExt_Model
{
	function guardarimagen($imagen) {
	     $imagen->save();
	}


}

