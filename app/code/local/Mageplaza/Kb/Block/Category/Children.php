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
 * Category children list block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Category_Children extends Mageplaza_Kb_Block_Category_List
{
    /**
     * prepare the layout
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Category_Children
     * @author
     */
    protected function _prepareLayout()
    {
        $this->getCategories()->addFieldToFilter('parent_id', $this->getCurrentCategory()->getId());
        return $this;
    }

    /**
     * get the current category
     *
     * @access protected
     * @return Mageplaza_Kb_Model_Category
     * @author
     */
    public function getCurrentCategory()
    {
        return Mage::registry('current_category');
    }
}
