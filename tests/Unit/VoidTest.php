<?php

namespace Tests\Unit;

use Dividebuy\Payment\Contracts\Card;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoidTest extends TestCase
{
    protected $card;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

}
