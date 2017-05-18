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
 * Tag front contrller
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_TagController extends Mage_Core_Controller_Front_Action
{

    /**
      * default action
      *
      * @access public
      * @return void
      * @author
      */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('mageplaza_kb/tag')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('mageplaza_kb')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'tags',
                    array(
                        'label' => Mage::helper('mageplaza_kb')->__('Tags'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('mageplaza_kb/tag')->getTagsUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('mageplaza_kb/tag/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('mageplaza_kb/tag/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('mageplaza_kb/tag/meta_description'));
        }
        $this->renderLayout();
    }

    /**
     * init Tag
     *
     * @access protected
     * @return Mageplaza_Kb_Model_Tag
     * @author
     */
    protected function _initTag()
    {
        $tagId   = $this->getRequest()->getParam('id', 0);
        $tag     = Mage::getModel('mageplaza_kb/tag')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($tagId);
        if (!$tag->getId()) {
            return false;
        } elseif (!$tag->getStatus()) {
            return false;
        }
        return $tag;
    }

    /**
     * view tag action
     *
     * @access public
     * @return void
     * @author
     */
    public function viewAction()
    {
        $tag = $this->_initTag();
        if (!$tag) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_tag', $tag);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('kb-tag kb-tag' . $tag->getId());
        }
        if (Mage::helper('mageplaza_kb/tag')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('mageplaza_kb')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'tags',
                    array(
                        'label' => Mage::helper('mageplaza_kb')->__('Tags'),
                        'link'  => Mage::helper('mageplaza_kb/tag')->getTagsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'tag',
                    array(
                        'label' => $tag->getName(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $tag->getTagUrl());
        }
        if ($headBlock) {
            if ($tag->getMetaTitle()) {
                $headBlock->setTitle($tag->getMetaTitle());
            } else {
                $headBlock->setTitle($tag->getName());
            }
            $headBlock->setKeywords($tag->getMetaKeywords());
            $headBlock->setDescription($tag->getMetaDescription());
        }
        $this->renderLayout();
    }

    /**
     * tags rss list action
     *
     * @access public
     * @return void
     * @author
     */
    public function rssAction()
    {
        if (Mage::helper('mageplaza_kb/tag')->isRssEnabled()) {
            $this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
            $this->loadLayout(false);
            $this->renderLayout();
        } else {
            $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            $this->getResponse()->setHeader('Status', '404 File not found');
            $this->_forward('nofeed', 'index', 'rss');
        }
    }
}
