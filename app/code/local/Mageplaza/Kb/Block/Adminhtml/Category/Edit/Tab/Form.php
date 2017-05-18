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
 * Category edit form tab
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Category_Edit_Tab_Form
     * @author
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('category_');
        $form->setFieldNameSuffix('category');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'category_form',
            array('legend' => Mage::helper('mageplaza_kb')->__('Category'))
        );
        if (!$this->getCategory()->getId()) {
            $parentId = $this->getRequest()->getParam('parent');
            if (!$parentId) {
                $parentId = Mage::helper('mageplaza_kb/category')->getRootCategoryId();
            }
            $fieldset->addField(
                'path',
                'hidden',
                array(
                    'name'  => 'path',
                    'value' => $parentId
                )
            );
        } else {
            $fieldset->addField(
                'id',
                'hidden',
                array(
                    'name'  => 'id',
                    'value' => $this->getCategory()->getId()
                )
            );
            $fieldset->addField(
                'path',
                'hidden',
                array(
                    'name'  => 'path',
                    'value' => $this->getCategory()->getPath()
                )
            );
        }

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('mageplaza_kb')->__('Name'),
                'name'  => 'name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'description',
            'textarea',
            array(
                'label' => Mage::helper('mageplaza_kb')->__('Description'),
                'name'  => 'description',

           )
        );
        $fieldset->addField(
            'url_key',
            'text',
            array(
                'label' => Mage::helper('mageplaza_kb')->__('Url key'),
                'name'  => 'url_key',
                'note'  => Mage::helper('mageplaza_kb')->__('Relative to Website Base URL')
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('mageplaza_kb')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('mageplaza_kb')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('mageplaza_kb')->__('Disabled'),
                    ),
                ),
            )
        );
        $fieldset->addField(
            'in_rss',
            'select',
            array(
                'label'  => Mage::helper('mageplaza_kb')->__('Show in rss'),
                'name'   => 'in_rss',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('mageplaza_kb')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('mageplaza_kb')->__('No'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_category')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $form->addValues($this->getCategory()->getData());
        return parent::_prepareForm();
    }

    /**
     * get the current category
     *
     * @access public
     * @return Mageplaza_Kb_Model_Category
     */
    public function getCategory()
    {
        return Mage::registry('category');
    }
}
