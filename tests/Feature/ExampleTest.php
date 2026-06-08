<?php

// archivo que revisa que el sistema funcione
namespace Tests\Feature;
use Tests\TestCase;

// esta clase revisa pruebas de ejemplo
class ExampleTest extends TestCase
{
    // comprueba que el inicio responda

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
