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
 * Product helper
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Helper_Product extends Mageplaza_Kb_Helper_Data
{

    /**
     * get the selected articles for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author
     */
    public function getSelectedArticles(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedArticles()) {
            $articles = array();
            foreach ($this->getSelectedArticlesCollection($product) as $article) {
                $articles[] = $article;
            }
            $product->setSelectedArticles($articles);
        }
        return $product->getData('selected_articles');
    }

    /**
     * get article collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return Mageplaza_Kb_Model_Resource_Article_Collection
     * @author
     */
    public function getSelectedArticlesCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('mageplaza_kb/article_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
