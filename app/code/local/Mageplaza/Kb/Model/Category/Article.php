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
 * Category article model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Category_Article extends Mage_Core_Model_Abstract
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
        $this->_init('mageplaza_kb/category_article');
    }

    /**
     * Save data for category - article relation
     * @access public
     * @param  Mageplaza_Kb_Model_Category $category
     * @return Mageplaza_Kb_Model_Category_Article
     * @author
     */
    public function saveCategoryRelation($category)
    {
        $data = $category->getArticlesData();
        if (!is_null($data)) {
            $this->_getResource()->saveCategoryRelation($category, $data);
        }
        return $this;
    }

    /**
     * get  for category
     *
     * @access public
     * @param Mageplaza_Kb_Model_Category $category
     * @return Mageplaza_Kb_Model_Resource_Category_Article_Collection
     * @author
     */
    public function getArticlesCollection($category)
    {
        $collection = Mage::getResourceModel('mageplaza_kb/category_article_collection')
            ->addCategoryFilter($category);
        return $collection;
    }
}
