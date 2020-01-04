<?php

$pages = ceil($number_of_records/$limit); //total number of pages to split db into/ last page.

$first_record = ($page -1) * $limit; //calculates the number of the 1st record in each page.


$start_page = ($page) > 3 ? $page-3 : 1; // calculates the first page. current page-5. Equals to 1 otherwise.
$end_page = ($page + 3) < $pages ? $page+3 : $pages; //calculates end page.


//$check_dsb = $page == 1 ? "disabled" : '';

if ( ($page ==1) || ($pages == 0) ) {
	$prev_page = '<div class="field has-addons"><p class="control"><button class="button" href="" disabled>&lt;-</button></p>'; //disabled <- previous page button if page 1
} else {
	$prev_page = '<div class="field has-addons"><p class="control"><a class="button" href="?page='.($page-1).'&limit='.$limit.'">&lt;-</a></p>'; //prints <- previous page button
}
$list=$prev_page;

if ($start_page > 1) {
	$list .= '<p class="control"><a class="button" href="?page=1&limit='.$limit.'">1</a></p><p class="control"><button class="button" disabled>...</button></p>'; // prints 1st page button and ... if user is on a page > 5.
	}
for ( $i = $start_page; $i <= $end_page; $i++) {
		$state =(($page == $i) && ($pages != 0)) ? " is-active" : "";
		$list .= '<p class="control"><a class="button'.$state.'" href="?page='.$i.'&limit='.$limit.'">'.$i.'</a><p>'; //prints all page links and focuses element of current page.
}
if (($end_page < $pages)) {
	$list .= '<p class="control"><button class="button" disabled>...</button></p><p class="control"><a class="button" href="?page='.$pages.'&limit='.$limit.'">'.$pages.'</a></p>'; //prints  ... and last page button.
}
if (($page == $pages) || ($pages == 0) ) { //if we are on the last page, disable next button
	$next_page = '<p class="control"><button class="button" disabled>-&gt;</button></p>';
} else {
	$next_page = '<p class="control"><a class="button" href="?page='.($page+1).'&limit='.$limit.'">-&gt;</a></p>';
}
$list .= $next_page.'</div>';
?>