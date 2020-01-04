-- DATABASE patients_myderm stores all info about clients.

DROP DATABASE IF EXISTS patients_myderm;
CREATE DATABASE patients_myderm;
USE patients_myderm;


--
-- STRUCTURE OF CLIENTS TABLE WITH UNIQUE ID (PRIMARY KEY) client_id which increments by +1 automatically.
--
CREATE TABLE IF NOT EXISTS clients (
 client_id INT NOT NULL AUTO_INCREMENT,
 first_name VARCHAR(50) NOT NULL,
 last_name VARCHAR(80) NOT NULL,
 diagnosis VARCHAR(80) NOT NULL,
 birth_date DATE,
 address VARCHAR(80),
 med_history TEXT,
 fam_history TEXT,
 PRIMARY KEY (client_id)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Sample clients inserted to the database.
INSERT INTO clients VALUES (1, "George", "Gred", "Example Diagnosis", "2000-01-03", "1234 Alpha Street", "Nothing special.", "Nothing special too");
INSERT INTO clients VALUES (2, "Ιωάννης", "Παπαδόπουλος", "Unicode Example", "1999-02-17", "Οδός Παπαδοπούλου 99", "Τίποτα το σημαντικό", "Καλός κ'αγαθός");

--
-- STRUCTURE OF VISITS TABLE WITH PRIMARY KEY visit_id. CONNECTION WITH client_id via FOREIGN KEY -> deletes visits if client is removed.
--
CREATE TABLE IF NOT EXISTS visits (
visit_id INT NOT NULL AUTO_INCREMENT,
client_id INT NOT NULL,
date DATE NOT NULL,
diagnosis VARCHAR(80) NOT NULL,
notes TEXT,
present_symptom VARCHAR(80),
lab VARCHAR(255),
img_test VARCHAR(255),
histology VARCHAR(255),
treatment VARCHAR(255),
attach MEDIUMBLOB,
PRIMARY KEY (visit_id),
CONSTRAINT fk_visits_clientid FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Sample visits of clients 1 and 2 inserted into database.
INSERT INTO visits VALUES (1,1,"2020-01-03", "Μια διάγνωση", "Αυτό είναι ένα παράδειγμα που μπορεί να μπεί στα notes.", "example symptom", "test test 1968493", "Lorem ipsum", "Hello world", "Cough syrup", NULL);
INSERT INTO visits VALUES (2,1,"2020-01-04", "Μια όμορφη ημέρα", "Different notes example", "example symptom", "@!#$&+(#))#(@ΤΕΣΤ", "Ιπσδ θοιθοιθοσνασ", "Γεια σου κόσμε", "Depon", NULL);
INSERT INTO visits VALUES (3,2,"2019-12-10", "λορεμ ιπσοθμ ", NULL, NULL, "Μου αρέσει το καλοκαίρι.", "Hello everyone.", " ", "έναδύοτρία τεστ", NULL);

--
-- STRUCTURE OF PHONES TABLE. FOREIGN KEY client_id deletes all phone information of a client, if client is deleted from clients table.
--
CREATE TABLE IF NOT EXISTS phones (
client_id INT NOT NULL,
phone VARCHAR(50) NOT NULL,
owner VARCHAR(80),
CONSTRAINT fk_phones_clientid FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Sample phone numbers inserted into database for clients.
INSERT INTO phones VALUES (1,"+306918171615","κινητό/mobile");
INSERT INTO phones VALUES (1,"2106078793","σταθερό/landline");
INSERT INTO phones VALUES (1,"(+32)5643287219","abroad");
INSERT INTO phones VALUES (2,"(+30)2106945764","landline");

--
-- STRUCTURE OF events table with primary key id.
--
CREATE TABLE IF NOT EXISTS events (
  id INT NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  description varchar(255) NOT NULL,
  color varchar(7) DEFAULT NULL,
  start datetime NOT NULL,
  end datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=15;

-- RANDOM SAMPLE EVENTS INSERTED INTO AGENDA
INSERT INTO events (id, title, description, color, start, end) VALUES
(1, 'All Day Event', 'some text for all day event', '#40E0D0', '2020-01-01 00:00:00', '2020-01-02 00:00:00'),
(2, 'Long Event', 'some text for long event', '#FF0000', '2020-01-07 00:00:00', '2020-01-10 00:00:00'),
(3, 'Short Event', 'some text for repeating event', '#0071c5', '2020-01-09 16:00:00', '2020-01-09 16:30:00'),
(4, 'Conference', 'some text for conference', '#40E0D0', '2020-01-10 00:00:00', '2020-01-12 00:00:00'),
(5, 'Meeting', 'some text for meeting', '#000', '2020-01-11 10:30:00', '2020-01-11 12:30:00'),
(6, 'Lunch', 'some text for lunch', '#0071c5', '2020-01-11 12:00:00', '2020-01-11 1:00:00'),
(7, 'Happy Hour', 'some text for happy hour', '#0071c5', '2020-01-11 17:30:00', '2020-01-11 19:00:00'),
(8, 'Dinner', 'some text for dinner', '#0071c5', '2020-01-11 16:00:00', '2020-01-11 17:30:00'),
(9, 'Birthday Party', 'some text for birthday party', '#FFD700', '2020-01-13 09:00:00', '2020-01-13 12:00:00'),
(10, 'Vacation', 'some text for vacation', '#008000', '2020-01-18 00:00:00', '2020-01-21 00:00:00'),
(11, 'Shopping', 'some text for shopping', '#FF8C00', '2020-01-31 17:30:00', '2020-01-31 18:30:00'),
(12, 'Double click to change', 'some text for double click', '#000', '2020-01-22 00:00:00', '2020-01-22 00:00:00');

-- WE CREATE A DIFFERENT DATABASE FOR users for cleaner organization/distinction of tables. 
DROP DATABASE IF EXISTS crm_users;
CREATE DATABASE crm_users;
USE crm_users;

--
-- STRUCTURE OF TABLE users with primary key usr_id which auto increments. Passwords are saved is sha256 hash format via php mysqli connection.
--
CREATE TABLE IF NOT EXISTS users (
usr_id INT NOT NULL AUTO_INCREMENT,
username VARCHAR(50) NOT NULL,
password VARCHAR(255) NOT NULL,
PRIMARY KEY(usr_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci; 

INSERT INTO users VALUES (1, "test", "9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08");
--
--
-- DEFAULT CREDENTIALS ARE username: test, password: test.
--