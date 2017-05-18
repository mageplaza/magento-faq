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
 * Article list on product page block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Catalog_Product_List_Article extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * get the list of articles
     *
     * @access protected
     * @return Mageplaza_Kb_Model_Resource_Article_Collection
     * @author
     */
    public function getArticleCollection()
    {
        if (!$this->hasData('article_collection')) {
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('mageplaza_kb/article_collection')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->addAttributeToSelect('name', 1)
                ->addAttributeToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('article_collection', $collection);
        }
        return $this->getData('article_collection');
    }
}
