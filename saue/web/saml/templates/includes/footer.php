<?php

//echo "<pre>";print_r($this->data);die;

if(!empty($this->data['htmlinject']['htmlContentPost'])) {
	foreach($this->data['htmlinject']['htmlContentPost'] AS $c) {
		echo $c;
	}
}


?>



		<hr />

		<img src="/<?php echo $this->data['baseurlpath']; ?>resources/icons/ssplogo-fish-small1.png" alt="" style="float: right" />		
		<!-- <a href="http://rnd.feide.no/">Feide RnD</a>-->
		
		<br style="clear: right" />
	
	</div><!-- #content -->

</div><!-- #wrap -->

</body>
</html>
