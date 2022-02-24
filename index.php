<?php

if (!file_exists(__DIR__ . '/core/config.php')) {
    $sampleConfig = require(__DIR__ . '/core/config-sample.php');
    if (file_exists(__DIR__ . '/core/.env')) {
        $env = fopen(__DIR__ . '/core/.env', "r");
        while (!feof($env)) {
            $line = fgets($env);
            $envLine = explode('=', $line);
            if (count($envLine) > 1) {
                $sampleConfig[$envLine[0]] = '"' . trim(preg_replace('/\s\s+/', ' ', $envLine[1])) . '",';
            }
        }
    } else {
        foreach ($sampleConfig as $key => $value) {
            $sampleConfig[$key] = '"' . $value . '",';
        }
        $sampleConfig['APP_URL'] = '"localhost",';
        $sampleConfig['DB_HOST'] = '"localhost",';
        $sampleConfig['DB_DATABASE'] = '"",';
        $sampleConfig['DB_USERNAME'] = '"",';
        $sampleConfig['DB_PASSWORD'] = '"",';
    }

    $config = print_r($sampleConfig, true);
    $config = str_replace("[", '"', $config);
    $config = str_replace("]", '"', $config);
    file_put_contents(__DIR__ . '/core/config.php', '<?php return ' . $config . ';');

    if (file_exists(__DIR__ . '/core/.env')) {
        unlink(__DIR__ . '/core/.env');
    }
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__ . '/core/bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__ . '/core/bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
