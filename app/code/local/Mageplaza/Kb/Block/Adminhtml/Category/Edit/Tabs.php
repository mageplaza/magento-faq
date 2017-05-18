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
 * Category admin edit tabs
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Category_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author
     */
    public function __construct()
    {
        $this->setId('category_info_tabs');
        $this->setDestElementId('category_tab_content');
        $this->setTitle(Mage::helper('mageplaza_kb')->__('Category'));
        $this->setTemplate('widget/tabshoriz.phtml');
    }

    /**
     * Prepare Layout Content
     *
     * @access public
     * @return Mageplaza_Kb_Block_Adminhtml_Category_Edit_Tabs
     */
    protected function _prepareLayout()
    {
        $this->addTab(
            'form_category',
            array(
                'label'   => Mage::helper('mageplaza_kb')->__('Category'),
                'title'   => Mage::helper('mageplaza_kb')->__('Category'),
                'content' => $this->getLayout()->createBlock(
                    'mageplaza_kb/adminhtml_category_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'form_meta_category',
            array(
                'label'   => Mage::helper('mageplaza_kb')->__('Meta'),
                'title'   => Mage::helper('mageplaza_kb')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'mageplaza_kb/adminhtml_category_edit_tab_meta'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_category',
                array(
                    'label'   => Mage::helper('mageplaza_kb')->__('Store views'),
                    'title'   => Mage::helper('mageplaza_kb')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'mageplaza_kb/adminhtml_category_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        $this->addTab(
            'articles',
            array(
                'label'   => Mage::helper('mageplaza_kb')->__('Articles'),
                'content' => $this->getLayout()->createBlock(
                    'mageplaza_kb/adminhtml_category_edit_tab_article',
                    'category.article.grid'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve category entity
     *
     * @access public
     * @return Mageplaza_Kb_Model_Category
     * @author
     */
    public function getCategory()
    {
        return Mage::registry('current_category');
    }
}
