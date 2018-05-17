

--Filename: profiles.sql
--Author: Ryan Beckett and Shreeji Patel
--Date: Nov. 21, 2017
--Description: Create user profile table
--THESE FILES MUST BE RUN IN SEQUENCE AS SHOWN IN db_setup.bat


DROP TABLE IF EXISTS profiles;



CREATE TABLE profiles(
	user_id VARCHAR(20) PRIMARY KEY 
		REFERENCES users (user_id), 
	headline VARCHAR(100) NOT NULL, 
	self_description VARCHAR(1000) NOT NULL, 
	match_description VARCHAR(100) NOT NULL, 
	city INTEGER NOT NULL, 
	state SMALLINT NOT NULL, 
	gender SMALLINT NOT NULL, 
	gender_sought SMALLINT NOT NULL, 
	images SMALLINT NOT NULL DEFAULT(0),
	tax_bracket SMALLINT NOT NULL, 
	education SMALLINT NOT NULL, 
	occupation SMALLINT NOT NULL, 
	housing_status SMALLINT NOT NULL, 
	vehicle_type SMALLINT NOT NULL, 
	hobbies SMALLINT NOT NULL, 
	sports SMALLINT NOT NULL, 
	religion SMALLINT NOT NULL
	
);

/*INSERT INTO profiles(user_id, gender, gender_sought,city,images,headline,education_completed,education_current,self_description,match_description)
	VALUES('spatel', 'M', 'L', 'Oshawa', 12, 'Hi there', 'Diploma', 'PHD', 'whatever', 'nkj');*/

	