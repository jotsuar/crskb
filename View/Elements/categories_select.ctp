<script>
	var structure = <?php echo json_encode($categorias); ?>;
</script>

<?php echo $this->Html->script("controller/categories/categories_down.js?".rand(), array('block' => 'AppScript')); ?>

