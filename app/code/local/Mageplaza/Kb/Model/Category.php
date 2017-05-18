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
 * Category model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Category extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'mageplaza_kb_category';
    const CACHE_TAG = 'mageplaza_kb_category';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_kb_category';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'category';
    protected $_articleInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('mageplaza_kb/category');
    }

    /**
     * before save category
     *
     * @access protected
     * @return Mageplaza_Kb_Model_Category
     * @author
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the category details page
     *
     * @access public
     * @return string
     * @author
     */
    public function getCategoryUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('mageplaza_kb/category/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('mageplaza_kb/category/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('mageplaza_kb/category/view', array('id'=>$this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author
     */
    public function checkUrlKey($urlKey, $active = true)
    {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * save category relation
     *
     * @access public
     * @return Mageplaza_Kb_Model_Category
     * @author
     */
    protected function _afterSave()
    {
        $this->getArticleInstance()->saveCategoryRelation($this);
        return parent::_afterSave();
    }

    /**
     * get article relation model
     *
     * @access public
     * @return Mageplaza_Kb_Model_Category_Article
     * @author
     */
    public function getArticleInstance()
    {
        if (!$this->_articleInstance) {
            $this->_articleInstance = Mage::getSingleton('mageplaza_kb/category_article');
        }
        return $this->_articleInstance;
    }

    /**
     * get selected  array
     *
     * @access public
     * @return array
     * @author
     */
    public function getSelectedArticles()
    {
        if (!$this->hasSelectedArticles()) {
            $articles = array();
            foreach ($this->getSelectedArticlesCollection() as $article) {
                $articles[] = $article;
            }
            $this->setSelectedArticles($articles);
        }
        return $this->getData('selected_articles');
    }

    /**
     * Retrieve collection selected 
     *
     * @access public
     * @return Mageplaza_Kb_Model_Category_Article_Collection
     * @author
     */
    public function getSelectedArticlesCollection()
    {
        $collection = $this->getArticleInstance()->getArticlesCollection($this);
        return $collection;
    }

    /**
     * get the tree model
     *
     * @access public
     * @return Mageplaza_Kb_Model_Resource_Category_Tree
     * @author
     */
    public function getTreeModel()
    {
        return Mage::getResourceModel('mageplaza_kb/category_tree');
    }

    /**
     * get tree model instance
     *
     * @access public
     * @return Mageplaza_Kb_Model_Resource_Category_Tree
     * @author
     */
    public function getTreeModelInstance()
    {
        if (is_null($this->_treeModel)) {
            $this->_treeModel = Mage::getResourceSingleton('mageplaza_kb/category_tree');
        }
        return $this->_treeModel;
    }

    /**
     * Move category
     *
     * @access public
     * @param   int $parentId new parent category id
     * @param   int $afterCategoryId category id after which we have put current category
     * @return  Mageplaza_Kb_Model_Category
     * @author
     */
    public function move($parentId, $afterCategoryId)
    {
        $parent = Mage::getModel('mageplaza_kb/category')->load($parentId);
        if (!$parent->getId()) {
            Mage::throwException(
                Mage::helper('mageplaza_kb')->__(
                    'Category move operation is not possible: the new parent category was not found.'
                )
            );
        }
        if (!$this->getId()) {
            Mage::throwException(
                Mage::helper('mageplaza_kb')->__(
                    'Category move operation is not possible: the current category was not found.'
                )
            );
        } elseif ($parent->getId() == $this->getId()) {
            Mage::throwException(
                Mage::helper('mageplaza_kb')->__(
                    'Category move operation is not possible: parent category is equal to child category.'
                )
            );
        }
        $this->setMovedCategoryId($this->getId());
        $eventParams = array(
            $this->_eventObject => $this,
            'parent'            => $parent,
            'category_id'     => $this->getId(),
            'prev_parent_id'    => $this->getParentId(),
            'parent_id'         => $parentId
        );
        $moveComplete = false;
        $this->_getResource()->beginTransaction();
        try {
            $this->getResource()->changeParent($this, $parent, $afterCategoryId);
            $this->_getResource()->commit();
            $this->setAffectedCategoryIds(array($this->getId(), $this->getParentId(), $parentId));
            $moveComplete = true;
        } catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }
        if ($moveComplete) {
            Mage::app()->cleanCache(array(self::CACHE_TAG));
        }
        return $this;
    }

    /**
     * Get the parent category
     *
     * @access public
     * @return  Mageplaza_Kb_Model_Category
     * @author
     */
    public function getParentCategory()
    {
        if (!$this->hasData('parent_category')) {
            $this->setData(
                'parent_category',
                Mage::getModel('mageplaza_kb/category')->load($this->getParentId())
            );
        }
        return $this->_getData('parent_category');
    }

    /**
     * Get the parent id
     *
     * @access public
     * @return  int
     * @author
     */
    public function getParentId()
    {
        $parentIds = $this->getParentIds();
        return intval(array_pop($parentIds));
    }

    /**
     * Get all parent categories ids
     *
     * @access public
     * @return array
     * @author
     */
    public function getParentIds()
    {
        return array_diff($this->getPathIds(), array($this->getId()));
    }

    /**
     * Get all categories children
     *
     * @access public
     * @param bool $asArray
     * @return mixed (array|string)
     * @author
     */
    public function getAllChildren($asArray = false)
    {
        $children = $this->getResource()->getAllChildren($this);
        if ($asArray) {
            return $children;
        } else {
            return implode(',', $children);
        }
    }

    /**
     * Get all categories children
     *
     * @access public
     * @return string
     * @author
     */
    public function getChildCategorys()
    {
        return implode(',', $this->getResource()->getChildren($this, false));
    }

    /**
     * check the id
     *
     * @access public
     * @param int $id
     * @return bool
     * @author
     */
    public function checkId($id)
    {
        return $this->_getResource()->checkId($id);
    }

    /**
     * Get array categories ids which are part of category path
     *
     * @access public
     * @return array
     * @author
     */
    public function getPathIds()
    {
        $ids = $this->getData('path_ids');
        if (is_null($ids)) {
            $ids = explode('/', $this->getPath());
            $this->setData('path_ids', $ids);
        }
        return $ids;
    }

    /**
     * Retrieve level
     *
     * @access public
     * @return int
     * @author
     */
    public function getLevel()
    {
        if (!$this->hasLevel()) {
            return count(explode('/', $this->getPath())) - 1;
        }
        return $this->getData('level');
    }

    /**
     * Verify category ids
     *
     * @access public
     * @param array $ids
     * @return bool
     * @author
     */
    public function verifyIds(array $ids)
    {
        return $this->getResource()->verifyIds($ids);
    }

    /**
     * check if category has children
     *
     * @access public
     * @return bool
     * @author
     */
    public function hasChildren()
    {
        return $this->_getResource()->getChildrenAmount($this) > 0;
    }

    /**
     * check if category can be deleted
     *
     * @access protected
     * @return Mageplaza_Kb_Model_Category
     * @author
     */
    protected function _beforeDelete()
    {
        if ($this->getResource()->isForbiddenToDelete($this->getId())) {
            Mage::throwException(Mage::helper('mageplaza_kb')->__("Can't delete root category."));
        }
        return parent::_beforeDelete();
    }

    /**
     * get the categories
     *
     * @access public
     * @param Mageplaza_Kb_Model_Category $parent
     * @param int $recursionLevel
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @author
     */
    public function getCategorys($parent, $recursionLevel = 0, $sorted=false, $asCollection=false, $toLoad=true)
    {
        return $this->getResource()->getCategorys($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
    }

    /**
     * Return parent categories of current category
     *
     * @access public
     * @return array
     * @author
     */
    public function getParentCategorys()
    {
        return $this->getResource()->getParentCategorys($this);
    }

    /**
     * Return children categories of current category
     *
     * @access public
     * @return array
     * @author
     */
    public function getChildrenCategorys()
    {
        return $this->getResource()->getChildrenCategorys($this);
    }

    /**
     * check if parents are enabled
     *
     * @access public
     * @return bool
     * @author
     */
    public function getStatusPath()
    {
        $parents = $this->getParentCategorys();
        $rootId = Mage::helper('mageplaza_kb/category')->getRootCategoryId();
        foreach ($parents as $parent) {
            if ($parent->getId() == $rootId) {
                continue;
            }
            if (!$parent->getStatus()) {
                return false;
            }
        }
        return $this->getStatus();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        $values['in_rss'] = 1;
        return $values;
    }
    
}
