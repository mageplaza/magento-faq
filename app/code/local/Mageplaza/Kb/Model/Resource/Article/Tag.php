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
 * Article - Tag relation model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Resource_Article_Tag extends Mage_Core_Model_Resource_Db_Abstract
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
        $this->_init('mageplaza_kb/article_tag', 'rel_id');
    }

    /**
     * Save article - tag relations
     *
     * @access public
     * @param Mageplaza_Kb_Model_Article $article
     * @param array $data
     * @return Mageplaza_Kb_Model_Resource_Article_Tag
     * @author
     */
    public function saveArticleRelation($article, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }

        $adapter = $this->_getWriteAdapter();
        $bind    = array(
            ':article_id'    => (int)$article->getId(),
        );
        $select = $adapter->select()
            ->from($this->getMainTable(), array('rel_id', 'tag_id'))
            ->where('article_id = :article_id');

        $related   = $adapter->fetchPairs($select, $bind);
        $deleteIds = array();
        foreach ($related as $relId => $tagId) {
            if (!isset($data[$tagId])) {
                $deleteIds[] = (int)$relId;
            }
        }
        if (!empty($deleteIds)) {
            $adapter->delete(
                $this->getMainTable(),
                array('rel_id IN (?)' => $deleteIds)
            );
        }

        foreach ($data as $tagId => $info) {
            $adapter->insertOnDuplicate(
                $this->getMainTable(),
                array(
                    'article_id'      => $article->getId(),
                    'tag_id'     => $tagId,
                    'position'      => @$info['position']
                ),
                array('position')
            );
        }
        return $this;
    }
}
