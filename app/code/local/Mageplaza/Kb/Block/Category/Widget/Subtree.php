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
 * Category subtree block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Category_Widget_Subtree extends Mageplaza_Kb_Block_Category_List implements
    Mage_Widget_Block_Interface
{
    protected $_template = 'mageplaza_kb/category/widget/subtree.phtml';
    /**
     * prepare the layout
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Category_Widget_Subtree
     * @author
     */
    protected function _prepareLayout()
    {
        $this->getCategorys()->addFieldToFilter('entity_id', $this->getCategoryId());
        return $this;
    }

    /**
     * get the display mode
     *
     * @access protected
     * @return int
     * @author
     */
    protected function _getDisplayMode()
    {
        return 1;
    }

    /**
     * get the element id
     *
     * @access protected
     * @return int
     * @author
     */
    public function getUniqueId()
    {
        if (!$this->getData('uniq_id')) {
            $this->setData('uniq_id', uniqid('subtree'));
        }
        return $this->getData('uniq_id');
    }
}
