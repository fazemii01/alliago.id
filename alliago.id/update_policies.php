<?php

$policies = [
    'UserPolicy' => 'user',
    'ApplicationPolicy' => 'application',
    'VisaProductPolicy' => 'visaproduct',
    'InvoicePolicy' => 'invoice',
    'PaymentMethodPolicy' => 'paymentmethod',
    'SiteFaqPolicy' => 'sitefaq',
    'TestimonialPolicy' => 'testimonial',
    'CountryPolicy' => 'country',
];

$dir = __DIR__ . '/app/Policies/';

foreach ($policies as $file => $modelName) {
    $path = $dir . $file . '.php';
    if (!file_exists($path)) {
        echo "Missing $path\n";
        continue;
    }

    $content = file_get_contents($path);

    // Replace return false; with return $user->hasPermissionTo('...');
    
    $replacements = [
        "public function viewAny(User \$user): bool\n    {\n        return false;" => "public function viewAny(User \$user): bool\n    {\n        return \$user->hasPermissionTo('view_any_{$modelName}');",
        
        "public function view(User \$user, \$model): bool\n    {\n        return false;" => "public function view(User \$user, \$model): bool\n    {\n        return \$user->hasPermissionTo('view_{$modelName}');",
        
        "public function create(User \$user): bool\n    {\n        return false;" => "public function create(User \$user): bool\n    {\n        return \$user->hasPermissionTo('create_{$modelName}');",
        
        "public function update(User \$user, \$model): bool\n    {\n        return false;" => "public function update(User \$user, \$model): bool\n    {\n        return \$user->hasPermissionTo('update_{$modelName}');",
        
        "public function delete(User \$user, \$model): bool\n    {\n        return false;" => "public function delete(User \$user, \$model): bool\n    {\n        return \$user->hasPermissionTo('delete_{$modelName}');",
        
        "public function restore(User \$user, \$model): bool\n    {\n        return false;" => "public function restore(User \$user, \$model): bool\n    {\n        return \$user->hasPermissionTo('delete_{$modelName}');",
        
        "public function forceDelete(User \$user, \$model): bool\n    {\n        return false;" => "public function forceDelete(User \$user, \$model): bool\n    {\n        return \$user->hasPermissionTo('delete_{$modelName}');",
    ];

    foreach ($replacements as $search => $replace) {
        // We use regex because the model typehint varies (e.g. `User $model`, `Application $model`)
        $pattern = preg_quote("{\n        return false;", '/');
        $searchPattern = '/public function (viewAny|view|create|update|delete|restore|forceDelete)\(User \$user(.*?)\): bool\n    \{\n        return false;/';
        
        $content = preg_replace_callback($searchPattern, function($matches) use ($modelName) {
            $method = $matches[1];
            $perm = '';
            switch ($method) {
                case 'viewAny': $perm = 'view_any_' . $modelName; break;
                case 'view': $perm = 'view_' . $modelName; break;
                case 'create': $perm = 'create_' . $modelName; break;
                case 'update': $perm = 'update_' . $modelName; break;
                case 'delete':
                case 'restore':
                case 'forceDelete': $perm = 'delete_' . $modelName; break;
            }
            return "public function $method(User \$user{$matches[2]}): bool\n    {\n        return \$user->hasPermissionTo('{$perm}');";
        }, $content);
    }
    
    file_put_contents($path, $content);
    echo "Updated $file\n";
}
