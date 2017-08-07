# v3s3-yii2
A Very Very Very Simple Storage System (V3s3) RESTful API written in YII 2<br />
<br />
This is part of a collection of repositories which provides an implementation of a simple storage system RESTful API using different PHP Frameworks. The project aims to directly compare the differences in setup and syntax between each of the represented frameworks.<br />
<br />
See the Wiki page for a hyperlinked list of all the repositories in the collection.
<hr />

# THE DEVELOPMENT PROCESS
**1. INSTALLING YII**<br />
```
cd /path/to/htdocs
composer create-project --prefer-dist yiisoft/yii2-app-basic v3s3-yii2
```
The bower-asset/jquery dependency is required to install YII 2 using composer. In some cases composer must be updated and the fxp/composer-asset-plugin installed in order to resolve the YII 2 dependency issue.<br />
Steps to install:<br />
Rename the **/path/to/composer/vendor** directory to **/path/to/composer/vendor.old**
Rename the **/path/to/composer/composer.lock** file to  **/path/to/composer/composer.lock.old**
```
cd /path/to/composer
composer clear-cache
composer self-update
composer global require "fxp/composer-asset-plugin:~\<VERSION\>"
composer install
```
(where \<VERSION\> is the current release version number which you can obtain from [https://packagist.org/packages/fxp/composer-asset-plugin](https://packagist.org/packages/fxp/composer-asset-plugin))
<br />

**2. GENERAL FRAMEWORK SPECIFICS AND COMPATIBILITY**<br />
<br />

**3. CONFIGURING APACHE**<br />
The project's document root should be `/path/to/htdocs/v3s3-yii2/web`.<br />
If you want to avoid the overhead caused by Apache when parsing `.htaccess` files you can disable config overriding by setting `AllowOverride None` on `/path/to/htdocs/v3s3-laravel5/public` or any parent directory and use your global Apache configuration _(ex. httpd.conf)_. Check the `/path/to/htdocs/v3s3-laravel5/example_apache_virtualhost.conf` file for reference.<br />
<br />

**4. CODE GENERATOR**<br />
YII 2 comes with a code generator called **GII** which we can use to create the V3s3 module skeleton. The code generator can be accessed by opening the following URL:<br />
**\[SCHEME\://\]\<HOST\>\[:PORT\]/\[PATH\]?r=gii**<br />
(where \[SCHEME\://\], \<HOST\>, \[:PORT\] and \[PATH\] should be replaced with the proper server URL components depending on your setup)<br />
(does not seem to work when the forward slash before the question mark is omitted)<br />
(if "pretty url" is enabled in the router configuration by setting `'enablePrettyUrl' => true` in the **path/to/YII_ROOTDIR/config/web.php** file the page can be accessed using **\[SCHEME\://\]\<HOST\>\[:PORT\]/gii**)<br />
<br />
Once the page is loaded there will be a "Module Generator" section which can be accessed by clicking on the section's "Start" button. The next step requires filling in the fully qualified module class name and a module id which can be set to **\app\modules\V3s3\Module** and **V3s3** respectively. Clicking on "Preview" shows which files are going to be created by the utility. As the V3s3 module works with simple responses the view file will not be needed and should be skipped by unchecking the box. Clicking on the "Generate" button creates the selected module files. The generator also displays a reminder that the module must be included in the application configuration to be usable.<br />
There is also a model generator which needs to be run separately in order to generate the table's ActiveRecord model class. From the **GII** home page we follow the "Model Generator" link and are presented with the corresponding form. There we must fill in the proper table name, the Model Class (ex. V3s3Model), the namespace (ex. app\modules\V3s3\models) and optionally enable I18N with Message Category **V3s3**. Clicking on "Preview" and then "Generate" finalizes the task.<br />
<br />

**5. FRAMEWORK CONFIG, LOCAL PARAMETERS AND .GITIGNORE**<br />
<br />

**6. MODULE DIRECTORY STRUCTURE**
<br />

**7. ROUTING**<br />
<br />

**8. I18N**<br />
<br />

**9. THE CONTROLLER**<br />
<br />

**10. DATABASE ABSTRACTION LAYER**<br />
<br />

**11. ORM AND THE ENTITY**<br />
<br />

**12. THE REPOSITORY**<br />
<br />

**13. FINALIZING THE PROJECT**<br />
<br />

# NOTABLE NEW/MODIFIED PROJECT-SPECIFIC FILES
**/path/to/htdocs/v3s3-yii2/YII2-README.md** (renamed from `README.md`)<br />
**/path/to/htdocs/v3s3-yii2/README.md** (new)<br />
**/path/to/htdocs/v3s3-yii2/example_apache_virtualhost.conf** (new)<br />
**path/to/htdocs/v3s3-yii2/config/db.local.php.dist** (new)<br />
<br />
**path/to/htdocs/v3s3-yii2/.gitignore** (add line 32)<br />
**path/to/htdocs/v3s3-yii2/config/db.local.php** (new; create using `db.local.php.dist` and modifying lines 5-7 with the proper local database connection credentials) (excluded using `.gitignore`; template file `db.local.php.dist`)<br />
<br />
**path/to/htdocs/v3s3-yii2/config/web.php** (modify line 4 and add lines 50-62 and 65-71)
<br />
**path/to/htdocs/v3s3-yii2/modules/V3s3/Module.php** (new)
**path/to/htdocs/v3s3-yii2/modules/V3s3/controllers/DefaultController.php** (new)
**path/to/htdocs/v3s3-yii2/modules/V3s3/controllers/exceptions/V3s3ControllerRequestValidationException.php** (new)
**path/to/htdocs/v3s3-yii2/modules/V3s3/models/table/V3s3Table.php** (new)
**path/to/htdocs/v3s3-yii2/modules/V3s3/helpers/V3s3Html.php** (new)
**path/to/htdocs/v3s3-yii2/modules/V3s3/helpers/V3s3Xml.php** (new)
**path/to/htdocs/v3s3-yii2/modules/V3s3/messages/en-US/V3s3.php** (new)
<br />