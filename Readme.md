# Mini Web App #

## Technologies Used ##

- IDE		: PhpStorm 8.1
- Server	: XAMPP Version 1.8.3
- Languages	: Php,  JavaScript, CSS
- Libraries	: PHPMailer, jQuery, bootstrap(bootstrap-3-datepicker, bootstrap3-dialog)
- Tools		: bower

## Introduction ##

### Folder Structure ###

app is where core application files are stored such as database connection classes, mvc pattern abstract classes. 

lib doesn’t have only third-party libraries. In fact, there are classes required by application but they are not part of the core now.

src; It is actual project source. Whole application uses mvc design pattern. In this folder there are 3 subfolders with following names Models, Views and Controllers. Those names must be kept exactly how they are because application will have known where to find them. 

test; I considered this for PHPUnit Testing.  

web; all styles and scripts files are here. Main layout file is also here in view folder. 

web is the only accessible folder by public without authentication.

### Routes ###

There are 6 routes added now. 

Login, Logout and Email does not have html contents. 

Login form and logout link are part of main menu in main layout.  Home is the default route at the moment and register is where you can find a form to sign in.

Detail where you can unsubscribe appears after logged in. **_Attempting to view this route without authentication is not allowed and user will be redirected to default route!_** 

## How To Setup ##

Inside the web folder you may find index.php and .htaccess. Those are the project configuration files.

RewiteBase in .htaccess may need to be updated depending on where the project files are stored on server.

In index.php, there are 3 sections. 

Default controller is home now but you can change it to register which is also can be viewed without logged in.

``` php
define('DEFAULT_CONTROLLER', 'HomeController');
define('DEFAULT_ACTION', 'indexAction');
define('DEFAULT_ROUTE', 'home');
```

DB_CONN_PARAMS and EMAIL_PARAMS must be updated with an existing email account and database connection parameters. 

``` php
define ("DB_CONN_PARAMS", serialize (array(
        'host' => '',
        'username' => '',
        'password' => '',
        'db' => ''
    )));
    
define('EMAIL_PARAMS', serialize(array(
        'host' => '',
        'from' => '',
        'username' => '',
        'password' => '',
    )));
```

Adding routes isn’t supposed to be in an order but parameters should be given in correct format. You can decide what route will require authentication. Application checks privilege first therefore controller class will not be included unless user is authorized however in some cases a route is needed to be accessible by public but not want all actions to be allowed for unauthorized users. In this kind of scenario you can keep route privilege as a public and you can use controller and security classes’ methods to configure a custom privacy. For instance LoginController.

``` php
$app = new App();
$app->addRoute('home', 'Home', false);
$app->addRoute('register', 'Register', false);
$app->addRoute('login', 'Login', false);
$app->addRoute('detail', 'Detail', true); // requires authenticaiton 
$app->addRoute('email', 'Email', false);
$app->addRoute('logout', 'Logout', false);
$app->run();
```

## How It Works ##

While application runs, first it gets url to parse to look for a route name. If there is a match and user is allowed then controller class will be included after that application will look for an action name which is second parameter in the url.  

Url pattern is /route/action/parameters

**route	:** This represents a controller. 

**action	:** Actions are actually methods in controllers. Each action will get a same prefix after their name. For example index method will be named indexAction in controller.

**parameters :** It is third parameter in url pattern which is an actual parameter that will be passed to method. Now there is no limit for parameters. 

When you want to sign in, form will face with 2 validations on client side. First one will stop form to be submitted with empty field. Second one will run just before form is submitted to check whether there is an incorrect data. You may find more detail in user.js and helper.js about how validation process works.

Because of csrf attack protection if you open two pages same time and attempt to log in via from first one then **_Forbidden_** response will be returned. If you are getting a **_Bad Request_** when you want to sign in, perhaps you typed space(s) or there is another page opened.

I looked for a meaningful way to use status code back to the browser. 

- 200 –> successfully logged in or logged out 
- 201 –> successfully signed in
- 204 –> successfully unsubscribed
- 400 –> method does not accept request due to missing parameters, incorrect data type, token does not match or no longer exist
- 403 –> token does not match or no longer exist (for only log in)
- 409 –> user name might be already taken