<!DOCTYPE html >
<?php
/**
 * Support the htmlinject hook, which allows modules to change header, pre and post body on all pages.
 */
$this->data['htmlinject'] = array(
    'htmlContentPre' => array(),
    'htmlContentPost' => array(),
    'htmlContentHead' => array(),
);


$jquery = array();
if (array_key_exists('jquery', $this->data))
    $jquery = $this->data['jquery'];

if (array_key_exists('pageid', $this->data)) {
    $hookinfo = array(
        'pre' => &$this->data['htmlinject']['htmlContentPre'],
        'post' => &$this->data['htmlinject']['htmlContentPost'],
        'head' => &$this->data['htmlinject']['htmlContentHead'],
        'jquery' => &$jquery,
        'page' => $this->data['pageid']
    );

    SimpleSAML_Module::callHooks('htmlinject', $hookinfo);
}
// - o - o - o - o - o - o - o - o - o - o - o - o -
?>
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>

        <meta charset="UTF-8"/>
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Formulario de autenticación"/>
        <meta name="keywords" content="html5, css3, form, switch, animation, :target, pseudo-class"/>
        <meta name="author" content="abs"/>
        <link rel="shortcut icon" href="/<?php echo $this->data['baseurlpath']; ?>resources/assets/img/icon_1.gif">
        
		
		<link href="/<?php echo $this->data['baseurlpath']; ?>resources/assets/css/lib/bootstrap.css" rel="stylesheet">
		<link href="/<?php echo $this->data['baseurlpath']; ?>resources/assets/css/lib/bootstrap-responsive.css" rel="stylesheet">
		<link href="/<?php echo $this->data['baseurlpath']; ?>resources/assets/css/boo.css" rel="stylesheet">
		<link href="/<?php echo $this->data['baseurlpath']; ?>resources/assets/css/boo-coloring.css" rel="stylesheet">
		<link href="/<?php echo $this->data['baseurlpath']; ?>resources/assets/css/saue.css" rel="stylesheet">

        <title><?php
//echo "<pre>";print_r($this->data['header']);die;

if (array_key_exists('header', $this->data)) {
    //echo $this->data['header'];
    echo 'Universidad Ecotec';
} else {
    echo 'simpleSAMLphp';
}
?></title>

       <!--<link rel="icon" type="image/icon" href="/<?php /* echo $this->data['baseurlpath']; */ ?>resources/icons/favicon.ico" />-->

            <?php


            if (!empty($this->data['htmlinject']['htmlContentHead'])) {
                foreach ($this->data['htmlinject']['htmlContentHead'] AS $c) {
                    echo $c;
                }
            }
            ?>


        <meta name="robots" content="noindex, nofollow" />

    </head>
        <?php
        $onLoad = '';
        if (array_key_exists('autofocus', $this->data)) {
            $onLoad .= 'SimpleSAML_focus(\'' . $this->data['autofocus'] . '\');';
        }
        if (isset($this->data['onLoad'])) {
            $onLoad .= $this->data['onLoad'];
        }

        if ($onLoad !== '') {
            $onLoad = ' onload="' . $onLoad . '"';
        }
        ?>
    <body class="signin signin-horizontal">

        <div id="wrap">

            <div id="content">



            <?php
            if (!empty($this->data['htmlinject']['htmlContentPre'])) {
                foreach ($this->data['htmlinject']['htmlContentPre'] AS $c) {
                    echo $c;
                }
            }
