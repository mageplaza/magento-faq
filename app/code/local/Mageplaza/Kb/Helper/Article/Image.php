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
 * Article image helper
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Helper_Article_Image extends Mageplaza_Kb_Helper_Image_Abstract
{
    /**
     * image placeholder
     * @var string
     */
    protected $_placeholder = 'images/placeholder/article.jpg';
    /**
     * image subdir
     * @var string
     */
    protected $_subdir      = 'article';
}
