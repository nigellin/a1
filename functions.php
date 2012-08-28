<?php
	@mysql_connect(HOST, USER, PSW) or die("Failed connect to the ".HOST.".");
	@mysql_select_db(DB) or die("Failed connect to the DATABASE.");
	
	function drop_down_list_db($field, $table){
		$query= @mysql_query("SELECT $field From $table;") or die("Internal error occurred!");
		
		$output="<select name=$field><option value=''>Please select</option>";
		
		while($results= mysql_fetch_array($query, MYSQL_ASSOC))
			$output.= "<option value=".urlencode($results[$field]).">$results[$field]</option>";
		
		$output.= "</select>";
		
		return $output;
	}
	
	function drop_down_list_num($field, $table, $bound){
		$query= @mysql_query("SELECT MAX($field) AS max, MIN($field) AS min FROM $table;") or die("Internal error occurred!");
		
		$result= mysql_fetch_assoc($query);
		$field.= "_".$bound;

		$limit= $result['max']- ($result['max']- $result['min'])/ 2;
		
		$output= "<select name=$field>";
		
		switch($bound){
			case "min":
				for($i= $result['min']; $i<= $limit; $i++)
					$output.= "<option value=$i>$i</option>";
				break;
			case "max":
				for($i= $result['max']; $i>= $limit; $i--)
					$output.= "<option value=$i>$i</option>";
				break;
		}
			
		$output.= "</select>";
		return $output;
	}
	
	function get_search_result(){
		$wine_name= isset($_GET['wine_name'])? $_GET['wine_name']: "";
		$winery_name= isset($_GET['winery_name'])? $_GET['winery_name']: "";
		$region_name= isset($_GET['region_name'])? urldecode($_GET['region_name']): "";
		$variety= isset($_GET['variety'])? $_GET['variety']: "";
		$year_max= $_GET['year_max'];
		$year_min= $_GET['year_min'];
		$instock_num= isset($_GET['instock_num'])? $_GET['instock_num']: "";
		$ordered_num= isset($_GET['ordered_num'])? $_GET['ordered_num']: "";
		$cost_min= isset($_GET['cost_min'])? $_GET['cost_min']: "";
		$cost_max= isset($_GET['cost_max'])? $_GET['cost_max']: "";
		
		$select= "wine_name, variety, year, winery_name, region_name, on_hand, cost, SUM(qty), SUM(price)";
		
		$table_join= "wine JOIN wine_variety ON wine.wine_id= wine_variety.wine_id ".
			"JOIN grape_variety ON wine_variety.variety_id= grape_variety.variety_id ".
			"JOIN winery ON wine.winery_id= winery.winery_id ".
			"JOIN region ON winery.region_id= region.region_id ".
			"JOIN inventory ON wine.wine_id= inventory.wine_id ".
			"JOIN items ON wine.wine_id= items.wine_id";
		
		
		$condition= "year<= $year_max and year>= $year_min";
		
		if($wine_name)
			$condition.= " AND wine_name= '$wine_name'";
		
		if($winery_name)
			$condition.= " AND winery_name= '$winery_name'";
		
		if($region_name && $region_name!= "All")
			$condition.= " AND region_name= '$region_name'";
		
		if($variety && $variety!= "All")
			$condition.= " AND variety= '$variety'";
		
		
		if($cost_min){
			if(is_numeric($cost_min))
				$condition.= " AND cost>= $cost_min";
			else
				die("Invalid entered!<meta http-equiv='refresh' content='2;url=search_page.php'>");
		}
		
		if($cost_max){
			if(is_numeric($cost_max))
				$condition.= " AND cost<= $cost_max";
			else
				die("Invalid entered!<meta http-equiv='refresh' content='2;url=search_page.php'>");
		}
		
		if($cost_min && $cost_max)
			if($cost_max< $cost_min)
				die("Maximum number cannot less than minimum number!<meta http-equiv='refresh' content='3;url=search_page.php'>");
		
		if($instock_num)
			if(is_numeric($instock_num))
				$condition.= " AND on_hand>= $instock_num";
			else
				die("Invalid entered!<meta http-equiv='refresh' content='2;url=search_page.php'>");
			
		$query= "SELECT $select FROM $table_join WHERE $condition GROUP BY items.wine_id ORDER BY variety, wine_name, winery_name DESC;";
		
		$exec_query= mysql_query($query) or die(mysql_error());
		
		if(mysql_num_rows($exec_query)< 1)
			die("No records match your search criteria!<meta http-equiv='refresh' content='2;url=search_page.php'>");
		
		$output= "
			<table border='1' id='result_table'>
				<tr>
					<td>Wine Name</td>
					<td>Grape Variety</td>
					<td>Year</td>
					<td>Winery Name</td>
					<td>Region Name</td>
					<td>Instock</td>
					<td>Each Cost ($)</td>
					<td>Sold</td>
					<td>Total Cost ($)</td>
				</tr>";

		while($result= mysql_fetch_row($exec_query)){
			$output.= "<tr>";
			foreach($result as $value)
				$output.="<td>$value</td>";

			$output.= "</tr>";
		}
		
		$output.= "
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><input onclick=\"location='search_page.php'\" value='Go Back' type='button'/></td>
				</tr>
			<table>";
		
		return $output;
	}
?>
