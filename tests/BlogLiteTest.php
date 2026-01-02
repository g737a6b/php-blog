<?php

require(__DIR__."/../autoload.php");

use PHPUnit\Framework\TestCase;
use BlogLite\BlogLite;
use PHPUnit\Framework\Attributes\DataProvider;

class BlogLiteTest extends TestCase
{
    protected $BlogLite;

    protected function setUp(): void
    {
        $this->BlogLite = new BlogLite([
            "sample-article-1" => [
                "title" => "Sample article 1"
            ],
            "sample-article-2" => [
                "title" => "Sample article 2",
                "foo" => "val"
            ],
            "sample-article-3" => [
                "title" => "Sample article 3"
            ],
            "sample-article-4" => [
                "title" => "Sample article 4"
            ],
            "sample-article-5" => [
                "title" => "Sample article 5"
            ],
            "sample-article-6" => [
                "title" => "Sample article 6"
            ],
            "sample-article-7" => [
                "title" => "Sample article 7"
            ],
            "sample-article-8" => [
                "title" => "Sample article 8"
            ],
            "sample-article-9" => [
                "title" => "Sample article 9"
            ],
            "sample-article-10" => [
                "title" => "Sample article 10"
            ],
            "sample-article-11" => [
                "title" => "Sample article 11"
            ],
            "sample-article-12" => [
                "title" => "Sample article 12"
            ]
        ]);
    }

    public function testArticleCount()
    {
        $this->assertSame(12, $this->BlogLite->articleCount());
    }

    public function testMaxPage()
    {
        // 12 articles / 4 per page = 3 pages
        $this->assertSame(3, $this->BlogLite->maxPage(4));
        // 12 articles / 5 per page = 2.4 → ceil = 3 pages
        $this->assertSame(3, $this->BlogLite->maxPage(5));
        // 12 articles / 20 per page = 0.6 → ceil = 1 page
        $this->assertSame(1, $this->BlogLite->maxPage(20));
    }

    public static function navPagesProvider(): array
    {
        // Setup: 12 articles, 2 per page = 6 total pages
        // Parameters: [$current, $parpage, $width, $expected]
        return [
            // Width=10 is larger than total pages (6), so all pages are always shown
            'first page with large width' => [1, 2, 10, [1,2,3,4,5,6]],
            'middle page with large width' => [3, 2, 10, [1,2,3,4,5,6]],
            'last page with large width' => [6, 2, 10, [1,2,3,4,5,6]],

            // Invalid page numbers return empty array
            'page below minimum' => [0, 2, 10, []],
            'page above maximum' => [7, 2, 10, []],

            // Width=4 limits the navigation to 4 pages centered around current page
            'first page with small width' => [1, 2, 4, [1,2,3,4]],
            'middle page with small width' => [3, 2, 4, [2,3,4,5]],
            'last page with small width' => [6, 2, 4, [3,4,5,6]],

            // Edge cases with very small width values
            'width=2 shows current and one neighbor' => [1, 10, 2, [1,2]],
            'width=1 shows only current page' => [1, 10, 1, [1]],
            'width=0 shows only current page' => [1, 10, 0, [1]],
        ];
    }

    #[DataProvider('navPagesProvider')]
    public function testNavPages($current, $parpage, $width, $expected)
    {
        $this->assertSame($expected, $this->BlogLite->navPages($current, $parpage, $width));
    }

    public function testArticleList()
    {
        // Page 1, 2 articles per page, return IDs only
        $this->assertSame(["sample-article-1", "sample-article-2"], $this->BlogLite->articleList(1, 2, true));
        // Page 3, 1 article per page, return IDs only
        $this->assertSame(["sample-article-3"], $this->BlogLite->articleList(3, 1, true));
    }

    public function testArticleInfo()
    {
        $this->BlogLite->setId("sample-article-3");
        $this->assertSame("Sample article 3", $this->BlogLite->info("title"));
        $this->assertNull($this->BlogLite->info("undefined"));
        $this->BlogLite->setId("sample-article-6");
        $this->assertSame("Sample article 6", $this->BlogLite->info("title"));
        $this->assertSame("Sample article 2", $this->BlogLite->article("sample-article-2", "title"));
        $this->assertSame([
            "title" => "Sample article 2",
            "foo" => "val"
        ], $this->BlogLite->article("sample-article-2"));
        $this->assertSame("Sample article 6", $this->BlogLite->info("title"));
    }

    public function testContent()
    {
        $data = [
            "sample-id" => [
                "foo" => "val"
            ]
        ];
        $dir = __DIR__ . "/articles";
        $BlogLite = new BlogLite($data, $dir);

        $expected = file_get_contents($dir . "/sample-id.md");
        $this->assertSame($expected, $BlogLite->content("sample-id"));

        $this->assertNull($BlogLite->content("undefined-id"));
    }

    public function testArticleListWithFullData()
    {
        // Page 1, 2 articles per page, return full data (not just IDs)
        $result = $this->BlogLite->articleList(1, 2, false);
        $this->assertSame([
            "sample-article-1" => ["title" => "Sample article 1"],
            "sample-article-2" => ["title" => "Sample article 2", "foo" => "val"]
        ], $result);
    }

    public function testArticleListOutOfRange()
    {
        // Page 100 doesn't exist (only 2 pages with 10 articles per page from 12 total)
        $this->assertSame([], $this->BlogLite->articleList(100, 10, true));
        $this->assertSame([], $this->BlogLite->articleList(100, 10, false));
    }

    public function testArticleWithNonExistentId()
    {
        $this->assertNull($this->BlogLite->article("non-existent-id"));
        $this->assertNull($this->BlogLite->article("non-existent-id", "title"));
    }

    public function testInfoWithNonExistentId()
    {
        $this->BlogLite->setId("non-existent-id");
        $this->assertNull($this->BlogLite->info());
        $this->assertNull($this->BlogLite->info("title"));
    }

    public function testInfoWithAsterisk()
    {
        $this->BlogLite->setId("sample-article-2");
        $expected = [
            "title" => "Sample article 2",
            "foo" => "val"
        ];
        $this->assertSame($expected, $this->BlogLite->info("*"));
        $this->assertSame($expected, $this->BlogLite->info());
    }

    public function testEmptyArticles()
    {
        $BlogLite = new BlogLite([]);
        $this->assertSame(0, $BlogLite->articleCount());
        $this->assertSame(0, $BlogLite->maxPage());
        $this->assertSame(0, $BlogLite->maxPage(10));
        $this->assertSame([], $BlogLite->navPages(1, 10, 10));
        $this->assertSame([], $BlogLite->articleList(1, 10, true));
        $this->assertSame([], $BlogLite->articleList(1, 10, false));
    }

    public function testMaxPageWithSingleArticle()
    {
        $BlogLite = new BlogLite(["id1" => ["title" => "Article 1"]]);
        // 1 article / 1 per page = 1 page
        $this->assertSame(1, $BlogLite->maxPage(1));
        // 1 article / 10 per page = 0.1 → ceil = 1 page
        $this->assertSame(1, $BlogLite->maxPage(10));
    }

    public function testContentWithNonExistentFile()
    {
        $data = [
            "id-without-file" => [
                "title" => "Article without file"
            ]
        ];
        $dir = __DIR__ . "/articles";
        $BlogLite = new BlogLite($data, $dir);

        $this->assertNull($BlogLite->content("id-without-file"));
    }
}
