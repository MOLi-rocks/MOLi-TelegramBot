<?php

namespace Tests\Unit;

use MOLiBot\DataSources\MoliKktix;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KktixDataTest extends TestCase
{
    /**
     * Test for get content.
     *
     * @return void
     * @throws
     */
    public function testMoliGetContent()
    {
        $dataSource = new MoliKktix();

        $data = $dataSource->getContent();

        $this->assertArraySubset([
            'entry' => []
        ], $data);
    }
}
