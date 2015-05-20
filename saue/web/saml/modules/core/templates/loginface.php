<?php
assert('array_key_exists("retryURL", $this->data)');
$retryURL = $this->data['retryURL'];
$this->data['header'] = $header;
$this->includeAtTemplateBase('includes/header.php');
//print_r($this->configuration->tema);die;
$this->data['stateparams'] ['AuthState']= $_REQUEST['AuthState'];

?>
        
 
 <script type="text/javascript" src="../../../lib/UCID/js/imporcss.js"></script>
  <link rel="stylesheet" type="text/css" href="../../../lib/ExtJS/temas/<?php echo $this->configuration->tema;?>/css/ext-all.css"/>
 <script type="text/javascript" src="../../../lib/ExtJS/idioma/es/js/ext-base.js"></script>
 <script type="text/javascript" src="../../../lib/ExtJS/idioma/es/js/ext-all.js"></script>
 <script type="text/javascript" src="../../../lib/UCID/js/ucid-all.js"></script>
 <script type="text/javascript" src="jpegcam-1.0.9/webcam.js"></script>
	<h4 style="break: both"></h4>
	<p><?php echo ''; ?></p>
	<p><?php echo ''; ?></p>
	<p><?php echo ''; ?></p>
	<p><?php echo ''; ?></p>
        
        
        
        
       

        
        
        
        
        
        
           <?php
          
if ($this->data['errorcode'] !== NULL) {
 if($this->data['errorcode'] == 'WRONRECOGNITION'){ 
?>
	<!--<p><?php /*echo $this->t('{login:user_pass_text}');*/ ?></p>-->
	<div style="" id ="sss">
	<img src="/<?php echo $this->data['baseurlpath']; ?>resources/icons/experience/gtk-dialog-error.48x48.png" style="float: left; margin: 0px 0px 0px 0px "/>
	 
	<?php echo '<h1 style="color:#FF0000">'.$this->t('{login:wronrecogniton}').'</h1>'; ?>
	</div>
	<?php
}
else if($this->data['errorcode'] == 'EXPIRE'){
?>
<div style="">
	<img src="/<?php echo $this->data['baseurlpath']; ?>resources/icons/experience/gtk-dialog-error.48x48.png" style="float: left; margin: 0px 0px 0px 0px "/>
	 
	<?php echo '<h1 style="color:#FF0000">'.$this->t('{login:expire}').'</h1>'; ?>
	</div>
	
<?php
}
else if($this->data['errorcode'] == 'BLOCKUSER'){
?>
<div style="">
	<img src="/<?php echo $this->data['baseurlpath']; ?>resources/icons/experience/gtk-dialog-error.48x48.png" style="float: left; margin: 0px 0px 0px 0px "/>
	 
	<?php echo '<h1 style="color:#FF0000">'.$this->t('{login:blockuser}').'</h1>'; ?>
	</div>
<?php
}}
?>
        

     
        
<br>
<script language="JavaScript">
webcam.set_api_url('jpegcam-1.0.9/test.php' );
		webcam.set_quality( 90 ); // JPEG quality (1 - 100)
		webcam.set_shutter_sound( true ); // play shutter click sound
	</script>
         <svg version="1.1" width="320" height="250" xmlns="http://www.w3.org/2000/svg" style="background:transparent;position:absolute;left:50;top:50;z-index:100"> <ellipse fill="url(#circleGrad)" stroke="#F20000" cx="50%" cy="45%" rx="15%" ry="30%"> </ellipse> </svg>
        <script language="JavaScript">   



		document.write( webcam.get_html(320, 240) );
	</script>
     
        

	<form action="?" method="post" name="f">

	<table>	
		
		<div id="mycenter">			

			
									
		
                <td><input type="hidden" id="username" tabindex="1" name="username" value="<?php echo $_REQUEST['username'] ?>" /></td>	
		<td><input id="password" type="hidden" tabindex="2" name="password" value="<?php echo $_REQUEST['password'] ?>" /></td>
                <?php
foreach ($this->data['stateparams'] as $name => $value) {
	echo('<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" />');
}
?>
               
                <td><input id="tipo" type="hidden" tabindex="2" name="tipo" value="1" /></td>
		<tr>
				
		
			</tr>
                <tr>
		
		</tr>
		<tr>
                    <br/>
                    <input id="capture" type=button value="Capturar" onClick="do_upload()">
		    &nbsp;&nbsp;
                    <input id="login" type="submit" tabindex="4" value="Entrar"disabled = true />
			
			</tr>
                        
                   
                        
                        
                        
        

<?php
if (array_key_exists('organizations', $this->data)) {
?>
		<tr>
			<td><?php echo $this->t('{login:organization}'); ?></td>

			</select></td>
		</tr>
<?php
}
?>

	</table>



	</form>
        <script language="JavaScript">
		function do_upload() 
                {
                     
                     webcam.snap();
                     webcam.reset();
                     document.getElementById('login').disabled = false;
		}
	</script>
       

                         
                         
       
        
	
          
             
          	
	
	
<?php






echo('<h2></h2>');
echo('<p>Copyright &copy; 2013 UCI </p>');

//$this->includeAtTemplateBase('includes/footer.php');


