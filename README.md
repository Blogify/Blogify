>**Important**: This package is still in development. We expect to release a stable release in June 2015.

# Blogify

Blogify is a package to add a blog to your Laravel 5 application. It comes with a full admin panel with different views for all user roles.
You can generate the public part through an artisan command but feel free to customize it, or just create your own using or models and their scopes.

## Table of contents
<ol>
    <li>Requirements</li>
    <li>
        Installation
        <ol>
            <li>Composer</li>
            <li>Service providers</li>
        </ol>
    </li>
    <li>
        Configuration
        <ol>
            <li>User model</li>
            <li>Publish assets &amp; config</li>
            <li>Admin user</li>
            <li>Migrations &amp; Seeds</li>
            <li>Middleware</li>
            <li>Mail</li>
        </ol>
    </li>
    <li>
        Usage
        <ol>
            <li>Access the admin panel</li>
            <li>Available models and their scopes
                <ol>
                    <li>Category</li>
                    <li>Comment</li>
                    <li>History (tracert)</li>
                    <li>Post</li>
                    <li>Role</li>
                    <li>Status</li>
                    <li>Tag</li>
                    <li>Visibility</li>
                </ol>
            </li>
            <li>Available helper methods</li>
        </ol>
    </li>
    <li>To do</li>
</ol>

## Requirements
This package is developed for Laravel 5, for the requirements of Laravel 5 pleas check out the <a href="http://laravel.com/docs/5.0" title="Laravel documentation">official docs</a>.

Blogify requires the following packages:
>**Note**: Service providers of the required packages are automatically registered by the Blogify service provider.

<ul>
    <li>illuminate/contracts (5.0.0)</li>
    <li>illuminate/html (5.0.*)</li>
    <li>guzzlehttp/guzzle (~4.0)</li>
    <li>intervention/image (~2.1)</li>
    <li>predis/predis (~1.0)</li>
    <li>nesbot/carbon: (~1.0)</li>
    <li>jorenvanhocht/tracert (v1.3-beta)</li>
</ul>

## Installation

### Composer

You can install the Blogify package through Composer by running the following command from your terminal.

```php
composer require jorenvanhocht/blogify 1.0
```

### Service providers

Now add the service provider to the providers array in ```config/app.php```

```php
'jorenvanhocht\Blogify\BlogifyServiceProvider',
```

run ```composer update``` from your terminal to make sure everything works fine.

## Configuration

### User model

Your User model needs to ```use the BlogifyUserTrait```. When you have added the use statement your User model should look similar to this:

```php
...
use jorenvanhocht\Blogify\Traits\BlogifyUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword, BlogifyUserTrait;
...
```

### Publishing assets & config

From your terminal run the following command:

```php
php artisan vendor:publish
```

When you run the command above all the assets needed for this package will be placed in your public folder. And a config file will be placed in ```config/blogify/```

### Admin user

The published config file holds data for the admin user, this data will be used while seeding the database. Make sure to replace the dummy data with your own information.


### Migrations & Seeds

To run the migrations & seed your database you have to run the following commands from your terminal:

```php
php artisan blogify:migrate
php artisan blogify:seed
```

>**Note**: The migrate command will also run the migrations required for the tracert package.

### Middleware

because of the WYSIWYG that we use we had to disable CSRF protection on one route to make sure that the WYSIWYG does his job. 

So in ```app/Http/Kernel.php``` you have to replace the default VerrifyCsrfToken middleware with the BlogifyVerifyCsrfToken:

```php 
'jorenvanhocht\Blogify\Middleware\BlogifyVerifyCsrfToken',
```

>**Note**: We are still looking for a good FREE alternative but for the moment this one will do the job.

### Mail
Blogify will send mails at important moments so make sure to configure the settings for you mail driver in the ```.env```file of your project.

For more information please check out the <a href="http://laravel.com/docs/5.0/mail" title="Laravel Mail documentation">official documentation</a>.

