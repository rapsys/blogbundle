<?php

namespace Rapsys\BlogBundle\Entity;

/**
 * ArticleKeyword
 */
class ArticleKeyword
{
    /**
     * @var integer
     */
    private $article_id;

    /**
     * @var integer
     */
    private $keyword_id;

    /**
     * Constructor
     */
    public function __construct($article_id, $keyword_id) {
	    $this->article_id = $article_id;
	    $this->keyword_id = $keyword_id;
    }
}
