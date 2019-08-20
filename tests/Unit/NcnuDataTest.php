<?php

namespace Tests\Unit;

use MOLiBot\DataSources\Ncnu;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NcnuDataTest extends TestCase
{
    /**
     * Test for get content.
     *
     * @return void
     * @throws
     */
    public function testGetContent()
    {
        $dataSource = new Ncnu();

        $data = $dataSource->getContent();

        $this->assertArraySubset([
            'channel' => [
                'item' => []
            ]
        ], $data);
    }
}
