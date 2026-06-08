<?php

// archivo que revisa que el sistema funcione
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

// esta clase revisa pruebas de ejemplo
class ExampleTest extends TestCase
{
    // comprueba una prueba basica

    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
