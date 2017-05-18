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
 * Article comment admin edit tabs
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Article_Comment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('article_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('mageplaza_kb')->__('Article Comment'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Article_Edit_Tabs
     * @author
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_article_comment',
            array(
                'label'   => Mage::helper('mageplaza_kb')->__('Article comment'),
                'title'   => Mage::helper('mageplaza_kb')->__('Article comment'),
                'content' => $this->getLayout()->createBlock(
                    'mageplaza_kb/adminhtml_article_comment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_article_comment',
                array(
                    'label'   => Mage::helper('mageplaza_kb')->__('Store views'),
                    'title'   => Mage::helper('mageplaza_kb')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'mageplaza_kb/adminhtml_article_comment_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve comment
     *
     * @access public
     * @return Mageplaza_Kb_Model_Article_Comment
     * @author
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }
}
