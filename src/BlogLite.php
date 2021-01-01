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
class BlogLite{
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
	public $contentDir = "PATH/TO/.md";

	/**
	 * @var string
	 */
	private $articleId = null;

	/**
	 * @param array $articles (optional)
	 * @param string $contentDir (optional)
	 */
	public function __construct($articles = [], $contentDir = ""){
		$this->articles = $articles;
		$this->contentDir = $contentDir;
	}

	/**
	 * @return integer
	 */
	public function articleCount(){
		return count($this->articles);
	}

	/**
	 * @param integer $parpage (optional)
	 * @return integer
	 */
	public function maxPage($parpage = 10){
		return intval(ceil(count($this->articles) / $parpage));
	}

	/**
	 * @param integer $current (optional)
	 * @param integer $parpage (optional)
	 * @param integer $width (optional)
	 * @return array
	 */
	public function navPages($current = 1, $parpage = 10, $width = 10){
		$min = 1;
		$max = $this->maxPage($parpage);
		if( $current < $min || $current > $max ) return [];
		$prepend = [];
		$append = [];
		for($i = $width; $i > 0; $i--){
			$page = $current - $i;
			if( $page >= $min ) $prepend[] = $page;
		}
		for($i = 1; $i <= $width; $i++){
			$page = $current + $i;
			if( $page <= $max ) $append[] = $page;
		}
		while( true ){
			if( count($prepend) + count($append) + 1 <= $width ) break;
			if( count($prepend) >= count($append) ){
				array_shift($prepend);
			}else{
				array_pop($append);
			}
		}
		return array_merge($prepend, [$current], $append);
	}

	/**
	 * @param integer $page (optional)
	 * @param integer $parpage (optional)
	 * @param boolean $onlyIds (optional)
	 * @return array
	 */
	public function articleList($page = 1, $parpage = 10, $onlyIds = false){
		$articles = array_slice($this->articles, ($page - 1) * $parpage, $parpage);
		return ( $onlyIds ) ? array_keys($articles) : $articles;
	}

	/**
	 * @param string $id
	 * @param string $info (optional)
	 * @return mixed
	 */
	public function article($id, $info = "*"){
		if( empty($this->articles[$id]) ) return false;
		if( $info === "*" ) return $this->articles[$id];
		return ( isset($this->articles[$id][$info]) ) ? $this->articles[$id][$info] : "";
	}

	/**
	 * @param string $id
	 */
	public function setId($id){
		$this->articleId = $id;
	}

	/**
	 * @param string $info (optional)
	 * @return mixed
	 */
	public function info($info = "*"){
		return $this->article($this->articleId, $info);
	}

	/**
	 * @param string $id
	 * @return string
	 */
	public function content($id){
		if( empty($this->articles[$id]) ) return false;
		$file = rtrim($this->contentDir, "/")."/{$id}.md";
		if( !file_exists($file) ) return false;
		return file_get_contents($file);
	}
}
