<?php

namespace Tests\Unit;

use MOLiBot\DataSources\NcnuRss;
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
        $dataSource = new NcnuRss();

        $data = $dataSource->getContent();

        $this->assertArraySubset([
            'channel' => [
                'item' => []
            ]
        ], $data);
    }
}
