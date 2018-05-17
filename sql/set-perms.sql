--Filename: set-perms.sql
--Author: Ryan Beckett and Shreeji Patel
--Date: Nov. 21, 2017
--Description: set permissions on postgres tables
--THESE FILES MUST BE RUN IN SEQUENCE AS SHOWN IN db_setup.bat
GRANT SELECT ON ALL TABLES IN SCHEMA public TO group14_admin;
GRANT DELETE ON ALL TABLES IN SCHEMA public TO group14_admin;
GRANT UPDATE ON ALL TABLES IN SCHEMA public TO group14_admin;
GRANT INSERT ON ALL TABLES IN SCHEMA public TO group14_admin;