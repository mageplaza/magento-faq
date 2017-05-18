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
 * Tag admin controller
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Adminhtml_Kb_TagController extends Mageplaza_Kb_Controller_Adminhtml_Kb
{
    /**
     * init the tag
     *
     * @access protected
     * @return Mageplaza_Kb_Model_Tag
     */
    protected function _initTag()
    {
        $tagId  = (int) $this->getRequest()->getParam('id');
        $tag    = Mage::getModel('mageplaza_kb/tag');
        if ($tagId) {
            $tag->load($tagId);
        }
        Mage::register('current_tag', $tag);
        return $tag;
    }

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
        $this->_title(Mage::helper('mageplaza_kb')->__('Knowledge Base'))
             ->_title(Mage::helper('mageplaza_kb')->__('Tags'));
        $this->renderLayout();
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
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit tag - action
     *
     * @access public
     * @return void
     * @author
     */
    public function editAction()
    {
        $tagId    = $this->getRequest()->getParam('id');
        $tag      = $this->_initTag();
        if ($tagId && !$tag->getId()) {
            $this->_getSession()->addError(
                Mage::helper('mageplaza_kb')->__('This tag no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getTagData(true);
        if (!empty($data)) {
            $tag->setData($data);
        }
        Mage::register('tag_data', $tag);
        $this->loadLayout();
        $this->_title(Mage::helper('mageplaza_kb')->__('Knowledge Base'))
             ->_title(Mage::helper('mageplaza_kb')->__('Tags'));
        if ($tag->getId()) {
            $this->_title($tag->getName());
        } else {
            $this->_title(Mage::helper('mageplaza_kb')->__('Add tag'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new tag action
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
     * save tag - action
     *
     * @access public
     * @return void
     * @author
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('tag')) {
            try {
                $tag = $this->_initTag();
                $tag->addData($data);
                $articles = $this->getRequest()->getPost('articles', -1);
                if ($articles != -1) {
                    $tag->setArticlesData(
                        Mage::helper('adminhtml/js')->decodeGridSerializedInput($articles)
                    );
                }
                $tag->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mageplaza_kb')->__('Tag was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $tag->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setTagData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mageplaza_kb')->__('There was a problem saving the tag.')
                );
                Mage::getSingleton('adminhtml/session')->setTagData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mageplaza_kb')->__('Unable to find tag to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete tag - action
     *
     * @access public
     * @return void
     * @author
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $tag = Mage::getModel('mageplaza_kb/tag');
                $tag->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mageplaza_kb')->__('Tag was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mageplaza_kb')->__('There was an error deleting tag.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mageplaza_kb')->__('Could not find tag to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete tag - action
     *
     * @access public
     * @return void
     * @author
     */
    public function massDeleteAction()
    {
        $tagIds = $this->getRequest()->getParam('tag');
        if (!is_array($tagIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mageplaza_kb')->__('Please select tags to delete.')
            );
        } else {
            try {
                foreach ($tagIds as $tagId) {
                    $tag = Mage::getModel('mageplaza_kb/tag');
                    $tag->setId($tagId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mageplaza_kb')->__('Total of %d tags were successfully deleted.', count($tagIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mageplaza_kb')->__('There was an error deleting tags.')
                );
                Mage::logException($e);
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
        $tagIds = $this->getRequest()->getParam('tag');
        if (!is_array($tagIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mageplaza_kb')->__('Please select tags.')
            );
        } else {
            try {
                foreach ($tagIds as $tagId) {
                $tag = Mage::getSingleton('mageplaza_kb/tag')->load($tagId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d tags were successfully updated.', count($tagIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mageplaza_kb')->__('There was an error updating tags.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get  action
     *
     * @access public
     * @return void
     * @author
     */
    public function articlesAction()
    {
        $this->_initTag();
        $this->loadLayout();
        $this->getLayout()->getBlock('mageplaza_kb.tag.edit.tab.article')
            ->setTagArticles($this->getRequest()->getPost('tag_articles', null));
        $this->renderLayout();
    }

    /**
     * get  grid  action
     *
     * @access public
     * @return void
     * @author
     */
    public function articlesgridAction()
    {
        $this->_initTag();
        $this->loadLayout();
        $this->getLayout()->getBlock('mageplaza_kb.tag.edit.tab.article')
            ->setTagArticles($this->getRequest()->getPost('tag_articles', null));
        $this->renderLayout();
    }
    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author
     */
    public function exportCsvAction()
    {
        $fileName   = 'tag.csv';
        $content    = $this->getLayout()->createBlock('mageplaza_kb/adminhtml_tag_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author
     */
    public function exportExcelAction()
    {
        $fileName   = 'tag.xls';
        $content    = $this->getLayout()->createBlock('mageplaza_kb/adminhtml_tag_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author
     */
    public function exportXmlAction()
    {
        $fileName   = 'tag.xml';
        $content    = $this->getLayout()->createBlock('mageplaza_kb/adminhtml_tag_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('mageplaza_kb/tag');
    }
}
