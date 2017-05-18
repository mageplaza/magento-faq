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
 * Tag article model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Tag_Article extends Mage_Core_Model_Abstract
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
        $this->_init('mageplaza_kb/tag_article');
    }

    /**
     * Save data for tag - article relation
     * @access public
     * @param  Mageplaza_Kb_Model_Tag $tag
     * @return Mageplaza_Kb_Model_Tag_Article
     * @author
     */
    public function saveTagRelation($tag)
    {
        $data = $tag->getArticlesData();
        if (!is_null($data)) {
            $this->_getResource()->saveTagRelation($tag, $data);
        }
        return $this;
    }

    /**
     * get  for tag
     *
     * @access public
     * @param Mageplaza_Kb_Model_Tag $tag
     * @return Mageplaza_Kb_Model_Resource_Tag_Article_Collection
     * @author
     */
    public function getArticlesCollection($tag)
    {
        $collection = Mage::getResourceModel('mageplaza_kb/tag_article_collection')
            ->addTagFilter($tag);
        return $collection;
    }
}
