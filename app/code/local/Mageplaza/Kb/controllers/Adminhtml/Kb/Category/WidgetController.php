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
 * Category admin widget controller
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Adminhtml_Kb_Category_WidgetController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Chooser Source action
     *
     * @access public
     * @return void
     * @author
     */
    public function chooserAction()
    {
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $grid = $this->getLayout()->createBlock(
            'mageplaza_kb/adminhtml_category_widget_chooser',
            '',
            array(
                'id' => $uniqId,
            )
        );
        $this->getResponse()->setBody($grid->toHtml());
    }

    /**
     * categories json action
     *
     * @access public
     * @return void
     * @author
     */
    public function categorysJsonAction()
    {
        if ($categoryId = (int) $this->getRequest()->getPost('id')) {
            $category = Mage::getModel('mageplaza_kb/category')->load($categoryId);
            if ($category->getId()) {
                Mage::register('category', $category);
                Mage::register('current_category', $category);
            }
            $this->getResponse()->setBody(
                $this->_getCategoryTreeBlock()->getTreeJson($category)
            );
        }
    }

    /**
     * get category tree block
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Category_Widget_Chooser
     * @author
     */
    protected function _getCategoryTreeBlock()
    {
        return $this->getLayout()->createBlock(
            'mageplaza_kb/adminhtml_category_widget_chooser',
            '',
            array(
                'id' => $this->getRequest()->getParam('uniq_id'),
                'use_massaction' => $this->getRequest()->getParam('use_massaction', false)
            )
        );
    }
}
