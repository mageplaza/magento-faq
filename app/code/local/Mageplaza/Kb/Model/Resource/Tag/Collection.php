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
 * Tag collection resource model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Resource_Tag_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
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
        $this->_init('mageplaza_kb/tag');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Add filter by store
     *
     * @access public
     * @param int|Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Mageplaza_Kb_Model_Resource_Tag_Collection
     * @author
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!isset($this->_joinedFields['store'])) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }
            if (!is_array($store)) {
                $store = array($store);
            }
            if ($withAdmin) {
                $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
            }
            $this->addFilter('store', array('in' => $store), 'public');
            $this->_joinedFields['store'] = true;
        }
        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @access protected
     * @return Mageplaza_Kb_Model_Resource_Tag_Collection
     * @author
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('mageplaza_kb/tag_store')),
                'main_table.entity_id = store_table.tag_id',
                array()
            )
            ->group('main_table.entity_id');
            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }

    /**
     * get tags as array
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
        return parent::_toOptionHash($valueField, $labelField);
    }

    /**
     * add the article filter to collection
     *
     * @access public
     * @param mixed (Mageplaza_Kb_Model_Article|int) $article
     * @return Mageplaza_Kb_Model_Resource_Tag_Collection
     * @author
     */
    public function addArticleFilter($article)
    {
        if ($article instanceof Mageplaza_Kb_Model_Article) {
            $article = $article->getId();
        }
        if (!isset($this->_joinedFields['article'])) {
            $this->getSelect()->join(
                array('related_article' => $this->getTable('mageplaza_kb/tag_article')),
                'related_article.tag_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_article.article_id = ?', $article);
            $this->_joinedFields['article'] = true;
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
