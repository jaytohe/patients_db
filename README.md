# ![patients_db logo](https://raw.githubusercontent.com/jaytohe/patients_db/master/css/logo.png)
**A frontend & backend solution for easily managing patients in a clinic.**

This is my Computer Science IA for the International Baccalaurete Diploma Program.

Written in PHP, MySQL and HTML,  CSS,  JS.
Tested to run on MySQL version 8.0.x and PHP 7.3.x.

Frontend is written using the following FOSS projects:
*  [Bulma CSS framework](https://github.com/jgthms/bulma)
*  [Bootstrap CSS framework](https://github.com/twbs/bootstrap)
*  [EasyAutocomplete.js](https://github.com/pawelczak/EasyAutocomplete) 
*  [Jquery Repetable.js](https://github.com/jenwachter/jquery.repeatable)
* [FullCalendar.js](https://github.com/fullcalendar/fullcalendar)
*  [Popper.js](https://github.com/popperjs/popper-core)
*  [Moment.js](https://github.com/moment/moment/)

## Features Rundown
1. Autocomplete search for a patient or a patient's visit.
2. Advanced search of a patient's visit. Search by giving *name or surname or both* **and** *date of visit or diagnosis.* Example: *Foo,1/2019* fetches all visits of patient *Foo* from Jan 2019.
4. Batch manipulation of patients. Easy and fast removal of multiple rows from database.
5. Multiple phone number support for each patient using [Jquery Repeatable](https://github.com/jenwachter/jquery.repeatable) on frontend.
6. Intuitive agenda/calendar showing future and past appointments using [FullCalendar.js](https://github.com/fullcalendar/fullcalendar)
7. Secure | SQL Injection, Self-XSS and CSRF protection.
 
 [Demo video](https://vid.lelux.fi/videos/watch/c2810ca5-d1aa-44bb-a666-0cc344c8ce04) showing some of the features.

## Initial setup


  - Clone this github repository.
  - Execute SCRIPT.sql on your MySQL Server.
  - Move all files of this repository to your localhost,root directory.
  - Change the MySQL credentials present in **/login/index_int.php** and **/Classes/Connect.php**
  - Start your Apache,PHP Server and navigate to localhost.


## Note
For further explanation of features please see the Changelog.
