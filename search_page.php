<!DOCTYPE html>
<?php
	require "initialize.php";
	require "functions.php";
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>WDA Assignment 1</title>
		<link rel="stylesheet" href="style.css"/>
    </head>
    <body>
		<div>
			<form name="search_form" method="get" autocomplete="off" action="search_result.php" class="search_form">
				<table id="search_form_table">
					<tr>
						<td>Wine Name:</td>
						<td>
							<input name="wine_name" type="text"/>
						</td>
					</tr>
					<tr>
						<td>Winery Name:</td>
						<td>
							<input name="winery_name" type="text"/>
						</td>
					</tr>
					<tr>
						<td>Region:</td>
						<td>
							<?php
								print drop_down_list_db("region_id", "region_name", "region");
							?>
						</td>
					<tr/>
					<tr>
						<td>Grape Variety:</td>
						<td>
							<?php
								print drop_down_list_db("variety_id", "variety", "grape_variety")
							?>
						</td>
					</tr>
					<tr>
						<td>Year:</td>
						<td>
							<?php
								print drop_down_list_num("year", "wine", "min");
								print "->";
								print drop_down_list_num("year", "wine", "max");
							?>
						</td>
					</tr>
					<tr>
						<td>Minimum of in stock:</td>
						<td>
							<input type="text" name="instock_num"/>
						</td>
					</tr>
					<tr>
						<td>Minimum of ordered:</td>
						<td>
							<input type="text" name="ordered_num"/>
						</td>
					</tr>
					<tr>
						<td>Cost range:</td>
						<td>$<input type="text" name="cost_min"/>->$<input type="text" name="cost_max"/></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="submit" value="Search"/>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>