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
 * Frontend observer
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Observer
{
    /**
     * add items to main menu
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return array()
     * @author
     */
    public function addItemsToTopmenuItems($observer)
    {
        if(Mage::getConfig('mageplaza_kb/article/on_menu')) {
            $menu = $observer->getMenu();
            $tree = $menu->getTree();
            $action = Mage::app()->getFrontController()->getAction()->getFullActionName();
            $articleNodeId = 'article';
            $data = array(
                'name' => Mage::helper('mageplaza_kb')->__('FAQ'),
                'id' => $articleNodeId,
                'url' => Mage::helper('mageplaza_kb/article')->getArticlesUrl(),
                'is_active' => ($action == 'mageplaza_kb_article_index' || $action == 'mageplaza_kb_article_view')
            );
            $articleNode = new Varien_Data_Tree_Node($data, 'id', $tree, $menu);
            $menu->addChild($articleNode);
            return $this;
        } else{
            return $this;
        }


    }
}
