<?php


class ComercialCSSBase extends CSSHandler{
	
	/*
    function ComercialCSSBase($model,$comparisonModel) {
		$this->setModel($model,$comparisonModel);
		
		//Slogan
		$this->setSource('../lib/ExtJS/temas/'.$model['nombre_tema'].'/css/style.css');
		$this->addRule(PropiedRegla::declareRule('SloganNegrita1')->to('slogan_bold')->replace()->when('newModel:slogan_bold==false'));
		$this->addRule(PropiedRegla::declareRule('SloganNegrita2')->to('slogan_bold')->withValue('font-weight:bold')->when('newModel:slogan_bold==true'));
		$this->addRule(PropiedRegla::declareRule('SloganItalic1')->to('slogan_italic')->replace()->when('newModel:slogan_italic==false'));
		$this->addRule(PropiedRegla::declareRule('SloganItalic2')->to('slogan_italic')->withValue('font-style:italic')->when('newModel:slogan_italic==true'));
		$this->addRule(PropiedRegla::declareRule('SloganUnderline1')->to('slogan_underline')->replace()->when('newModel:slogan_underline==false'));
		$this->addRule(PropiedRegla::declareRule('SloganUnderline2')->to('slogan_underline')->withValue('text-decoration:underline')->when('newModel:slogan_underline==true'));
		$this->addRule(PropiedRegla::declareRule('SloganInicioSize')->to('slogan_size')->withValue('font-size:?px'));
		$this->addRule(PropiedRegla::declareRule('SloganInicioLetra')->to('slogan_font')->withValue('font-family:?'));
		$this->addRule(PropiedRegla::declareRule('SloganColor')->to('slogan_color')->withValue('color:#? !important'));
		//Bloques de Contenido
		$this->addRule(PropiedRegla::declareRule('BloqContentBold1')->to('bloq_bold')->replace()->when('newModel:bloq_bold==false'));
		$this->addRule(PropiedRegla::declareRule('BloqContentBold2')->to('bloq_bold')->withValue('font-weight:bold')->when('newModel:bloq_bold==true'));
		$this->addRule(PropiedRegla::declareRule('BloqContentItalic1')->to('bloq_italic')->replace()->when('newModel:bloq_italic==false'));
		$this->addRule(PropiedRegla::declareRule('BloqContentItalic2')->to('bloq_italic')->withValue('font-style:italic')->when('newModel:bloq_italic==true'));
		$this->addRule(PropiedRegla::declareRule('BloqContentUnderline1')->to('bloq_underline')->replace()->when('newModel:bloq_underline==false'));
		$this->addRule(PropiedRegla::declareRule('BloqContentUnderline2')->to('bloq_underline')->withValue('text-decoration:underline')->when('newModel:bloq_underline==true'));
		$this->addRule(PropiedRegla::declareRule('BloqContentSize')->to('bloq_size')->withValue('font-size:?px'));
		$this->addRule(PropiedRegla::declareRule('BloqContentFont')->to('bloq_font')->withValue('font-family:?'));
		$this->addRule(PropiedRegla::declareRule('BloqContentColor')->to('bloq_color')->withValue('color:#?'));
		$this->addRule(PropiedRegla::declareRule('BloqHeaderColor')->to('bloq_header_color')->withValue('color:#?'));
		$this->addRule(PropiedRegla::declareRule('BloqHeaderBackground')->to('bloq_header_background')->withValue('background:#?'));
		//Navegacion
		$this->addRule(PropiedRegla::declareRule('NavColor')->to('nav_color')->withValue('color:#?'));
		$this->addRule(PropiedRegla::declareRule('NavBackground')->to('nav_background')->withValue('background:#?'));
		$this->addRule(PropiedRegla::declareRule('NavHover')->to('nav_hover')->withValue('background:#?'));
		$this->addRule(PropiedRegla::declareRule('NavHover2')->to('nav_hover2')->withValue('background:#?'));
		$this->addRule(PropiedRegla::declareRule('NavColorHover')->to('nav_color_hover')->withValue('color:#?'));
		//textura
		$this->addRule(PropiedRegla::declareRule('NavTexture')->to('nav_img_texture')->withValue('background-image:url("images/texture_bot.png")')->when('newModel:nav_texture==?',array(true)));
		$this->addRule(PropiedRegla::declareRule('NavTextureCover')->to('nav_img_texture_cover')->withValue('background-size:cover')->when('newModel:nav_texture==?',array(true)));
		
		$this->addRule(PropiedRegla::declareRule('NavTexture2')->to('nav_img_texture')->replace()->when('newModel:nav_texture==?'),array(false));
		$this->addRule(PropiedRegla::declareRule('NavTextureCover2')->to('nav_img_texture_cover')->replace()->when('newModel:nav_texture==?'),array(false));
		//hover texture
		$this->addRule(PropiedRegla::declareRule('NavTextureHover')->to('nav_img_texture_hover')->withValue('background-image:url("images/texture_bot_hover.png")')->when('newModel:nav_texture==?',array(true)));
		$this->addRule(PropiedRegla::declareRule('NavTextureCoverHover')->to('nav_img_texture_cover_hover')->withValue('background-size:cover')->when('newModel:nav_texture==?',array(true)));
		
		$this->addRule(PropiedRegla::declareRule('NavTexture2Hover')->to('nav_img_texture_hover')->replace()->when('newModel:nav_texture==?'),array(false));
		$this->addRule(PropiedRegla::declareRule('NavTextureCover2Hover')->to('nav_img_texture_cover_hover')->replace()->when('newModel:nav_texture==?'),array(false));
		
		//menu
		$this->addRule(PropiedRegla::declareRule('NavMenuBackground')->to('nav_menu_background')->withValue('background:#?'));
		$this->addRule(PropiedRegla::declareRule('NavMenuBorder')->to('nav_menu_border')->withValue('border-color:#?'));
		$this->addRule(PropiedRegla::declareRule('NavMenuColor')->to('nav_menu_color')->withValue('color:#?'));
		$this->addRule(PropiedRegla::declareRule('NavMenuBackgroundHover')->to('nav_menu_background_hover')->withValue('background:#?'));
		$this->addRule(PropiedRegla::declareRule('NavMenuBorderHover')->to('nav_menu_border_hover')->withValue('border-color:#?'));
		$this->addRule(PropiedRegla::declareRule('NavMenuColorHover')->to('nav_menu_color_hover')->withValue('color:#?'));
		
		
		
		
		
		
	}
	*/
	function ComercialCSSBase($model,$comparisonModel=array()) {
		$this->setModel($model,$comparisonModel);
		/********** Configuracion de las propiedades CSS a modificar **********/
		//Slogan
		$this->setSource('../lib/ExtJS/temas/'.$model['nombre_tema'].'/css/style.css');

		
		$this->addRule(AstsRule::update('#sauxe-comercial-slogan')->removeStyle('font-weight')->when('newModel:slogan_bold==false'));
		$this->addRule(AstsRule::update('#sauxe-comercial-slogan')->setStyle('font-weight:bold')->when('newModel:slogan_bold==true'));
		$this->addRule(AstsRule::update('#sauxe-comercial-slogan')->removeStyle('font-style')->when('newModel:slogan_italic==false'));
		$this->addRule(AstsRule::update('#sauxe-comercial-slogan')->setStyle('font-style:italic')->when('newModel:slogan_italic==true'));
		$this->addRule(AstsRule::update('#sauxe-comercial-slogan')->removeStyle('text-decoration')->when('newModel:slogan_underline==false'));
		$this->addRule(AstsRule::update('#sauxe-comercial-slogan')->setStyle('text-decoration:underline')->when('newModel:slogan_underline==true'));
		$this->addRule(AstsRule::update('#sauxe-comercial-slogan')->setStyle('font-size:?px;font-family:?;color:#? !important')->values('slogan_size,slogan_font,slogan_color'));
		
		
		//Bloques de Contenido
		
		
		$this->addRule(AstsRule::update('#sauxe-comercial-bloq')->removeStyle('font-weight')->when('newModel:bloq_bold==false'));
		$this->addRule(AstsRule::update('#sauxe-comercial-bloq')->setStyle('font-weight:bold')->when('newModel:bloq_bold==true'));
		$this->addRule(AstsRule::update('#sauxe-comercial-bloq')->removeStyle('font-style')->when('newModel:bloq_italic==false'));
		$this->addRule(AstsRule::update('#sauxe-comercial-bloq')->setStyle('font-style:italic')->when('newModel:bloq_italic==true'));
		$this->addRule(AstsRule::update('#sauxe-comercial-bloq')->removeStyle('text-decoration')->when('newModel:bloq_underline==false'));
		$this->addRule(AstsRule::update('#sauxe-comercial-bloq')->setStyle('text-decoration:underline')->when('newModel:bloq_underline==true'));
		
		$this->addRule(AstsRule::update('#sauxe-comercial-bloq')->setStyle('font-size:?px;font-family:?;color:#?')->values('bloq_size,bloq_font,bloq_color'));
		$this->addRule(AstsRule::update('#sauxe-comercial-header')->setStyle('color:#?')->values('bloq_header_color'));
		$this->addRule(AstsRule::update('#sauxe-comercial-back-header')->setStyle('background:#?')->values('bloq_header_background'));
		
		//Navegacion
		
		
		$this->addRule(AstsRule::update('#sauxe-comercial-navcolor')->setStyle('color:#?')->values('nav_color'));
		$this->addRule(AstsRule::update('#sauxe-comercial-navback')->setStyle('background:#?')->values('nav_background'));
		$this->addRule(AstsRule::update('#sauxe-comercial-nav-hover')->setStyle('background:#?')->values('nav_hover'));
		$this->addRule(AstsRule::update('#sauxe-comercial-nav-hover2')->setStyle('background:#?')->values('nav_hover2'));
		$this->addRule(AstsRule::update('#sauxe-comercial-nav-hover3')->setStyle('color:#?')->values('nav_color_hover'));
		//textura
		
		
		$this->addRule(AstsRule::update('#sauxe-comercial-navback')->setStyle('background-image:url("images/texture_bot.png");background-size:cover')->when('newModel:nav_texture==?',array(true)));
		$this->addRule(AstsRule::update('#sauxe-comercial-navback')->removeStyle('background-image,background-size')->when('newModel:nav_texture==?',array(false)));
		
		//hover texture
		
		
		$this->addRule(AstsRule::update('#sauxe-comercial-nav-hover')->setStyle('background-image:url("images/texture_bot_hover.png");background-size:cover')->when('newModel:nav_texture==?',array(true)));
		$this->addRule(AstsRule::update('#sauxe-comercial-nav-hover')->removeStyle('background-image,background-size')->when('newModel:nav_texture==?',array(false)));
		
		
		//menu
		
		
		$this->addRule(AstsRule::update('#sauxe-comercial-nav-menu')->setStyle('background:#?;border-color:#?;color:#?')->values('nav_menu_background,nav_menu_border,nav_menu_color'));
		//hover
		
		
		$this->addRule(AstsRule::update('#sauxe-comercial-nav-menu-hover')->setStyle('background:#?;color:#?')->values('nav_menu_background_hover,nav_menu_color_hover'));
		$this->addRule(AstsRule::update('#sauxe-comercial-nav-menu-hover2')->setStyle('border-color:#?')->values('nav_menu_border_hover'));
		
		
		
		
	}
	
	
	
}

?>
