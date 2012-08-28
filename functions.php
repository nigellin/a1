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
	
	function check_numbers($min, $max, $compare){
		if($compare){
			if(is_int($min) && is_int($max))
				if($min> $max){
					echo "The minimum number cannot greater than maximum number.";
					return false;
				}
			else{
				echo "The entered is invalid.";
				return false;
			}
			
		}else{
			if(!is_int($min) || $min< 0){
				echo "The entered is invalid or too small.";
				return false;
			}
		}
		
		return true;
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
		
		$select= "wine_name, variety, year, winery_name, region_name, on_hand, cost";
		
		$table_join= "wine JOIN wine_variety ON wine.wine_id= wine_variety.wine_id ".
			"JOIN grape_variety ON wine_variety.variety_id= grape_variety.variety_id ".
			"JOIN winery ON wine.winery_id= winery.winery_id ".
			"JOIN region ON winery.region_id= region.region_id ".
			"JOIN inventory ON wine.wine_id= inventory.wine_id";
		
		
		$condition= "year<= $year_max and year>= $year_min";
		
		if($wine_name)
			$condition.= " AND wine_name= '$wine_name'";
		
		if($winery_name)
			$condition.= " AND winery_name= '$winery_name'";
		
		if($region_name && $region_name!= "All")
			$condition.= " AND region_name= '$region_name'";
		
		if($variety && $variety!= "All")
			$condition.= " AND variety= '$variety'";
		
		
		$query= "SELECT $select FROM $table_join WHERE $condition ORDER BY variety, wine_name, winery_name DESC;";
		
		$exec_query= mysql_query($query) or die("Internal error occurred");
		
		$output= "
			<table border='1' id='result_table'>
				<tr>
					<td>Wine Name</td>
					<td>Grape Variety</td>
					<td>Year</td>
					<td>Winery Name</td>
					<td>Region Name</td>
					<td>Total Bottle</td>
					<td>Cost ($)</td>
				</tr>";

		while($result= mysql_fetch_row($exec_query)){
			$output.= "<tr>";
			foreach($result as $value)
				$output.="<td>$value</td>";

			$output.= "</tr>";
		}

		$output.= "<table>";
		
		return $output;
	}
?>
