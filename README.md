# Wirtualny-Dziekanat
author: Daniel Gabryś  


Students and lecturer management system 


CHECK OUT HOW IT WORKS HERE https://wirtualnydziekanat.000webhostapp.com/ 



AS STUDENT - you have access to your marks and information about your faculty

AS TEACHER - you have access to students data, for exmaple you can modify their marks

AS WORKER - you have access to students and teachers, you can manage them adding/deleting students or changing teachers



EXAMPLE LOGIN AS STUDENT: 

LOGIN: 100006 

PASSWORD :qwerty123 


EXAMPLE LOGIN AS TEACHER: 

LOGIN: c 

PASSWORD :qwerty12345 


EXAMPLE LOGIN AS WORKER: 

LOGIN: ekruk@gmail.com 

PASSWORD :qwerty12345 




INSTALLATION LINUX 


0. Loggin as administartor : 

su [name] 


Install apache2 


apt-get install apache2 

service apache2 status 


tap localhost in your browser, you should see apache page. 


2. Install php

apt-get install php 7.2* //version may differ

3. Install mysql

apt install mysql-server -y

4. Copy dowloaded files into /var/www/html

cp -r [path]/* ./

5. Database inport:

mysql
CREATE DATABASE new_db_name;
mysql (–u username –p) new_db_name < dump_file.sql

6. Edit file connect.php:

$username = "root";
$password = "your password";
