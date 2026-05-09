<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simulate request
$request = Illuminate\Http\Request::create('/admin/roles', 'GET');
$app->instance('request', $request);

try {
    // Force auth
    $app->make(\Illuminate\Contracts\Auth\Factory::class)->guard()->loginUsingId(4);

    // Attempt to render the component or route
    $response = $kernel->handle($request);
    echo "Response status: " . $response->getStatusCode() . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
