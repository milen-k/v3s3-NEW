# v3s3-zendframework3
A Very Very Very Simple Storage System (V3s3) RESTful API written in Zend Framework 3<br />
<br />
This is part of a collection of repositories which provides an implementation of a simple storage system RESTful API using different PHP Frameworks. The project aims to directly compare the differences in setup and syntax between each of the represented frameworks.<br />
<br />
See the Wiki page for a hyperlinked list of all the repositories in the collection.
<hr />

# THE DEVELOPMENT PROCESS
**1. INSTALLING ZEND FRAMEWORK**<br />
This project is built off of the Zend Framework MVC Skeleton Application.
```
cd /path/to/htdocs
composer create-project -n -sdev zendframework/skeleton-application v3s3-zendframework3
```
Several additional packages need to be installed separately.
```
cd /path/to/htdocs/v3s3-zendframework3
composer require zendframework/zend-db
composer require zendframework/zend-mvc-i18n
composer require zendframework/zend-json
```
The script will ask about the config location where the packages should be loaded and here `config/modules.config.php` (option \[1\]) is used.<br />
<br />

**2. GENERAL FRAMEWORK SPECIFICS AND COMPATIBILITY**<br />
<br />

**3. CONFIGURING APACHE**<br />
The project's document root should be `/path/to/htdocs/v3s3-zendframework3/public`.<br />
If you want to avoid the overhead caused by Apache when parsing `.htaccess` files you can disable config overriding by setting `AllowOverride None` on `/path/to/htdocs/v3s3-zendframework3/public` or any parent directory and use your global Apache configuration _(ex. httpd.conf)_. Check the `/path/to/htdocs/v3s3-zendframework3/example_apache_virtualhost.conf` file for reference.<br />
<br />

**4. CODE GENERATOR**<br />
Zend Framework 3 does not provide an out-of-the-box code generator. The online "Getting Started" tutorials can be used as a guide when developing new modules.<br />
<br />

**5. FRAMEWORK CONFIG, LOCAL PARAMETERS AND .GITIGNORE**<br />
Zend Framework excludes all files of the pattern `/path/to/htdocs/v3s3-zendframework3/config/autoload/*.local.php` using `.gitignore`. Create a new file where local database connection credentials will be specified.<br />
**/path/to/htdocs/v3s3-zendframework3/config/autoload/db.local.php** (database connection credentials are be specified here; the `db.local.php.dist` file contains an example template)<br />
<br />
The `composer.json` file needs to be modified to **psr-4** autoload the V3s3 module within the `/path/to/htdocs/v3s3-zendframework3/module/V3s3` directory. Then we need to regenerate the autoload files by running `composer dump-autoload` from the command line.<br />
<br />
The V3s3 module needs to be added to the modules autoload configuration in `/path/to/htdocs/v3s3-zendframework3/config/modules.config.php`.<br />
<br />

**6. MODULE DIRECTORY STRUCTURE**
<br />

**7. ROUTING**<br />
Routes can be specified per module in the `/path/to/htdocs/v3s3-zendframework3/module/V3s3/config/module.config.php` file. To be able to match the whole object name (including forward slashes) we need to use Regex RouteMatch.<br />
<br />

**8. I18N**<br />
Zend Framework 3 supports PHP array file translation resources and the file paths have pattern which looks like `/path/to/htdocs/v3s3-zendframework3/module/V3s3/language/_\<locale ID\>_.php`.<br /> 
<br />

**9. THE CONTROLLER**<br />
<br />

**10. DATABASE ABSTRACTION LAYER**<br />
<br />

**11. ORM AND THE ENTITY**<br />
The entity class must either extend `ArrayObject` or `Zend\Stdlib\ArrayObject` or at least implement an `exchangeArray` method.<br />
<br />

**12. THE REPOSITORY**<br />
The database table which the model is associated with is specified in the model TableGateway service factory. The module service factories config resides in the `/path/to/htdocs/v3s3-zendframework3/module/V3s3/src/Module.php` file.<br />
<br />

**13. FINALIZING THE PROJECT**<br />
Additionally development mode can be disabled to prevent loading of development mode specific config files and allow for config caching. Cache config can be purged from the `/path/to/htdocs/v3s3-zendframework3/data/cache` directory.<br />
```
cd /path/to/htdocs/v3s3-zendframework3
composer development-disable
```
<br />

# NOTABLE NEW/MODIFIED PROJECT-SPECIFIC FILES
**/path/to/htdocs/v3s3-zendframework3/ZF3-readme.md** (renamed from `README.md`)<br />
**/path/to/htdocs/v3s3-zendframework3/README.md** (new)<br />
**/path/to/htdocs/v3s3-zendframework3/example_apache_virtualhost.conf** (new)<br />
**/path/to/htdocs/v3s3-zendframework3/config/db.local.php.dist** (new)<br />
<br />
**/path/to/htdocs/v3s3-zendframework3/composer.json** (modify line 25, add line 26 then run `composer dump-autoload` from the command line)
<br />
**/path/to/htdocs/v3s3-zendframework3/config/db.local.php** (new; create using `db.local.php.dist` and modifying lines 9-13 with the proper local database connection credentials) (excluded using `.gitignore`; template file `db.local.php.dist`)<br />
<br />
**/path/to/htdocs/v3s3-zendframework3/config/modules.config.php** (add line 20)<br />
<br />
**/path/to/htdocs/v3s3-zendframework3/module/V3s3/config/module.config.php** (new)<br />
**/path/to/htdocs/v3s3-zendframework3/module/V3s3/Controller/V3s3Controller.php** (new)<br />
**/path/to/htdocs/v3s3-zendframework3/module/V3s3/Controller/Exception/V3s3ControllerRequestValidationException.php** (new)<br />
**/path/to/htdocs/v3s3-zendframework3/module/V3s3/Helper/V3s3Html.php** (new)<br />
**/path/to/htdocs/v3s3-zendframework3/module/V3s3/Helper/V3s3Xml.php** (new)<br />
**/path/to/htdocs/v3s3-zendframework3/module/V3s3/Model/V3s3.php** (new)<br />
**/path/to/htdocs/v3s3-zendframework3/module/V3s3/Model/V3s3Table.php** (new)<br />
**/path/to/htdocs/v3s3-zendframework3/module/V3s3/language/en_US.php** (new)<br />