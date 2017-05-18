<?php
/**
 * Mageplaza_Kb extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Mageplaza
 * @package        Mageplaza_Kb
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Article Categories list block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Article_Category_List extends Mageplaza_Kb_Block_Category_List
{
    /**
     * initialize
     *
     * @access public
     * @return void
     * @author
     */
    public function __construct()
    {
        parent::__construct();
        $article = $this->getArticle();
         if ($article) {
             $this->getCategorys()->addArticleFilter($article->getId());
             $this->getCategorys()->unshiftOrder('related_article.position', 'ASC');
         }
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Article_Category_List
     * @author
     */
    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * get the current article
     *
     * @access public
     * @return Mageplaza_Kb_Model_Article
     * @author
     */
    public function getArticle()
    {
        return Mage::registry('current_article');
    }
}
