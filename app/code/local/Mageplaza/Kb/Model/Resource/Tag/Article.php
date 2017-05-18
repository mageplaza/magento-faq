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
 * Tag - Article relation model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Resource_Tag_Article extends Mage_Core_Model_Resource_Db_Abstract
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
        $this->_init('mageplaza_kb/tag_article', 'rel_id');
    }

    /**
     * Save tag - article relations
     *
     * @access public
     * @param Mageplaza_Kb_Model_Tag $tag
     * @param array $data
     * @return Mageplaza_Kb_Model_Resource_Tag_Article
     * @author
     */
    public function saveTagRelation($tag, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }

        $adapter = $this->_getWriteAdapter();
        $bind    = array(
            ':tag_id'    => (int)$tag->getId(),
        );
        $select = $adapter->select()
            ->from($this->getMainTable(), array('rel_id', 'article_id'))
            ->where('tag_id = :tag_id');

        $related   = $adapter->fetchPairs($select, $bind);
        $deleteIds = array();
        foreach ($related as $relId => $articleId) {
            if (!isset($data[$articleId])) {
                $deleteIds[] = (int)$relId;
            }
        }
        if (!empty($deleteIds)) {
            $adapter->delete(
                $this->getMainTable(),
                array('rel_id IN (?)' => $deleteIds)
            );
        }

        foreach ($data as $articleId => $info) {
            $adapter->insertOnDuplicate(
                $this->getMainTable(),
                array(
                    'tag_id'      => $tag->getId(),
                    'article_id'     => $articleId,
                    'position'      => @$info['position']
                ),
                array('position')
            );
        }
        return $this;
    }
}
