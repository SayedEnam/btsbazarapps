<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_homepage_loads_for_guests(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Monthly Bazar');
    }
}
