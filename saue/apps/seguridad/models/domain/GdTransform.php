<?php


class GdTransform {
	
	
    function GdTransform() {
		
		
		
	}
	
	static public function resizeImageJpg($originalImage,$finalSource,$toWidth,$toHeight){
  /* Esta función redimensiona un archivo JPG manteniendo
   * su radio de aspecto original dentro de los límites
   * $toWidth y $toHeight.
   * Parámetros:
   * $originalImage: Nombre del archivo en formato JPG
   * a redimensionar.
   * $toWidth: Ancho máximo de la imágen redimensionada.
   * $toHeight: Alto máximo de la imágen redimensionada.
   * Devuelve una imágen en memoria con las proporciones
   * correctas.
   */
   
  list($width, $height) = getimagesize($originalImage);
   
  // Obtenemos la relación de tamaño respecto
  // al ancho y alto máximo.
  $xscale=$width/$toWidth;
  $yscale=$height/$toHeight;
 
  // Cuando la escala en y es mayor que la escala en x
  // implica que debemos redimensionar en base al nuevo
  // alto.
  if ($yscale>$xscale){
    $new_width = round($width * (1/$yscale));
    $new_height = round($height * (1/$yscale));
     
  // Por el contrario si la escala en x es mayor o igual
  // debemos de redimensionar en base al nuevo ancho.
  } else {
    $new_width = round($width * (1/$xscale));
    $new_height = round($height * (1/$xscale));
  }
   
  // Reservamos espacio en memoria para la nueva imágen
  $imageResized = imagecreatetruecolor($new_width, $new_height);
   
  // Cargamos la imágen original y redimensionamos
  $imageTmp     = imagecreatefromjpeg ($originalImage);
  imagecopyresampled($imageResized, $imageTmp,
    0, 0, 0, 0, $new_width, $new_height, $width, $height);
 $calidad=95;
//Se crea la imagen final en el directorio indicado
imagejpeg($imageResized,$finalSource,$calidad);
  // Devolvemos la nueva imágen redimensionada.
  
  
}
	
static public function resize($img, $w, $h, $newfilename) {
 
 //Check if GD extension is loaded
 if (!extension_loaded('gd') && !extension_loaded('gd2')) {
  trigger_error("GD is not loaded", E_USER_WARNING);
  return false;
 }
 
 //Get Image size info
 $imgInfo = getimagesize($img);
 switch ($imgInfo[2]) {
  case 1: $im = imagecreatefromgif($img); break;
  case 2: $im = imagecreatefromjpeg($img);  break;
  case 3: $im = imagecreatefrompng($img); break;
  default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
 }
 
 //If image dimension is smaller, do not resize

                //yeah, resize it, but keep it proportional
  
   $nWidth = $w;
  
   $nHeight = $h;
  

 $nWidth = round($nWidth);
 $nHeight = round($nHeight);
 
 $newImg = imagecreatetruecolor($nWidth, $nHeight);
 
 /* Check if this image is PNG or GIF, then set if Transparent*/  
 if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
  imagealphablending($newImg, false);
  imagesavealpha($newImg,true);
  $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
  imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
 }
 imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
 
 //Generate the file, and rename it to $newfilename
 switch ($imgInfo[2]) {
  case 1: imagegif($newImg,$newfilename); break;
  case 2: imagejpeg($newImg,$newfilename);  break;
  case 3: imagepng($newImg,$newfilename); break;
  default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
 }
   
