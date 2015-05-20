<?php 

$this->data['header'] = $this->t('{core:frontpage:page_title}');
$this->includeAtTemplateBase('includes/header.php'); 

?>


<?php
if ($this->data['isadmin']) {
	echo '<p style="float: right">' . $this->t('{core:frontpage:loggedin_as_admin}') . '</p>';
} else {
	echo '<p style="float: right"><a href="' . $this->data['loginurl'] . '">' . $this->t('{core:frontpage:login_as_admin}') . '</a></p>';
}
?>

<p><?php echo $this->t('{core:frontpage:intro}'); ?></p>


<ul>
<?php
	foreach ($this->data['links_welcome'] AS $link) {
		echo '<li><a href="' . htmlspecialchars($link['href']) . '">' . $this->t($link['text']) . '</a></li>';
	}
?>
</ul>
	
	
	
	<h2><?php echo $this->t('{core:frontpage:about_header}'); ?></h2>
		<p><?php echo $this->t('{core:frontpage:about_text}'); ?></p>



		
<?php $this->includeAtTemplateBase('includes/footer.php'); ?>