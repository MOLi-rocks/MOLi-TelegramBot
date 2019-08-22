<?php

namespace Tests\Unit;

use MOLiBot\DataSources\MoliBlogArticle;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MoliBlogDataTest extends TestCase
{
    /**
     * Test for get content.
     *
     * @return void
     * @throws
     */
    public function testGetContent()
    {
        $dataSource = new MoliBlogArticle();

        $data = $dataSource->getContent();

        $this->assertArraySubset([
            'posts' => []
        ], $data);
    }

    /**
     * Test for get content with custom page.
     *
     * @return void
     * @throws
     */
    public function testGetPageContent()
    {
        $dataSource = new MoliBlogArticle();

        $page = rand(2, 999);

        $dataSource->setPage($page);

        $data = $dataSource->getContent();

        $this->assertArraySubset([
            'posts' => []
        ], $data);

        $this->assertTrue($data['meta']['pagination']['page'] === $page);
    }
}
