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
 * Tag model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Tag extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'mageplaza_kb_tag';
    const CACHE_TAG = 'mageplaza_kb_tag';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mageplaza_kb_tag';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'tag';
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
        $this->_init('mageplaza_kb/tag');
    }

    /**
     * before save tag
     *
     * @access protected
     * @return Mageplaza_Kb_Model_Tag
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
     * get the url to the tag details page
     *
     * @access public
     * @return string
     * @author
     */
    public function getTagUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('mageplaza_kb/tag/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('mageplaza_kb/tag/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('mageplaza_kb/tag/view', array('id'=>$this->getId()));
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
     * save tag relation
     *
     * @access public
     * @return Mageplaza_Kb_Model_Tag
     * @author
     */
    protected function _afterSave()
    {
        $this->getArticleInstance()->saveTagRelation($this);
        return parent::_afterSave();
    }

    /**
     * get article relation model
     *
     * @access public
     * @return Mageplaza_Kb_Model_Tag_Article
     * @author
     */
    public function getArticleInstance()
    {
        if (!$this->_articleInstance) {
            $this->_articleInstance = Mage::getSingleton('mageplaza_kb/tag_article');
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
     * @return Mageplaza_Kb_Model_Tag_Article_Collection
     * @author
     */
    public function getSelectedArticlesCollection()
    {
        $collection = $this->getArticleInstance()->getArticlesCollection($this);
        return $collection;
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
