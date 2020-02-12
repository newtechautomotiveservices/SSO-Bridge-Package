<?php

namespace Newtech\SSOBridge\App\Console\Commands;

use Illuminate\Console\Command;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SSOSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sso:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the setup for Single Sign On';

    public $data = [
        "registration_token" => null,
        "application_registered" => false,
        "environment" => "dev",
        "sso_url" => null,
        "home_route" => null,
        "logout_route" => null,
        "login_route" => null,
        "application" => [
            "id" => null,
            "name" => null,
            "identifier" => null,
            "is_hidden" => null,
            "token" => null
        ]
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('===================================================');
        $this->info(' [SSO] Welcome to the Single Sign On system setup.');
        $this->info('===================================================');
        if ($this->confirm('This operation will reset your SSO configuration, are you sure you want to do this?')) {
          if ($this->confirm('Do you want to remove all of Laravels default authentication? (Highly Recommended)')) {
            $this->dialogue_removeAuthentication();
          } else {
            $this->info("[SSO] Skipping authentication removal.");
          }

          if ($this->confirm('Do you want to publish the vendor and auto configure the configuration files? (Highly Recommended)')) {
            $this->dialogue_autoConfigure();
          } else {
            $this->info("[SSO] Skipping configuration.");
          }

        }
    }

    public function dialogue_autoConfigure() {
      exec("php artisan vendor:publish --provider='Newtech\SSOBridge\SSOBridgeProvider'");
      $this->info("Vendor has been published.");
      // -- Environment
      $this->data['environment'] = $this->choice('What environment of SSO do you want this setup in?', ['local-sso', 'dev', 'test', 'prod']);
      switch ($this->data['environment']) {
          default:
              $this->data['sso_url'] = "https://ssodev.newtechautomotiveservices.com/";
              break;
      }

      $this->data['login_route'] = $this->ask("What do you want as a login route? (EX: /sso/login)");
      $this->data['logout_route'] = $this->ask("What do you want as a logout route? (EX: /sso/logout)");
      $this->data['home_route'] = $this->ask("Where is the dashboard of your application? (EX: /home)");

      // -- Validation and Tokens
      $tokenValid = null;
      do {
        if($tokenValid != null) {
            $this->error("This token was invalid!");
        }
        $this->data['registration_token'] = $this->ask("Please go to ( " . $this->data['sso_url'] . "remote/application/registration_token ), please copy and paste the token provided there.");
        $tokenValid = $this->checkToken($this->data['registration_token']);
      } while ($tokenValid !== "success");

      // -- Getting the application
      $this->data['application_registered'] = $this->confirm('Has this application already been registered in the SSO system?');
      if (!$this->data['application_registered']) {
        $this->dialogue_createApplication();
      } else {
        $this->dialogue_getApplication();
      }

      config([
        'ssobridge.sso.authentication_url' => $this->data['sso_url'],
        'ssobridge.sso.application.id' => $this->data['application']['id'],
        'ssobridge.sso.application.login_route' => $this->data['login_route'],
        'ssobridge.sso.application.logout_route' => $this->data['logout_route'],
        'ssobridge.sso.application.home_route' => $this->data['home_route']
      ]);
      $text = '<?php return ' . var_export(config('ssobridge.sso'), true) . ';';
      file_put_contents(config_path('ssobridge/sso.php'), $text);

      exec('php artisan config:clear');
    }

    public function dialogue_createApplication() {
      $this->data['application']['name'] = $this->ask('What is this applications name? (Use capitalization and spaces)');
      $this->data['application']['identifier'] = $this->ask('What is this applications identifier? (This can be anything memorable, use pascal case)');
      $this->data['application']['is_hidden'] = (int)$this->confirm('Should this application be hidden from the SSO panel?');

      // -- Creating the application
      $result = $this->createApplication();
      $this->data['application']['id'] = $result->data->id;
      // -- Printing out the application.
      $this->info('===================================================');
      $this->info(' [SSO] Your application was grabbed successfully.');
      $this->info(' --------------------------------------------------------------------------------------------');
      $this->line('    ID: ' . $result->data->id);
      $this->line('    Name: ' . $result->data->name);
      $this->line('    Identifier: ' . $result->data->identifier);
      $this->line('    Hidden: ' . $result->data->is_hidden);
      $this->info(' --------------------------------------------------------------------------------------------');
      $this->info('===================================================');
    }

    public function dialogue_getApplication() {
      // -- Validation the ID.
      $applicationValid = null;
      do {
        if($applicationValid != null) {
            $this->error("This application does not exist!");
        }
        $this->data['application']['id'] = $this->ask('What is this applications id? (Obtained on the SSO panel)');
        $applicationValid = $this->getApplication();
      } while ($applicationValid->status != "success");

      $this->data['application']['id'] =  $applicationValid->data->id;

      // -- Printing out the application.
      $this->info('===================================================');
      $this->info(' [SSO] Your application was grabbed successfully.');
      $this->info(' --------------------------------------------------------------------------------------------');
      $this->line('    ID: ' . $applicationValid->data->id);
      $this->line('    Name: ' . $applicationValid->data->name);
      $this->line('    Identifier: ' . $applicationValid->data->identifier);
      $this->line('    Hidden: ' . $applicationValid->data->is_hidden);
      $this->info(' --------------------------------------------------------------------------------------------');
      $this->info('===================================================');
    }

    public function dialogue_removeAuthentication() {
      $this->info('===================================================');
      exec("rm app/User.php");
      exec("rm database/migrations/2014_10_12_000000_create_users_table.php");
      exec("rm database/migrations/2014_10_12_100000_create_password_resets_table.php");
      $this->info("Removed the default migrations.");
      exec("rm -rf app/Http/Controllers/Auth");
      $this->info("Removed the auth controllers.");
      exec("rm resources/lang/en/{passwords.php}");
      $this->info("Removed the lang files.");
      exec("rm app/Http/Middleware/{Authenticate.php,RedirectIfAuthenticated.php}");
      $this->info("Removed the auth middleware.");
      exec("sed -i '/auth/d; /guest/d' app/Http/Kernel.php");
      $this->info("Replaced kernel configurations.");
      $this->info('-------------------------------------------------------------');
      $this->info("[SSO] Completely removed Laravels default authentication.");
      $this->info('===================================================');
    }


    // ---------- REQUESTS
    public function checkToken($token) {
        try {
          $client = new Client();
          $request = $client->get($this->data['sso_url'] . "api/remote/application/checkRegistrationToken/" . $token);
          $result = json_decode($request->getBody()->getContents());
          return $result->status;
        } catch (Exception $e) {
          return $e;
        }
    }

    public function createApplication() {
        try {
          $client = new Client();
          $request = $client->post($this->data['sso_url'] . "api/remote/application/create", [
            'headers' => [
              'Authorization' => 'Bearer ' . $this->data['registration_token']
            ],
            'form_params' => [
              'name' => $this->data['application']['name'],
              'identifier' => $this->data['application']['identifier'],
              'is_hidden' => $this->data['application']['is_hidden']
            ]
          ]);
          $result = json_decode($request->getBody()->getContents());
          return $result;
        } catch (Exception $e) {
          return [
            "status" => "failure",
            "message" => $e
          ];
        }
    }

    public function getApplication() {
        try {
          $client = new Client();
          $request = $client->get($this->data['sso_url'] . "api/remote/application/get/" . $this->data['application']['id'], [
            'headers' => [
              'Authorization' => 'Bearer ' . $this->data['registration_token']
            ]
          ]);
          $result = json_decode($request->getBody()->getContents());
          return $result;
        } catch (Exception $e) {
          return [
            "status" => "failure",
            "message" => $e
          ];
        }
    }
}
