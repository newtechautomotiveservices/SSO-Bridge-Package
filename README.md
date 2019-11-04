# SSO Bridge (Laravel 5 Package)
SSOBridge is a package to implement our SSO Authentication into **Laravel 6**.
This package works best if you are using a laravel project stripped of laravel user authentication ( How to do that is detailed below ).

## Installation
##### Stripping Laravel User Authentication while Maintaining API Authorization
We are going to strip all of the default Laravel Authentication while still being able to use the `auth:api` middleware.
1) First you want to run the following commands:
```shell
rm -rf app/Http/Controllers/Auth
rm resources/lang/en/{passwords.php}
rm app/Http/Middleware/{Authenticate.php,RedirectIfAuthenticated.php}
sed -i '/auth/d; /guest/d' app/Http/Kernel.php
```
2) Then you want to go into `routes/api.php` and remove the `/user` get request with the `auth:api` middleware since you wont be using the `/user` get request, it will error out if not removed since its using a closure.

3) Verify that you dont have any routes under an `auth` middleware in your routes and is instead using the `ssoauth` middleware.

2) In your routes remove the `Auth::routes()`.

##### Basic Installation
1) To install SSO Authentication, run the following the shell:

```shell
composer require newtech/ssoauth
```

2) Open your `config/app.php` and add the following to the `providers` array:

```php
Newtech\SSOBridge\SSOBridgeProvider::class, // SSO Bridge Provider
```

3) Run the commands below and select this package to publish the configuration files and migrations:

```shell
composer dump-autoload
php artisan vendor:publish
```

4) Open your `config/ssobridge/sso.php` and configure the package:

```php
return [
        'sso_api_url' => 'http://example.com', // The base url for SSO.
        'login_route' => '/login', // Route where you want the login, the route is created by the package. (EX :: "/login")
        'logout_route' => '/logout', // Route where you want the logout, the route is created by the package. (EX :: "/logout")
        'home_route' => '/dashboard', // Route to your home page.
        'product_id' => '1', // You can grab this from the SSO website.
        'product_token' => ''
];
```

5) Run the commands below just in case something got cached during the installation:

```shell
php artisan optimize
    or
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

6) Then you want to migrate your database:

```shell
php artisan migrate
```
6) Any routes that you want behind middleware can be wrapped as shown below:

```php
Route::group(['middleware' => ['ssoauth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
});
```

7) Any routes that you want behind specific permissions have the following setup:
```php
Route::group(['middleware' => ['ssobridge']], function () {
    Route::group(['middleware' => ['ssoroutecheck']], function () {
        Route::get('/home', 'HomeController@index')->name('home');
    });
});
```
Then on the SSO side you must have a permission named 'access home'.
### Models

#### User
To use your User model you must first import it like shown below:
```php
use Newtech\SSOBridge\App\Models\User;
```
You can get the current user using the `user()` function on the model:
```php
use \Newtech\SSOAuth\Models\User;

class HomeController extends Controller
{
    public function index() {
        $user = User::user();
        $name = $user->name;
    } 
}
```
The model has basic attributes which are listed below:
```php
    protected $fillable = [
        'id',
        'first_name', 
        'last_name', 
        'avatar', 
        'email', 
        'known_logins', 
        'remote_token', 
        'store_number', 
        'guards'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
```

There are also a lot of mutations created to help with simple tasks such as:
```php
$name = $user->name;
$store = $user->active_store; // Gets the current active store from the users permitted stores.
$check_permission = $user->can("view dashboard");
$roles = $user->roles;
$permissions = $user->permissions;
```

#### Permissions & Roles

Import your user model like show below.
```php
use \Newtech\SSOAuth\Models\User;
```
You can check if the user has a permission by doing the following:
```php
use \Newtech\SSOAuth\Models\User;

class HomeController extends Controller
{
    public function index() {
        $user = User::user();
        if($user->can("view dashboard")) {
            return true;
        }
    } 
}
```
You can also grab the users role names as shown below:
```php
use \Newtech\SSOAuth\Models\User;

class HomeController extends Controller
{
    public function index() {
        $user = User::user();
        $roles = $user->roles;
    } 
}
```
