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
 * Router
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    /**
     * init routes
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Mageplaza_Kb_Controller_Router
     * @author
     */
    public function initControllerRouters($observer)
    {
        $front = $observer->getEvent()->getFront();
        $front->addRouter('mageplaza_kb', $this);
        return $this;
    }

    /**
     * Validate and match entities and modify request
     *
     * @access public
     * @param Zend_Controller_Request_Http $request
     * @return bool
     * @author
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
        $urlKey = trim($request->getPathInfo(), '/');
        $check = array();
        $check['article'] = new Varien_Object(
            array(
                'prefix'        => Mage::getStoreConfig('mageplaza_kb/article/url_prefix'),
                'suffix'        => Mage::getStoreConfig('mageplaza_kb/article/url_suffix'),
                'list_key'      => Mage::getStoreConfig('mageplaza_kb/article/url_rewrite_list'),
                'list_action'   => 'index',
                'model'         =>'mageplaza_kb/article',
                'controller'    => 'article',
                'action'        => 'view',
                'param'         => 'id',
                'check_path'    => 0
            )
        );
        $check['category'] = new Varien_Object(
            array(
                'prefix'        => Mage::getStoreConfig('mageplaza_kb/category/url_prefix'),
                'suffix'        => Mage::getStoreConfig('mageplaza_kb/category/url_suffix'),
                'list_key'      => Mage::getStoreConfig('mageplaza_kb/category/url_rewrite_list'),
                'list_action'   => 'index',
                'model'         =>'mageplaza_kb/category',
                'controller'    => 'category',
                'action'        => 'view',
                'param'         => 'id',
                'check_path'    => 1
            )
        );
        $check['tag'] = new Varien_Object(
            array(
                'prefix'        => Mage::getStoreConfig('mageplaza_kb/tag/url_prefix'),
                'suffix'        => Mage::getStoreConfig('mageplaza_kb/tag/url_suffix'),
                'list_key'      => Mage::getStoreConfig('mageplaza_kb/tag/url_rewrite_list'),
                'list_action'   => 'index',
                'model'         =>'mageplaza_kb/tag',
                'controller'    => 'tag',
                'action'        => 'view',
                'param'         => 'id',
                'check_path'    => 0
            )
        );
        foreach ($check as $key=>$settings) {
            if ($settings->getListKey()) {
                if ($urlKey == $settings->getListKey()) {
                    $request->setModuleName('kb')
                        ->setControllerName($settings->getController())
                        ->setActionName($settings->getListAction());
                    $request->setAlias(
                        Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                        $urlKey
                    );
                    return true;
                }
            }
            if ($settings['prefix']) {
                $parts = explode('/', $urlKey);
                if ($parts[0] != $settings['prefix'] || count($parts) != 2) {
                    continue;
                }
                $urlKey = $parts[1];
            }
            if ($settings['suffix']) {
                $urlKey = substr($urlKey, 0, -strlen($settings['suffix']) - 1);
            }
            $model = Mage::getModel($settings->getModel());
            $id = $model->checkUrlKey($urlKey, Mage::app()->getStore()->getId());
            if ($id) {
                if ($settings->getCheckPath() && !$model->load($id)->getStatusPath()) {
                    continue;
                }
                $request->setModuleName('kb')
                    ->setControllerName($settings->getController())
                    ->setActionName($settings->getAction())
                    ->setParam($settings->getParam(), $id);
                $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    $urlKey
                );
                return true;
            }
        }
        return false;
    }
}
