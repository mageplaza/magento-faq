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
 * Category edit form
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Category_Edit_Form extends Mageplaza_Kb_Block_Adminhtml_Category_Abstract
{
    /**
     * Additional buttons on category page
     * @var array
     */
    protected $_additionalButtons = array();
    /**
     * constructor
     *
     * set template
     * @access public
     * @author
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mageplaza_kb/category/edit/form.phtml');
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Category_Edit_Form
     * @author
     */
    protected function _prepareLayout()
    {
        $category = $this->getCategory();
        $categoryId = (int)$category->getId();
        $this->setChild(
            'tabs',
            $this->getLayout()->createBlock('mageplaza_kb/adminhtml_category_edit_tabs', 'tabs')
        );
        $this->setChild(
            'save_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label'   => Mage::helper('mageplaza_kb')->__('Save Category'),
                        'onclick' => "categorySubmit('" . $this->getSaveUrl() . "', true)",
                        'class'   => 'save'
                    )
                )
        );
        // Delete button
        if (!in_array($categoryId, $this->getRootIds())) {
            $this->setChild(
                'delete_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(
                        array(
                            'label'   => Mage::helper('mageplaza_kb')->__('Delete Category'),
                            'onclick' => "categoryDelete('" . $this->getUrl(
                                '*/*/delete',
                                array('_current' => true)
                            )
                            . "', true, {$categoryId})",
                            'class'   => 'delete'
                        )
                    )
            );
        }

        // Reset button
        $resetPath = $category ? '*/*/edit' : '*/*/add';
        $this->setChild(
            'reset_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label' => Mage::helper('mageplaza_kb')->__('Reset'),
                        'onclick'   => "categoryReset('".$this->getUrl(
                            $resetPath,
                            array('_current'=>true)
                        )
                        ."',true)"
                    )
                )
        );
        return parent::_prepareLayout();
    }

    /**
     * get html for delete button
     *
     * @access public
     * @return string
     * @author
     */
    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    /**
     * get html for save button
     *
     * @access public
     * @return string
     * @author
     */
    public function getSaveButtonHtml()
    {
        return $this->getChildHtml('save_button');
    }

    /**
     * get html for reset button
     *
     * @access public
     * @return string
     * @author
     */
    public function getResetButtonHtml()
    {
        return $this->getChildHtml('reset_button');
    }

    /**
     * Retrieve additional buttons html
     *
     * @access public
     * @return string
     * @author
     */
    public function getAdditionalButtonsHtml()
    {
        $html = '';
        foreach ($this->_additionalButtons as $childName) {
            $html .= $this->getChildHtml($childName);
        }
        return $html;
    }

    /**
     * Add additional button
     *
     * @param string $alias
     * @param array $config
     * @return Mageplaza_Kb_Block_Adminhtml_Category_Edit_Form
     * @author
     */
    public function addAdditionalButton($alias, $config)
    {
        if (isset($config['name'])) {
            $config['element_name'] = $config['name'];
        }
        $this->setChild(
            $alias . '_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')->addData($config)
        );
        $this->_additionalButtons[$alias] = $alias . '_button';
        return $this;
    }

    /**
     * Remove additional button
     *
     * @access public
     * @param string $alias
     * @return Mageplaza_Kb_Block_Adminhtml_Category_Edit_Form
     * @author
     */
    public function removeAdditionalButton($alias)
    {
        if (isset($this->_additionalButtons[$alias])) {
            $this->unsetChild($this->_additionalButtons[$alias]);
            unset($this->_additionalButtons[$alias]);
        }
        return $this;
    }

    /**
     * get html for tabs
     *
     * @access public
     * @return string
     * @author
     */
    public function getTabsHtml()
    {
        return $this->getChildHtml('tabs');
    }

    /**
     * get the form header
     *
     * @access public
     * @return string
     * @author
     */
    public function getHeader()
    {
        if ($this->getCategoryId()) {
            return $this->getCategoryName();
        } else {
            return Mage::helper('mageplaza_kb')->__('New Root Category');
        }
    }

    /**
     * get the delete url
     *
     * @access public
     * @param array $args
     * @return string
     * @author
     */
    public function getDeleteUrl(array $args = array())
    {
        $params = array('_current'=>true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/delete', $params);
    }

    /**
     * Return URL for refresh input element 'path' in form
     *
     * @access public
     * @param array $args
     * @return string
     * @author
     */
    public function getRefreshPathUrl(array $args = array())
    {
        $params = array('_current'=>true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/refreshPath', $params);
    }

    /**
     * check if request is ajax
     *
     * @access public
     * @return bool
     * @author
     */
    public function isAjax()
    {
        return Mage::app()->getRequest()->isXmlHttpRequest() || Mage::app()->getRequest()->getParam('isAjax');
    }

    /**
     * get  in json format
     *
     * @access public
     * @return string
     * @author
     */
    public function getArticlesJson()
    {
        $articles = $this->getCategory()->getSelectedArticles();
        if (!empty($articles)) {
            $positions = array();
            foreach ($articles as $article) {
                $positions[$article->getId()] = $article->getPosition();
            }
            return Mage::helper('core')->jsonEncode($positions);
        }
        return '{}';
    }
}
