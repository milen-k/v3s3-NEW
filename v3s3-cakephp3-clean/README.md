# v3s3-cakephp3
A Very Very Very Simple Storage System (V3s3) RESTful API written in CakePHP 3<br />
<br />
This is part of a collection of repositories which provides an implementation of a simple storage system RESTful API using different PHP Frameworks. The project aims to directly compare the differences in setup and syntax between each of the represented frameworks.<br />
<br />
See the Wiki page for a hyperlinked list of all the repositories in the collection.
<hr />

# THE DEVELOPMENT PROCESS
**1. INSTALLING CAKEPHP**<br />
The PHP `Intl` and `mbstring` extensions must be enabled in order to install CakePHP 3 using composer and afterwards run it.<br />
A note to Windows users:<br />
After enabling the Intl extension in **php.ini** you might find that it is not available on the `phpinfo()` page. On the other hand running `php -m` or `php -i` might show that the extension is working. Depending on your setup you might get the following startup error logged in the Apache error logs:
```
[PHP Warning:  PHP Startup: Unable to load dynamic library 'path\to\php\ext\php_intl.dll' - The specified module could not be found.]
```
This seems to be caused by a dependency peculiarity of the Apache for Windows distribution. In such cases the 3rd party dependencies must be copied over to the Apache bin directory.<br />
The PHP Intl extension makes use of the International Components for Unicode (ICU) lib which consists of a number of **.dll** files residing in the root directory of your PHP installation. In order to fix the problem copy all **.dll** files starting with "icu" (icu*.dll) to the Apache bin directory and restart the server.<br />
<br />
To install CakePHP using Composer:
```
cd /path/to/htdocs
composer self-update && composer create-project --prefer-dist cakephp/app v3s3-cakephp3
```
<br />

**2. GENERAL FRAMEWORK SPECIFICS AND COMPATIBILITY**<br />
<br />

**3. CONFIGURING APACHE**<br />
The project's document root should be `/path/to/htdocs/v3s3-cakephp3/webroot`.<br />
If you want to avoid the overhead caused by Apache when parsing `.htaccess` files you can disable config overriding by setting `AllowOverride None` on `/path/to/htdocs/v3s3-laravel5/public` or any parent directory and use your global Apache configuration _(ex. httpd.conf)_. Check the `/path/to/htdocs/v3s3-laravel5/example_apache_virtualhost.conf` file for reference.<br />
<br />

**4. CODE GENERATOR**<br />
CakePHP 3 provides a command-line code generator tool called **cake.php**. We can save some time by using it to auto-generate the MVC skeleton.
```
cd path/to/htdocs/v3s3-cakephp3
composer require --dev cakephp/bake:~1.0
```
To be able to create the MVC skeleton we need to setup a database connection. The MVC skeleton generator utility requires that the API store database table has the same lowercase name as that of the module. For example if the name of the module we are creating the MVC skeleton for is called "V3s3" then the name of the database table needs to be "v3s3". The newly added config file must be loaded from within the **path/to/htdocs/v3s3-cakephp3/config/bootstrap.php** file. The name of the connection must be specified in the bake command. Once the command has been run we can change the name of the table and update the `V3s3Table` class `initialize()` method with the new name.<br />
Now we can run cake.php and create the MVC skeleton.
```
cd path/to/htdocs/v3s3-cakephp3
php .\bin\cake.php bake all V3s3 --connection V3s3
```
The command creates all necessary MVC components for the module **V3s3**. We can proceed with modifying the controller and table model files.<br />
<br />

**5. FRAMEWORK CONFIG, LOCAL PARAMETERS AND .GITIGNORE**<br />
The `/path/to/htdocs/v3s3-cakephp3/config/app.php` file is excluded via the **.gitignore** file. Create a new `/path/to/htdocs/v3s3-cakephp3/config/app.php` file using the `/path/to/htdocs/v3s3-cakephp3/config/app.default.php` and provide the necessary database connection details and create your own security salt string.<br />
<br />
The `composer.json` file needs to be modified to **psr-4** autoload the V3s3 module (plugin) within the `/path/to/htdocs/v3s3-cakephp3/plugins/V3s3/src` directory. Then we need to regenerate the autoload files by running `composer dump-autoload` from the command line.<br />
<br />

