<?php
require_once 'Connect.php';
class Modify {
	
	public function add($query, $bind, $args, $return=0) {
		$conn =Connect::getInstance()->getConnection();
		try {
		$stmt = $conn->prepare($query);
		} catch(Exception $e) {exit($e->getMessage());}
		$args = !is_array($args) ? array($args) : $args; //simple check implemented so that bind_param doesn't whine about args not being an array.
		$args = $this->escape($args);
		try { //With prepared statements mysql injection is nearly impossible. 
		$stmt->bind_param($bind, ...$args); /// The "..." is called splat operator and it tells php to expect any amount of arguments.
		$stmt->execute();
		if ($return == 1) {
			return $stmt->get_result();
		} else if ($return ==2) {
			return $stmt->insert_id;
		}
		} catch(Exception $e) {
			echo $e->getMessage();
			die(); //stop execution on exception.
		}
		
	}
	public static function htmlarrayescape($arr) {
		$new_arr = array();
		foreach ($arr as $index => $val) {
			$new_arr[$index] = htmlspecialchars($val);
		}
		return $new_arr;
	}
	private function escape($args) { //Escape dangerous characters. 
		foreach ($args as $key => $value) {
		$args[$key] = stripcslashes($value); //convert escape chars to their actual meaning
		$args[$key] = ($value == "") ? $args[$key] = NULL : $value; // NULLify empty string.
		}
		return $args;
	}
	public static function dateconv($string) { //This function converts date DD/MM/YY or DD/MM/YYYY to ISO YYYY-MM-DD (mode=0) and vice-versa (mode=1). MySQL accepts ONLY YYYY-MM-DD (ISO 8601)!!
		$form_string = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$string);
		/* ---EXPLANATION OF REGEX---
		> (\d+) -> Find one or more digits belonging to [0,9]. | \D+ -> Find one or more non [0,9] characters. Why? The date contains a / or - Example(1) : 12/1/2002 or 12-1-2002.
		> We define 3 sets of regex expressions by using parentheses around (\d+). Every match of every group is recorded onto $1, $2, $3. For example (1) $1 is 12, $2 is 1 and $3 is 2002. 
		> $3-$2-$1 tells php to replace it as 2002-1-12.
		*/
		$output = preg_replace("/\b([0-9])\b/", "0$1", $form_string); 
		/*
		> MySQL will not accept 2002-1-12 as date. Why? Because it is of the format YYYY-M-DD. Hence, we need to add a zero to 1 to have the correct format ie. 2002-01-12.
		> \b matches a boundary position between a letter and a number. In our case - or /. It matches start and end of string too.
		> ([0-9]) gets any strictly single digit belonging to [0,9]. 
		> 0$1 tells php to run the regex and if and only if any number digit is single, add a zero in front of it.
		*/
		return $output;
	}
	public function phones($query, $bind, $client_id=null) {
		//Following code gathers all phone numbers and owners inputted.
		$owners = [];
		$phones = [];
		//$phone_ids = [];
		// print_r($_POST);
		// print_r($_POST["phone_nums"]);
		foreach ($_POST["phone_nums"] as $key => $subarray) { //From Multidimensional Array $_POST --> Get Array phone_nums --> Get every Array newX where X belongs to [0, inf) and is integer.
			foreach ($subarray as $subkey => $val) {// For every array newX traverse it.
				switch ($subkey) {
					case "task":
					$phones[] = $val;
					break;
					case "owner":
					$owners[] = $val;
					break;
				}
		} 
		
		}
		
		if ($client_id == null) {
			foreach ($phones as $key => $val) { //Traverse phones and owners in parralel and call add function. 
			$this->add($query, $bind, array($val, $owners[$key])); //we use array() since the splat operator accepts only an array of parameters.
			}
		} else {
			foreach ($phones as $key => $val) { //Traverse phones and owners in parralel and call add function. 
				$this->add($query, $bind, array($client_id,$val, $owners[$key])); //we use array() since the splat operator accepts only an array of parameters.
			}
		}
	
	
	}

	
}

?>