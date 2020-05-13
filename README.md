# Timeloom User Interface Documentation

## Programming language, tool kits and prerequisites
* PHP
* HTML,CSS, JavaScript
* Bootstrap

## Use Case Diagram
The following covers the main use cases of [Timeloom interface](https://timeloom.mcs.cmu.edu/).
![](https://lh3.googleusercontent.com/UEFEfXw3U3j9fUVi4tTw-duLfx01u7nvY3GuuYsOfN5vRzmO9wkW-kAoIFoYtPdoYjJMcXaC3KyDBn0yvK7yto-C4iVvTswxQ1EiPuNWmWbNtZIhv-AQVYWCMYhP-Q077j_0MOTEaVmvbP0pW-h-y0aVBwZr8CaX7BHLJX55Z-UQvebaACLs9TbeQQnw5-ypmvv2y9AC49go9bL7MPZhcz6ccMXdIbXxhNCrhPxY2VFJ4BReIFr1vQ1ioto6B0Aj_mQOgzjPsjeAgQ_frXuDBq-MnxE7pJyq71sP9Eo0u0XyAHcH8nyak8PhFdRNuyNpDH2YQRa-KBZetjlgU4RsLEdXWkOrlWH2KZ5M3APB5NERVS06RnXb8-E4hdEObI1kcrC1_fZzm0ylNxJQ5KbMyXF6Enm8bhp1W8Rj4O5BAjz6QArSWo5gxafu3CqmefxhKDTimDXKWXDgde8ynCIQvyxDtdUdztUIFaELDdk6OfmR86YEzCi2IhDFVr2tl5-pKHRdkoqIQNredpX90cEfxOypQz2aSlyONyYTPMOPTZbvb7idCdk6-RpvZSqCnZAU9rR-I90_jG5KlxUA8AZdwICPu79W8QtY8eOkObS9N-xXktACXsiX-mF_emnJLpurIWXrYR1Gx4VMc5zCVu83HPARByxGSfgvKxi-UzNPASPgVR0gY9AnuPVak6k=w519-h692-no?authuser=2)


## Repo Structures
The following tree represented the structure of the whole repo.
```
.
├── includes
│   ├── db.php
│   ├── dropdown.php
│   ├── footer.php
│   └── header.php
├── public
│   ├── js
│   │   ├── bootstrap-formhelpers.min.js
│   │   ├── dashboard.js
│   │   └── login.js
│   └── stylesheets
│       ├── dashboard.css
│       ├── login.css
│       ├── resetpw.css
│       └── welcome.css
├── src
│   ├── add_list.php
│   ├── add_task.php
│   ├── change_rank.php
│   ├── delete_task.php
│   ├── edit_task.php
│   ├── google_callback.php
│   ├── login_authen.php
│   ├── move_task.php
│   ├── register_authen.php
│   ├── reset_pw_authen.php
│   ├── search_properties.php
│   └── switch_list.php
├── dashboard.php
├── index.php
├── login.php
├── logout.php
├── register.php
├── resetpw.php
└── README.md
```

The outmost PHP files includes all pages (PHP embedded in HTML) of Timeloom: ```dashboard.php``` is the dashboard where all lists and tasks were listed, ```index.php``` is the welcome(home) page, ```login.php, logout.php, register.php``` represent the user login, logout and register page, separately, and ```resetpw.php``` is the page where users can reset their password. (Note: if new user registers through Google accounts, they will be directed to this reset password page.)

The ```includes``` directory includes modules for each web page (```dropdown.php, footer.php, header.php``` represents the header and footer of all pages, and the dropdown module is for the dropdown meno of dashboard). ```db.php``` includes database infomation for the interface to connect to the MySQL database.

The ```public``` directory contains all JavaScript and CSS files for web pages. All JavaScript files are located in ```public/js```. ```bootstrap-formhelpers.min.js``` is a library from [Bootstrap Form Helpers](https://bootstrapformhelpers.com/) and is used for the input box of Time Zone in the register page. ```dashboard.js``` contains all the JavaScript functions for dashboard, while ```login.js``` is for the login page. All CSS files are located in ```public/stylesheets```. Detailed clarification of each file can be found in the comments of the file.  ```dashboard.css, login.css, resetpw.css, welcome.css``` are CSS files for the dashboard, login, reset password and welcome page, separately.

The ```src``` directory includes all the functional PHP files that conduct one or more use case functions and the name of each file is quite self-explanatory.  It is worth noting that ```google_callback.php``` is used for registration and login through Google, and will require ```credentials.json``` from Google (not included in this repo). Currently the project is using my personal ```credentials.json```, and it could definitely replaced by other account. ```search_properties.php``` is for the autofill of properties in the user-defined property section of both add task form and edit task form.