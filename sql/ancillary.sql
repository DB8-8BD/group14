-- ancillary.sql
DROP TABLE IF EXISTS gender;

CREATE TABLE gender(
value SMALLINT PRIMARY KEY,
property VARCHAR(6) NOT NULL
);

INSERT INTO gender (value, property) VALUES (0, 'Female');

INSERT INTO gender (value, property) VALUES (1, 'Male');

DROP TABLE IF EXISTS gender_sought;

CREATE TABLE gender_sought(value SMALLINT PRIMARY KEY, property VARCHAR(6) NOT NULL);

INSERT INTO gender_sought (value, property) VALUES (0, 'Female');

INSERT INTO gender_sought (value, property) VALUES (1, 'Male');

DROP TABLE IF EXISTS city;

CREATE TABLE city(
value SMALLINT PRIMARY KEY,
property VARCHAR(20) NOT NULL
);

INSERT INTO city (value, property) VALUES (0, 'Not Specified');
INSERT INTO city (value, property) VALUES (1, 'Toronto');
INSERT INTO city (value, property) VALUES (2, 'Oshawa');
INSERT INTO city (value, property) VALUES (4, 'Newmarket');
INSERT INTO city (value, property) VALUES (8, 'Mississauga');
INSERT INTO city (value, property) VALUES (16, 'Oakville');
INSERT INTO city (value, property) VALUES (32, 'Burlington');
INSERT INTO city (value, property) VALUES (64, 'Hamilton');
INSERT INTO city (value, property) VALUES (128, 'Stoney Creek');
INSERT INTO city (value, property) VALUES (256, 'St. Catherines');
INSERT INTO city (value, property) VALUES (512, 'Niagara Falls');
INSERT INTO city (value, property) VALUES (1024, 'Brampton');
INSERT INTO city (value, property) VALUES (2048, 'Richmond Hill');
INSERT INTO city (value, property) VALUES (4096, 'Markham');
INSERT INTO city (value, property) VALUES (8192, 'Whitby');

DROP TABLE IF EXISTS state;

CREATE TABLE state(
value SMALLINT PRIMARY KEY,
property VARCHAR(2) NOT NULL
);

INSERT INTO state (value, property) VALUES (0, 'ON');
INSERT INTO state (value, property) VALUES (1, 'ON');
INSERT INTO state (value, property) VALUES (2, 'ON');
INSERT INTO state (value, property) VALUES (4, 'ON');
INSERT INTO state (value, property) VALUES (8, 'ON');
INSERT INTO state (value, property) VALUES (16, 'ON');

DROP TABLE IF EXISTS images;

CREATE TABLE images(
value SMALLINT PRIMARY KEY,
property VARCHAR(30) NOT NULL
);

INSERT INTO images (value, property) VALUES (0, 'Not Specified');

DROP TABLE IF EXISTS tax_bracket;

CREATE TABLE tax_bracket(
value SMALLINT PRIMARY KEY,
property VARCHAR(20) NOT NULL
);

INSERT INTO tax_bracket (value, property) VALUES (0, 'Not Specified');
INSERT INTO tax_bracket (value, property) VALUES (1, '0 – $11,138');
INSERT INTO tax_bracket (value, property) VALUES (2, '$11,138 - $44,701');
INSERT INTO tax_bracket (value, property) VALUES (4, '$44,701 - $89,401');
INSERT INTO tax_bracket (value, property) VALUES (8, '$89,401 - $138,586');
INSERT INTO tax_bracket (value, property) VALUES (16, '$138,586 and above');
INSERT INTO tax_bracket (value, property) VALUES (32, 'borrowing this year');

DROP TABLE IF EXISTS hobbies;

CREATE TABLE hobbies(
value SMALLINT PRIMARY KEY,
property VARCHAR(15) NOT NULL
);

INSERT INTO hobbies (value, property) VALUES (0, 'Not Specified');
INSERT INTO hobbies (value, property) VALUES (1, 'games');
INSERT INTO hobbies (value, property) VALUES (2, 'art');
INSERT INTO hobbies (value, property) VALUES (4, 'crafts');
INSERT INTO hobbies (value, property) VALUES (8, 'knitting');
INSERT INTO hobbies (value, property) VALUES (16, 'making apps');
INSERT INTO hobbies (value, property) VALUES (32, 'gambling');


DROP TABLE IF EXISTS education;

CREATE TABLE education(
value SMALLINT PRIMARY KEY,
property VARCHAR(30) NOT NULL
);

