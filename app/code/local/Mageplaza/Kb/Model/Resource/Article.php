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
 * Article resource model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Resource_Article extends Mage_Catalog_Model_Resource_Abstract
{
    protected $_articleProductTable = null;
    protected $_articleCategoryTable = null;
    protected $_articleTagTable = null;


    /**
     * constructor
     *
     * @access public
     * @author
     */
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('mageplaza_kb_article')
            ->setConnection(
                $resource->getConnection('article_read'),
                $resource->getConnection('article_write')
            );
        $this->_articleProductTable = $this->getTable('mageplaza_kb/article_product');
        $this->_articleCategoryTable = $this->getTable('mageplaza_kb/article_category');
        $this->_articleTagTable = $this->getTable('mageplaza_kb/article_tag');

    }

    /**
     * wrapper for main table getter
     *
     * @access public
     * @return string
     * @author
     */
    public function getMainTable()
    {
        return $this->getEntityTable();
    }

    /**
     * check url key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author
     */
    public function checkUrlKey($urlKey, $storeId, $active = true)
    {
        $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeId);
        $select = $this->_initCheckUrlKeySelect($urlKey, $stores);
        if (!$select) {
            return false;
        }
        $select->reset(Zend_Db_Select::COLUMNS)
            ->columns('e.entity_id')
            ->limit(1);
        return $this->_getReadAdapter()->fetchOne($select);
    }

    /**
     * init the check select
     *
     * @access protected
     * @param string $urlKey
     * @param array $store
     * @return Zend_Db_Select
     * @author
     */
    protected function _initCheckUrlKeySelect($urlKey, $store)
    {
        $urlRewrite = Mage::getModel('eav/config')->getAttribute('mageplaza_kb_article', 'url_key');
        if (!$urlRewrite || !$urlRewrite->getId()) {
            return false;
        }
        $table = $urlRewrite->getBackend()->getTable();
        $select = $this->_getReadAdapter()->select()
            ->from(array('e' => $table))
            ->where('e.attribute_id = ?', $urlRewrite->getId())
            ->where('e.value = ?', $urlKey)
            ->where('e.store_id IN (?)', $store)
            ->order('e.store_id DESC');
        return $select;
    }

    /**
     * Check for unique URL key
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     * @author
     */
    public function getIsUniqueUrlKey(Mage_Core_Model_Abstract $object)
    {
        if (Mage::app()->isSingleStoreMode() || !$object->hasStores()) {
            $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID);
        } else {
            $stores = (array)$object->getData('stores');
        }
        $select = $this->_initCheckUrlKeySelect($object->getData('url_key'), $stores);
        if ($object->getId()) {
            $select->where('e.entity_id <> ?', $object->getId());
        }
        if ($this->_getWriteAdapter()->fetchRow($select)) {
            return false;
        }
        return true;
    }

    /**
     * Check if the URL key is numeric
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     * @author
     */
    protected function isNumericUrlKey(Mage_Core_Model_Abstract $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('url_key'));
    }

    /**
     * Check if the URL key is valid
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     * @author
     */
    protected function isValidUrlKey(Mage_Core_Model_Abstract $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('url_key'));
    }
}
