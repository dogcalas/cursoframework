<?php


class DesktopCSSBase extends CSSHandler{
	
	
    function DesktopCSSBase($model,$comparisonModel) {
		$this->setModel($model,$comparisonModel);
		/********** Configuracion de las propiedades CSS a modificar **********/
		
		$this->setSource('../lib/ExtJS/temas/'.$model['nombre_tema'].'/css/desktop.css');
		//viejo
		/*$this->addRule(PropiedRegla::declareRule('negrita1')->to('inicio_bold')->replace()->when('newModel:inicio_bold==false'));
		$this->addRule(PropiedRegla::declareRule('negrita2')->to('inicio_bold')->withValue('font-weight:bold')->when('newModel:inicio_bold==true'));
		$this->addRule(PropiedRegla::declareRule('italic1')->to('inicio_italic')->replace()->when('newModel:inicio_italic==false'));
		$this->addRule(PropiedRegla::declareRule('italic2')->to('inicio_italic')->withValue('font-style:italic')->when('newModel:inicio_italic==true'));
		$this->addRule(PropiedRegla::declareRule('underline1')->to('inicio_underline')->replace()->when('newModel:inicio_underline==false'));
		$this->addRule(PropiedRegla::declareRule('underline2')->to('inicio_underline')->withValue('text-decoration:underline')->when('newModel:inicio_underline==true'));
		*/
		//Migracion ASTS
		//inicio negrita
		$this->addRule(AstsRule::update('#sauxe-inicio')->removeStyle('font-weight')->when('newModel:inicio_bold==false'));
		$this->addRule(AstsRule::update('#sauxe-inicio')->setStyle('font-weight:bold')->when('newModel:inicio_bold==true'));
		//inicio italica
		$this->addRule(AstsRule::update('#sauxe-inicio')->removeStyle('font-style')->when('newModel:inicio_italic==false'));
		$this->addRule(AstsRule::update('#sauxe-inicio')->setStyle('font-style:italic')->when('newModel:inicio_italic==true'));
		//inicio underline
		$this->addRule(AstsRule::update('#sauxe-inicio')->removeStyle('text-decoration')->when('newModel:inicio_underline==false'));
		$this->addRule(AstsRule::update('#sauxe-inicio')->setStyle('text-decoration:underline')->when('newModel:inicio_underline==true'));
		//viejo
		/*$this->addRule(PropiedRegla::declareRule('inicio-size')->to('inicio_font_size')->withValue('font-size:?px'));
		$this->addRule(PropiedRegla::declareRule('inicio-letra')->to('inicio_tipo_letra')->withValue('font-family:?'));
		$this->addRule(PropiedRegla::declareRule('inicio-text-color')->to('inicio_texto_hex')->withValue('color:#? !important'));
		*/
		//Migracion ASTS
		//inicio size,letra,color
		$this->addRule(AstsRule::update('#sauxe-inicio')->setStyle('font-size:?px;font-family:?;color:#? !important')->values('inicio_font_size,inicio_tipo_letra,inicio_texto_hex'));
		//viejo
		//$this->addRule(PropiedRegla::declareRule('inicio-color')->to('inicio_hex')->withValue('background-color:#?'));
		//$this->addRule(PropiedRegla::declareRule('inicio-border')->to('btn_inicio_border')->withValue('border: 1px solid #?'));
		//Migracion ASTS
		//inicio fondo
		$this->addRule(AstsRule::update('#sauxe-inicio-fondo')->setStyle('background-color:#?;border: 1px solid #?')->values('inicio_hex,btn_inicio_border'));
		
		//viejo barra
		//$this->addRule(PropiedRegla::declareRule('barra1')->to('barra_hex_sup')->withValue('background-color:black')->when('oldModel:shadow==? and newModel:shadow==?',array(false,true)));
		//$this->addRule(PropiedRegla::declareRule('barra2')->to('barra_hex_sup')->withValue('background-color:#?')->when('oldModel:shadow==? and newModel:shadow==?',array(true,false)));
		//$this->addRule(PropiedRegla::declareRule('barra3')->to('barra_hex_sup')->withValue('background-color:#?')->when('oldModel:shadow==? and newModel:shadow==?',array(false,false)));
		//barra
		$this->addRule(AstsRule::update('#sauxe-barra')->setStyle('background-color:black')->when('newModel:shadow==?',array(true)));
		$this->addRule(AstsRule::update('#sauxe-barra')->setStyle('background-color:#?')->values('barra_hex_sup')->when('newModel:shadow==?',array(false)));
		//$this->addRule(AstsRule::update('#sauxe-barra')->setStyle('background-color:#?')->values('barra_hex_sup')->when('oldModel:shadow==? and newModel:shadow==?',array(false,false)));
		
		
		//viejo
		//$this->addRule(PropiedRegla::declareRule('degrad-barra1')->to('degradadoBarra')->withValue('background-image: -moz-linear-gradient(top, rgba(?,1), rgba(?,0.5) 49%, rgba(0,0,0,0.5) 51%, rgba(0,0,0,1))')->when('newModel:shadow==?',array(true)));
		//$this->addRule(PropiedRegla::declareRule('degrad-barra2')->to('degradadoBarra')->withValue('background-image: -moz-linear-gradient(top, rgba(255,255,255,0.5), rgba(255,255,255,0.2) 49%, rgba(0,0,0,0.05) 51%, rgba(0,0,0,0.15))')->when('newModel:shadow==? and oldModel:shadow==?',array(false,true)));
		//ASTS Migracion
		$this->addRule(AstsRule::update('#sauxe-barra')->setStyle('background-image: -moz-linear-gradient(top, rgba(?,1), rgba(?,0.5) 49%, rgba(0,0,0,0.5) 51%, rgba(0,0,0,1))')->values('degradadoBarra')->when('newModel:shadow==?',array(true)));
		$this->addRule(AstsRule::update('#sauxe-barra')->setStyle('background-image: -moz-linear-gradient(top, rgba(255,255,255,0.5), rgba(255,255,255,0.2) 49%, rgba(0,0,0,0.05) 51%, rgba(0,0,0,0.15))')->when('newModel:shadow==?',array(false)));
		//viejo tarea
		//$this->addRule(PropiedRegla::declareRule('tarea-color')->to('tarea_hex')->withValue('background-color:#?'));
		//ASTS tarea
		$this->addRule(AstsRule::update('#sauxe-tarea')->setStyle('background-color:#?')->values('tarea_hex'));
		
		//viejo inicio hover,press
		//$this->addRule(PropiedRegla::declareRule('inicio-hover')->to('btn_inicio_hover')->withValue('background-color:#?'));
		//$this->addRule(PropiedRegla::declareRule('inicio-press')->to('btn_inicio_press')->withValue('background-color:#?'));
		//nuevo
		$this->addRule(AstsRule::update('#sauxe-inicio-hover')->setStyle('background-color:#?;border: 1px solid #?')->values('btn_inicio_hover,btn_inicio_hover'));
		$this->addRule(AstsRule::update('#sauxe-inicio-press')->setStyle('background-color:#?;border: 1px solid #?')->values('btn_inicio_press,btn_inicio_press'));
		
		//viejo menu header,dock
		//$this->addRule(PropiedRegla::declareRule('menu-header')->to('menu_header')->withValue('background-color: #?'));
		//$this->addRule(PropiedRegla::declareRule('menu-dock')->to('menu_dock_rgba')->withValue('background: ?'));
		//nuevo
		$this->addRule(AstsRule::update('#sauxe-menu-header')->setStyle('background-color: #?')->values('menu_header'));
		$this->addRule(AstsRule::update('#sauxe-menu-dock')->setStyle('background: ?')->values('menu_dock_rgba'));
		
		
		
	}
	
	
	
}

?>
