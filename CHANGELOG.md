# Changelog

## Version 1.3 - 28/01/2020
**Security Update**
- New! Login Error Handling. If credentials are wrong or a MySQL error occurs, a nag message shows up and the user may retry.
- Fixed permanent XSS injection due to insufficient sanitaziation in /new_page, /info, /visit. Use of PHP's ~~~~htmlspecialchars();~~~~
- New! CSRF Protection. A random token is generated on sign-in which is then saved on $_SESSION cookie. By comparing a POSTed token with $_SESSION's token, CSRF is prevented on /Classes/Delete, /index, /info, /new_page, /visits, /visit.

## Version 1.2 - 08/01/2020
- Added Changelog.
- Commit button is now hidden by default (HTML) in /visits and /index

## Version 1.1 - 05/01/2020
- Added JS regex on new_page and visit to validate Date and Phone Number input.
- Dates are pushed and fetched strictly in DD/MM/YYYY. Conversation to ISO-8601 is done server-side.
- Fixed required fields in /new_page and /visit. Added required field indication (*).


## Version 1.0 - 04/01/2020
- Initial release.
  