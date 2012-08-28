<?php
	require "initialize.php";
	require "functions.php";
?>
<html>
	<head>
		<title>Search Result</title>
		<link rel="stylesheet" href="style.css"/>
	</head>
	<body>
		<div>
			<?php
				print get_search_result();
				mysql_close();
			?>
		</div>
	</body>
</html>