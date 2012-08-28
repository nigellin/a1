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
			<table border="1" id="result_table">
				<tr>
					<td>Wine Name</td>
					<td>Grape Variety</td>
					<td>Year</td>
					<td>Winery Name</td>
					<td>Region Name</td>
					<td>Total Bottle</td>
					<td>Cost ($)</td>
				</tr>
				<?php
					print get_search_result();
				?>
			</table>
		</div>
	</body>
</html>