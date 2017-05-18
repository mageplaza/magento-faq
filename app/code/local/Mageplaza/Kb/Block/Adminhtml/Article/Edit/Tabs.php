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
 * Article admin edit tabs
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Article_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('article_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('mageplaza_kb')->__('Article Information'));
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Article_Edit_Tabs
     * @author
     */
    protected function _prepareLayout()
    {
        $article = $this->getArticle();
        $entity = Mage::getModel('eav/entity_type')
            ->load('mageplaza_kb_article', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->addFieldToFilter(
            'attribute_code',
            array(
                'nin' => array('meta_title', 'meta_description', 'meta_keywords')
            )
        );
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'info',
            array(
                'label'   => Mage::helper('mageplaza_kb')->__('Article Information'),
                'content' => $this->getLayout()->createBlock(
                    'mageplaza_kb/adminhtml_article_edit_tab_attributes'
                )
                ->setAttributes($attributes)
                ->toHtml(),
            )
        );
        $seoAttributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId())
            ->addFieldToFilter(
                'attribute_code',
                array(
                    'in' => array('meta_title', 'meta_description', 'meta_keywords')
                )
            );
        $seoAttributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'meta',
            array(
                'label'   => Mage::helper('mageplaza_kb')->__('Meta'),
                'title'   => Mage::helper('mageplaza_kb')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'mageplaza_kb/adminhtml_article_edit_tab_attributes'
                )
                ->setAttributes($seoAttributes)
                ->toHtml(),
            )
        );
        $this->addTab(
            'categorys',
            array(
                'label' => Mage::helper('mageplaza_kb')->__('Categories'),
                'url'   => $this->getUrl('*/*/categorys', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        $this->addTab(
            'tags',
            array(
                'label' => Mage::helper('mageplaza_kb')->__('Tags'),
                'url'   => $this->getUrl('*/*/tags', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('mageplaza_kb')->__('Associated products'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve article entity
     *
     * @access public
     * @return Mageplaza_Kb_Model_Article
     * @author
     */
    public function getArticle()
    {
        return Mage::registry('current_article');
    }
}
