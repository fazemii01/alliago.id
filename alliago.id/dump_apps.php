<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('name', 'Alliago Administrator')->first();
$apps = App\Models\Application::where('user_id', $user->id)->get();
foreach($apps as $app) {
    echo "Ref: {$app->reference_number}, Status: {$app->status}\n";
    echo "Metadata: " . json_encode($app->metadata) . "\n\n";
}
