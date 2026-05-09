<?php

// Convert JSON translation files to PHP array files for Laravel

$locales = ['id', 'en'];
$files   = ['client', 'visa'];

foreach ($locales as $locale) {
    foreach ($files as $file) {
        $jsonPath = __DIR__ . "/resources/lang/{$locale}/{$file}.json";
        $phpPath  = __DIR__ . "/lang/{$locale}/{$file}.php";

        if (!file_exists($jsonPath)) {
            echo "SKIP: $jsonPath not found\n";
            continue;
        }

        $data = json_decode(file_get_contents($jsonPath), true);
        if (!$data) {
            echo "ERROR: Could not decode $jsonPath\n";
            continue;
        }

        $out = "<?php\n\nreturn [\n";
        foreach ($data as $k => $v) {
            $escaped = str_replace("'", "\\'", $v);
            $out .= "    '{$k}' => '{$escaped}',\n";
        }
        $out .= "];\n";

        file_put_contents($phpPath, $out);
        echo "OK: lang/{$locale}/{$file}.php\n";
    }
}

echo "Done.\n";
