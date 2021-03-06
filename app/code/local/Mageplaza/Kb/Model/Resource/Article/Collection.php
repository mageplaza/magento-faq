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
 * Article collection resource model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Resource_Article_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('mageplaza_kb/article');
    }

    /**
     * get articles as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='name', $additional=array())
    {
        $this->addAttributeToSelect('name');
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='name')
    {
        $this->addAttributeToSelect('name');
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * add the product filter to collection
     *
     * @access public
     * @param mixed (Mage_Catalog_Model_Product|int) $product
     * @return Mageplaza_Kb_Model_Resource_Article_Collection
     * @author
     */
    public function addProductFilter($product)
    {
        if ($product instanceof Mage_Catalog_Model_Product) {
            $product = $product->getId();
        }
        if (!isset($this->_joinedFields['product'])) {
            $this->getSelect()->join(
                array('related_product' => $this->getTable('mageplaza_kb/article_product')),
                'related_product.article_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_product.product_id = ?', $product);
            $this->_joinedFields['product'] = true;
        }
        return $this;
    }

    /**
     * add the category filter to collection
     *
     * @access public
     * @param mixed (Mageplaza_Kb_Model_Category|int) $category
     * @return Mageplaza_Kb_Model_Resource_Article_Collection
     * @author
     */
    public function addCategoryFilter($category)
    {
        if ($category instanceof Mageplaza_Kb_Model_Category) {
            $category = $category->getId();
        }
        if (!isset($this->_joinedFields['category'])) {
            $this->getSelect()->join(
                array('related_category' => $this->getTable('mageplaza_kb/article_category')),
                'related_category.article_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_category.category_id = ?', $category);
            $this->_joinedFields['category'] = true;
        }
        return $this;
    }

    /**
     * add the tag filter to collection
     *
     * @access public
     * @param mixed (Mageplaza_Kb_Model_Tag|int) $tag
     * @return Mageplaza_Kb_Model_Resource_Article_Collection
     * @author
     */
    public function addTagFilter($tag)
    {
        if ($tag instanceof Mageplaza_Kb_Model_Tag) {
            $tag = $tag->getId();
        }
        if (!isset($this->_joinedFields['tag'])) {
            $this->getSelect()->join(
                array('related_tag' => $this->getTable('mageplaza_kb/article_tag')),
                'related_tag.article_id = e.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_tag.tag_id = ?', $tag);
            $this->_joinedFields['tag'] = true;
        }
        return $this;
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     * @author
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
