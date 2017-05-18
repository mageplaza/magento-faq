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
 * article - tag relation edit block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Article_Edit_Tab_Tag extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('tag_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getArticle()->getId()) {
            $this->setDefaultFilter(array('in_tags' => 1));
        }
    }

    /**
     * prepare the tag collection
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Article_Edit_Tab_Tag
     * @author
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('mageplaza_kb/tag_collection');
        if ($this->getArticle()->getId()) {
            $constraint = 'related.article_id='.$this->getArticle()->getId();
        } else {
            $constraint = 'related.article_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('mageplaza_kb/article_tag')),
            'related.tag_id=main_table.entity_id AND '.$constraint,
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
     * @return Mageplaza_Kb_Block_Adminhtml_Article_Edit_Tab_Tag
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
     * @return Mageplaza_Kb_Block_Adminhtml_Article_Edit_Tab_Tag
     * @author
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_tags',
            array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_tags',
                'values'            => $this->_getSelectedTags(),
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
                'base_link' => 'adminhtml/kb_tag/edit',
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
    protected function _getSelectedTags()
    {
        $tags = $this->getArticleTags();
        if (!is_array($tags)) {
            $tags = array_keys($this->getSelectedTags());
        }
        return $tags;
    }

    /**
     * Retrieve selected {{siblingsLabels}}
     *
     * @access protected
     * @return array
     * @author
     */
    public function getSelectedTags()
    {
        $tags = array();
        $selected = Mage::registry('current_article')->getSelectedTags();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $tag) {
            $tags[$tag->getId()] = array('position' => $tag->getPosition());
        }
        return $tags;
    }

    /**
     * get row url
     *
     * @access public
     * @param Mageplaza_Kb_Model_Tag
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
            '*/*/tagsGrid',
            array(
                'id' => $this->getArticle()->getId()
            )
        );
    }

    /**
     * get the current article
     *
     * @access public
     * @return Mageplaza_Kb_Model_Article
     * @author
     */
    public function getArticle()
    {
        return Mage::registry('current_article');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return Mageplaza_Kb_Block_Adminhtml_Article_Edit_Tab_Tag
     * @author
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_tags') {
            $tagIds = $this->_getSelectedTags();
            if (empty($tagIds)) {
                $tagIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$tagIds));
            } else {
                if ($tagIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$tagIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
