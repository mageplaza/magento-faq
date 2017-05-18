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
 * Article model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Article extends Mage_Catalog_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'mageplaza_kb_article';
    const CACHE_TAG = 'mageplaza_kb_article';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_kb_article';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'article';
    protected $_categoryInstance = null;
    protected $_tagInstance = null;
    protected $_productInstance = null;

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
        $this->_init('mageplaza_kb/article');
    }

    /**
     * before save article
     *
     * @access protected
     * @return Mageplaza_Kb_Model_Article
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
     * get the url to the article details page
     *
     * @access public
     * @return string
     * @author
     */
    public function getArticleUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('mageplaza_kb/article/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('mageplaza_kb/article/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('mageplaza_kb/article/view', array('id'=>$this->getId()));
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
     * get the article Content
     *
     * @access public
     * @return string
     * @author
     */
    public function getContent()
    {
        $content = $this->getData('content');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($content);
        return $html;
    }

    /**
     * save article relation
     *
     * @access public
     * @return Mageplaza_Kb_Model_Article
     * @author
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->saveArticleRelation($this);
        $this->getCategoryInstance()->saveArticleRelation($this);
        $this->getTagInstance()->saveArticleRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return Mageplaza_Kb_Model_Article_Product
     * @author
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('mageplaza_kb/article_product');
        }
        return $this->_productInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author
     */
    public function getSelectedProducts()
    {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return Mageplaza_Kb_Resource_Article_Product_Collection
     * @author
     */
    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * get category relation model
     *
     * @access public
     * @return Mageplaza_Kb_Model_Article_Category
     * @author
     */
    public function getCategoryInstance()
    {
        if (!$this->_categoryInstance) {
            $this->_categoryInstance = Mage::getSingleton('mageplaza_kb/article_category');
        }
        return $this->_categoryInstance;
    }

    /**
     * get selected  array
     *
     * @access public
     * @return array
     * @author
     */
    public function getSelectedCategorys()
    {
        if (!$this->hasSelectedCategorys()) {
            $categorys = array();
            foreach ($this->getSelectedCategorysCollection() as $category) {
                $categorys[] = $category;
            }
            $this->setSelectedCategorys($categorys);
        }
        return $this->getData('selected_categorys');
    }

    /**
     * Retrieve collection selected 
     *
     * @access public
     * @return Mageplaza_Kb_Model_Article_Category_Collection
     * @author
     */
    public function getSelectedCategorysCollection()
    {
        $collection = $this->getCategoryInstance()->getCategorysCollection($this);
        return $collection;
    }

    /**
     * get tag relation model
     *
     * @access public
     * @return Mageplaza_Kb_Model_Article_Tag
     * @author
     */
    public function getTagInstance()
    {
        if (!$this->_tagInstance) {
            $this->_tagInstance = Mage::getSingleton('mageplaza_kb/article_tag');
        }
        return $this->_tagInstance;
    }

    /**
     * get selected  array
     *
     * @access public
     * @return array
     * @author
     */
    public function getSelectedTags()
    {
        if (!$this->hasSelectedTags()) {
            $tags = array();
            foreach ($this->getSelectedTagsCollection() as $tag) {
                $tags[] = $tag;
            }
            $this->setSelectedTags($tags);
        }
        return $this->getData('selected_tags');
    }

    /**
     * Retrieve collection selected 
     *
     * @access public
     * @return Mageplaza_Kb_Model_Article_Tag_Collection
     * @author
     */
    public function getSelectedTagsCollection()
    {
        $collection = $this->getTagInstance()->getTagsCollection($this);
        return $collection;
    }

    /**
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * get attribute text value
     *
     * @access public
     * @param $attributeCode
     * @return string
     * @author
     */
    public function getAttributeText($attributeCode)
    {
        $text = $this->getResource()
            ->getAttribute($attributeCode)
            ->getSource()
            ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
    }

    /**
     * check if comments are allowed
     *
     * @access public
     * @return array
     * @author
     */
    public function getAllowComments()
    {
        if ($this->getData('allow_comment') == Mageplaza_Kb_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == Mageplaza_Kb_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('mageplaza_kb/article/allow_comment');
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
        $values['allow_comment'] = Mageplaza_Kb_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }
    
}
