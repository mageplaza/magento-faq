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
 * Article RSS block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Article_Rss extends Mage_Rss_Block_Abstract
{
    /**
     * Cache tag constant for feed reviews
     *
     * @var string
     */
    const CACHE_TAG = 'block_html_kb_article_rss';

    /**
     * constructor
     *
     * @access protected
     * @return void
     * @author
     */
    protected function _construct()
    {
        $this->setCacheTags(array(self::CACHE_TAG));
        /*
         * setting cache to save the rss for 10 minutes
         */
        $this->setCacheKey('mageplaza_kb_article_rss');
        $this->setCacheLifetime(600);
    }

    /**
     * toHtml method
     *
     * @access protected
     * @return string
     * @author
     */
    protected function _toHtml()
    {
        $url    = Mage::helper('mageplaza_kb/article')->getArticlesUrl();
        $title  = Mage::helper('mageplaza_kb')->__('Articles');
        $rssObj = Mage::getModel('rss/rss');
        $data  = array(
            'title'       => $title,
            'description' => $title,
            'link'        => $url,
            'charset'     => 'UTF-8',
        );
        $rssObj->_addHeader($data);
        $collection = Mage::getModel('mageplaza_kb/article')->getCollection()
            ->addFieldToFilter('status', 1)
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('in_rss', 1)
            ->setOrder('created_at');
        $collection->load();
        foreach ($collection as $item) {
            $description = '<p>';
            $description .= '<div>'.
                Mage::helper('mageplaza_kb')->__('Name').': 
                '.$item->getName().
                '</div>';
            $description .= '<div>'.
                Mage::helper('mageplaza_kb')->__('Content').': 
                '.$item->getContent().
                '</div>';
            $description .= '<div>'.
                Mage::helper('mageplaza_kb')->__('Average Rating').': 
                '.$item->getAvgRating().
                '</div>';
            $description .= '</p>';
            $data = array(
                'title'       => $item->getName(),
                'link'        => $item->getArticleUrl(),
                'description' => $description
            );
            $rssObj->_addEntry($data);
        }
        return $rssObj->createRssXml();
    }
}
