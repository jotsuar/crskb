<div class="error404 text-center">
	<!-- <img src="<?php echo $this->Html->url('/img/back404.png'); ?>" class="img-fluid"> -->
	<span class="errorspan">ERROR</span>
	<div class="efectoletra sizebig">404</div>
	<div class="efectoletra text-center">TODO IBA BIEN !</div>
	<h2 class="copieerror"><?php echo $message; ?></h2>
</div>
<?php 
	echo $this->Html->script(array('lib/jquery-3.0.0.js?'.rand()));
?>