   return $newfilename;
}

	
	
	static public function createBody($tema,$color,$opacidad){
		$imgname = '../lib/ExtJS/temas/working directory/bodyB.png';        
    $im = imagecreatefrompng ($imgname);              
  // Crear una imagen de 100x100
$rgb=GdTransform::hex2RGB($color);
$opacity=$opacidad*1.27;
$opacity=127-$opacity;


imagealphablending($im, false);
imagesavealpha($im, true);

$negro = imagecolorallocatealpha($im, $rgb['red'],$rgb['green'],$rgb['blue'],$opacity);
 
// Dibujar el fondo transparente
imagefilledrectangle($im, 5, 0, 895, 15, $negro);
imagefilledrectangle($im, 10, 15, 890, 20, $negro);
imagefilledellipse($im,10,15,10, 10, $negro);
imagefilledellipse($im,890,15,10, 10, $negro);
// Dibujar un rectángulo rojo


         imagepng($im, '../lib/ExtJS/temas/'.$tema.'/css/images/sheet_b.png');             
         imagedestroy($im); 
         
 $imgname = '../lib/ExtJS/temas/working directory/bodyT.png';          
    $im = imagecreatefrompng ($imgname);              
  // Crear una imagen de 100x100

imagealphablending($im, false);
imagesavealpha($im, true);

$negro = imagecolorallocatealpha($im, $rgb['red'],$rgb['green'],$rgb['blue'],$opacity);
 
// Dibujar el fondo transparente
imagefilledrectangle($im, 5, 10, 895, 25, $negro);
imagefilledrectangle($im, 10, 5, 890, 10, $negro);
imagefilledellipse($im,10,10,10, 10, $negro);
imagefilledellipse($im,890,10,10, 10, $negro);
// Dibujar un rectángulo rojo


         imagepng($im, '../lib/ExtJS/temas/'.$tema.'/css/images/sheet_t.png');             
         imagedestroy($im);                    
   
   
    $imgname = '../lib/ExtJS/temas/working directory/body.png';            
    $im = imagecreatefrompng ($imgname);              
  // Crear una imagen de 100x100


imagealphablending($im, false);
imagesavealpha($im, true);

$negro = imagecolorallocatealpha($im, $rgb['red'],$rgb['green'],$rgb['blue'],$opacity);
 
// Dibujar el fondo transparente
imagefilledrectangle($im, 5, 0, 895, 3, $negro);


// Dibujar un rectángulo rojo


         imagepng($im,'../lib/ExtJS/temas/'.$tema.'/css/images/sheet.png');             
         imagedestroy($im); 
		
	}
	
	static public function createFooter($tema,$color){
		$imgname = '../lib/ExtJS/temas/working directory/footer-1.png';          
		$im = imagecreatefrompng ($imgname);              
		// Crear una imagen de 100x100
		$rgb=GdTransform::hex2RGB($color);
		imagealphablending($im, true);
		imagesavealpha($im, true);
		$rojo = imagecolorallocate($im, 255,255, 255);
		$negro = imagecolorallocatealpha($im,$rgb['red'],$rgb['green'],$rgb['blue'],50);
 
		// Dibujar el fondo transparente
		imagefilledrectangle($im, 0, 0,64,52, $negro);


		// Dibujar un rectángulo rojo


         imagepng($im, '../lib/ExtJS/temas/'.$tema.'/cache/footer-1_1.png');             
         imagedestroy($im);
	}
	
	static public function createBoton($tema,$color,$color2,$dir){
		$imgname ='../'.$dir;          
		$im = imagecreatefrompng ($imgname);              
		// Crear una imagen de 100x100
		$rgb=GdTransform::hex2RGB($color);
		imagealphablending($im, true);
		imagesavealpha($im, true);
		$rojo = imagecolorallocate($im, 255,255, 255);
		$negro = imagecolorallocatealpha($im,$rgb['red'],$rgb['green'],$rgb['blue'],50);
 
		// Dibujar el fondo transparente
		imagefilledrectangle($im, 0, 0,64,52, $negro);


		// Dibujar un rectángulo rojo


         imagepng($im, '../lib/ExtJS/temas/'.$tema.'/cache/texture_bot_1.png');             
         imagedestroy($im);
         
         $im2 = imagecreatefrompng ($imgname);              
		// Crear una imagen de 100x100
		$rgb2=GdTransform::hex2RGB($color2);
		imagealphablending($im2, true);
		imagesavealpha($im2, true);
		
		$negro2 = imagecolorallocatealpha($im2,$rgb2['red'],$rgb2['green'],$rgb2['blue'],50);
 
		// Dibujar el fondo transparente
		imagefilledrectangle($im2, 0, 0,64,52, $negro2);


		// Dibujar un rectángulo rojo


         imagepng($im2, '../lib/ExtJS/temas/'.$tema.'/cache/texture_bot_1_hover.png');             
         imagedestroy($im2);
	}
	
	
	static public function createBar($tema,$color,$opacidad){
		$imgname = '../lib/ExtJS/temas/working directory/nav.png';           
    $im = imagecreatefrompng ($imgname);              
  // Crear una imagen de 100x100
$rgb=GdTransform::hex2RGB($color);
$opacity=$opacidad*1.27;
$opacity=127-$opacity;

imagealphablending($im, false);
imagesavealpha($im, true);

$negro = imagecolorallocatealpha($im, $rgb['red'],$rgb['green'],$rgb['blue'],$opacity);
 
// Dibujar el fondo transparente
imagefilledrectangle($im, 5, 0, 895, 37, $negro);
imagefilledrectangle($im, 10, 37, 890, 42, $negro);
imagefilledellipse($im,10,37,10, 10, $negro);
imagefilledellipse($im,890,37,10, 10, $negro);
// Dibujar un rectángulo rojo


         imagepng($im,'../lib/ExtJS/temas/'.$tema.'/css/images/nav.png');             
         imagedestroy($im);
		
	}
	
	static public function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}
	
}

?>
