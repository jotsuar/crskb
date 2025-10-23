<div>
	<head>
		<meta charset="UTF-8"/>
		<meta name="keywords" charset="UTF-8" content="Meta Tags, Metadata" /> 
		<link rel="stylesheet" type="text/css" href="<?php echo Router::url("/",true) ?>/css/stylePdf.css?<?php echo time() ?>">
	</head>
	<body>
		<div>
			<?php 
				echo $this->fetch('content');
			?>
		</div>
	</body>
</div>