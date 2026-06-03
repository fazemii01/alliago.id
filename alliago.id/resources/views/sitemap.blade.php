<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>https://www.alliago.id</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc>https://www.alliago.id/visa</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>https://www.alliago.id/flights</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>https://www.alliago.id/detail</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    <url>
        <loc>https://www.alliago.id/proses</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    <url>
        <loc>https://www.alliago.id/faq</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    @foreach ($visaProducts as $product)
    <url>
        <loc>https://www.alliago.id/visa/{{ $product->slug }}</loc>
        <lastmod>{{ $product->updated_at->format('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

</urlset>
