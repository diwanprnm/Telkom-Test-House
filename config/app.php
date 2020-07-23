<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),
    // 'url_api_bsp' => env('url_api_bsp', 'http://otr.ptbsp.com/OTR_Privy/api/'),
    'url_api_bsp' => env('url_api_bsp', 'http://revitalisasiotr-otr.apps.playcourt.id/api/'),
    
    /* ENV DEV TPN */
/*
    'url_api_tpn' => env('url_api_tpn', 'http://telkompartner-tpncms-dev.vsan-apps.playcourt.id/api/'),    
    'gateway_tpn' => env('gateway_tpn', 'apiKey 4ZU03BLNm1ebXSlQa4ou3y:6MHfjHpbOVv3FKTFAf8jIv'),
    'product_id_tth' => env('product_id_tth', '5e5377a0c5f8d40010fc4594'),
    'gateway_tpn_2' => env('gateway_tpn_2', 'apiKey 6PVO8UeiCOgdc3tZgxfXNx:1ujXtoe5tcTCJnRNCb6MmC'),
    'product_id_tth_2' => env('product_id_tth_2', '5e9817b8a621010011844698'),
*/
    /* END ENV DEV TPN */

    /* ENV PROD TPN */

    'url_api_tpn' => env('url_api_tpn', 'https://partner.telkom.co.id/api/'),
    'gateway_tpn' => env('gateway_tpn', 'apiKey 1w4kG2nmIYPiHfjxIEeSsh:3fhd4B65RoTRp2HCUhJbsJ'),
    'product_id_tth' => env('product_id_tth', '5e5377a0c5f8d40010fc4594'),
    'gateway_tpn_2' => env('gateway_tpn_2', 'apiKey 0ODyxA3NcofUgCJuoKBp7J:24ZhRwJddUuZvBjluUZRhR'),
    'product_id_tth_2' => env('product_id_tth_2', '5ea2ce4992a56f001066d48d'),

    /* END ENV PROD TPN */
    'IS_ENABLED_NOTIFICATION' => env('IS_ENABLED_NOTIFICATION', true),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', true),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    // 'timezone' => 'UTC',
    'timezone' => 'Asia/Jakarta',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',
	
	/*
	|--------------------------------------------------------------------------
	| Application available Languages
	|--------------------------------------------------------------------------
	|
	| A list of available languages defined from their ISO Language Codes codes, see more: http://www.w3schools.com/tags/ref_language_codes.asp.
	| If the code isn't in the list, HomeController@language is set from fallback_locale value.
	! To set new language, pelase create a folder in /resources/lang/{ISO-CODE}, create a flag image in public/img/{ISO-CODE}-flang.png
	! and at least, add the ISO code in languages array.
	*/

	'languages' => ['en', 'in'],	
	
    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'single'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        Collective\Html\HtmlServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
		
		// Anouar\Fpdf\FpdfServiceProvider::class,
        Unisharp\Ckeditor\ServiceProvider::class,
        // Unisharp\Laravelfilemanager\LaravelFilemanagerServiceProvider::class,
		Maatwebsite\Excel\ExcelServiceProvider::class,
		Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider::class,
		// PragmaRX\Tracker\Vendor\Laravel\ServiceProvider::class,
		'Witty\LaravelDbBackup\DBBackupServiceProvider',
        Gloudemans\Shoppingcart\ShoppingcartServiceProvider::class,
          Ixudra\Curl\CurlServiceProvider::class,
          
        App\Providers\MinIOStorageServiceProvider::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis'    => 'Illuminate\Support\Facades\Redis',
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Curl'          => Ixudra\Curl\Facades\Curl::class,
        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,
        'Image' => Intervention\Image\Facades\Image::class,
		// 'FPDF' => setasign\Fpdf\Facades\Fpdf::class,
		'Excel'     => Maatwebsite\Excel\Facades\Excel::class,
		// 'Tracker' => PragmaRX\Tracker\Vendor\Laravel\Facade::class,
        'Cart' => Gloudemans\Shoppingcart\Facades\Cart::class,
    ],

    'merchant_id' => env('merchant_id', 'SMART424'),
    'merchant_secret' => env('merchant_secret', 'smart2016'),
    'main_api_server' => env('main_api_server', 'https://api.mainapi.net/finpay02111/1.0.0'),
    'auth_key' => env('auth_key', '8a2677ce150bb2b9b2b7c801479618d2')

];
