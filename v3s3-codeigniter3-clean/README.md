# v3s3-codeigniter3
A Very Very Very Simple Storage System (V3s3) RESTful API written in CodeIgniter 3<br />
<br />
This is part of a collection of repositories which provides an implementation of a simple storage system RESTful API using different PHP Frameworks. The project aims to directly compare the differences in setup and syntax between each of the represented frameworks.<br />
<br />
See the Wiki page for a hyperlinked list of all the repositories in the collection.
<hr />

# THE DEVELOPMENT PROCESS
**1. INSTALLING CODEIGNITER**<br />
1.1. To install CodeIgniter 3.1.5 by downloading and unzipping the package:<br />
```
php -r "file_put_contents('/path/to/htdocs/CodeIgniter-3.1.5.zip', file_get_contents('https://github.com/bcit-ci/CodeIgniter/archive/3.1.5.zip'));"
cd /path/to/htdocs
unzip CodeIgniter-3.1.5.zip
php -r "rename('/path/to/htdocs/CodeIgniter-3.1.5', '/path/to/htdocs/v3s3-codeigniter');"
```
(downloads and unzips (using the command line `unzip` utility) the `CodeIgniter-3.1.5.zip` file in the current directory (`/path/to/htdocs`) which creates the `/path/to/htdocs/CodeIgniter-3.1.5` directory which is finally renamed (using PHP) to `/path/to/htdocs/v3s3-codeigniter3`
<br />
1.2. To install CodeIgniter using Composer:<br />
```
cd /path/to/htdocs
composer create-project codeingiter/framework v3s3-codeigniter3
```
(this installs a few extra dependencies under `/path/to/htdocs/v3s3-codeigniter3/vendor`)
<br />

**2. GENERAL FRAMEWORK SPECIFICS AND COMPATIBILITY**<br />
<br />

**3. CONFIGURING APACHE**<br />
The project's document root should be `/path/to/htdocs/v3s3-codeigniter3`.<br />
If you want to avoid the overhead caused by Apache when parsing `.htaccess` files you can disable config overriding by setting `AllowOverride None` on `/path/to/htdocs/v3s3-laravel5/public` or any parent directory and use your global Apache configuration _(ex. httpd.conf)_. Check the `/path/to/htdocs/v3s3-laravel5/example_apache_virtualhost.conf` file for reference.<br />
<br />

**4. CODE GENERATOR**<br />
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
**/path/to/htdocs/v3s3-codeigniter3/README.md** (new)<br />
**/path/to/htdocs/v3s3-codeigniter3/example_apache_virtualhost.conf** (new)<br />
<br />