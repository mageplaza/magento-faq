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
 * Article helper
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Helper_Article extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the articles list page
     *
     * @access public
     * @return string
     * @author
     */
    public function getArticlesUrl()
    {
        if ($listKey = Mage::getStoreConfig('mageplaza_kb/category/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('mageplaza_kb/category/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('mageplaza_kb/article/breadcrumbs');
    }

    /**
     * check if the rss for article is enabled
     *
     * @access public
     * @return bool
     * @author
     */
    public function isRssEnabled()
    {
        return  Mage::getStoreConfigFlag('rss/config/active') &&
            Mage::getStoreConfigFlag('mageplaza_kb/article/rss');
    }

    /**
     * get the link to the article rss list
     *
     * @access public
     * @return string
     * @author
     */
    public function getRssUrl()
    {
        return Mage::getUrl('mageplaza_kb/article/rss');
    }

    /**
     * get base files dir
     *
     * @access public
     * @return string
     * @author
     */
    public function getFileBaseDir()
    {
        return Mage::getBaseDir('media').DS.'article'.DS.'file';
    }

    /**
     * get base file url
     *
     * @access public
     * @return string
     * @author
     */
    public function getFileBaseUrl()
    {
        return Mage::getBaseUrl('media').'article'.'/'.'file';
    }

    /**
     * get article attribute source model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author
     */
     public function getAttributeSourceModelByInputType($inputType)
     {
         $inputTypes = $this->getAttributeInputTypes();
         if (!empty($inputTypes[$inputType]['source_model'])) {
             return $inputTypes[$inputType]['source_model'];
         }
         return null;
     }

    /**
     * get attribute input types
     *
     * @access public
     * @param string $inputType
     * @return array()
     * @author
     */
    public function getAttributeInputTypes($inputType = null)
    {
        $inputTypes = array(
            'multiselect' => array(
                'backend_model' => 'eav/entity_attribute_backend_array'
            ),
            'boolean'     => array(
                'source_model'  => 'eav/entity_attribute_source_boolean'
            ),
            'file'          => array(
                'backend_model' => 'mageplaza_kb/article_attribute_backend_file'
            ),
            'image'          => array(
                'backend_model' => 'mageplaza_kb/article_attribute_backend_image'
            ),
        );

        if (is_null($inputType)) {
            return $inputTypes;
        } else if (isset($inputTypes[$inputType])) {
            return $inputTypes[$inputType];
        }
        return array();
    }

    /**
     * get article attribute backend model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author
     */
    public function getAttributeBackendModelByInputType($inputType)
    {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }

    /**
     * filter attribute content
     *
     * @access public
     * @param Mageplaza_Kb_Model_Article $article
     * @param string $attributeHtml
     * @param string @attributeName
     * @return string
     * @author
     */
    public function articleAttribute($article, $attributeHtml, $attributeName)
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute(
            Mageplaza_Kb_Model_Article::ENTITY,
            $attributeName
        );
        if ($attribute && $attribute->getId() && !$attribute->getIsWysiwygEnabled()) {
            if ($attribute->getFrontendInput() == 'textarea') {
                $attributeHtml = nl2br($attributeHtml);
            }
        }
        if ($attribute->getIsWysiwygEnabled()) {
            $attributeHtml = $this->_getTemplateProcessor()->filter($attributeHtml);
        }
        return $attributeHtml;
    }

    /**
     * get the template processor
     *
     * @access protected
     * @return Mage_Catalog_Model_Template_Filter
     * @author
     */
    protected function _getTemplateProcessor()
    {
        if (null === $this->_templateProcessor) {
            $this->_templateProcessor = Mage::helper('catalog')->getPageTemplateProcessor();
        }
        return $this->_templateProcessor;
    }
}
