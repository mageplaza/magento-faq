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
 * Article admin controller
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Adminhtml_Kb_ArticleController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Mageplaza_Kb');
    }

    /**
     * init the article
     *
     * @access protected 
     * @return Mageplaza_Kb_Model_Article
     * @author
     */
    protected function _initArticle()
    {
        $this->_title($this->__('Knowledge Base'))
             ->_title($this->__('Manage Articles'));

        $articleId  = (int) $this->getRequest()->getParam('id');
        $article    = Mage::getModel('mageplaza_kb/article')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($articleId) {
            $article->load($articleId);
        }
        Mage::register('current_article', $article);
        return $article;
    }

    /**
     * default action for article controller
     *
     * @access public
     * @return void
     * @author
     */
    public function indexAction()
    {
        $this->_title($this->__('Knowledge Base'))
             ->_title($this->__('Manage Articles'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new article action
     *
     * @access public
     * @return void
     * @author
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * edit article action
     *
     * @access public
     * @return void
     * @author
     */
    public function editAction()
    {
        $articleId  = (int) $this->getRequest()->getParam('id');
        $article    = $this->_initArticle();
        if ($articleId && !$article->getId()) {
            $this->_getSession()->addError(
                Mage::helper('mageplaza_kb')->__('This article no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getArticleData(true)) {
            $article->setData($data);
        }
        $this->_title($article->getName());
        Mage::dispatchEvent(
            'mageplaza_kb_article_edit_action',
            array('article' => $article)
        );
        $this->loadLayout();
        if ($article->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('mageplaza_kb')->__('Default Values'))
                    ->setWebsiteIds($article->getWebsiteIds())
                    ->setSwitchUrl(
                        $this->getUrl(
                            '*/*/*',
                            array(
                                '_current'=>true,
                                'active_tab'=>null,
                                'tab' => null,
                                'store'=>null
                            )
                        )
                    );
            }
        } else {
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * save article action
     *
     * @access public
     * @return void
     * @author
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $articleId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $article     = $this->_initArticle();
            $articleData = $this->getRequest()->getPost('article', array());
            $article->addData($articleData);
            $article->setAttributeSetId($article->getDefaultAttributeSetId());
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $article->setProductsData(
                        Mage::helper('adminhtml/js')->decodeGridSerializedInput($products)
                    );
                }
            if (isset($data['tags'])) {
                $article->setTagsData(
                    Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['tags'])
                );
            }
                $categorys = $this->getRequest()->getPost('category_ids', -1);
                if ($categorys != -1) {
                    $categorys = explode(',', $categorys);
                    $categorys = array_unique($categorys);
                    $article->setCategorysData($categorys);
                }
            if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $article->setData($attributeCode, false);
                }
            }
            try {
                $article->save();
                $articleId = $article->getId();
                $this->_getSession()->addSuccess(
                    Mage::helper('mageplaza_kb')->__('Article was saved')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage())
                    ->setArticleData($articleData);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(
                    Mage::helper('mageplaza_kb')->__('Error saving article')
                )
                ->setArticleData($articleData);
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $articleId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    /**
     * delete article
     *
     * @access public
     * @return void
     * @author
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $article = Mage::getModel('mageplaza_kb/article')->load($id);
            try {
                $article->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('mageplaza_kb')->__('The articles has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store')))
        );
    }

    /**
     * mass delete articles
     *
     * @access public
     * @return void
     * @author
     */
    public function massDeleteAction()
    {
        $articleIds = $this->getRequest()->getParam('article');
        if (!is_array($articleIds)) {
            $this->_getSession()->addError($this->__('Please select articles.'));
        } else {
            try {
                foreach ($articleIds as $articleId) {
                    $article = Mage::getSingleton('mageplaza_kb/article')->load($articleId);
                    Mage::dispatchEvent(
                        'mageplaza_kb_controller_article_delete',
                        array('article' => $article)
                    );
                    $article->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('mageplaza_kb')->__('Total of %d record(s) have been deleted.', count($articleIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author
     */
    public function massStatusAction()
    {
        $articleIds = $this->getRequest()->getParam('article');
        if (!is_array($articleIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mageplaza_kb')->__('Please select articles.')
            );
        } else {
            try {
                foreach ($articleIds as $articleId) {
                $article = Mage::getSingleton('mageplaza_kb/article')->load($articleId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d articles were successfully updated.', count($articleIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mageplaza_kb')->__('There was an error updating articles.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * restrict access
     *
     * @access protected
     * @return bool
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     * @author
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('mageplaza_kb/article');
    }

    /**
     * Export articles in CSV format
     *
     * @access public
     * @return void
     * @author
     */
    public function exportCsvAction()
    {
        $fileName   = 'articles.csv';
        $content    = $this->getLayout()->createBlock('mageplaza_kb/adminhtml_article_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export articles in Excel format
     *
     * @access public
     * @return void
     * @author
     */
    public function exportExcelAction()
    {
        $fileName   = 'article.xls';
        $content    = $this->getLayout()->createBlock('mageplaza_kb/adminhtml_article_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export articles in XML format
     *
     * @access public
     * @return void
     * @author
     */
    public function exportXmlAction()
    {
        $fileName   = 'article.xml';
        $content    = $this->getLayout()->createBlock('mageplaza_kb/adminhtml_article_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * wysiwyg editor action
     *
     * @access public
     * @return void
     * @author
     */
    public function wysiwygAction()
    {
        $elementId     = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId       = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'mageplaza_kb/adminhtml_kb_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author
     */
    public function productsAction()
    {
        $this->_initArticle();
        $this->loadLayout();
        $this->getLayout()->getBlock('article.edit.tab.product')
            ->setArticleProducts($this->getRequest()->getPost('article_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author
     */
    public function productsgridAction()
    {
        $this->_initArticle();
        $this->loadLayout();
        $this->getLayout()->getBlock('article.edit.tab.product')
            ->setArticleProducts($this->getRequest()->getPost('article_products', null));
        $this->renderLayout();
    }

    /**
     *  on the current article
     *
     * @access public
     * @return void
     * @author
     */
    public function tagsAction()
    {
        $this->_initArticle();
        $this->loadLayout();
        $this->getLayout()->getBlock('mageplaza_kb.article.edit.tab.tag')
            ->setArticleTags($this->getRequest()->getPost('tags', null));
        $this->renderLayout();
    }

    /**
     *  on the current article
     *
     * @access public
     * @return void
     * @author
     */
    public function tagsGridAction()
    {
        $this->_initArticle();
        $this->loadLayout();
        $this->getLayout()->getBlock('mageplaza_kb.article.edit.tab.tag')
            ->setArticleTags($this->getRequest()->getPost('tags', null));
        $this->renderLayout();
    }
    /**
     * get  action
     *
     * @access public
     * @return void
     * @author
     */
    public function categorysAction()
    {
        $this->_initArticle();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * get child   action
     *
     * @access public
     * @return void
     * @author
     */
    public function categorysJsonAction()
    {
        $this->_initArticle();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mageplaza_kb/adminhtml_article_edit_tab_category')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }
}
