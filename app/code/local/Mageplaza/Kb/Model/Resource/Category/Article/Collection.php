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
 * Category - Article relation resource model collection
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Resource_Category_Article_Collection extends Mageplaza_Kb_Model_Resource_Article_Collection
{
    /**
     * remember if fields have been joined
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return Mageplaza_Kb_Model_Resource_Category_Article_Collection
     * @author
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('mageplaza_kb/category_article')),
                'related.article_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add category filter
     *
     * @access public
     * @param Mageplaza_Kb_Model_Category | int $category
     * @return Mageplaza_Kb_Model_Resource_Category_Article_Collection
     * @author
     */
    public function addCategoryFilter($category)
    {
        if ($category instanceof Mageplaza_Kb_Model_Category) {
            $category = $category->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.category_id = ?', $category);
        return $this;
    }
}
