

/* 

Authors  : Ryan Beckett, Shreeji Patel
Date	 : Nov. 21, 2017
Filename : users.sql
Course	 : WEBD-3201
Purpose  : create the users table, per our program requiresments

*/

-- DROPping tables clear out any existing data
DROP TABLE IF EXISTS users;



CREATE TABLE users(
	user_id VARCHAR (20) PRIMARY KEY,
	password VARCHAR (32) NOT NULL,
	user_type VARCHAR (2) NOT NULL,
	email_address VARCHAR (256) NOT NULL,
	first_name VARCHAR (128) NOT NULL,
	last_name VARCHAR (128) NOT NULL,
	birth_date DATE NOT NULL, 
	enrol_date DATE NOT NULL,
	last_access DATE NOT NULL
	
);

