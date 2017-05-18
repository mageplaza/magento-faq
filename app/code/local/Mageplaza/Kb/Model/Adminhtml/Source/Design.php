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
 * Admin source select design
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Adminhtml_Source_Design extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{

    /**
     * get possible values
     *
     * @access public
     * @return array
     * @author
     */
    public function toOptionArray()
    {
        return array(
            array(
                'label' => Mage::helper('mageplaza_kb')->__('Default Design'),
                'value' => 'default'
            ),

            array(
                'label' => Mage::helper('mageplaza_kb')->__('Material Design'),
                'value' => 'material'
            ),
            array(
                'label' => Mage::helper('mageplaza_kb')->__('Flat Design'),
                'value' => 'flat'
            ),


        );
    }

    /**
     * Get list of all available values
     *
     * @access public
     * @return array
     * @author
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }
}
