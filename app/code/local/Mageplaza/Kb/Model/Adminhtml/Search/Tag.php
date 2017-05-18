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
class Mageplaza_Kb_Model_Adminhtml_Search_Tag extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Mageplaza_Kb_Model_Adminhtml_Search_Tag
     * @author
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('mageplaza_kb/tag_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $tag) {
            $arr[] = array(
                'id'          => 'tag/1/'.$tag->getId(),
                'type'        => Mage::helper('mageplaza_kb')->__('Tag'),
                'name'        => $tag->getName(),
                'description' => $tag->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/kb_tag/edit',
                    array('id'=>$tag->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
