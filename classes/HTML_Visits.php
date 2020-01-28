<?php 
Class HTML_Visits {
	
		public $arr = Array();
		
		public function data_get($strindex ) {
		if (isset($this->arr[$strindex])) {
			return stripcslashes(htmlspecialchars($this->arr[$strindex])); //prevent XSS injection.
		} else {
			return "";
		}
	}
}
?>

