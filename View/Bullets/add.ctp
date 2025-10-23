	<div class="row">
		
	
		<div class="col-md-9">
			<?php
			$id = "Bid_".time();
			echo $this->Form->input('bullets.title.',["id" => $id]);
		?>
		</div>
		<div class="col-md-3">
			<a href="#" class="btn btn-danger mt-4 deleteBull" data-id="<?php echo $id ?>">
				<i class="fa fa-trash"></i>
			</a>
		</div>
	</div>