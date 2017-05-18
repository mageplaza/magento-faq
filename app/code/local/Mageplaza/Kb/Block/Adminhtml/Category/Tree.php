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
 * Category admin tree block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Category_Tree extends Mageplaza_Kb_Block_Adminhtml_Category_Abstract
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mageplaza_kb/category/tree.phtml');
        $this->setUseAjax(true);
        $this->_withProductCount = true;
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Adminhtml_Category_Tree
     * @author
     */
    protected function _prepareLayout()
    {
        $addUrl = $this->getUrl(
            "*/*/add",
            array(
                '_current'=>true,
                'id'=>null,
                '_query' => false
            )
        );

        $this->setChild(
            'add_sub_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label'   => Mage::helper('mageplaza_kb')->__('Add Child Category'),
                        'onclick' => "addNew('".$addUrl."', false)",
                        'class'   => 'add',
                        'id'      => 'add_child_category_button',
                        'style'   => $this->canAddChild() ? '' : 'display: none;'
                    )
                )
        );

        $this->setChild(
            'add_root_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label'   => Mage::helper('mageplaza_kb')->__('Add Root Category'),
                        'onclick' => "addNew('".$addUrl."', true)",
                        'class'   => 'add',
                        'id'      => 'add_root_category_button'
                    )
                )
        );
        return parent::_prepareLayout();
    }

    /**
     * get the category collection
     *
     * @access public
     * @return Mageplaza_Kb_Model_Resource_Category_Collection
     * @author
     */
    public function getCategoryCollection()
    {
        $collection = $this->getData('category_collection');
        if (is_null($collection)) {
            $collection = Mage::getModel('mageplaza_kb/category')->getCollection();
            $this->setData('category_collection', $collection);
        }
        return $collection;
    }

    /**
     * get html for add root button
     *
     * @access public
     * @return string
     * @author
     */
    public function getAddRootButtonHtml()
    {
        return $this->getChildHtml('add_root_button');
    }

    /**
     * get html for add child button
     *
     * @access public
     * @return string
     * @author
     */
    public function getAddSubButtonHtml()
    {
        return $this->getChildHtml('add_sub_button');
    }

    /**
     * get html for expand button
     *
     * @access public
     * @return string
     * @author
     */
    public function getExpandButtonHtml()
    {
        return $this->getChildHtml('expand_button');
    }

    /**
     * get html for add collapse button
     *
     * @access public
     * @return string
     * @author
     */
    public function getCollapseButtonHtml()
    {
        return $this->getChildHtml('collapse_button');
    }

    /**
     * get url for tree load
     *
     * @access public
     * @param mxed $expanded
     * @return string
     * @author
     */
    public function getLoadTreeUrl($expanded=null)
    {
        $params = array('_current' => true, 'id' => null, 'store' => null);
        if ((is_null($expanded) &&
            Mage::getSingleton('admin/session')->getCategoryIsTreeWasExpanded()) ||
            $expanded == true) {
            $params['expand_all'] = true;
        }
        return $this->getUrl('*/*/categorysJson', $params);
    }

    /**
     * get url for loading nodes
     *
     * @access public
     * @return string
     * @author
     */
    public function getNodesUrl()
    {
        return $this->getUrl('*/kb_categorys/jsonTree');
    }

    /**
     * check if tree is expanded
     *
     * @access public
     * @return string
     * @author
     */
    public function getIsWasExpanded()
    {
        return Mage::getSingleton('admin/session')->getCategoryIsTreeWasExpanded();
    }

    /**
     * get url for moving category
     *
     * @access public
     * @return string
     * @author
     */
    public function getMoveUrl()
    {
        return $this->getUrl('*/kb_category/move');
    }

    /**
     * get the tree as json
     *
     * @access public
     * @param mixed $parentNodeCategory
     * @return string
     * @author
     */
    public function getTree($parentNodeCategory = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNodeCategory));
        $tree = isset($rootArray['children']) ? $rootArray['children'] : array();
        return $tree;
    }

    /**
     * get the tree as json
     *
     * @access public
     * @param mixed $parentNodeCategory
     * @return string
     * @author
     */
    public function getTreeJson($parentNodeCategory = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNodeCategory));
        $json = Mage::helper('core')->jsonEncode(isset($rootArray['children']) ? $rootArray['children'] : array());
        return $json;
    }

    /**
     * Get JSON of array of categories, that are breadcrumbs for specified category path
     *
     * @access public
     * @param string $path
     * @param string $javascriptVarName
     * @return string
     * @author
     */
    public function getBreadcrumbsJavascript($path, $javascriptVarName)
    {
        if (empty($path)) {
            return '';
        }

        $categorys = Mage::getResourceSingleton('mageplaza_kb/category_tree')
            ->loadBreadcrumbsArray($path);
        if (empty($categorys)) {
            return '';
        }
        foreach ($categorys as $key => $category) {
            $categorys[$key] = $this->_getNodeJson($category);
        }
        return
            '<script type="text/javascript">'
            . $javascriptVarName . ' = ' . Mage::helper('core')->jsonEncode($categorys) . ';'
            . ($this->canAddChild() ? '$("add_child_category_button").show();' : '$("add_child_category_button").hide();')
            . '</script>';
    }

    /**
     * Get JSON of a tree node or an associative array
     *
     * @access protected
     * @param Varien_Data_Tree_Node|array $node
     * @param int $level
     * @return string
     * @author
     */
    protected function _getNodeJson($node, $level = 0)
    {
        // create a node from data array
        if (is_array($node)) {
            $node = new Varien_Data_Tree_Node($node, 'entity_id', new Varien_Data_Tree);
        }
        $item = array();
        $item['text'] = $this->buildNodeName($node);
        $item['id']   = $node->getId();
        $item['path'] = $node->getData('path');
        $item['cls']  = 'folder';
        if ($node->getStatus()) {
            $item['cls'] .= ' active-category';
        } else {
            $item['cls'] .= ' no-active-category';
        }
        $item['allowDrop'] = true;
        $item['allowDrag'] = true;
        if ((int)$node->getChildrenCount()>0) {
            $item['children'] = array();
        }
        $isParent = $this->_isParentSelectedCategory($node);
        if ($node->hasChildren()) {
            $item['children'] = array();
            if (!($this->getUseAjax() && $node->getLevel() > 1 && !$isParent)) {
                foreach ($node->getChildren() as $child) {
                    $item['children'][] = $this->_getNodeJson($child, $level+1);
                }
            }
        }
        if ($isParent || $node->getLevel() < 1) {
            $item['expanded'] = true;
        }
        return $item;
    }

    /**
     * Get node label
     *
     * @access public
     * @param Varien_Object $node
     * @return string
     * @author
     */
    public function buildNodeName($node)
    {
        $result = $this->escapeHtml($node->getName());
        return $result;
    }

    /**
     * check if entity is movable
     *
     * @access protected
     * @param Varien_Object $node
     * @return bool
     * @author
     */
    protected function _isCategoryMoveable($node)
    {
        return true;
    }

    /**
     * check if parent is selected
     *
     * @access protected
     * @param Varien_Object $node
     * @return bool
     * @author
     */
    protected function _isParentSelectedCategory($node)
    {
        if ($node && $this->getCategory()) {
            $pathIds = $this->getCategory()->getPathIds();
            if (in_array($node->getId(), $pathIds)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if page loaded by outside link to category edit
     *
     * @access public
     * @return boolean
     * @author
     */
    public function isClearEdit()
    {
        return (bool) $this->getRequest()->getParam('clear');
    }

    /**
     * Check availability of adding root category
     *
     * @access public
     * @return boolean
     * @author
     */
    public function canAddRootCategory()
    {
        return true;
    }

    /**
     * Check availability of adding child category
     *
     * @access public
     * @return boolean
     */
    public function canAddChild()
    {
        return true;
    }
}
