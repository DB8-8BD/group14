--Filename: create-db.sql
--Author: Ryan Beckett
--Date: Oct. 20, 2017
--Description: Creates database if it doesn't already exist.
--DO NOT RUN THIS ON OPENTECH2
--THESE FILES MUST BE RUN IN SEQUENCE AS SHOWN IN db_setup.bat
CREATE USER group14_admin WITH PASSWORD '4ermonkeyi9o';
CREATE DATABASE group14_db
       OWNER=group14_admin
       CONNECTION LIMIT=-1;
GRANT CONNECT ON DATABASE group14_db TO group14_admin;
