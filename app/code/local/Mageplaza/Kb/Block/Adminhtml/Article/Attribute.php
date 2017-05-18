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
 * Article admin attribute block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Article_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_article_attribute';
        $this->_blockGroup = 'mageplaza_kb';
        $this->_headerText = Mage::helper('mageplaza_kb')->__('Manage Article Attributes');
        parent::__construct();
        $this->_updateButton(
            'add',
            'label',
            Mage::helper('mageplaza_kb')->__('Add New Article Attribute')
        );
    }
}
