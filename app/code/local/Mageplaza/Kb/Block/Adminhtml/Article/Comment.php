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
 * Article comments admin block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Adminhtml_Article_Comment extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_article_comment';
        $this->_blockGroup = 'mageplaza_kb';
        parent::__construct();
        $this->_headerText = Mage::helper('mageplaza_kb')->__('Article Comments');
        $this->_removeButton('add');
    }
}
