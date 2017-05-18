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
 * Category list block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Sidebar extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author
     */
    public function _construct()
    {
        parent::_construct();
        $categories = Mage::getResourceModel('mageplaza_kb/category_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        ;
        $categories->getSelect()->order('main_table.position');
        $this->setCategories($categories);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Category_List
     * @author
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getCategories()->addFieldToFilter('level', 1);
        return $this;
    }

    public function getArticlesByCategory($category)
    {
        $collection = Mage::getSingleton('mageplaza_kb/category_article')->getArticlesCollection($category);
        return $collection;
    }
}
