<?php


class GestimagenesusuarioController extends ZendExt_Controller_Secure {

    function init() {
        parent::init();
    }

    function gestimagenesusuarioAction() {
        $this->render();
    }

    function cargarusuarioAction() {

        $limit = $this->_request->getPost('limit');
        $start = $this->_request->getPost('start');
        $idusuario = $this->global->Perfil->idusuario;
        $nombreusuario = $this->_request->getPost('nombreusuario');
        $dominiobuscar = $this->_request->getPost('dominiobuscar');
        $iddominio = $this->global->Perfil->iddominio;
       
        $cantf = 0;
        $arrayresult = array();
        $datosusuario = array();
        $usuariosSinDominio = array();
        $usuariosconpermisosadominios = array();
        $permisos = SegCompartimentacionusuario::cargardominioUsuario($idusuario);
        $filtroDominio = $this->arregloToUnidimensional($permisos);
        if (count($filtroDominio))
            $usuariosconpermisosadominios = SegUsuario::cargarUsuariosconpermisosaDominios($filtroDominio);
        $usuariosconpermisosadominios = $this->arregloToUnidimensionalUsuario($usuariosconpermisosadominios);
        $usuariosdelDominio = SegUsuarioNomDominio::cargarUsuariosDominios($iddominio);
        $usuariosdelDominio = $this->arregloToUnidimensionalUsuario($usuariosdelDominio);
        $usuariosSinDominio = SegUsuario::usuariosSinDominio();
        $usuariosSinDominio = $this->arregloToUnidimensionalUsuario($usuariosSinDominio);
        $arrayresult = array_merge($usuariosconpermisosadominios, $usuariosdelDominio);
        $arrayresult = array_merge($arrayresult, $usuariosSinDominio);
        if ($nombreusuario || $dominiobuscar || $activar) {
            if (count($arrayresult)) {
                $datosusuario = SegUsuario::cargarGridUsuarioBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar, $limit, $start);
                $cantf = SegUsuario::cantidadFilasUsuariosBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar);
            }
        } else {
            if (count($arrayresult)) {
                   
                $datosusuario = SegUsuario::cargarGridUsuario($arrayresult, $limit, $start);
                
                $cantf = SegUsuario::cantidadFilas($arrayresult);
            }
        }
        
        if (count($datosusuario)) {
          
            $arrayusuario = $this->datosGridUsuarios($datosusuario);
           
            $result = array('cantidad_filas' => $cantf, 'datos' => $arrayusuario);
            echo json_encode($result);
            return;
        } else {
            $result = array('cantidad_filas' => $cantf, 'datos' => $datosusuario);
            echo json_encode($result);
            return;
        }
    }

    function arregloToUnidimensionalUsuario($arrayvalores) {
        $array = array();
        foreach ($arrayvalores as $idusuario)
            $array[] = $idusuario['idusuario'];
        return $array;
    }

    function arregloToUnidimensional($arrayDominios) {
        $array = array();
        foreach ($arrayDominios as $dominios)
            $array[] = $dominios['iddominio'];
        return $array;
    }

    function datosGridUsuarios($datosusuario) {

        foreach ($datosusuario as $key => $usuario) {
            $arrayusuario[$key]['idusuario'] = $usuario->idusuario;
            $arrayusuario[$key]['nombreusuario'] = $usuario->nombreusuario;
            $imagenes = DatImagenesUsuario::cargarimagenesusuario($usuario->idusuario);
            $cant = SegDatosImagenes::cargarcantidad();            
            if (count($imagenes) == $cant) {
                $arrayusuario[$key]['estado'] = 1;
            }

            $arrayusuario[$key]['nimg'] = count($imagenes);
        }
        return $arrayusuario;
    }

    function guardarAction() {
        $idusuario = $this->_request->getPost('usuario');
        $cant = SegDatosImagenes::cargarcantidad();
        $cantimg = count(DatImagenesUsuario::cargarimagenesusuario($idusuario));

        if ($cantimg == $cant) {
            echo "{'bien':3}";
        } else {

            $fecha = date('d/m/Y');

            $tmp_name = $_FILES["photo"]["tmp_name"];

            $type = $_FILES["photo"]["type"];
            $size = $_FILES["photo"]["size"];
            $nombre = basename($_FILES["photo"]["name"]);
            $esta = count(DatImagenesUsuario::estaimagen($idusuario, $nombre));
            if ($esta) {
                echo "{'bien':4}";
            } else {

                if (/* $_FILES['photo']['type'] == "image/gif" || *//* $_FILES['photo']['type'] == "image/jpeg" || */ $_FILES['photo']['type'] == "image/png" || /* $_FILES['photo']['type'] == "image/x-portable-graymap"|| */ $_FILES['photo']['type'] == "image/bmp") {
                    $fp = fopen($tmp_name, "rb");
                    $buffer = fread($fp, filesize($tmp_name));
                    fclose($fp);
                    $buffer = base64_encode($buffer);

                    $imageusuario = new DatImagenesUsuario();
                    $imageusuario->idusuario = $idusuario;
                    $imageusuario->nombreimage = $nombre;
                    $imageusuario->fecha = $fecha;
                    $imageusuario->imagen = $buffer;


                    $model = new DatImagenesUsuarioModel();
                    $model->guardarimagen($imageusuario);
                    echo "{'bien':2}";
                }
                else
                    echo "{'bien':1}";
                //$this->showMessage('el archivo deve ser una imagen.');
            }
        }
    }

    function getimagesAction() {


        $idusuario = $this->_request->getPost('usuario');
        $arreglofecha = array();
        chown('../images', 'www-data');

        if (!is_writable('../images')) {
            echo "{'codMsg':3,'mensaje': perfil.etiquetas.lbErrorPermisos }";
            
        }

        $file = new ZendExt_File('../images/temp');
        $file->mkdir();

        $a = DatImagenesUsuario::cargarimagenesusuario($idusuario);

        foreach ($a as $value) {
            $imagen = base64_decode(stream_get_contents($value['imagen']));

            $arreglofecha[$value['nombreimage']] = $value['fecha'];

            $file->mkfile($imagen, $file->get_path() . DIRECTORY_SEPARATOR . $value['nombreimage']);
        }



        $dir = $file->get_path() . DIRECTORY_SEPARATOR;
        $images = array();
        $d = dir($dir);
        while ($name = $d->read()) {
            if (!preg_match('/\.(jpg|gif|png|JPG|pgm|bmp)$/', $name))
                continue;
            $size = filesize($dir . $name);
            $lastmod = $arreglofecha[$name];

            $images[] = array('name' => $name, 'size' => $size,
                'lastmod' => $lastmod, 'url' => '../../' . $dir . $name . '?' . rand());
        }


        $d->close();
        $o = array('images' => $images);
        echo json_encode($o);
    }

    function borrardirAction() {
        $file = new ZendExt_File('../images/temp');
        $file->rm();
    }

    function eliminarimagenAction() {
        $name = $this->_request->getPost('img');
        $user = $this->_request->getPost('usuario');


        DatImagenesUsuario::eliminarimagen($name, $user);

        $file = new ZendExt_File('../images/temp');
        $file->rm();
        echo "{'bien':1}";
    }
    
    function entrenamientoAction() {

        // $rec = $this->integrator->seguridad->loginFace('instalacion');
        // $rec = $this->component->seguridadInterface->loginFace('instalacion');
        //print_r($rec);die;
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        
        $dirapps = str_replace('web', 'apps', $dirweb);
        
        $dir_xml = $dirapps . DIRECTORY_SEPARATOR . 'seguridad' . DIRECTORY_SEPARATOR . 'comun' . DIRECTORY_SEPARATOR . 'recursos' . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'reconocimientoFacial' . DIRECTORY_SEPARATOR;
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'reconocimiento';
       
        $file = new ZendExt_File($path);
        $file->mkdir();


        $arregloimg = DatImagenesUsuario::cargarimagenes();
        if (!count($arregloimg)) {
            echo "{'bien':3}";
        } else {


            //mkdir($dir);

            foreach ($arregloimg as $value) {
                $imagen = base64_decode(stream_get_contents($value['imagen']));
                $file->mkfile($imagen, $path . DIRECTORY_SEPARATOR . $value['idusuario'] . "_" . $value['nombreimage']);
            }

            //Obtengo las direcciones de las imagenes para reconocimiento
            $files = array();
            $arrayid = array();
            $aux = array();
            $directory = opendir($path);
            while ($item = readdir($directory)) {

                if (($item != ".") && ($item != "..")) {
                    $files[] = $path . DIRECTORY_SEPARATOR . $item;
                    $aux = explode("_", $item);

                    $arrayid[] = $aux[0];
                }
            }
            $numFiles = count($files);


            if ($this->ComprobarLibreria()){
            $rec = new Rec();
            $rec->entrenamientoPCA($files, $arrayid, $numFiles, $dir_xml . "facedataPCA.xml");
            $rec->entrenamientoWPCA($files, $arrayid, $numFiles, $dir_xml . "facedataWPCA.xml", "db3", 3);
            $file->rm();
            echo "{'bien':1}";
            }
            else{
            $file->rm();
            echo "{'bien':2}";   
            }
            
        }
    }
        function ComprobarLibreria() {
        if (!extension_loaded('reconocimiento')) {

            return 0;
        }
        else
            return 1;
    }

}

?>
