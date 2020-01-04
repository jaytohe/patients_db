<?php

session_start();
if ( !isset($_SESSION['usr_id']) || !isset($_SESSION['username']) ) {
	header('Location: /login/'); //User is not logged in. Redirect them to login page.
	exit;
}	

if (isset($_GET['query'])) {
	
$query = $_GET['query'];
$pattern = "/^([(+]*[0-9]+[()+. -]*)$/"; // source : https://www.regextester.com/99524
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
		"SELECT c.client_id,first_name,last_name,phone FROM clients c LEFT JOIN phones p ON c.client_id=p.client_id WHERE (CONCAT(first_name, ' ',last_name) LIKE CONCAT(?,'%'))  OR (CONCAT(last_name,  ' ', first_name) LIKE CONCAT(?,'%'))",
		"SELECT visit_id,c.first_name,c.last_name,v.date,v.diagnosis FROM clients c JOIN visits v ON v.client_id=c.client_id WHERE (CONCAT(c.first_name, ' ',c.last_name) LIKE CONCAT(?,'%')) OR (CONCAT(c.last_name, ' ',c.first_name) LIKE CONCAT(?,'%')) OR (v.diagnosis LIKE CONCAT(?,'%')) ORDER BY DATE DESC"
		];
		$i=0;
		$bind='ss';
		$params[] = $query;
		$params[] = $query;
		if (isset($_GET['mode'])) { // if mode is set search visits else search clients.
			$i=1;
			$bind='sss';
			$params[] = $query;
		} 
		$result = $fetcher->add($multi[$i], $bind, $params,1);
	}
while($row = $result->fetch_object()) {
	array_push($arr,$row);
}
$json = json_encode($arr,JSON_UNESCAPED_UNICODE);
echo $json;
}
//1st stmt is for searching for a client's fullname OR first name OR surname in the database. If the user adds a comma ',' in the search query and then types a diagnosis then the results are sorted based on the diagnosis too.
//2nd stmt searches the visits. It is  the same with the 1st stmt since it looks for the fullname OR the first name OR the surname of a client BUT if a comma is given and a string afterwards, the db is searched for
//the specific visit where that specific diagnosis has been recorded.


?>