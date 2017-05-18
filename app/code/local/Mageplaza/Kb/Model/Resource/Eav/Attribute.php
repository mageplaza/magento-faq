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
 * Attribute resource model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Resource_Eav_Attribute extends Mage_Eav_Model_Entity_Attribute
{
    const MODULE_NAME   = 'Mageplaza_Kb';
    const ENTITY        = 'mageplaza_kb_eav_attribute';

    protected $_eventPrefix = 'mageplaza_kb_entity_attribute';
    protected $_eventObject = 'attribute';

    /**
     * Array with labels
     *
     * @var array
     */
    static protected $_labels = null;

    /**
     * constructor
     *
     * @access protected
     * @return void
     * @author
     */
    protected function _construct()
    {
        $this->_init('mageplaza_kb/attribute');
    }

    /**
     * check if scope is store view
     *
     * @access public
     * @return void
     * @author
     */
    public function isScopeStore()
    {
        return $this->getIsGlobal() == Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE;
    }

    /**
     * check if scope is website
     *
     * @access public
     * @return void
     * @author
     */
    public function isScopeWebsite()
    {
        return $this->getIsGlobal() == Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE;
    }

    /**
     * check if scope is global
     *
     * @access public
     * @return void
     * @author
     */
    public function isScopeGlobal()
    {
        return (!$this->isScopeStore() && !$this->isScopeWebsite());
    }

    /**
     * get backend input type
     *
     * @access public
     * @param string $type
     * @return string
     * @author
     */
    public function getBackendTypeByInput($type)
    {
        switch ($type) {
            case 'file':
                //intentional fallthrough
            case 'image':
                return 'varchar';
                break;
            default:
                return parent::getBackendTypeByInput($type);
            break;
        }
    }

    /**
     * don't delete system attributes
     *
     * @access public
     * @param string $type
     * @return string
     * @author
     */
    protected function _beforeDelete()
    {
        if (!$this->getIsUserDefined()) {
            throw new Mage_Core_Exception(
                Mage::helper('mageplaza_kb')->__('This attribute is not deletable')
            );
        }
        return parent::_beforeDelete();
    }
}
