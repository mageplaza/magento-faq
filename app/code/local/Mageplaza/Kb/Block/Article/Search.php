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
class Mageplaza_Kb_Block_Article_Search extends Mageplaza_Kb_Block_Article_List
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
        $q = $this->getRequest()->getParam('q');
        $articles = Mage::getResourceModel('mageplaza_kb/article_collection')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->addAttributeToSelect('*')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'name', 'like' => '%' . $q . '%'),
                    array('attribute' => 'content', 'like' => '%' . $q . '%')
                )
            )
            ->addAttributeToFilter('status', 1);
        $articles->setOrder('name', 'asc');
        $this->setArticles($articles);
    }

    public function getQuery()
    {
        $q = $this->getRequest()->getParam('q');

        return $q;
    }


}
