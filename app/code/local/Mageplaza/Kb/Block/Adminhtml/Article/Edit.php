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
 * Article admin edit form
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Article_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'mageplaza_kb';
        $this->_controller = 'adminhtml_article';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('mageplaza_kb')->__('Save Article')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('mageplaza_kb')->__('Delete Article')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('mageplaza_kb')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_article') && Mage::registry('current_article')->getId()) {
            return Mage::helper('mageplaza_kb')->__(
                "Edit Article '%s'",
                $this->escapeHtml(Mage::registry('current_article')->getName())
            );
        } else {
            return Mage::helper('mageplaza_kb')->__('Add Article');
        }
    }
}
