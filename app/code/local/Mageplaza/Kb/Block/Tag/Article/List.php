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
 * Tag Articles list block
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Block_Tag_Article_List extends Mageplaza_Kb_Block_Article_List
{
    /**
     * initialize
     *
     * @access public
     * @return void
     * @author
     */
    public function __construct()
    {
        parent::__construct();
        $tag = $this->getTag();
         if ($tag) {
             $this->getArticles()->addTagFilter($tag->getId());
             $this->getArticles()->unshiftOrder('related_tag.position', 'ASC');
         }
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Mageplaza_Kb_Block_Tag_Article_List
     * @author
     */
    protected function _prepareLayout()
    {
        return $this;
    }

    /**
     * get the current tag
     *
     * @access public
     * @return Mageplaza_Kb_Model_Tag
     * @author
     */
    public function getTag()
    {
        return Mage::registry('current_tag');
    }
}
