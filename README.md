# Web-based Event and Participation Manager
This is a web-based event and participation manager. Admins can create events and users can participate these events. 

![participate](https://user-images.githubusercontent.com/7523395/34412178-0e30e200-ebdc-11e7-8c82-b5db12b3e81a.gif)

## create event (admin)
There is also an opportunity to create a news. 

![createevent](https://user-images.githubusercontent.com/7523395/34412243-653ec170-ebdc-11e7-9c28-db41fbf84151.gif)

## Dependencies

* [jQuery](https://jquery.com/)
* [Bootstrap](http://getbootstrap.com/)
* [Font Awesome](http://fontawesome.io/)
* [FullCalendar](https://fullcalendar.io/)
* [PHPMailer](https://github.com/PHPMailer/PHPMailer)
* [startbootstrap-freelancer](https://github.com/BlackrockDigital/startbootstrap-freelancer)
* [Magnific Popup](http://dimsemenov.com/plugins/magnific-popup/)
* [datetimepicker](https://github.com/xdan/datetimepicker)

## Installing / Getting started

1. Install an Apache Webserver, MySQL and PHP. I recommend to use PHP 7.x. You can use the package [XAMPP](https://www.apachefriends.org/index.html). In this package everthing is included what you need.
2. Download the repository into the htdocs folder. Maybe you want to rename the folder.
3. Create an empty database on your MySQL. 
4. Import the sql-file out of the sql-schema folder. 
5. Create an user to access this database.
6. Edit the db_config_sample.php to your setup. And rename the file to: db_config.php
7. Edit the path_sample.php and SMTP_config_sample.php to your setup and rename these files according to the comments in the file.
8. Do a first test: Go to your specified url and try to load the project. Then choose some login credentials and click on login. This should be shown: 

![image](https://user-images.githubusercontent.com/7523395/34408213-c618b5ba-ebc2-11e7-99c4-484d7197cd8d.png)

9. There is a super admin account. This account is used to activate new users and to reset passwords. 
    * Create a normal user over the register form. 
    * Go into the table "users" and set these flags: "active" = 1 and "flagAdmin" = 1. 
    * set the "id" = 1  

10. Try the super admin login. You should see this: 

  ![image](https://user-images.githubusercontent.com/7523395/34408400-f6c97ea0-ebc3-11e7-9455-2b539f1b7d40.png)

11. Normal users can be set to be admin. Use the "flagAdmin" to do this. 

## Usage
Go to your browser and use the specified url: http://127.0.0.1/EventManager/

## Nice to Know

### Super Admin
The super admin can activate new users and reset passwords. There is only one super admin. The super admin has the id equals 1.

### Admin
Every normal user can be set to be admin. This can be done by editing the user in MySQL. To do so set the "flagAdmin" equals 1. Admin users can create new events and news. They also can deactivate old news.

### Users
Normal users can see the events and news. Furthermore they can participate events.

### Timetable
There is a view for all working hours per user. You have to set the timeFlag in mySQL to access this option. 

![screenshot_20180505_182119](https://user-images.githubusercontent.com/7523395/39665316-3d683b0c-5092-11e8-84a0-8b390823e0ac.png)


## Author
Christian Högerle

## Licensing
The code in this project is licensed under MIT license.
