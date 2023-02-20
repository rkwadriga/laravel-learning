CREATE DATABASE IF NOT EXISTS laravel_db;
CREATE DATABASE IF NOT EXISTS laravel_db_test;

CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%';