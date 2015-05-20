<?php

class UsuarioValidator {

	public function verificarDominioDadoUsuario () {
		$confirmar = $_POST['confirmar'];
		$iddominio = $_POST['iddominio'];
		$idusuario = $_POST['idusuario'];
		$iddominioold = SegUsuario::obtenerIdDominioDadoIdUsuario($idusuario);
		if ($iddominio != $iddominioold) {
		$ioc = ZendExt_IoC::getInstance();

			$arrayEntidades = $ioc->metadatos->IdEstructurasDominio($iddominio);

			if ($confirmar != 'false') {
				$arrayEntNoAsign = DatEntidadSegUsuarioSegRol::obtenerEntidadesNoAsignadasDadoUsuario($idusuario, $arrayEntidades);
				$arrayIdEntNoAsign = array();
				foreach ($arrayEntNoAsign as $entidades)
					$arrayIdEntNoAsign[] = $entidades['identidad'];
				DatEntidadSegUsuarioSegRol::eliminarUsuarioDadoIdEntidades($idusuario, $arrayIdEntNoAsign);
			}
			else {
				$cantEntNoAsign = DatEntidadSegUsuarioSegRol::cantidadEntidadesNoAsignadasDadoUsuario($idusuario, $arrayEntidades);

				if ($cantEntNoAsign > 0)
				{
					return false;
				}
			}
		}
		return true;
	}

	public function verificarDominio () {
		$arrayEntidadesEliminar = json_decode(stripslashes($_POST['arrayEntidadesEliminar']));
		if (is_array($arrayEntidadesEliminar) && count($arrayEntidadesEliminar)) {
			$iddominio = $_POST['iddominio'];
			$arrayIdUsuarios = SegUsuario::obtenerIdUsuariosDadoIdDominio($iddominio,1);
			if (is_array($arrayIdUsuarios) && count($arrayIdUsuarios)) {
				$arrayIdUsuariosSimples = array();
				foreach ($arrayIdUsuarios as $usuario)
					$arrayIdUsuariosSimples[] = $usuario['idusuario'];
				$cantEntNoAsign = DatEntidadSegUsuarioSegRol::cantidadEntidadesNoAsignadasDadoArrayUsuarios($arrayIdUsuariosSimples, $arrayEntidadesEliminar);

				if ($cantEntNoAsign > 0)
					return false;
			}
		}
		return true;
	}

	public function obtenerIdEntidadesDadoDominio($array) {
		$resultado = array();
		foreach($array as $valor) {
			$temp = explode("-",$valor);
			$resultado[] = $this->existeValorArrayUltimo($temp);
		}
		return $resultado;
        }
        
	public function existeValorArrayUltimo($array)
	{
		$i = count($array) - 1;
		return substr($array[$i],0,strlen($array[$i])-2);
	}
}

