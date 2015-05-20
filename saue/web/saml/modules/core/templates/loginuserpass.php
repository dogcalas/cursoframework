<?php


$this->data['header'] = $this->t('{login:user_pass_header}');

if (strlen($this->data['username']) > 0) {
    $this->data['autofocus'] = 'password';
} else {
    $this->data['autofocus'] = 'username';
}
$this->includeAtTemplateBase('includes/header.php');
?>



<div class="page-container">
   
    <!-- // header-container -->
    
    <div id="main-container">
        <div id="main-content" class="main-content container">
            <div id="page-content" class="page-content">
                <div class="row">
                    <div class="tab-content overflow form-dark">
                        <div class="tab-pane fade in active" id="login">
                            <div class="span5">
                                <h4 class="welcome"> <small><i class="fontello-icon-user-4"></i></small><img src="/<?php echo $this->data['baseurlpath']; ?>resources/assets/img/org.png"></h4>
        <label class="uname">
        <?php


        if ($this->data['errorcode'] !== NULL) {
            if ($this->data['errorcode'] == 'WRONGUSERPASS') {
                ?>
                <div class="error">
					
                    <?php echo '<h5><i class="icon-info-red" style="margin-right: 5px"></i>' . $this->t('{login:error_header}') . '</h4>'; ?>
                </div>
            <?php


            } else
                if ($this->data['errorcode'] == 'EXPIRE') {
                    ?>
                    <div class="error">
                        <?php echo '<h5><i class="icon-info-red" style="margin-right: 5px">' . $this->t('{login:expire}') . '</h5>'; ?>
                    </div>

                <?php


                } else
                    if ($this->data['errorcode'] == 'BLOCKUSER') {
                        ?>
                        <div class="error">
                            <?php echo '<h5><i class="icon-info-red" style="margin-right: 5px">' . $this->t('{login:blockuser}') . '</h5>'; ?>
                        </div>
                    <?php


                    }
        }

        ?>
        </label>
        
                                <form method="post" >
                                    <fieldset>
                                        <div class="controls">
                                            <input id="idLogin" class="span5" style="background: white;" type="text" name="username" value="<?php htmlspecialchars($this->data['username']) ?>" placeholder="Usuario">
                                        </div>
                                        <div class="controls controls-row">
                                            <input id="idPassword" class="span3" style="background: white;" type="password" name="password" placeholder="Contraseña">
                                            <button type="submit" class="span2 btn btn-primary btn-large">Ingresar</button>
                                        </div>
                                        <hr class="margin-xm">
                                        <label class="checkbox">
                                            <input id="remember" class="checkbox" type="checkbox">
                                            Recuerdame</label>
                                        <hr class="margin-mm ">
                                        
                                    </fieldset>
                                     <?php
										foreach ($this->data['stateparams'] as $name => $value) {
											echo ('<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '" />');
										}
										?>
                                </form>
                                <!-- // form --> 
                            </div>
                            <div class="span7 inform">
                                
                                
                                <h4 class="welcome"><small style="color:#1F72DC">MISIÓN</small></h4>
                                <hr>
                                <img style="float:right;margin: 15px;" src="/<?php echo $this->data['baseurlpath']; ?>resources/images/emp2.png" width="100">
                                <p style=""> Brindar una enseñanza de calidad que impulse la generación de conocimiento permitiendo la formación de personas comprometidas a ser promotoras y agentes transformadores de su entorno, preparadas para la investigación práctica en su campo laboral, y alineadas al PNBV </p>
                                
                                
                                <h4 class="welcome"><small style="color:#2BCA0A">VISIÓN</small></h4>
                                <hr>
                                <img style="float:right;margin: 15px;" src="/<?php echo $this->data['baseurlpath']; ?>resources/images/campus.png" width="100">
                                <p style=""> Para el 2019 ECOTEC será referente de las Universidades Particulares del Ecuador por la excelencia de su cuerpo docente, estudiantes y graduados, contribuyendo al desarrollo productivo-social y a la investigación científica del país  </p>
                                
                            </div>
                        </div>
                        <!-- // Tab Login -->
                        
                        
                        <div class="web-description span12">
                            <h5>Copyright &copy; 2015 </h5>
                            <p>XOA <br />Todos los derechos reservados.</p>
                        </div>
                    </div>
                    
                </div>

                
            </div>
            <!-- // page content --> 
            
        </div>
        <!-- // main-content --> 
        
    </div>
    <!-- // main-container  --> 
    
</div>
<!-- // page-container --> 

<!-- Le javascript --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="/<?php echo $this->data['baseurlpath']; ?>resources/assets/js/lib/jquery.js"></script> 

<script src="/<?php echo $this->data['baseurlpath']; ?>resources/assets/js/lib/bootstrap/bootstrap.js"></script> 




