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
 * Article admin widget chooser
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */

class Mageplaza_Kb_Block_Adminhtml_Article_Widget_Chooser extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Block construction, prepare grid params
     *
     * @access public
     * @param array $arguments Object data
     * @return void
     * @author
     */
    public function __construct($arguments=array())
    {
        parent::__construct($arguments);
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setDefaultFilter(array('chooser_status' => '1'));
    }

    /**
     * Prepare chooser element HTML
     *
     * @access public
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     * @author
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = Mage::helper('core')->uniqHash($element->getId());
        $sourceUrl = $this->getUrl(
            '*/kb_article_widget/chooser',
            array('uniq_id' => $uniqId)
        );
        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);
        if ($element->getValue()) {
            $article = Mage::getModel('mageplaza_kb/article')->load($element->getValue());
            if ($article->getId()) {
                $chooser->setLabel($article->getName());
            }
        }
        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Grid Row JS Callback
     *
     * @access public
     * @return string
     * @author
     */
    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var articleId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var articleTitle = trElement.down("td").next().innerHTML;
                '.$chooserJsObject.'.setElementValue(articleId);
                '.$chooserJsObject.'.setElementLabel(articleTitle);
                '.$chooserJsObject.'.close();
            }
        ';
        return $js;
    }

    /**
     * Prepare a static blocks collection
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Article_Widget_Chooser
     * @author
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('mageplaza_kb/article')->getCollection();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('status');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for the a grid
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Article_Widget_Chooser
     * @author
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'chooser_id',
            array(
                'header' => Mage::helper('mageplaza_kb')->__('Id'),
                'align'  => 'right',
                'index'  => 'entity_id',
                'type'   => 'number',
                'width'  => 50
            )
        );

        $this->addColumn(
            'chooser_name',
            array(
                'header' => Mage::helper('mageplaza_kb')->__('Name'),
                'align'  => 'left',
                'index'  => 'name',
            )
        );
        $this->addColumn(
            'chooser_status',
            array(
                'header'  => Mage::helper('mageplaza_kb')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    0 => Mage::helper('mageplaza_kb')->__('Disabled'),
                    1 => Mage::helper('mageplaza_kb')->__('Enabled')
                ),
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * get url for grid
     *
     * @access public
     * @return string
     * @author
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            'adminhtml/kb_article_widget/chooser',
            array('_current' => true)
        );
    }
}
