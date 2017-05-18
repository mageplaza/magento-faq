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
 * Article list block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Article_List extends Mage_Core_Block_Template
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
        $articles = Mage::getResourceModel('mageplaza_kb/article_collection')
                         ->setStoreId(Mage::app()->getStore()->getId())
                         ->addAttributeToSelect('*')
                         ->addAttributeToFilter('status', 1);
        $articles->setOrder('name', 'asc');
        $this->setArticles($articles);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Article_List
     * @author
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'mageplaza_kb.article.html.pager'
        )
        ->setCollection($this->getArticles());
        $this->setChild('pager', $pager);
        $this->getArticles()->load();
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

    public function getFaqLimit()
    {
        $config = Mage::helper('mageplaza_kb/config');
        return 1;
        return $config->getHomeConfig('limit_faq') ? $config->getHomeConfig('limit_faq') : 5;
    }
}
