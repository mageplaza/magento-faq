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
 * category - article relation edit block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Category_Edit_Tab_Article extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access protected
     * @author
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('article_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(array('in_articles' => 1));
        }
    }

    /**
     * prepare the article collection
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Category_Edit_Tab_Article
     * @author
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('mageplaza_kb/article_collection')->addAttributeToSelect('name');
        if ($this->getCategory()->getId()) {
            $constraint = 'related.category_id='.$this->getCategory()->getId();
        } else {
            $constraint = 'related.category_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('mageplaza_kb/category_article')),
            'related.article_id=e.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Category_Edit_Tab_Article
     * @author
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Category_Edit_Tab_Article
     * @author
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_articles',
            array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_articles',
                'values'            => $this->_getSelectedArticles(),
                'align'             => 'center',
                'index'             => 'entity_id'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('mageplaza_kb')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
                'renderer'  => 'mageplaza_kb/adminhtml_helper_column_renderer_relation',
                'params'    => array(
                    'id'    => 'getId'
                ),
                'base_link' => 'adminhtml/kb_article/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('mageplaza_kb')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
    }

    /**
     * Retrieve selected 
     *
     * @access protected
     * @return array
     * @author
     */
    protected function _getSelectedArticles()
    {
        $articles = $this->getCategoryArticles();
        if (!is_array($articles)) {
            $articles = array_keys($this->getSelectedArticles());
        }
        return $articles;
    }

    /**
     * Retrieve selected {{siblingsLabels}}
     *
     * @access protected
     * @return array
     * @author
     */
    public function getSelectedArticles()
    {
        $articles = array();
        $selected = Mage::registry('current_category')->getSelectedArticles();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $article) {
            $articles[$article->getId()] = array('position' => $article->getPosition());
        }
        return $articles;
    }

    /**
     * get row url
     *
     * @access public
     * @param Mageplaza_Kb_Model_Article
     * @return string
     * @author
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/articlesGrid',
            array(
                'id' => $this->getCategory()->getId()
            )
        );
    }

    /**
     * get the current category
     *
     * @access public
     * @return Mageplaza_Kb_Model_Category
     * @author
     */
    public function getCategory()
    {
        return Mage::registry('current_category');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return Mageplaza_Kb_Block_Adminhtml_Category_Edit_Tab_Article
     * @author
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_articles') {
            $articleIds = $this->_getSelectedArticles();
            if (empty($articleIds)) {
                $articleIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$articleIds));
            } else {
                if ($articleIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$articleIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