INSERT INTO education (value, property) VALUES (0, 'Not Specified');
INSERT INTO education (value, property) VALUES (1, 'Elementary school');
INSERT INTO education (value, property) VALUES (2, 'High school');
INSERT INTO education (value, property) VALUES (4, 'Partially completed college');
INSERT INTO education (value, property) VALUES (8, 'College Diploma');
INSERT INTO education (value, property) VALUES (16, 'Bachelors Degree');
INSERT INTO education (value, property) VALUES (32, 'Masters, Phd or higher');

DROP TABLE IF EXISTS occupation;

CREATE TABLE occupation(
value SMALLINT PRIMARY KEY,
property VARCHAR(15) NOT NULL
);

INSERT INTO occupation (value, property) VALUES (0, 'Not Specified');
INSERT INTO occupation (value, property) VALUES (1, 'student');
INSERT INTO occupation (value, property) VALUES (2, 'clerical');
INSERT INTO occupation (value, property) VALUES (4, 'manual labour');
INSERT INTO occupation (value, property) VALUES (8, 'skilled labour');
INSERT INTO occupation (value, property) VALUES (16, 'professional');
INSERT INTO occupation (value, property) VALUES (32, 'management');

DROP TABLE IF EXISTS housing_status;

CREATE TABLE housing_status(
value SMALLINT PRIMARY KEY,
property VARCHAR(15) NOT NULL
);

INSERT INTO housing_status (value, property) VALUES (0, 'Not Specified');
INSERT INTO housing_status (value, property) VALUES (1, 'colocation');
INSERT INTO housing_status (value, property) VALUES (2, 'rent apartment');
INSERT INTO housing_status (value, property) VALUES (4, 'rent house');
INSERT INTO housing_status (value, property) VALUES (8, 'own apartment');
INSERT INTO housing_status (value, property) VALUES (16, 'own house');
INSERT INTO housing_status (value, property) VALUES (32, 'own island');

DROP TABLE IF EXISTS vehicle_type;

CREATE TABLE vehicle_type(
value SMALLINT PRIMARY KEY,
property VARCHAR(15) NOT NULL
);

INSERT INTO vehicle_type (value, property) VALUES (0, 'Not Specified');
INSERT INTO vehicle_type (value, property) VALUES (1, 'none');
INSERT INTO vehicle_type (value, property) VALUES (2, 'compact');
INSERT INTO vehicle_type (value, property) VALUES (4, 'sedan');
INSERT INTO vehicle_type (value, property) VALUES (8, 'pickup');
INSERT INTO vehicle_type (value, property) VALUES (16, 'suv');
INSERT INTO vehicle_type (value, property) VALUES (32, 'luxury');

DROP TABLE IF EXISTS sports;

CREATE TABLE sports(
value SMALLINT PRIMARY KEY,
property VARCHAR(20) NOT NULL
);

INSERT INTO sports (value, property) VALUES (0, 'Not Specified');
INSERT INTO sports (value, property) VALUES (1, 'Sedentary');
INSERT INTO sports (value, property) VALUES (2, 'indoor gym');
INSERT INTO sports (value, property) VALUES (4, 'walking hiking');
INSERT INTO sports (value, property) VALUES (8, 'basketball');
INSERT INTO sports (value, property) VALUES (16, 'snowshoeing');
INSERT INTO sports (value, property) VALUES (32, 'hula hoop');

DROP TABLE IF EXISTS religion;

CREATE TABLE religion(
value SMALLINT PRIMARY KEY,
property VARCHAR(15) NOT NULL
);

INSERT INTO religion (value, property) VALUES (0, 'Not Specified');
INSERT INTO religion (value, property) VALUES (1, 'Hinduism');
INSERT INTO religion (value, property) VALUES (2, 'Christian');
INSERT INTO religion (value, property) VALUES (4, 'No Religion');
INSERT INTO religion (value, property) VALUES (8, 'Muslim');
INSERT INTO religion (value, property) VALUES (16, 'Bahaii');
INSERT INTO religion (value, property) VALUES (32, 'Buddhism');


DROP TABLE IF EXISTS interests;
CREATE TABLE interests(user_id VARCHAR(20), prospective_id VARCHAR(20) NOT NULL, interested_time timestamp NOT NULL );

DROP TABLE IF EXISTS offensives;
CREATE TABLE offensives(user_id VARCHAR(20), offending_id VARCHAR(20) NOT NULL, offended_time timestamp NOT NULL, status CHAR(1) DEFAULT('o'));--'o' means open exclusively