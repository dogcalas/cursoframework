<?php


class AutenticacionCSSBase extends CSSHandler{
	
	
	function AutenticacionCSSBase($model,$comparisonModel=array()) {
		$this->setModel($model,$comparisonModel);
		/********** Configuracion de las propiedades CSS a modificar **********/
		//Slogan
		$this->setSource('../saml/www/resources/default.css');

		
		$this->addRule(AstsRule::update('body')->setStyle('background-color:#?;')->values('altcolor'));
		$this->addRule(AstsRule::update('td')->setStyle('color:#?;')->values('etiquetas'));
		$this->addRule(AstsRule::update('#languagebar')->setStyle('color:#?;')->values('idioma_sel'));
		$this->addRule(AstsRule::update('#languagebar a')->setStyle('color:#?;')->values('idioma_no_sel'));
		$this->addRule(AstsRule::update('#btn-entrada')->setStyle('color:#?;background:#?')->values('submit_color,submit_fondo'));
		$this->addRule(AstsRule::update('#btn-entrada-hover')->setStyle('color:#?;background:#?')->values('submit_color_hover,submit_fondo_hover'));
		$this->addRule(AstsRule::update('#text-user')->setStyle('color:#?;background:#?')->values('input_color,input_fondo'));
		$this->addRule(AstsRule::update('#text-pass')->setStyle('color:#?;background:#?')->values('input_color,input_fondo'));
		$this->addRule(AstsRule::update('#content p')->setStyle('color:#?;')->values('copyrigth'));
		$this->addRule(AstsRule::update('#barras')->setStyle('border-bottom: 2px solid #?;')->values('bar_horiz'));
		
		
	}
	
	
	
}

?>
