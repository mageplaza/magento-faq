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
 * Article admin grid block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Article_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('articleGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Article_Grid
     * @author
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('mageplaza_kb/article')
            ->getCollection()
            ->addAttributeToSelect('avg_rating')
            ->addAttributeToSelect('status')
            ->addAttributeToSelect('url_key');
        
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $store = $this->_getStore();
        $collection->joinAttribute(
            'name', 
            'mageplaza_kb_article/name', 
            'entity_id', 
            null, 
            'inner', 
            $adminStore
        );
        if ($store->getId()) {
            $collection->joinAttribute(
                'mageplaza_kb_article_name', 
                'mageplaza_kb_article/name', 
                'entity_id', 
                null, 
                'inner', 
                $store->getId()
            );
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Article_Grid
     * @author
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('mageplaza_kb')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('mageplaza_kb')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        
        if ($this->_getStore()->getId()) {
            $this->addColumn(
                'mageplaza_kb_article_name', 
                array(
                    'header'    => Mage::helper('mageplaza_kb')->__('Name in %s', $this->_getStore()->getName()),
                    'align'     => 'left',
                    'index'     => 'mageplaza_kb_article_name',
                )
            );
        }

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('mageplaza_kb')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('mageplaza_kb')->__('Enabled'),
                    '0' => Mage::helper('mageplaza_kb')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'avg_rating',
            array(
                'header' => Mage::helper('mageplaza_kb')->__('Average Rating'),
                'index'  => 'avg_rating',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'url_key',
            array(
                'header' => Mage::helper('mageplaza_kb')->__('URL key'),
                'index'  => 'url_key',
            )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('mageplaza_kb')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('mageplaza_kb')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('mageplaza_kb')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('mageplaza_kb')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('mageplaza_kb')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('mageplaza_kb')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('mageplaza_kb')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * get the selected store
     *
     * @access protected
     * @return Mage_Core_Model_Store
     * @author
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Article_Grid
     * @author
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('article');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('mageplaza_kb')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('mageplaza_kb')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('mageplaza_kb')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('mageplaza_kb')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('mageplaza_kb')->__('Enabled'),
                            '0' => Mage::helper('mageplaza_kb')->__('Disabled'),
                        )
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Mageplaza_Kb_Model_Article
     * @return string
     * @author
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
