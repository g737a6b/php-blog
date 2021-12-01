<?php
require(__DIR__."/../autoload.php");

use PHPUnit\Framework\TestCase;
use BlogLite\BlogLite;

class BlogLiteTest extends TestCase{
	protected $BlogLite;

	protected function setUp() : void{
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
		], "");
	}

	public function testArticleCount(){
		$this->assertSame(12, $this->BlogLite->articleCount());
	}

	public function testMaxPage(){
		$this->assertSame(3, $this->BlogLite->maxPage(4));
		$this->assertSame(3, $this->BlogLite->maxPage(5));
		$this->assertSame(1, $this->BlogLite->maxPage(20));
	}

	public function testNavPages(){
		$this->assertSame([1,2,3,4,5,6], $this->BlogLite->navPages(1, 2, 10));
		$this->assertSame([1,2,3,4,5,6], $this->BlogLite->navPages(3, 2, 10));
		$this->assertSame([1,2,3,4,5,6], $this->BlogLite->navPages(6, 2, 10));
		$this->assertSame([], $this->BlogLite->navPages(0, 2, 10));
		$this->assertSame([], $this->BlogLite->navPages(7, 2, 10));
		$this->assertSame([1,2,3,4], $this->BlogLite->navPages(1, 2, 4));
		$this->assertSame([2,3,4,5], $this->BlogLite->navPages(3, 2, 4));
		$this->assertSame([3,4,5,6], $this->BlogLite->navPages(6, 2, 4));
	}

	public function testArticleList(){
		$this->assertSame(["sample-article-1", "sample-article-2"], $this->BlogLite->articleList(1, 2, true));
		$this->assertSame(["sample-article-3"], $this->BlogLite->articleList(3, 1, true));
	}

	public function testArticleInfo(){
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
}
