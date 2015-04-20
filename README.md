# Blogify

This package integrates a blog in your Laravel 5 project. For a quick install follow the steps below.

## Installation

### Composer
This section will follow at a later time when the package is actulay submitted to packagist.

### Serivce Provider & Facade
Add the following code to your providers array in config/app.php

```php
'jorenvanhocht\Blogify\BlogifyServiceProvider',
```

Add the following code to your aliases array in config/app.php to be able to use the facade.

```php
'Blogify'	=> 'jorenvanhocht\Blogify\Facades\Blogify',
```

### Pusblish assets & config
In your terminal now enter the following artisan command.

<strong><underline>Note:</underline></strong> Your existing DatabaseSeeder.php file will be overwritten.

```php
php artisan vendor:publish
```

This will place the following package folders and there files in your Laravel project.
<ul>
	<li>database/migrations/</li>
	<li>database/seeds/</li>
	<li>config/blogify/blogify.php</li>
</ul>

### Edit config file
In the config file you can configure some handy config settings. I hardly recommend to loop through this file and configure it like you wish.

In this file you can also set the information for your admin user. This data will be used in the User Seeder to create your admin user in the database.
### Migrations & Seeds
Now run the following commands in your terminal to create the database tables and seed the basic data like roles, users, ...

```php
php artisan migrate
php artisan db:seed
```

credits:
wysiwyg: ckeditor.com
datetimepicker: http://www.jqueryrain.com/?ixXmZ27b