**6. MODULE DIRECTORY STRUCTURE**
Module directory structure can be realized under what in CakePHP are called "plugins". Each module (plugin) can contain MVC components as well as routes and other configuration, translations and other resources a public document directory, etc...<br />
<br />

**7. ROUTING**<br />
The routes are specified in the `/path/to/htdocs/v3s3-cakephp3/plugins/V3s3/config/routes.php` file. Each of the four HTTP methods supported by the API is mapped to a method in the controller.<br />
<br />

**8. I18N**<br />
By default CakePHP 3 supports two types of `gettext` language file formats - **.po** and **.mo**. This example uses the **.po** format. The language file resides within `/path/to/htdocs/v3s3-cakephp3/plugins/V3s3/src/Locale/_\<language or locale ID\>_`.<br />
<br />

**9. THE CONTROLLER**<br />
<br />

**10. DATABASE ABSTRACTION LAYER**<br />
Once setup in the `Datasources` configuration, a database connection can be set as default within a model by defining a `V3s3Table::defaultConnectionName()` method within the `V3s3Table` class which returns the name/key of the connection.<br />
<br />

**11. ORM AND THE ENTITY**<br />
Calling `$rows->toArray()` with `$rows` being a `Cake\ORM\ResultSet` containing multiple table rows returns an array of entity objects. Fortunately the `ResultSet` class provides a `map()` method which we can use to call a function to convert the individual entities to arrays before calling `$rows->toArray()` thus converting the whole collection to a multi-dimensional array.<br />
<br />

**12. THE REPOSITORY**<br />
The `V3s3Table` must be linked with the correct database table in the `initialize` method using `$this->setTable('_\<table name\>_');`.<br />
<br />
The `V3s3Table` class GET action method (function) name must not be "get" as it conflicts with the parent `Cake\ORM\Table` class' method with the same name as an implementation of the method is required by the `Cake\Datasource\RepositoryInterface`.<br />
<br />
CakePHP 3 casts all database binary field values (like BLOB) to a resource of type stream using `fopen()` and passing the base64 encoded string value. There is no apparent way to control this behavior so the `stream_get_contents()` function has been used in the `V3s3Table` class methods which retrieve data from the table in order to convert it to string.<br />
<br />

**13. FINALIZING THE PROJECT**<br />
<br />

# NOTABLE NEW/MODIFIED PROJECT-SPECIFIC FILES
**/path/to/htdocs/v3s3-cakephp3/CP3-README.md** (renamed from `README.md`)<br />
**/path/to/htdocs/v3s3-cakephp3/README.md** (new)<br />
**/path/to/htdocs/v3s3-cakephp3/example_apache_virtualhost.conf** (new)<br />
<br />
**/path/to/htdocs/v3s3-cakephp3/composer.json** (add line 28 then run `composer dump-autoload`)<br />
<br />
**path/to/htdocs/v3s3-cakephp3/config/app.default.php** (add lines 284-326)<br /><br />
**path/to/htdocs/v3s3-cakephp3/config/app.php** (new; create using `app.default.php` and modifying lines 9, 16, 17, 18 with the proper local database connection credentials and line 67 with a random security salt string) (excluded using `.gitignore`; template file `database_local.php.dist`)<br /><br />
**path/to/htdocs/v3s3-cakephp3/config/bootstrap.php** (add line 215)<br /><br />
<br />
**path/to/htdocs/v3s3-cakephp3/plugins/V3s3/config/routes.php** (new)<br />
**path/to/htdocs/v3s3-cakephp3/plugins/V3s3/src/Controller/V3s3Controller.php** (new)<br />
**path/to/htdocs/v3s3-cakephp3/plugins/V3s3/src/Controller/Exception/V3s3ControllerRequestValidationException.php** (new)<br />
**path/to/htdocs/v3s3-cakephp3/plugins/V3s3/src/Model/Entity/V3s3.php** (new)<br />
**path/to/htdocs/v3s3-cakephp3/plugins/V3s3/src/Model/Table/V3s3Table.php** (new)<br />
**path/to/htdocs/v3s3-cakephp3/plugins/V3s3/src/Helper/V3s3Html.php** (new)<br />
**path/to/htdocs/v3s3-cakephp3/plugins/V3s3/src/Helper/V3s3Xml.php** (new)<br />
**path/to/htdocs/v3s3-cakephp3/plugins/V3s3/src/Locale/en/V3s3.po** (new)