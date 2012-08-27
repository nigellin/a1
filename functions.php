<?php
	@mysql_connect(HOST, USER, PSW) or die("Failed connect to the ".HOST.".");
	@mysql_select_db(DB) or die("Failed connect to the DATABASE.");
	
	function drop_down_list_db($field_id, $field, $table){
		$query= @mysql_query("SELECT $field_id ,$field From $table;") or die("Internal error occurred!");
		
		$output= "<select name=$field><option name=$field value=''>Please select</option>";
		
		while($results= mysql_fetch_array($query, MYSQL_ASSOC))
			$output.= "<option value=$results[$field_id]>$results[$field]</option>";
		
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
		
	}
?>
