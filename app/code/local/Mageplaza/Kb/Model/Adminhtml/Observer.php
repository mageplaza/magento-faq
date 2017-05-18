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
 * Adminhtml observer
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author
     */
    protected function _canAddTab($product)
    {
        if ($product->getId()) {
            return true;
        }
        if (!$product->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    /**
     * add the article tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Mageplaza_Kb_Model_Adminhtml_Observer
     * @author
     */
    public function addProductArticleBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'articles',
                array(
                    'label' => Mage::helper('mageplaza_kb')->__('Knowledge Base'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/kb_article_catalog_product/articles',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save article - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Mageplaza_Kb_Model_Adminhtml_Observer
     * @author
     */
    public function saveProductArticleData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('articles', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $articleProduct = Mage::getResourceSingleton('mageplaza_kb/article_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }}
