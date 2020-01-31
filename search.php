<?php

session_start();
if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}	

if (isset($_GET['query'])) {
	
$query = $_GET['query'];
$pattern = "/^([(+]*[0-9]+[()+. -]*)$/"; // source : https://www.regextester.com/99524
// SPECIAL REGEX FOR WHEN USER INPUTS something comma date. Example: George,01/2018
$clients_mixed_search = "/^([^\d]+),(\d{4}|(((0)[1-9])|((1)[0-2])|[0-9])(\/)\d{4})$/";
// SPECIAL REGEX FOR WHEN USER INPUTS something comma something.
$clients_simple_comma = "/^([^\d]+),(.+)$/";

$arr=[];
header('Content-Type: application/json');
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Modify.php');

$fetcher = new Modify();

if ( preg_match($pattern,$query) ) { //query is a phone number
	$stmt = "SELECT c.client_id, first_name, last_name, phone FROM clients c JOIN phones p ON c.client_id=p.client_id WHERE phone LIKE CONCAT('%',CONCAT(?,'%'))";
	$result = $fetcher->add($stmt, 's', $query,1);
} 
else { 
		$multi =[
		"SELECT c.client_id,first_name,last_name,phone FROM clients c LEFT JOIN phones p ON c.client_id=p.client_id WHERE (CONCAT(first_name, ' ',last_name) LIKE CONCAT(?,'%')  OR CONCAT(last_name,  ' ', first_name) LIKE CONCAT(?,'%'))",
		"SELECT visit_id,c.first_name,c.last_name,v.date,v.diagnosis FROM clients c JOIN visits v ON v.client_id=c.client_id WHERE (CONCAT(c.first_name, ' ',c.last_name) LIKE CONCAT(?,'%') OR CONCAT(c.last_name, ' ',c.first_name) LIKE CONCAT(?,'%')) ORDER BY DATE DESC",
		"SELECT visit_id,c.first_name,c.last_name,v.date,v.diagnosis FROM clients c JOIN visits v ON v.client_id=c.client_id WHERE (CONCAT(c.first_name, ' ',c.last_name) LIKE CONCAT(?,'%') OR CONCAT(c.last_name, ' ',c.first_name) LIKE CONCAT(?,'%')) AND v.diagnosis LIKE CONCAT(?,'%')",
		"SELECT visit_id,c.first_name,c.last_name,v.date,v.diagnosis FROM clients c JOIN visits v ON v.client_id=c.client_id WHERE (CONCAT(c.first_name, ' ',c.last_name) LIKE CONCAT(?,'%') OR CONCAT(c.last_name, ' ',c.first_name) LIKE CONCAT(?,'%')) AND v.date LIKE CONCAT(?,'%')"
		];
		$i=0;
		$bind='ss';
		$args_to_send = array($query, $query);
		if (isset($_GET['mode'])) { // if mode is set search visits else search clients.
			
			$i=1; //choose 2nd statement in multi.
			
			if( preg_match($clients_mixed_search, $query) ) {
				$fullname = preg_replace($clients_mixed_search, "$1", $query);
				$datecomma = preg_replace($clients_mixed_search, "$2", $query);
				
				if(strpos($datecomma, "/")) { //if date is in mm/yyyy and not just yyyy
					$datecomma = str_replace("-","/",$datecomma);
					$datecomma = preg_replace("/^(\d+)\D+(\d{4})$/", "$2-$1", $datecomma); //switcharoo mm-yyyy to yyyy-mm to comply with MySQL's ISO 8601.
					$datecomma = preg_replace("/\b([0-9])\b/", "0$1", $datecomma); //in case of yyyy-m add a 0 to make it yyyy-mm.
				}
				$args_to_send = array($fullname, $fullname, $datecomma);
				$i=3; //choose 3rd stmt in multi.
				$bind = "sss"; 
			} else if ( preg_match($clients_simple_comma, $query) ) {
				$fullname = preg_replace($clients_simple_comma, "$1", $query);
				$diag_s = preg_replace($clients_simple_comma, "$2", $query);
				$args_to_send = array($fullname, $fullname, $diag_s);
				$i=2;
				$bind = "sss";
			}
		} 
		$result = $fetcher->add($multi[$i], $bind, $args_to_send,1);
	}
while($row = $result->fetch_assoc()) {
	$temparray = $fetcher->htmlarrayescape($row); //prevent XSS injection.
	$new_obj = (object) $temparray; //typecast temp array to an object. Easily converts in to a JSON readable array.
	array_push($arr,$new_obj);
}
$json = json_encode($arr,JSON_UNESCAPED_UNICODE);
echo $json;
}
//1st stmt is for searching for a client's fullname OR first name OR surname in the database. If the user adds a comma ',' in the search query and then types a diagnosis then the results are sorted based on the diagnosis too.
//2nd stmt searches the visits. It is  the same with the 1st stmt since it looks for the fullname OR the first name OR the surname of a client BUT if a comma is given and a string afterwards, the db is searched for
//the specific visit where that specific diagnosis has been recorded.


?>