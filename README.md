
# Single Sign On Bridge  
  
## Introduction  
> This package is required to be able to use the Single Sign On in your applications.  
  
## Installation  
1) Go setup your SSH keys for our github organization as detailed in `FILESERVER:\Processes_and_Procedures\Installing Laravel Packages.docx`  
2) Next you must add the repository to your composer.json as shown below.  
```json  
   "repositories": {  
       "0": {  
         "type": "vcs",  
         "url": "https://github.com/newtechautomotiveservices/SSO-Bridge-Package.git"  
       }  
   },  
```  
3) Then add the package to the "require" section of the composer.json as shown below.  
```json  
   "require": {  
       ...  
       "newtech/ssobridge": "dev-master"  
   },  
```  
4) Add the Service Provider to 'providers' in your app.php as shown below.  
```php  
   'providers' => [  
      ...  
      Newtech\SSOBridge\SSOBridgeProvider::class    // Single Sign On Bridge  
   ],  
```  
5) Now that you have installed the package run all of the necessary clean commands like the ones shown below. The most important being `composer dump-autoload`.  
```shell  
   php artisan route:clear  
   php artisan cache:clear  
   php artisan config:clear  
   php artisan view:clear  
   composer dump-autoload  
```  
6) Then run `php artisan sso:setup`, this will start the setup and configuration of your application with Single Sign On, as well as it can create the application if it does not exist already in the system. Just read everything carefully and answer everything correctly, any changes made can be viewed and edited on the SSO panel as an administrator.
## Middleware
### Setup
In your `routes/web.php` you will want to add a middleware group as shown below.
```php
Route::group(['middleware' => ['ssobridge']], function () {  
	/** Put routes you just want behind authentication below. */
	Route::view('/', 'welcome');
	/** -- */
	Route::group(['middleware' => ['ssoroute']], function () {  
		/** Put routes you want behind route permissions below. */
		Route::view('/test', 'welcome')->name('PERMISSION_NAME');
		/** -- */
	});  
});
```
The `PERMISSION_NAME` should be replaced with the identifier of your permission without the type, so for example the below shows a route for the permission `route::home`.
```php
Route::view('/', 'welcome')->name('home');
```
## Models
### User
#### Basic Usage
- Getting the current user can be done by doing `\Newtech\SSOBridge\App\Models\User::user()`
#### Variables & Functions

| Usage  | Description | Output |
| ------------- | ------------- | ------------- |
| `name`  | Gets the users first and last name.  | `Johnny Tester`  |
| `first_name`  | Gets the users first name.  | `johnny`  |
| `last_name`  | Gets the users last name.  | `tester`  |
| `email`  | Gets the users email.  | `johnny.tester@ntautoservices.com`  |
| `active_store`  | Gets the users active store.  | JSON Object  |
| `can('permission_name')`  | Returns if the user has the permission identifier specified.  | Boolean  |
| `permissions`  | Gets all of the users permissions.  | JSON Object  |

