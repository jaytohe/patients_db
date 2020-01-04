<?php
if (empty($_POST)) {exit();};

require_once($_SERVER['DOCUMENT_ROOT'].'/classes/Connect.php');
$conn = Connect::getInstance()->getConnection();
$queries = ["DELETE FROM events WHERE id = (?)", "UPDATE events SET title=(?), description=(?), color=(?) WHERE id=(?)","UPDATE events SET start=(?), end=(?) WHERE id = (?)"];

if (isset($_POST['id'])){
	$id = $_POST['id'];
	if (isset($_POST['delete'])){
		try{
			$stmt = $conn->prepare($queries[0]);
			$stmt->bind_param('i',$id);
		} catch(Exception $e) {exit($e->getMessage());};
		if(!$stmt->execute()) {echo "Execute failed: (" . $query->errno . ") " . $query->error; exit(); }
	} 
	else if (isset($_POST['title']) && isset($_POST['description']) && isset($_POST['color']) && isset($_POST['id'])){
		try{
			$stmt = $conn->prepare($queries[1]);
			$stmt->bind_param('sssi', $tit, $desc, $col, $id);
		} catch(Exception $e) {exit($e->getMessage());};
		$tit = $_POST['title'];
		$desc = $_POST['description'];
		$col = $_POST['color'];
		if(!$stmt->execute()) {echo "Execute failed: (" . $query->errno . ") " . $query->error; exit(); }
	
	}
	header('Location: /agenda/index.html');
} else if (isset($_POST['Event'][0]) && isset($_POST['Event'][1]) && isset($_POST['Event'][2])){
	try{
	$stmt = $conn->prepare($queries[2]);
	$stmt->bind_param('ssi',$start, $end, $id);
	} catch(Exception $e) {exit($e->getMessage());};
	$id = $_POST['Event'][0];
	$start = $_POST['Event'][1];
	$end = $_POST['Event'][2];
	if(!$stmt->execute()) {echo "Execute failed: (" . $query->errno . ") " . $query->error; exit(); }
}
?>