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
 * Article - product controller
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class Mageplaza_Kb_Adminhtml_Kb_Article_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * construct
     *
     * @access protected
     * @return void
     * @author
     */
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('Mageplaza_Kb');
    }

    /**
     * articles in the catalog page
     *
     * @access public
     * @return void
     * @author
     */
    public function articlesAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.article')
            ->setProductArticles($this->getRequest()->getPost('product_articles', null));
        $this->renderLayout();
    }

    /**
     * articles grid in the catalog page
     *
     * @access public
     * @return void
     * @author
     */
    public function articlesGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.article')
            ->setProductArticles($this->getRequest()->getPost('product_articles', null));
        $this->renderLayout();
    }
}
