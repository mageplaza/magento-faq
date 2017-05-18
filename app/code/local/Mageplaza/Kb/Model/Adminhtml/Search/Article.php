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
 * Admin search model
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Adminhtml_Search_Article extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Mageplaza_Kb_Model_Adminhtml_Search_Article
     * @author
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('mageplaza_kb/article_collection')
            ->addAttributeToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $article) {
            $arr[] = array(
                'id'          => 'article/1/'.$article->getId(),
                'type'        => Mage::helper('mageplaza_kb')->__('Article'),
                'name'        => $article->getName(),
                'description' => $article->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/kb_article/edit',
                    array('id'=>$article->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
