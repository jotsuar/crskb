
	<header>
		<div class="divContenedor2">
		<table cellpadding="0" cellspacing="0" class="tableproductsst" border="1" >
			<tbody>
				<tr>
					<td>
						<div class="center">
							<img src="<?php echo WWW_ROOT.'/img/assets/logoproveedor.jpg' ?> " class="imgFull">
						</div>
						<p style="font-size: small; margin-left: 30px;"><b>ACT. ECONÓMICA 4659. RESPONSABLE DE IVA</b> <br>NO AUTORRETENEDOR. NO GRAN CONTRIBUYENTE</p></p>
						<p>
					</td>	
					<td style="width: 50px">
						
					</td>	
					<td style="width: 230px; display: block">
						<b><?php echo Configure::read("COMPANY.NAME") ?></b>
						<b><?php echo Configure::read("COMPANY.NIT") ?></b>
						<b><?php echo Configure::read("COMPANY.ADDRESS") ?></b>
						<b><?php echo Configure::read("COMPANY.CITY") ?></b>
		
					</td>
				</tr>
			</tbody>
		</table>
	</div>
		
	</header>

	<?php 
	echo $this->Html->script(array('lib/jquery-2.1.0.min.js?'.rand()),				array('block' => 'jqueryApp'));
?>
