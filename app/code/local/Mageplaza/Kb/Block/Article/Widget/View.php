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
 * Article widget block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Article_Widget_View extends Mage_Core_Block_Template implements
    Mage_Widget_Block_Interface
{
    protected $_htmlTemplate = 'mageplaza_kb/article/widget/view.phtml';

    /**
     * Prepare a for widget
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Article_Widget_View
     * @author
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $articleId = $this->getData('article_id');
        if ($articleId) {
            $article = Mage::getModel('mageplaza_kb/article')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($articleId);
            if ($article->getStatus()) {
                $this->setCurrentArticle($article);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
