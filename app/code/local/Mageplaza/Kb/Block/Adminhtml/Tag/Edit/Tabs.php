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
 * Tag admin edit tabs
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Tag_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('tag_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('mageplaza_kb')->__('Tag'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Tag_Edit_Tabs
     * @author
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_tag',
            array(
                'label'   => Mage::helper('mageplaza_kb')->__('Tag'),
                'title'   => Mage::helper('mageplaza_kb')->__('Tag'),
                'content' => $this->getLayout()->createBlock(
                    'mageplaza_kb/adminhtml_tag_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'form_meta_tag',
            array(
                'label'   => Mage::helper('mageplaza_kb')->__('Meta'),
                'title'   => Mage::helper('mageplaza_kb')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'mageplaza_kb/adminhtml_tag_edit_tab_meta'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_tag',
                array(
                    'label'   => Mage::helper('mageplaza_kb')->__('Store views'),
                    'title'   => Mage::helper('mageplaza_kb')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'mageplaza_kb/adminhtml_tag_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        $this->addTab(
            'articles',
            array(
                'label' => Mage::helper('mageplaza_kb')->__('Articles'),
                'url'   => $this->getUrl('*/*/articles', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve tag entity
     *
     * @access public
     * @return Mageplaza_Kb_Model_Tag
     * @author
     */
    public function getTag()
    {
        return Mage::registry('current_tag');
    }
}
