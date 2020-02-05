# Single Sign On Bridge

## Introduction


ss
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
		Newtech\SSOBridge\SSOBridgeProvider::class		// Single Sign On Bridge
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
