<?php 
Class HTML_Visits {
	
		public $arr = Array();
		
		public function data_get($strindex ) {
		if (isset($this->arr[$strindex])) {
			return stripcslashes($this->arr[$strindex]);
		} else {
			return "";
		}
	}
}
?>

