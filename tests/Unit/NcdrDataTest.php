<?php

namespace Tests\Unit;

use MOLiBot\DataSources\NcdrRss;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NcdrDataTest extends TestCase
{
    /**
     * Test for get content.
     *
     * @return void
     * @throws
     */
    public function testGetContent()
    {
        $dataSource = new NcdrRss();

        $data = $dataSource->getContent();

        $this->assertArraySubset([
            'entry' => []
        ], $data);
    }
}
