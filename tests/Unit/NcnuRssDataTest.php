<?php

namespace Tests\Unit;

use MOLiBot\DataSources\NcnuRss;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NcnuRssDataTest extends TestCase
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

        $this->assertIsArray($data['info_ncnu']['item'], 'channel.item must be an array');
    }
}
