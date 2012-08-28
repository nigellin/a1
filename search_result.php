<?php
	require "initialize.php";
	require "functions.php";
?>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="style.css"/>
	</head>
	<body>
		<div>
			<?php
				print get_search_result();
			?>
		</div>
	</body>
</html>