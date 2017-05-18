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
 * Tag list block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Tag_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author
     */
    public function _construct()
    {
        parent::_construct();
        $tags = Mage::getResourceModel('mageplaza_kb/tag_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        $tags->setOrder('name', 'asc');
        $this->setTags($tags);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Tag_List
     * @author
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'mageplaza_kb.tag.html.pager'
        )
        ->setCollection($this->getTags());
        $this->setChild('pager', $pager);
        $this->getTags()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
