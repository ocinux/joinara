<?php

namespace Tests\Feature;

use Tests\TestCase;

class PwaAssetsTest extends TestCase
{
    public function test_manifest_is_served(): void
    {
        $response = $this->get('/manifest.webmanifest');
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/manifest+json');

        $manifest = $response->json();
        $this->assertEquals('Joinara', $manifest['name']);
        $this->assertEquals('/dashboard', $manifest['start_url']);
    }

    public function test_service_worker_only_caches_static_assets(): void
    {
        $response = $this->get('/sw.js');
        $response->assertOk();

        $sw = $response->getContent();
        $this->assertStringContainsString("'/manifest.webmanifest'", $sw);
        $this->assertStringContainsString("'/css/app.css'", $sw);

        // SW harus tidak menyebut halaman privat di daftar cache.
        // Search the whole file to prove no /login or /dashboard appear inside precache list.
        $this->assertStringNotContainsString('"/login"', $sw);
        $this->assertStringNotContainsString('"/dashboard"', $sw);
    }

    public function test_css_asset_is_served(): void
    {
        $this->get('/css/app.css')->assertOk();
    }

    public function test_icons_are_served_as_png(): void
    {
        foreach ([192, 512] as $size) {
            $response = $this->get("/icons/icon-{$size}.png");
            $response->assertOk();
            $this->assertStringContainsString('image/png', $response->headers->get('Content-Type'));
        }
    }
}
