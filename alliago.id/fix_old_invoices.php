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
    if (!isset($metadata['invoice_amount'])) {
        $metadata['invoice_amount'] = $application->visaProduct->discount_price ?: $application->visaProduct->base_price;
        $application->metadata = $metadata;
        $application->save();
        $count++;
    }
}
echo "Fixed {$count} old paid applications by locking their invoice amount.\n";
