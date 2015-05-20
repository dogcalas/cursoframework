<?php


/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author William Amed Tamayo Guevara
 * @author Ricardo Enrique Suarez Riquenes
 * 
 * @version 1.0-0
 */
	class GestnomtemaController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}
		

	 function gestnomtemaAction(){
			$this->render();
		} 
		
		 /* @Descripcion Funcion para verificar si un tema existe
		  *
		  * @autor William Amed
		  * @autor Ricardo Enrique
		  * @return boolean
		  */
		
		function verificartema($denominacion,$abreviatura)
        {
         $tema = NomTema::comprobartema($denominacion,$abreviatura);
         if($tema)
            return 1;
         else 
           return 0;
        }
	
	 /* @Descripcion Funcion para insertar un tema
	  *
	  * @autor William Amed
	  * @autor Ricardo Enrique
	  * @return mensaje con el resultado de la accion en formato json
	  */
	 function insertartemaAction(){
			 $tema = new NomTema();
			 $tema->denominacion = $this->_request->getPost('denominacion');
			 $tema->abreviatura = $this->_request->getPost('abreviatura');
			 $tema->descripcion = $this->_request->getPost('descripcion');
			 if($this->_request->getPost('dinamico')=='on')
			 $tema->dinamico = true;	
			 else
			 $tema->dinamico = false;	
             if($this->verificartema($tema->denominacion,''))
				throw new ZendExt_Exception('SEG016'); 
			if($this->verificartema('', $tema->abreviatura))
				throw new ZendExt_Exception('SEG017');
			 $modeltema = new NomTemaModel();
			 $modeltema->insertartema($tema);
			 
			 
			 if($this->_request->getPost('dinamico')=='on'){
			
				$desktop=new NomPropDesktop();
				$desktop->idtema=$tema->idtema;
				$modelDesktop=new NomPropDesktopModel();
				$modelDesktop->insertartema($desktop);
				
				$comercial=new NomPropComercial();
				$comercial->idtema=$tema->idtema;
				$comercialModel=new NomPropComercialModel();
				$comercialModel->insertartema($comercial);
				
				$presentacion=new NomPropPresentacion();
				$presentacion->idtema=$tema->idtema;
				$presentacionModel=new NomPropPresentacionModel();
				$presentacionModel->insertartema($presentacion);
			 
				$src='../lib/ExtJS/temas/resources4/sauxeTheme';
				$dst='../lib/ExtJS/temas/'.$this->_request->getPost('abreviatura');
			 
				$this->copyDirToDir($src,$dst);
			 
			 
		    }
			  echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbResponseInsert }";
                    
		}
      
     /* @Descripcion Funcion para modificar un tema
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez
	  * @return mensaje con el resultado de la accion en formato json
	  */   
	 function modificartemaAction(){
          $tema = new NomTema();
          $denominacion = $this->_request->getPost('denominacion');
          $abreviatura =  $this->_request->getPost('abreviatura'); 
	      $tema = Doctrine::getTable('NomTema')->find($this->_request->getPost('idtema'));
          $tema->descripcion = $this->_request->getPost('descripcion');
          
       

            if($tema->denominacion != $denominacion)
            {
                if($this->verificartema($denominacion, '-1'))
                    throw new ZendExt_Exception('SEG016'); 
            }
            if($tema->abreviatura != $abreviatura)
            {
                if($this->verificartema('-1', $abreviatura))
                    throw new ZendExt_Exception('SEG017');
            }
             $tema->denominacion = $denominacion;                 
             $tema->abreviatura = $abreviatura;
             $modeltema = new NomTemaModel();
             $modeltema->modificartema($tema);
             echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbResponseUpdate }"; 
        }
      
     /* @Descripcion Funcion para modificar un tema personalizado
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return mensaje con el resultado de la accion en formato json
	  */   
     function modificartemaPresonalizadoBaseAction(){
         
		$Fileconten  = $_FILES['fondo']['name'];
		$Photoname  = $_FILES['Icono']['name'];
		$nombre_t =  $this->_request->getPost('nombre_tema');
		$idtema =  $this->_request->getPost('idtema');
		
		$mensajeFinal="";
		$errorF=false;
		
		copy("../lib/ExtJS/temas/".$nombre_t."/cache/desktop_1.jpg","../lib/ExtJS/temas/".$nombre_t."/images/entornos/desktop.jpg");
		copy("../lib/ExtJS/temas/".$nombre_t."/cache/entrada_1.jpg","../lib/ExtJS/temas/".$nombre_t."/images/entornos/entrada.jpg");
		copy("../lib/ExtJS/temas/".$nombre_t."/cache/comercial_1.jpg","../lib/ExtJS/temas/".$nombre_t."/images/entornos/comercial.jpg");
		copy("../lib/ExtJS/temas/".$nombre_t."/cache/icon_1.gif","../lib/ExtJS/temas/".$nombre_t."/images/entornos/icon.gif");
		copy("../lib/ExtJS/temas/".$nombre_t."/cache/cargando_1.gif","../lib/ExtJS/temas/".$nombre_t."/images/entornos/cargando.gif");
		copy("../lib/ExtJS/temas/".$nombre_t."/cache/org_1.png","../lib/ExtJS/temas/".$nombre_t."/images/entornos/org.png");
		copy("../lib/ExtJS/temas/".$nombre_t."/cache/entry_logo_1.png","../lib/ExtJS/temas/".$nombre_t."/images/entornos/entry_logo.png");
		
		GdTransform::resize("../lib/ExtJS/temas/".$nombre_t."/cache/header_1.jpg",900,155,"../lib/ExtJS/temas/".$nombre_t."/css/images/header.jpg");
		GdTransform::resize("../lib/ExtJS/temas/".$nombre_t."/cache/slogan_1.png",203,150,"../lib/ExtJS/temas/".$nombre_t."/css/images/header-object.png");
		
		copy("../lib/ExtJS/temas/".$nombre_t."/cache/footer-1_1.png","../lib/ExtJS/temas/".$nombre_t."/css/images/footer-1.png");
		copy("../lib/ExtJS/temas/".$nombre_t."/cache/texture_bot_1.png","../lib/ExtJS/temas/".$nombre_t."/css/images/texture_bot.png");	
		copy("../lib/ExtJS/temas/".$nombre_t."/cache/texture_bot_1_hover.png","../lib/ExtJS/temas/".$nombre_t."/css/images/texture_bot_hover.png");	
	
		 $somb=false;
		if($this->_request->getPost('framework')=='Sombreado')
			$somb=true;
		if($this->_request->getPost('nav_texture')=='true')
			$texture=true;
		
		$new_theme=DesktopCSSBase::createModel('newModel');
		
		$new_theme['nombre_tema']=$this->_request->getPost('nombre_tema');
		$new_theme['nombre_t']=$this->_request->getPost('nombre_tema');
		
		/******* Desktop *********/
		$new_theme['barra_hex_sup']=$this->_request->getPost('barra_hex_sup');
		//barra superior en rgb
		//$new_theme['barra_sup']=DesktopCSSBase::convertirHex($this->_request->getPost('barra_hex_sup'));
		$new_theme['degradadoBarra']=DesktopCSSBase::convertirHex($this->_request->getPost('barra_hex_sup'));
		$new_theme['tarea_hex']=$this->_request->getPost('tarea_hex');
		$new_theme['inicio_hex']=$this->_request->getPost('inicio_hex');
		$new_theme['btn_inicio_border']=$new_theme['inicio_hex'];
		$new_theme['inicio_fondo_hex']=$this->_request->getPost('inicio_fondo_hex');
		$new_theme['btn_inicio_hover']=$new_theme['inicio_fondo_hex'];
		$new_theme['btn_inicio_press']=$new_theme['inicio_fondo_hex'];
		$new_theme['inicio_texto_hex']=$this->_request->getPost('inicio_texto_hex');
		$new_theme['inicio_bold']=$this->_request->getPost('bold');		
        $new_theme['inicio_italic']=$this->_request->getPost('italic');
        $new_theme['inicio_underline']=$this->_request->getPost('underline');
        $new_theme['inicio_font_size']=$this->_request->getPost('inicio_font_size');
        $new_theme['inicio_tipo_letra']=$this->_request->getPost('inicio_tipo_letra');
        $new_theme['menu_header']=$this->_request->getPost('menu_header');
        $new_theme['menu_dock']=$this->_request->getPost('menu_dock');
        $new_theme['menu_dock_opacity']=$this->_request->getPost('menu_dock_opacity');
		$covert_opacity=$new_theme['menu_dock_opacity']/100;
        $new_theme['menu_dock_rgba']='rgba('.GdTransform::hex2RGB($new_theme['menu_dock'],true).','.$covert_opacity.')';
        $new_theme['shadow']=$somb;
		
        /******* Presentation *********/
        $new_theme['extjs']=$this->_request->getPost('ventanas');
        $new_theme['iconos']=$this->_request->getPost('iconos');
        
       /******** COMERCIAL *************/
        //slogan
        $new_theme['slogan_bold']=$this->_request->getPost('slogan_bold');		
        $new_theme['slogan_italic']=$this->_request->getPost('slogan_italic');
        $new_theme['slogan_underline']=$this->_request->getPost('slogan_underline');
        $new_theme['slogan_size']=$this->_request->getPost('slogan_size');
        $new_theme['slogan_font']=$this->_request->getPost('slogan_font');
        $new_theme['slogan_color']=$this->_request->getPost('slogan_color');
        //bloques
        $new_theme['bloq_bold']=$this->_request->getPost('bloq_bold');
        $new_theme['bloq_italic']=$this->_request->getPost('bloq_italic');
        $new_theme['bloq_underline']=$this->_request->getPost('bloq_underline');
        $new_theme['bloq_size']=$this->_request->getPost('bloq_size');
        $new_theme['bloq_font']=$this->_request->getPost('bloq_font');
        $new_theme['bloq_color']=$this->_request->getPost('bloq_color');
        $new_theme['bloq_header_color']=$this->_request->getPost('bloq_header_color');
        $new_theme['bloq_header_background']=$this->_request->getPost('bloq_header_background');
        //navegacion
        $new_theme['nav_color']=$this->_request->getPost('nav_color');
        $new_theme['nav_background']=$this->_request->getPost('nav_background');
        $new_theme['nav_hover']=$this->_request->getPost('nav_hover');
        $new_theme['nav_hover2']=$this->_request->getPost('nav_hover');
        $new_theme['nav_color_hover']=$this->_request->getPost('nav_color_hover');
        $new_theme['nav_texture']=$texture;
        $new_theme['footer_background']=$this->_request->getPost('footer_background');
		$new_theme['nav_color_texture']=$this->_request->getPost('nav_color_texture');
        //menu
        $new_theme['nav_menu_background']=$new_theme['nav_background'];
        $new_theme['nav_menu_border']=$new_theme['nav_background'];
        $new_theme['nav_menu_color']=$new_theme['nav_color'];
        $new_theme['nav_menu_background_hover']=$new_theme['nav_hover'];
        $new_theme['nav_menu_border_hover']=$new_theme['nav_hover'];
        $new_theme['nav_menu_color_hover']=$new_theme['nav_color_hover'];
        //pagina
        $new_theme['bar_background']=$this->_request->getPost('bar_background');
        $new_theme['bar_opacity']=$this->_request->getPost('bar_opacity');
        $new_theme['body_background']=$this->_request->getPost('body_background');
        $new_theme['body_opacity']=$this->_request->getPost('body_opacity');
        
        
       
        
        $temaP = Doctrine::getTable('NomPropDesktop')->find(NomPropDesktop::cargarTemaDesktop($idtema,true));
        $entornoComercial = Doctrine::getTable('NomPropComercial')->find(NomPropComercial::cargarTemaComercial($idtema,true));
        
        $oldModel=DesktopCSSBase::createModel('oldModel',$temaP);
        
       
        
      
        $desk=new DesktopCSSBase($new_theme,$oldModel);
        $desk->save();
        $comercial=new ComercialCSSBase($new_theme);
        $comercial->save();
        GdTransform::createBody($new_theme['nombre_tema'],'#'.$new_theme['body_background'],$new_theme['body_opacity']);
        GdTransform::createBar($new_theme['nombre_tema'],'#'.$new_theme['bar_background'],$new_theme['bar_opacity']);
		
		
       
       
		$temaP=DesktopCSSBase::fillDoctrineRecord($temaP,$new_theme);
		$entornoComercial=ComercialCSSBase::fillDoctrineRecord($entornoComercial,$new_theme);
		
		$temaGeneral = new NomTema();
      
	    $temaGeneral  = Doctrine::getTable('NomTema')->find($this->_request->getPost('idtema'));
        $temaGeneral->descripcion = $this->_request->getPost('descripcion');
		$temaGeneral->denominacion = $this->_request->getPost('denominacion');         
            
       
		
	 
		
		$temaPd = Doctrine::getTable('NomPropPresentacion')->find(NomPropPresentacion::cargarTemaPresentacion($idtema,true));

		$idext = fopen('../lib/ExtJS/temas/'.$nombre_t.'/css/ext-all.css','w+');

		$ext="@import '../../resources4/ExtThemes/".$_POST['ventanas']."/".$_POST['ventanas']."-all.css';";

		fwrite($idext,$ext);
		fclose($idext);


	   $temaPd->ventanas=$new_theme['extjs'];
	   $temaPd->iconos=$new_theme['iconos'];


	  
		 if($errorF){
			 echo "{'codMsg':3,'mensaje': '".$mensajeFinal."'}";
			}else{
			  $modeltema = new NomPropDesktopModel();
			  $modeltema->modificartema($temaP);
			  $modeltemap = new NomPropPresentacionModel();
			  $modeltemap->modificartema($temaPd);
			  $modelComercial=new NomPropComercialModel();
			  $modelComercial->modificartema($entornoComercial);
			  $modeltemaGeneral = new NomTemaModel();
			  $modeltemaGeneral->modificartema($temaGeneral);
			  echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbResponseUpdate }";
			  
			  $src='../lib/ExtJS/temas/resources4/SauxeIcons/'.$new_theme['iconos'];
			  $dst='../lib/ExtJS/temas/'.$nombre_t.'/icons';
			  $this->eliminarDir($dst);
			  $this->copyDirToDir($src,$dst);
			  $this->limpiarCache($this->_request->getPost('nombre_tema'));
			   
			}
			return ; 


        }   
        
     
   
	 /* @Descripcion Funcion para actualizar las texturas contenidas en el tema
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * 
	  */
	 function actualizarTexturaAction(){
			if($this->_request->getPost('texture')=='1'){
				GdTransform::createFooter($this->_request->getPost('nombre_tema'),$this->_request->getPost('footer_background'));
				echo "{'codMsg':1,'mensaje': 'se modifico la textura 1.'}";
				return; 
			}
			if($this->_request->getPost('texture')=='2'){
				GdTransform::createBoton($this->_request->getPost('nombre_tema'),$this->_request->getPost('nav_background'),$this->_request->getPost('nav_hover'),$this->_request->getPost('dir'));		
				echo "{'codMsg':1,'mensaje': 'se modifico la textura 2.'}";
				return;  
			}	
			echo "{'codMsg':1,'mensaje': 'No se modifico textura.'}";
	 }	
	 
	 /* @Descripcion Funcion para actualizar todos los fondos e imagenes en el tema seleccionado
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return mensaje con el resultado de la accion en formato json
	  */		
	 function actualizarFondoPreAction(){
			$nombre_t =  $this->_request->getPost('nombre_tema');


			if($_FILES['entrada']['name']!=""){
				if ($_FILES['entrada']['type'] == "image/jpeg" || $_FILES['entrada']['type'] == "image/x-png") 
				{
				  move_uploaded_file ($_FILES['entrada']['tmp_name'],"../lib/ExtJS/temas/".$nombre_t."/cache/entrada_1.jpg");
				//  move_uploaded_file ($_FILES['Icono']['tmp_name'],"../lib/ExtJS/temas/Personalizado/fondos/icon.gif");
					  //.$_FILES['Icono']['name']       
				  
					 
				  
			   }else{
				  $mensajeFinal.="perfil.etiquetas.lbResponseEntrada"; 
				   $errorF=true;
			   }
			}

		if($_FILES['carg']['name']!=""){
			if ($_FILES['carg']['type'] == "image/gif"&&$_FILES['carg']['size']<=4000) 
			{
			  move_uploaded_file ($_FILES['carg']['tmp_name'],"../lib/ExtJS/temas/".$nombre_t."/cache/cargando_1.gif");
			//  move_uploaded_file ($_FILES['Icono']['tmp_name'],"../lib/ExtJS/temas/Personalizado/fondos/icon.gif");
				  //.$_FILES['Icono']['name']       
			  
			   
			  
		   }else{
			  $mensajeFinal.="perfil.etiquetas.lbResponseAnim"; 
			   $errorF=true;
		   }
		}


		if($_FILES['fondo']['name']!=""){
			if ($_FILES['fondo']['type'] == "image/jpeg" || $_FILES['fondo']['type'] == "image/x-png") 
			{
			  move_uploaded_file ($_FILES['fondo']['tmp_name'],"../lib/ExtJS/temas/".$nombre_t."/cache/desktop_1.jpg");
			//  move_uploaded_file ($_FILES['Icono']['tmp_name'],"../lib/ExtJS/temas/Personalizado/fondos/icon.gif");
				  //.$_FILES['Icono']['name']       
			  
				 
			  
		   }else{
			  $mensajeFinal.="perfil.etiquetas.lbResponseFondo"; 
			   $errorF=true;
		   }
		}

		if($_FILES['fondo_comercial']['name']!=""){
			if ($_FILES['fondo_comercial']['type'] == "image/jpeg" || $_FILES['fondo_comercial']['type'] == "image/x-png") 
			{
			  move_uploaded_file ($_FILES['fondo_comercial']['tmp_name'],"../lib/ExtJS/temas/".$nombre_t."/cache/comercial_1.jpg");
			//  move_uploaded_file ($_FILES['Icono']['tmp_name'],"../lib/ExtJS/temas/Personalizado/fondos/icon.gif");
				  //.$_FILES['Icono']['name']       
			  
				 
			  
		   }else{
			  $mensajeFinal.="perfil.etiquetas.lbResponseFondo"; 
			   $errorF=true;
		   }
		}

		if($_FILES['banner_comercial']['name']!=""){
			if ($_FILES['banner_comercial']['type'] == "image/jpeg" || $_FILES['banner_comercial']['type'] == "image/png") 
			{
			  move_uploaded_file ($_FILES['banner_comercial']['tmp_name'],"../lib/ExtJS/temas/".$nombre_t."/cache/header_1.jpg");
			//  move_uploaded_file ($_FILES['Icono']['tmp_name'],"../lib/ExtJS/temas/Personalizado/fondos/icon.gif");
				  //.$_FILES['Icono']['name']       
			  
				 
			  
		   }else{
			  $mensajeFinal.="perfil.etiquetas.lbResponseBanner"; 
			   $errorF=true;
		   }
		}

		if($_FILES['slogan_comercial']['name']!=""){
			if ($_FILES['slogan_comercial']['type'] == "image/jpeg" || $_FILES['slogan_comercial']['type'] == "image/png") 
			{
			  move_uploaded_file ($_FILES['slogan_comercial']['tmp_name'],"../lib/ExtJS/temas/".$nombre_t."/cache/slogan_1.png");
			//  move_uploaded_file ($_FILES['Icono']['tmp_name'],"../lib/ExtJS/temas/Personalizado/fondos/icon.gif");
				  //.$_FILES['Icono']['name']       
			  
				 
			  
		   }else{
			  $mensajeFinal.="perfil.etiquetas.lbResponseSlogan"; 
			   $errorF=true;
		   }
		}

		if($_FILES['Icono']['name']!=""){
			

		   if ($_FILES['Icono']['type'] == "image/gif") 
			{
			//  move_uploaded_file ($_FILES['fondo']['tmp_name'],"../lib/ExtJS/temas/Personalizado/fondos/desktop.jpg");
			  move_uploaded_file ($_FILES['Icono']['tmp_name'],"../lib/ExtJS/temas/".$nombre_t."/cache/icon_1.gif");
				  //.$_FILES['Icono']['name']       
			  
			   
				 
			  
		   }else{
			  // echo "{'codMsg':3,'mensaje': 'Error'}"; 
			   $mensajeFinal.="perfil.etiquetas.lbResponseIcon"; 
				$errorF=true;
		   }
		 }   

		if($_FILES['Slogan']['name']!=""){
			if ($_FILES['Slogan']['type'] == "image/jpeg" || $_FILES['Slogan']['type'] == "image/png") 
			{
			  move_uploaded_file ($_FILES['Slogan']['tmp_name'],"../lib/ExtJS/temas/".$nombre_t."/cache/org_1.png");
			//  move_uploaded_file ($_FILES['Icono']['tmp_name'],"../lib/ExtJS/temas/Personalizado/fondos/icon.gif");
				  //.$_FILES['Icono']['name']       
			  
				 
			  
		   }else{
			  $mensajeFinal.="perfil.etiquetas.lbResponseOrg"; 
			   $errorF=true;
		   }
		}

		if($_FILES['fondo_autenticacion']['name']!=""){
			if ($_FILES['fondo_autenticacion']['type'] == "image/jpeg" || $_FILES['fondo_autenticacion']['type'] == "image/x-png") 
			{
			  move_uploaded_file ($_FILES['fondo_autenticacion']['tmp_name'],"../lib/ExtJS/temas/working directory/aut.jpg");
			 
			  
				 
			  
		   }else{
			  $mensajeFinal.="perfil.etiquetas.lbResponseFondoAut"; 
			   $errorF=true;
		   }
		}


		if($_FILES['pre_logo']['name']!=""){
			if ($_FILES['pre_logo']['type'] == "image/jpeg" || $_FILES['pre_logo']['type'] == "image/png") 
			{
			  move_uploaded_file ($_FILES['pre_logo']['tmp_name'],"../lib/ExtJS/temas/".$nombre_t."/cache/entry_logo_1.png");
			//  move_uploaded_file ($_FILES['Icono']['tmp_name'],"../lib/ExtJS/temas/Personalizado/fondos/icon.gif");
				  //.$_FILES['Icono']['name']       
			  
				 
			  
		   }else{
			  $mensajeFinal.="perfil.etiquetas.lbResponseLogo"; 
			   $errorF=true;
		   }
		}

		if($errorF==true)
		echo"{'codMsg':3,'mensaje': ".$mensajeFinal."}"; 
		else
			echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbResponseUpdate}"; 
			return;
		}
	 
	 /* @Descripcion Funcion para modificar las propiedades de la ventana de autenticacion
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return mensaje con el resultado de la accion en formato json
	  */	
	 function modificarAutenticacionAction(){
			
				 $xml = simplexml_load_file('../saml/www/resources/visual_config.xml');
                 $aut = new SimpleXMLElement($xml->asXml());
                 
                  
                 
				  $aut->components->attributes()->idioma_sel=$this->_request->getPost('idioma_sel');
				  $aut->components->attributes()->idioma_no_sel=$this->_request->getPost('idioma_no_sel');
				  $aut->components->attributes()->etiquetas=$this->_request->getPost('etiquetas');
				  $aut->components->attributes()->campos_color=$this->_request->getPost('input_color');
				  $aut->components->attributes()->boton_color=$this->_request->getPost('submit_color');
				  $aut->components->attributes()->campos_fondo=$this->_request->getPost('input_fondo');
				  $aut->components->attributes()->boton_fondo=$this->_request->getPost('submit_fondo');
				  $aut->components->attributes()->barras=$this->_request->getPost('bar_horiz');
				  $aut->components->attributes()->copyrigth=$this->_request->getPost('copyrigth');
				  $aut->components->attributes()->altcolor=$this->_request->getPost('altcolor');
				  $aut->components->attributes()->boton_color_hover=$this->_request->getPost('submit_color_hover');
				  $aut->components->attributes()->boton_fondo_hover=$this->_request->getPost('submit_fondo_hover');
				  
				  $new_theme=AutenticacionCSSBase::createModel('newModel');
		
				  $new_theme['idioma_sel']=$this->_request->getPost('idioma_sel');
				  $new_theme['idioma_no_sel']=$this->_request->getPost('idioma_no_sel');
				  $new_theme['etiquetas']=$this->_request->getPost('etiquetas');
				  $new_theme['input_color']=$this->_request->getPost('input_color');
				  $new_theme['submit_color']=$this->_request->getPost('submit_color');
				  $new_theme['input_fondo']=$this->_request->getPost('input_fondo');
				  $new_theme['submit_fondo']=$this->_request->getPost('submit_fondo');
				  $new_theme['bar_horiz']=$this->_request->getPost('bar_horiz');
				  $new_theme['copyrigth']=$this->_request->getPost('copyrigth');
				  $new_theme['altcolor']=$this->_request->getPost('altcolor');
				  $new_theme['submit_fondo_hover']=$this->_request->getPost('submit_fondo_hover');
				  $new_theme['submit_color_hover']=$this->_request->getPost('submit_color_hover');
				  
				  $autentication_theme=new AutenticacionCSSBase($new_theme);
				  $autentication_theme->save();
				
				
				 
				 
                   $aut->asXml('../saml/www/resources/visual_config.xml');
                   
                   copy("../lib/ExtJS/temas/working directory/aut.jpg","../saml/www/resources/icons/aut.jpg");
                   
                   echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbResponseUpdateAut}"; 
		}
		
	 /* @Descripcion Funcion para cargar las propiedades de la ventana de autenticacion
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return datos en formato JSON
	  */	
	 function cargarAutenticacionAction(){
			
				//$css=AstsRule::openCss('../saml/www/resources/default.css');
				
				//$idioma_sel=str_replace('#','',AstsRule::getRule('#languagebar',$css)->get('color'));
				//$idioma_no_sel=str_replace('#','',AstsRule::getRule('#languagebar a',$css)->get('color'));
				//$etiquetas=str_replace('#','',AstsRule::getRule('td',$css)->get('color'));
				
				
                  $xml = simplexml_load_file('../saml/www/resources/visual_config.xml');
                  $aut = new SimpleXMLElement($xml->asXml());
                 
                  
                 
				  $idioma_sel=(string)$aut->components->attributes()->idioma_sel;
				  $idioma_no_sel=(string)$aut->components->attributes()->idioma_no_sel;
				  $etiquetas=(string)$aut->components->attributes()->etiquetas;
				  $campos_color=(string)$aut->components->attributes()->campos_color;
				  $boton_color=(string)$aut->components->attributes()->boton_color;
				  $campos_fondo=(string)$aut->components->attributes()->campos_fondo;
				  $boton_fondo=(string)$aut->components->attributes()->boton_fondo;
				  $barras=(string)$aut->components->attributes()->barras;
				  $copyrigth=(string)$aut->components->attributes()->copyrigth;
				  $altcolor=(string)$aut->components->attributes()->altcolor;
				  $boton_color_hover=(string)$aut->components->attributes()->boton_color_hover;
				 
				  $boton_fondo_hover=(string)$aut->components->attributes()->boton_fondo_hover;
				
			  $datos=array('idioma_sel'=>$idioma_sel,
						   'idioma_no_sel'=>$idioma_no_sel,
							'etiquetas'=>$etiquetas,
							'input_fondo'=>$campos_fondo,
							'input_color'=>$campos_color,
							'submit_fondo'=>$boton_fondo,
							'submit_color'=>$boton_color,
							'bar_horiz'=>$barras,
							'copyrigth'=> $copyrigth,
							'altcolor'=> $altcolor,
							'submit_fondo_hover'=>$boton_fondo_hover,
							'submit_color_hover'=>$boton_color_hover,
							);
			
			  $result =  array('success'=>true, 'data' => $datos);
			
			
			
			copy("../saml/www/resources/icons/aut.jpg","../lib/ExtJS/temas/working directory/aut.jpg");
			
			
			
	        
		     echo json_encode($result);return;
		}
		
		
        
     function cargarDesktopAction(){
			//$nombre_t =  $this->_request->getPost('nombre_tema');
	        
	          $datostema = NomPropDesktop::cargarTemaDesktop($this->_request->getPost('idtema'));
			  $nombre_t=$this->_request->getPost('nombre_tema');
			  $datos=$datostema->toArray();
			  // $data = array($datos);
			  $result =  array('success'=>true, 'data' => $datos[0]);
			
			
			
			copy("../lib/ExtJS/temas/".$nombre_t."/images/entornos/desktop.jpg","../lib/ExtJS/temas/".$nombre_t."/cache/desktop_1.jpg");
			copy("../lib/ExtJS/temas/".$nombre_t."/images/entornos/cargando.gif","../lib/ExtJS/temas/".$nombre_t."/cache/cargando_1.gif");
			copy("../lib/ExtJS/temas/".$nombre_t."/images/entornos/entrada.jpg","../lib/ExtJS/temas/".$nombre_t."/cache/entrada_1.jpg");
			copy("../lib/ExtJS/temas/".$nombre_t."/images/entornos/comercial.jpg","../lib/ExtJS/temas/".$nombre_t."/cache/comercial_1.jpg");
			copy("../lib/ExtJS/temas/".$nombre_t."/images/entornos/org.png","../lib/ExtJS/temas/".$nombre_t."/cache/org_1.png");
			copy("../lib/ExtJS/temas/".$nombre_t."/images/entornos/entry_logo.png","../lib/ExtJS/temas/".$nombre_t."/cache/entry_logo_1.png");
			
			copy("../lib/ExtJS/temas/".$nombre_t."/images/entornos/icon.gif","../lib/ExtJS/temas/".$nombre_t."/cache/icon_1.gif");
			copy("../lib/ExtJS/temas/".$nombre_t."/css/images/header.jpg","../lib/ExtJS/temas/".$nombre_t."/cache/header_1.jpg");
			copy("../lib/ExtJS/temas/".$nombre_t."/css/images/header-object.png","../lib/ExtJS/temas/".$nombre_t."/cache/slogan_1.png");
			
			copy("../lib/ExtJS/temas/".$nombre_t."/css/images/texture_bot.png","../lib/ExtJS/temas/".$nombre_t."/cache/texture_bot_1.png");	
			copy("../lib/ExtJS/temas/".$nombre_t."/css/images/texture_bot_hover.png","../lib/ExtJS/temas/".$nombre_t."/cache/texture_bot_1_hover.png");
			copy("../lib/ExtJS/temas/".$nombre_t."/css/images/footer-1.png","../lib/ExtJS/temas/".$nombre_t."/cache/footer-1_1.png");		
	        
		     echo json_encode($result);return;
		}
     
     /* @Descripcion Funcion para cargar las propiedades de la ventana de presentacion
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return datos en formato JSON
	  */
     function cargarPresentacionAction(){
			//$nombre_t =  $this->_request->getPost('nombre_tema');
	        
	          $datostema = NomPropPresentacion::cargarTemaPresentacion($this->_request->getPost('idtema'));
			 
			  $datos=$datostema->toArray();
			  // $data = array($datos);
			  $result =  array('success'=>true, 'data' => $datos[0]);
			
            
	        
		     echo json_encode($result);return;
	}
	
	 /* @Descripcion Funcion para cargar las propiedades del entorno Comercial
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return datos en formato JSON
	  */		
	 function cargarComercialAction(){
			//$nombre_t =  $this->_request->getPost('nombre_tema');
	        
	          $datostema = NomPropComercial::cargarTemaComercial($this->_request->getPost('idtema'));
			 
			  $datos=$datostema->toArray();
			  // $data = array($datos);
			  $result =  array('success'=>true, 'data' => $datos[0]);
			
            
	        
		     echo json_encode($result);return;
		}
	  /* @Descripcion Funcion para cargar las texturas disponibles
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return datos en formato JSON
	  */	
	 function cargarTexturasAction(){
			
			  $data = array(  
            array('name'=>'1',
				'dir'=>'/lib/ExtJS/temas/Texturas/pattern-122s.png'
				),
			array('name'=>'2',
				'dir'=>'/lib/ExtJS/temas/Texturas/peasoup_02.png'
				),
			array('name'=>'3',
				'dir'=>'/lib/ExtJS/temas/Texturas/right-005w.png'
				),	
			array('name'=>'4',
				'dir'=>'/lib/ExtJS/temas/Texturas/top-010.png'
				),
			array('name'=>'5',
				'dir'=>'/lib/ExtJS/temas/Texturas/pattern-114s.png'
				),	
			array('name'=>'6',
				'dir'=>'/lib/ExtJS/temas/Texturas/pattern-136s.png'
				),
			array('name'=>'7',
				'dir'=>'/lib/ExtJS/temas/Texturas/pixel-01.png'
				),
			array('name'=>'8',
				'dir'=>'/lib/ExtJS/temas/Texturas/pattern-125.png'
				),
			array('name'=>'9',
				'dir'=>'/lib/ExtJS/temas/Texturas/pattern-126s.png'
				),
			array('name'=>'10',
				'dir'=>'/lib/ExtJS/temas/Texturas/pattern-131s.png'
				),
			array('name'=>'11',
				'dir'=>'/lib/ExtJS/temas/Texturas/pattern-136.png'
				),
			array('name'=>'12',
				'dir'=>'/lib/ExtJS/temas/Texturas/pixel-23.png'
				),
			array('name'=>'13',
				'dir'=>'/lib/ExtJS/temas/Texturas/pixel-27.png'
				),
			array('name'=>'14',
				'dir'=>'/lib/ExtJS/temas/Texturas/pixel-two.png'
				),
			array('name'=>'15',
				'dir'=>'/lib/ExtJS/temas/Texturas/right-001w.png'
				),
			array('name'=>'16',
				'dir'=>'/lib/ExtJS/temas/Texturas/right-003w.png'
				),
			array('name'=>'17',
				'dir'=>'/lib/ExtJS/temas/Texturas/scotish_05i.png'
				),
			array('name'=>'18',
				'dir'=>'/lib/ExtJS/temas/Texturas/scotish_06.png'
				),
			array('name'=>'19',
				'dir'=>'/lib/ExtJS/temas/Texturas/spots_01.png'
				),
			array('name'=>'20',
				'dir'=>'/lib/ExtJS/temas/Texturas/spots_02.png'
				),
			array('name'=>'21',
				'dir'=>'/lib/ExtJS/temas/Texturas/top-011.png'
				),
    ); 
			
			  $r=array($data);
			  $result =  array('success'=>true, 'data' =>$r[0] );
			
            
	        
		     echo json_encode($result);return;
		}
     /* @Descripcion Funcion para cerrar la edicion, limpiando la cache de trabajo
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return mensaje de respuesta en formato JSON
	  */
	 function cerrarEdicionAction(){
			$this->limpiarCache($this->_request->getPost('nombre_tema'));
			echo "{'codMsg':1,'mensaje': perfil.etiquetas.lbResponseClose }";
		}
		
	 /* @Descripcion Funcion para cargar las propiedades de la ventana de autenticacion
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return datos en formato JSON
	  */
	 function importarIconosAction(){
				$errorF=false;
				if($_FILES['importIcons']['name']!=""){
				
				if ($_FILES['importIcons']['type'] == 'application/octet-stream'||$_FILES['importIcons']['type'] == 'application/zip') 
				{
				  move_uploaded_file ($_FILES['importIcons']['tmp_name'],"../lib/ExtJS/temas/resources4/cache/".$_FILES['importIcons']['name']);
				
				}else{
				  $mensajeFinal.="perfil.etiquetas.lbResponseImportIconsError" ; 
				   $errorF=true;
				}
				//print_r($_FILES['importIcons']);
				if($errorF==true)
					echo"{'codMsg':3,'mensaje': ".$mensajeFinal."}"; 
				else{
					$a=new Zend_Filter_Compress_Zip();
					$a->setArchive("../lib/ExtJS/temas/resources4/cache/".$_FILES['importIcons']['name']);
					$a->setTarget("../lib/ExtJS/temas/resources4/SauxeIcons/");
					$a->decompress();
					unlink("../lib/ExtJS/temas/resources4/cache/".$_FILES['importIcons']['name']);
					echo "{'codMsg':1,'mensaje': perfil.etiquetas.lbResponseImportIcons}";
					return;
				} 
				
				}
				
				
				
				
				
		}
		
		/* @Descripcion Funcion para cargar las propiedades de la ventana de autenticacion
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return datos en formato JSON
	  */
	 function importarTemaExtAction(){
				$errorF=false;
				
				
				if($_FILES['importExt']['name']!=""){
				
				if ($_FILES['importExt']['type'] == 'application/octet-stream'||$_FILES['importExt']['type'] == 'application/zip') 
				{
				  move_uploaded_file ($_FILES['importExt']['tmp_name'],"../lib/ExtJS/temas/resources4/cache/".$_FILES['importExt']['name']);
				
				}else{
				  $mensajeFinal.="perfil.etiquetas.lbResponseImportTemaError"; 
				   $errorF=true;
				}
				
				if($errorF==true)
					echo"{'codMsg':3,'mensaje': ".$mensajeFinal."}"; 
				else{
					$a=new Zend_Filter_Compress_Zip();
					$a->setArchive("../lib/ExtJS/temas/resources4/cache/".$_FILES['importExt']['name']);
					$a->setTarget("../lib/ExtJS/temas/resources4/ExtThemes/");
					$a->decompress();
					unlink("../lib/ExtJS/temas/resources4/cache/".$_FILES['importExt']['name']);
					echo "{'codMsg':1,'mensaje': perfil.etiquetas.lbResponseImportTema}";
					return;
				} 
				
				}
				
				
				
				
				
		}
	
	 /* @Descripcion Funcion limpiar la cache de trabajo del tema
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @param String con el nombre del tema
	  */
	 function limpiarCache($tema){
			$src='../lib/ExtJS/temas/'.$tema.'/cache';
			$dir = opendir($src);

			while(false !== ( $file =  readdir($dir)) ) {

			if (( $file  != '.' ) && ( $file != '..' )) {

			if (  is_dir($src . DIRECTORY_SEPARATOR . $file) ) {

				//echo "carpeta ".$src . DIRECTORY_SEPARATOR . $file."<br>";
				
			}

			else {
				unlink($src . DIRECTORY_SEPARATOR . $file);
				//echo "archivo ".$src . DIRECTORY_SEPARATOR . $file."<br>";
			  }
			}
			}

			closedir($dir);
			
			
		}
	 
	 /* @Descripcion Funcion para cargar los iconos disponible, se carga solo la ruta de los iconos
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return datos en formato JSON
	  */
	 function cargarIconosAction(){
			$src='../lib/ExtJS/temas/resources4/SauxeIcons';
			$dir = opendir($src);

			  $data = array(); 
			
			 
			 

			while(false !== ( $file =  readdir($dir)) ) {

			if (( $file  != '.' ) && ( $file != '..' ) && ( $file != '.svn' )) {

			if (  is_dir($src . DIRECTORY_SEPARATOR . $file) ) {

				//echo "carpeta ".$src . DIRECTORY_SEPARATOR . $file."<br>";
				 $data[]=array('name'=>$file,
				'dir'=>$src . DIRECTORY_SEPARATOR . $file
				);

			}

			else {
				//echo "archivo ".$src . DIRECTORY_SEPARATOR . $file."<br>";
			  }
			}
			}

			closedir($dir);
			
			 $result =  array('success'=>true, 'data' =>$data );
			
		     echo json_encode($result);return;
		}

       /* @Descripcion Funcion para cargar los temas de Ext JS4 disponible, se carga solo la ruta y nombres de los themas
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return datos en formato JSON
	  */
	 function cargarExtThemesAction(){
			$src='../lib/ExtJS/temas/resources4/ExtThemes';
			$dir = opendir($src);

			  $data = array(); 
			
			 
			 

			while(false !== ( $file =  readdir($dir)) ) {

			if (( $file  != '.' ) && ( $file != '..' )&& ( $file != '.svn' )) {

			if (  is_dir($src . DIRECTORY_SEPARATOR . $file) ) {

				//echo "carpeta ".$src . DIRECTORY_SEPARATOR . $file."<br>";
				 $data[]=array('name'=>$file,
				'dir'=>$src . DIRECTORY_SEPARATOR . $file
				);

			}

			else {
				//echo "archivo ".$src . DIRECTORY_SEPARATOR . $file."<br>";
			  }
			}
			}

			closedir($dir);
			
			 $result =  array('success'=>true, 'data' =>$data );
			
		     echo json_encode($result);return;
		}
        
	
     /* @Descripcion Funcion para eliminar un tema
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return mensaje de respuesta en formato JSON
	  */   
	 function eliminarnomtemaAction(){
			 $modeltema = new NomTemaModel();
			 $tema = Doctrine::getTable('NomTema')->find($this->_request->getPost('idtema'));
			 $cant=NomTema::buscartemausuario($tema->idtema);
			
			 if($cant>0){
				 echo"{'codMsg':3,'mensaje': perfil.etiquetas.lbResponseDeleteError1+".$cant."+perfil.etiquetas.lbResponseDeleteError2}";
				return;
			}else
			 if($this->_request->getPost('din')=='true'){
				
					$this->eliminarDir('../lib/ExtJS/temas/'.$this->_request->getPost('nombre_tema'));
		    }
		    $modeltema->eliminartema($tema);
		    
			  echo"{'codMsg':1,'mensaje': perfil.etiquetas.lbResponseDelete}";
		}
	 
	 /* @Descripcion Funcion para cargar el nomenclador de los temas
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @return datos del tema en formato JSON
	  */ 	
	 function cargarnomtemaAction(){
			 $start = $this->_request->getPost("start");
	         $limit = $this->_request->getPost("limit");	
	         if($limit>0)
	         {
	          $datostema = NomTema::cargarnomtema($limit,$start);
			  $canfilas = NomTema::obtenercantnomtema();	
			  $datos=$datostema->toArray();
			  $result =  array('cantidad_filas'=> $canfilas, 'datos' => $datos);
	         }
	         
		     echo json_encode($result);return;
		}
		
	 /* @Descripcion Funcion para eliminar un carpeta o directorio
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @param String con la ruta de la carpeta
	  */	
	 function eliminarDir($carpeta){
			foreach(glob($carpeta . "/*") as $archivos_carpeta)
			{
			
			 
			if (is_dir($archivos_carpeta))
			{
				$this->eliminarDir($archivos_carpeta);
			}
			else
			{
				unlink($archivos_carpeta);
			}
			}
			 
			rmdir($carpeta);
		}
		
		
	 /* @Descripcion Copia un directorio
	  *
	  * @autor William Amed
	  * @autor Ricardo Suarez 
	  * @param String con la ruta de la carpeta
	  * @param String con la ruta de destino de la carpeta
	  */	
	 function copyDirToDir($src,$dst){

				$dir = opendir($src);

				@mkdir($dst);

				while(false !== ( $file =  readdir($dir)) ) {

				if (( $file  != '.' ) && ( $file != '..' )) {

				if (  is_dir($src . DIRECTORY_SEPARATOR . $file) && $file!='.svn') {

				$this->copyDirToDir($src . DIRECTORY_SEPARATOR . $file,$dst .  DIRECTORY_SEPARATOR . $file);

				}

				else {
				copy($src . DIRECTORY_SEPARATOR . $file,$dst . DIRECTORY_SEPARATOR .  $file);
				  }
				}
				}

				closedir($dir);
	}
				
		
                
     
		
    
	}
?>
