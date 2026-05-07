<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$applications = App\Models\Application::whereIn('status', [
    'draft', 'in_review', 'approved', 'completed', 'needs_revision'
])->get();

$count = 0;
foreach($applications as $application) {
    $metadata = $application->metadata ?? [];
    
    // Check if we have price_breakdown
    if (isset($metadata['price_breakdown']['total'])) {
        $metadata['invoice_amount'] = $metadata['price_breakdown']['total'];
        $application->metadata = $metadata;
        $application->save();
        $count++;
        echo "Fixed {$application->reference_number} with amount {$metadata['invoice_amount']}\n";
    }
}
echo "Fixed {$count} old paid applications with their real total amount.\n";