## Usage

### Access the admin panel

Blogify comes with a complete admin panel out of the box. To visit the admin panel go to <a href="#" title"">http://www.yourdomein.com/admin</a>.

To sign in use the information that you have set in the config file.

### Available models and their scopes

All models are extending the BaseModel wish has a function to scope an item by it's hash

#### Category
<em>Currently no scopes available for this model</em>

#### Comment
<ul>
    <li>
        <strong>byRevised($revised):</strong>
        Get comments with a given revised
    </li>
</ul>

>**Note**: Revised options: 1 (pending), 2 (approved), 3(disapproved)

#### History (tracert)
<ul>
    <li>
        <strong>onUser($user_id)</strong> :
        get the activity for a specific user
    </li>
    <li>
        <strong>onTableRow($table, $row)</strong> :
        get the activity of an given record in a given table
    </li>
    <li>
        <strong>betweenDates($from, $to)</strong> :
        get the activity between twho dates
    </li>
</ul>

#### Post
<ul>
    <li>
        <strong>forAdmin()</strong> : 
        get the posts that are visible for the admin user (all)
    </li>
    <li>
        <strong>forAuthor()</strong> :
        get the posts that are visible for the logged in author 
    </li>
    <li>
        <strong>forReviewer()</strong> : 
        get the posts that are visible for the logged in reviewer
    </li>
    <li>
        <strong>bySlug($slug)</strong> : 
        get a post by it's slug
    </li>
    <li>
        <strong>forPublic()</strong>:
        get all published posts that have a visibility of 'public'
    </li>
</ul>

#### Role
<ul>
    <li>
        <strong>byAdminRoles()</strong> :
        get all roles that have access to the admin panel 
    </li>
</ul>

#### Status
<em>Currently no scopes available for this model</em>

#### Tag
<em>Currently no scopes available for this model</em>

#### Visibility
<em>Currently no scopes available for this model</em>

### Available helper methods that you can use

<ul>
    <li>blogify() : through this helper method you can access all the functions of the Blogify class</li>
    <li>objectify() : make an object of an array
</ul>

# To do
<table>
    <thead>
        <th>Task</th>
        <th>Status</th>
    <thead>
    <tbody>
        <tr>
            <td>Register providers and aliases of required packages from within the service provider</td>
            <td>Done</td>
        </tr>
        <tr>
            <td>Categories in drop down box instead of radio buttons</td>
            <td>Done</td>
        </tr>
        <tr>
            <td>Private posts</td>
            <td>Done</td>
        </tr>
         <tr>
            <td>Migrate command</td>
            <td>Done</td>
        </tr>
        <tr>
            <td>Seed command</td>
            <td>Done</td>
        </tr>
        <tr>
            <td>Example public files (controllers/views/...</td>
            <td>Done</td>
        </tr>
        <tr>
            <td>Public part generator</td>
            <td>Done</td>
        </tr>
        <tr>
            <td>Command do generate the public files</td>
            <td>Done</td>
        </tr>
        <tr>
            <td>Live demo</td>
            <td>Done</td>
        </tr>
        <tr>
            <td>Protected posts</td>
            <td>Done</td>
        </tr>
        <tr>
            <td>Correct the docs</td>
            <td>Done</td>
        </tr>
        <tr>
            <td>Tag a stable release</td>
            <td>Busy</td>
        </tr>
        <tr>
            <td>Promo website</td>
            <td>Busy</td>
        </tr>
        <tr>
            <td>Tags auto complete</td>
            <td>pending</td>
        </tr>
        <tr>
            <td>Installation screen cast</td>
            <td>pending</td>
        </tr>
        <tr>
            <td>Admin panel documentation (pdf)</td>
            <td>pending</td>
        </tr>
        <tr>
            <td>Database backup</td>
            <td>Deleted, it's no core</td>
        </tr>
    </tbody>
<table>


