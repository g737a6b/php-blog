<?php

namespace BlogLite;

/**
 * BlogLite
 *
 * @package BlogLite
 * @author Hiroyuki Suzuki
 * @copyright Copyright (c) 2021 Hiroyuki Suzuki mofg.net
 * @license http://opensource.org/licenses/MIT The MIT License
 */
class BlogLite
{
    /**
     * @var array
     */
    public $articles = [
        "sample-article" => [
            "title" => "Sample article",
            "date" => "2016-12-31",
            "author" => "Hiroyuki Suzuki",
            "categories" => ["Science", "Technology"]
        ]
    ];

    /**
     * @var string
     */
    public $contentDir = "PATH/TO/.md/FILES";

    /**
     * @var string
     */
    private $articleId = null;

    /**
     * @param array $articles (optional)
     * @param string $contentDir (optional)
     */
    public function __construct(array $articles = [], string $contentDir = "")
    {
        $this->articles = $articles;
        $this->contentDir = $contentDir;
    }

    /**
     * @return int
     */
    public function articleCount(): int
    {
        return count($this->articles);
    }

    /**
     * @param int $parpage (optional)
     * @return int
     */
    public function maxPage(int $parpage = 10): int
    {
        return intval(ceil(count($this->articles) / $parpage));
    }

    /**
     * @param int $current (optional)
     * @param int $parpage (optional)
     * @param int $width (optional)
     * @return array
     */
    public function navPages(int $current = 1, int $parpage = 10, int $width = 10): array
    {
        $min = 1;
        $max = $this->maxPage($parpage);
        if($current < $min || $current > $max) {
            return [];
        }
        $prepend = [];
        $append = [];
        for($i = $width; $i > 0; $i--) {
            $page = $current - $i;
            if($page >= $min) {
                $prepend[] = $page;
            }
        }
        for($i = 1; $i <= $width; $i++) {
            $page = $current + $i;
            if($page <= $max) {
                $append[] = $page;
            }
        }
        if($width < 1) {
            return [$current];
        }
        while(true) {
            if(count($prepend) + count($append) + 1 <= $width) {
                break;
            }
            if(count($prepend) >= count($append)) {
                array_shift($prepend);
            } else {
                array_pop($append);
            }
        }
        return array_merge($prepend, [$current], $append);
    }

    /**
     * @param int $page (optional)
     * @param int $parpage (optional)
     * @param bool $onlyIds (optional)
     * @return array
     */
    public function articleList(int $page = 1, int $parpage = 10, bool $onlyIds = false): array
    {
        $articles = array_slice($this->articles, ($page - 1) * $parpage, $parpage);
        return $onlyIds ? array_keys($articles) : $articles;
    }

    /**
     * @param string $id
     * @param string $info (optional)
     * @return mixed
     */
    public function article(string $id, string $info = "*")
    {
        if(empty($this->articles[$id])) {
            return null;
        }
        if($info === "*") {
            return $this->articles[$id];
        }
        return (isset($this->articles[$id][$info])) ? $this->articles[$id][$info] : null;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->articleId = $id;
    }

    /**
     * @param string $info (optional)
     * @return mixed
     */
    public function info(string $info = "*")
    {
        return $this->article($this->articleId, $info);
    }

    /**
     * @param string $id
     * @return string|null
     */
    public function content(string $id): ?string
    {
        if(empty($this->articles[$id])) {
            return null;
        }
        $file = rtrim($this->contentDir, "/")."/{$id}.md";
        if(!file_exists($file)) {
            return null;
        }
        return file_get_contents($file);
    }
}
