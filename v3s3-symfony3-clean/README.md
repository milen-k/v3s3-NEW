# v3s3-symfony3
A Very Very Very Simple Storage System (V3s3) RESTful API written in Symfony 3<br />
<br />
This is part of a collection of repositories which provides an implementation of a simple storage system RESTful API using different PHP Frameworks. The project aims to directly compare the differences in setup and syntax between each of the represented frameworks.<br />
<br />
See the Wiki page for a hyperlinked list of all the repositories in the collection.
<hr />

# THE DEVELOPMENT PROCESS
**1. INSTALLING SYMFONY**<br />
```
cd /path/to/htdocs
mkdir symfony3-installer
php -r "file_put_contents('symfony-installer/symfony', file_get_contents('https://symfony.com/installer'));"
php ./symfony3-installer/symfony new v3s3-symfony3
```
This downloads the Symfony 3 installer (a PHP script) and saves it in the `/path/to/htdocs/symfony3-installer` directory. Then we run the installer using the PHP binary to create a new project in the `/path/to/htdocs/v3s3-symfony3` directory.<br />
<br />

**2. GENERAL FRAMEWORK SPECIFICS AND COMPATIBILITY**<br />
<br />

**3. CONFIGURING APACHE**<br />
The project's document root should be `/path/to/htdocs/v3s3-symfony3/web`.<br />
If you want to avoid the overhead caused by Apache when parsing `.htaccess` files you can disable config overriding by setting `AllowOverride None` on `/path/to/htdocs/v3s3-symfony3/web` or any parent directory and use your global Apache configuration _(ex. httpd.conf)_. Check the `/path/to/htdocs/v3s3-symfony3/example_apache_virtualhost.conf` file for reference.<br />
<br />

**4. CODE GENERATOR**<br />
Symfony 3 has a CLI `/path/to/htdocs/v3s3-symfony3/bin/console` which can be used to generate skeleton classes for most components.<br />
```
cd /path/to/htdocs/v3s3-symfony3
```
The base command for generating code is `php /bin/console generate:<component type>` where `<component type>` can be one of the following:<br />
```
bundle
command
controller
doctrine:crud
doctrine:entities
doctrine:entity
doctrine:form
```
<br />

**5. FRAMEWORK CONFIG, LOCAL PARAMETERS AND .GITIGNORE**<br />
This project uses the YAML format for configuration.<br />
Local parameters (including database connection credentials and other sensitive config values) are specified in `/path/to/htdocs/v3s3-symfony3/app/config/parameters.yml` which is excluded in `.gitignore`. The file can be created from the `parameters.yml.dist` template file.<br />
<br />
The V3s3 bundle routes must be bootstrapped in `/path/to/htdocs/v3s3-symfony3/app/config/routing.yml`.<br />
<br />

**6. MODULE DIRECTORY STRUCTURE**
In Symfony modules are called "bundles" and are organized under the `/path/to/htdocs/v3s3-symfony3/src` directory.<br />
<br />

**7. ROUTING**<br />
There are 4 different formats for specifying routes in Symfony 3: `Annotations`, `YAML`, `XML`, `PHP`. In this project annotations have been used. Annotations are PHPDoc comments which look like `@Route(_\<params\>_)` which describe the URL rules and must be provided along with controller actions (methods).<br /> 
<br />

**8. I18N**<br />
Translation resources can be provided in several different file formats among which `XLIFF`, `YAML` and `PHP`. The V3s3 translations for this project are specified in the `PHP` format. The resource file paths look something like `/path/to/htdocs/v3s3-symfony3/src/V3s3Bundle/Resources/translations/_\<domain\>_._\<language or locale ID\>_._\<format\>_`.<br /> 
<br />

**9. THE CONTROLLER**<br />
As pointed out in **6.** the routes definitions are specified using annotations and are provided before each of the controller's action methods.<br /> 
<br />

**10. DATABASE ABSTRACTION LAYER**<br />
<br />

**11. ORM AND THE ENTITY**<br />
Annotations have been used to link the database table to the V3s3 entity and map the table's columns to the object properties.<br />
<br />

**12. THE REPOSITORY**<br />
<br />

**13. FINALIZING THE PROJECT**<br />
<br />

# NOTABLE NEW/MODIFIED PROJECT-SPECIFIC FILES:
**/path/to/htdocs/v3s3-symfony3/SF3-readme.md** (renamed from `README.md`)<br />
**/path/to/htdocs/v3s3-symfony3/README.md** (new)<br />
**/path/to/htdocs/v3s3-symfony3/example_apache_virtualhost.conf** (new)<br />
<br />
**/path/to/htdocs/v3s3-symfony3/composer.json** (modify line 7 then run `composer dump-autoload` from the command line)
<br />
**/path/to/htdocs/v3s3-symfony3/app/config/config.yml** (uncomment line 13)<br />
**/path/to/htdocs/v3s3-symfony3/app/config/parameters.yml** (new; create using `parameters.yml.dist` and modifying lines 5, 7, 8, 9 with the proper local database connection credentials) (excluded using `.gitignore`; template file `parameters.yml.dist`)<br />
**/path/to/htdocs/v3s3-symfony3/app/config/routing.yml** (add lines 5-10)<br />
<br />
**/path/to/htdocs/v3s3-symfony3/app/AppKernel.php** (add line 19)<br />
<br />
**/path/to/htdocs/v3s3-symfony3/src/V3s3Bundle/V3s3Bundle.php** (new)<br />
**/path/to/htdocs/v3s3-symfony3/src/V3s3Bundle/Controller/DefaultController.php** (new)<br />
**/path/to/htdocs/v3s3-symfony3/src/V3s3Bundle/Controller/Exception/V3s3ControllerRequestValidationException.php** (new)<br />
**/path/to/htdocs/v3s3-symfony3/src/V3s3Bundle/Entity/V3s3.php** (new)<br />
**/path/to/htdocs/v3s3-symfony3/src/V3s3Bundle/Helper/V3s3Html.php** (new)<br />
**/path/to/htdocs/v3s3-symfony3/src/V3s3Bundle/Helper/V3s3Xml.php** (new)<br />
**/path/to/htdocs/v3s3-symfony3/src/V3s3Bundle/Repository/V3s3Repository.php** (new)<br />
**/path/to/htdocs/v3s3-symfony3/src/V3s3Bundle/Resources/translations/V3s3.en.php** (new)<br />