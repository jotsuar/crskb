<?php $urlHeader = $this->Html->url('/img/header/header/'.$header['Header']['img_big']); ?>
<?php $urlfooter = $this->Html->url('/img/header/header/'.$header['Header']['img_small']); ?>
<?php 
		
		echo json_encode( array(
			"imgHeader" => '<img src="'.$this->Html->url('/img/header/header/'.$header['Header']['img_big']).'" width="100%">',
			"urlfooter" => '<img src="'.$this->Html->url('/img/header/miniatura/'.$header['Header']['img_small']).'" width="100%">',
		));
		
 ?>

 <?php 


 /**
  * <?php $urlHeader = $this->Html->url('/img/header/header/'.$header['Header']['img_big']); ?>
<?php $urlfooter = $this->Html->url('/img/header/header/'.$header['Header']['img_small']); ?>
<?php 
		
		echo json_encode(compact("urlHeader","urlfooter"));
		
 ?>
  */

  ?>