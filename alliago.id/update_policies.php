<?php

$policies = [
    'UserPolicy' => 'users',
    'ApplicationPolicy' => 'applications',
    'VisaProductPolicy' => 'visa_products',
    'InvoicePolicy' => 'invoices',
    'PaymentMethodPolicy' => 'payment_methods',
    'SiteFaqPolicy' => 'site_faqs',
    'TestimonialPolicy' => 'testimonials',
    'CountryPolicy' => 'countries',
];

$dir = __DIR__ . '/app/Policies/';

foreach ($policies as $file => $modelName) {
    $path = $dir . $file . '.php';
    if (!file_exists($path)) {
        echo "Missing $path\n";
        continue;
    }

    $content = file_get_contents($path);

    $searchPattern = '/public function (viewAny|view|create|update|delete|restore|forceDelete)\(User \$user(.*?)\): bool\n    \{\n        return \$user->hasPermissionTo\(\'(.*?)\'\);/';
    
    $content = preg_replace_callback($searchPattern, function($matches) use ($modelName) {
        $method = $matches[1];
        $perm = '';
        switch ($method) {
            case 'viewAny': $perm = $modelName . '.view_any'; break;
            case 'view': $perm = $modelName . '.view'; break;
            case 'create': $perm = $modelName . '.create'; break;
            case 'update': $perm = $modelName . '.update'; break;
            case 'delete':
            case 'restore':
            case 'forceDelete': $perm = $modelName . '.delete'; break;
        }
        return "public function $method(User \$user{$matches[2]}): bool\n    {\n        if (\$user->hasRole('admin')) return true;\n        return \$user->hasPermissionTo('{$perm}');";
    }, $content);
    
    file_put_contents($path, $content);
    echo "Updated $file\n";
}
