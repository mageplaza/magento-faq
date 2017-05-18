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
 * Article product model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Article_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author
     */
    protected function _construct()
    {
        $this->_init('mageplaza_kb/article_product');
    }

    /**
     * Save data for article-product relation
     * @access public
     * @param  Mageplaza_Kb_Model_Article $article
     * @return Mageplaza_Kb_Model_Article_Product
     * @author
     */
    public function saveArticleRelation($article)
    {
        $data = $article->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveArticleRelation($article, $data);
        }
        return $this;
    }

    /**
     * get products for article
     *
     * @access public
     * @param Mageplaza_Kb_Model_Article $article
     * @return Mageplaza_Kb_Model_Resource_Article_Product_Collection
     * @author
     */
    public function getProductCollection($article)
    {
        $collection = Mage::getResourceModel('mageplaza_kb/article_product_collection')
            ->addArticleFilter($article);
        return $collection;
    }
}
