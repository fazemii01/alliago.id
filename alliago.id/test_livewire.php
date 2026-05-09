<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::find(4);
auth()->login($user);

try {
    $start = microtime(true);
    $html = app(\Livewire\LivewireManager::class)->mount('App\Filament\Resources\RoleResource\Pages\ListRoles');
    $elapsed = microtime(true) - $start;
    echo "Successfully rendered ListRoles. Length: " . strlen($html) . "\n";
    echo "Time elapsed: " . round($elapsed, 2) . " seconds\n";
} catch (\Throwable $e) {
    echo "ERROR: " . get_class($e) . "\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
