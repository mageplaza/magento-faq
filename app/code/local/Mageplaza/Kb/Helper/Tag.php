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
 * Tag helper
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Helper_Tag extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the tags list page
     *
     * @access public
     * @return string
     * @author
     */
    public function getTagsUrl()
    {
        if ($listKey = Mage::getStoreConfig('mageplaza_kb/tag/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('mageplaza_kb/tag/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('mageplaza_kb/tag/breadcrumbs');
    }

    /**
     * check if the rss for tag is enabled
     *
     * @access public
     * @return bool
     * @author
     */
    public function isRssEnabled()
    {
        return  Mage::getStoreConfigFlag('rss/config/active') &&
            Mage::getStoreConfigFlag('mageplaza_kb/tag/rss');
    }

    /**
     * get the link to the tag rss list
     *
     * @access public
     * @return string
     * @author
     */
    public function getRssUrl()
    {
        return Mage::getUrl('mageplaza_kb/tag/rss');
    }
}
