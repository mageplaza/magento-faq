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
 * Article - Category relation model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Resource_Article_Category extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @return void
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author
     */
    protected function  _construct()
    {
        $this->_init('mageplaza_kb/article_category', 'rel_id');
    }

    /**
     * Save article - category relations
     *
     * @access public
     * @param Mageplaza_Kb_Model_Article $article
     * @param array $data
     * @return Mageplaza_Kb_Model_Resource_Article_Category
     * @author
     */
    public function saveArticleRelation($article, $categoryIds)
    {
        if (is_null($categoryIds)) {
            return $this;
        }
        $oldCategorys = $article->getSelectedCategorys();
        $oldCategoryIds = array();
        foreach ($oldCategorys as $category) {
            $oldCategoryIds[] = $category->getId();
        }
        $insert = array_diff($categoryIds, $oldCategoryIds);
        $delete = array_diff($oldCategoryIds, $categoryIds);
        $write = $this->_getWriteAdapter();
        if (!empty($insert)) {
            $data = array();
            foreach ($insert as $categoryId) {
                if (empty($categoryId)) {
                    continue;
                }
                $data[] = array(
                    'category_id' => (int)$categoryId,
                    'article_id'  => (int)$article->getId(),
                    'position'=> 1
                );
            }
            if ($data) {
                $write->insertMultiple($this->getMainTable(), $data);
            }
        }
        if (!empty($delete)) {
            foreach ($delete as $categoryId) {
                $where = array(
                    'article_id = ?'  => (int)$article->getId(),
                    'category_id = ?' => (int)$categoryId,
                );
                $write->delete($this->getMainTable(), $where);
            }
        }
        return $this;
    }
}
