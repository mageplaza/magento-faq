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
class Mageplaza_Kb_Block_Category_List extends Mage_Core_Block_Template
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
        if ($this->_getDisplayMode() == 0) {
            $pager = $this->getLayout()->createBlock(
                'page/html_pager',
                'mageplaza_kb.categorys.html.pager'
            )
            ->setCollection($this->getCategories());
            $this->setChild('pager', $pager);
            $this->getCategories()->load();
        }
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * get the display mode
     *
     * @access protected
     * @return int
     * @author
     */
    protected function _getDisplayMode()
    {
        return Mage::getStoreConfigFlag('mageplaza_kb/category/tree');
    }

    /**
     * draw category
     *
     * @access public
     * @param Mageplaza_Kb_Model_Category
     * @param int $level
     * @return int
     * @author
     */
    public function drawCategory($category, $level = 0)
    {
        $html = '';
        $recursion = $this->getRecursion();
        if ($recursion !== '0' && $level >= $recursion) {
            return '';
        }
        $storeIds = Mage::getResourceSingleton(
            'mageplaza_kb/category'
        )
        ->lookupStoreIds($category->getId());
        $validStoreIds = array(0, Mage::app()->getStore()->getId());
        if (!array_intersect($storeIds, $validStoreIds)) {
            return '';
        }
        if (!$category->getStatus()) {
            return '';
        }
        $children = $category->getChildrenCategories();
        $activeChildren = array();
        if ($recursion == 0 || $level < $recursion-1) {
            foreach ($children as $child) {
                $childStoreIds = Mage::getResourceSingleton(
                    'mageplaza_kb/category'
                )
                ->lookupStoreIds($child->getId());
                $validStoreIds = array(0, Mage::app()->getStore()->getId());
                if (!array_intersect($childStoreIds, $validStoreIds)) {
                    continue;
                }
                if ($child->getStatus()) {
                    $activeChildren[] = $child;
                }
            }
        }
        $html .= '<div class="kb-category-item ">';
        $html .= '<h3><a href="'.$category->getCategoryUrl().'">'.$category->getName().'</a></h3>';
        if (count($activeChildren) > 0) {
            $html .= '<ul class="kb-category-child-listing" id="kb-category-child-listing-'.$category->getId() . '">';
            foreach ($children as $child) {
                $html .= $this->drawCategory($child, $level+1);
            }
            $html .= '</ul>';
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * get recursion
     *
     * @access public
     * @return int
     * @author
     */
    public function getRecursion()
    {
        if (!$this->hasData('recursion')) {
            $this->setData('recursion', Mage::getStoreConfig('mageplaza_kb/category/recursion'));
        }
        return $this->getData('recursion');
    }

    /**
     * get articles by category model
     * @param $category
     * @return Mageplaza_Kb_Model_Resource_Category_Article_Collection
     */
    public function getArticlesByCategory($category)
    {
        $collection = Mage::getSingleton('mageplaza_kb/category_article')->getArticlesCollection($category);
        return $collection;
    }

    /**
     * get limit faq at homepage, widget
     * @return int|mixed
     */
    public function getFaqLimit()
    {
        return ($limit = Mage::helper('mageplaza_kb/config')->getHomeConfig('limit_faq')) ? $limit : 5;
        
    }

}
