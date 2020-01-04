# patients_db
A frontend &amp; backend solution for making patients management in a clinic easy.

Written in PHP, MySQL (backend) and HTML, CSS, JS (frontend). Tested to run on MySQL version 8.0.x and PHP 7.3.x.

<h2> Features Rundown</h2>
<ol>
  <li>AJAX Autocomplete Search of patients by name, surname, phonenumber, diagnosis. </li>
  <li>Batch manipulation of patients. Easy and fast removal of multiple rows from database.</li>
  <li>Multiple phone number support for each patient.</li>
  <li>Intuitive agenda/calendar showing future and past appointments using FullCalendar.js v3</li>
</ol>

<h2> Initial setup </h2>

<ul>
  <li>Clone this github repository.</li>
  <li>Execute SCRIPT.sql on your MySQL Server.</li>
  <li>Move all files of this repository to your localhost,root directory.</li>
  <li>Change the MySQL credentials present in <b>/login/index_int.php</b> and <b>/Classes/Connect.php</b>.</li>
  <li>Start your Apache,PHP Server and navigate to http://localhost/.</li>
</ul>

<h2> Note </h2>

This is version 1.0 of patients_db. Many things need to be ironed out and some features haven't been implemented at all as of now. Future releases will hopefully squash any bugs and add multiple checks to ensure data integrity.

<a href="https://vid.lelux.fi/videos/watch/c2810ca5-d1aa-44bb-a666-0cc344c8ce04">Here's a DEMO video</a> on PeerTube showcasing the features patients_db offers.
