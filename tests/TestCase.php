<?php

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function disableExceptionHandling()
    {
        app()->instance(ExceptionHandler::class, new PassThroughHandler);
    }
}

class PassThroughHandler extends Handler
{
    public function __construct() {}

    public function report(Exception $e)
    {
        // no-op
    }

    public function render($request, Exception $e)
    {
        throw $e;
    }
}