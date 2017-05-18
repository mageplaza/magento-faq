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
 * Article front contrller
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_ArticleController extends Mage_Core_Controller_Front_Action
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
        if (Mage::helper('mageplaza_kb/article')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('mageplaza_kb')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'articles',
                    array(
                        'label' => Mage::helper('mageplaza_kb')->__('FAQ'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('mageplaza_kb/article')->getArticlesUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('mageplaza_kb/article/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('mageplaza_kb/article/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('mageplaza_kb/article/meta_description'));
        }
        $this->renderLayout();
    }

    /**
     * init Article
     *
     * @access protected
     * @return Mageplaza_Kb_Model_Article
     * @author
     */
    protected function _initArticle()
    {
        $articleId   = $this->getRequest()->getParam('id', 0);
        $article     = Mage::getModel('mageplaza_kb/article')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($articleId);
        if (!$article->getId()) {
            return false;
        } elseif (!$article->getStatus()) {
            return false;
        }
        return $article;
    }

    /**
     * view article action
     *
     * @access public
     * @return void
     * @author
     */
    public function viewAction()
    {
        $article = $this->_initArticle();
        if (!$article) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_article', $article);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('kb-article kb-article' . $article->getId());
        }
        if (Mage::helper('mageplaza_kb/article')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('mageplaza_kb')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'articles',
                    array(
                        'label' => Mage::helper('mageplaza_kb')->__('Articles'),
                        'link'  => Mage::helper('mageplaza_kb/article')->getArticlesUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'article',
                    array(
                        'label' => $article->getName(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $article->getArticleUrl());
        }
        if ($headBlock) {
            if ($article->getMetaTitle()) {
                $headBlock->setTitle($article->getMetaTitle());
            } else {
                $headBlock->setTitle($article->getName());
            }
            $headBlock->setKeywords($article->getMetaKeywords());
            $headBlock->setDescription($article->getMetaDescription());
        }
        $this->renderLayout();
    }

    /**
     * articles rss list action
     *
     * @access public
     * @return void
     * @author
     */
    public function rssAction()
    {
        if (Mage::helper('mageplaza_kb/article')->isRssEnabled()) {
            $this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
            $this->loadLayout(false);
            $this->renderLayout();
        } else {
            $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            $this->getResponse()->setHeader('Status', '404 File not found');
            $this->_forward('nofeed', 'index', 'rss');
        }
    }

    /**
     * Submit new comment action
     * @access public
     * @author
     */
    public function commentpostAction()
    {
        $data   = $this->getRequest()->getPost();
        $article = $this->_initArticle();
        $session    = Mage::getSingleton('core/session');
        if ($article) {
            if ($article->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() ||
                    Mage::getStoreConfigFlag('mageplaza_kb/article/allow_guest_comment'))) {
                    $comment  = Mage::getModel('mageplaza_kb/article_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setArticleId($article->getId())
                                ->setStatus(Mageplaza_Kb_Model_Article_Comment::STATUS_PENDING)
                                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                ->setStores(array(Mage::app()->getStore()->getId()))
                                ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        } catch (Exception $e) {
                            $session->setArticleCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    } else {
                        $session->setArticleCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        } else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                } else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            } else {
                $session->addError($this->__('This article does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }

    public function searchAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('mageplaza_kb/article')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('mageplaza_kb')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'articles',
                    array(
                        'label' => Mage::helper('mageplaza_kb')->__('FAQ'),
                        'link'  => '',
                    )
                );
            }
        }
        $this->renderLayout();

    }
}
