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
 * Kb setup
 *
 * @category    Mageplaza
 * @package     Mageplaza_Kb
 * @author
 */
class Mageplaza_Kb_Model_Resource_Setup extends Mage_Catalog_Model_Resource_Setup
{

    /**
     * get the default entities for kb module - used at installation
     *
     * @access public
     * @return array()
     * @author
     */
    public function getDefaultEntities()
    {
        $entities = array();
        $entities['mageplaza_kb_article'] = array(
            'entity_model'                  => 'mageplaza_kb/article',
            'attribute_model'               => 'mageplaza_kb/resource_eav_attribute',
            'table'                         => 'mageplaza_kb/article',
            'additional_attribute_table'    => 'mageplaza_kb/eav_attribute',
            'entity_attribute_collection'   => 'mageplaza_kb/article_attribute_collection',
            'attributes'                    => array(
                    'name' => array(
                        'group'          => 'General',
                        'type'           => 'varchar',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Name',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '1',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '10',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'content' => array(
                        'group'          => 'General',
                        'type'           => 'text',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Content',
                        'input'          => 'textarea',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '0',
                        'user_defined'   => true,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '20',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '1',
                    ),
                    'total_vote' => array(
                        'group'          => 'General',
                        'type'           => 'int',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Total Votes',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '0',
                        'user_defined'   => true,
                        'default'        => '0',
                        'unique'         => false,
                        'position'       => '30',
                        'note'           => 'Number of votes',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'avg_rating' => array(
                        'group'          => 'General',
                        'type'           => 'decimal',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Average Rating',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '0',
                        'user_defined'   => true,
                        'default'        => '5',
                        'unique'         => false,
                        'position'       => '40',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'status' => array(
                        'group'          => 'General',
                        'type'           => 'int',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Enabled',
                        'input'          => 'select',
                        'source'         => 'eav/entity_attribute_source_boolean',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '1',
                        'unique'         => false,
                        'position'       => '50',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'url_key' => array(
                        'group'          => 'General',
                        'type'           => 'varchar',
                        'backend'        => 'mageplaza_kb/article_attribute_backend_urlkey',
                        'frontend'       => '',
                        'label'          => 'URL key',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '60',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'in_rss' => array(
                        'group'          => 'General',
                        'type'           => 'int',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'In RSS',
                        'input'          => 'select',
                        'source'         => 'eav/entity_attribute_source_boolean',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '1',
                        'unique'         => false,
                        'position'       => '70',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'meta_title' => array(
                        'group'          => 'General',
                        'type'           => 'varchar',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Meta title',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '80',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'meta_keywords' => array(
                        'group'          => 'General',
                        'type'           => 'text',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Meta keywords',
                        'input'          => 'textarea',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '90',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'meta_description' => array(
                        'group'          => 'General',
                        'type'           => 'text',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Meta description',
                        'input'          => 'textarea',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '100',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'allow_comment' => array(
                        'group'          => 'General',
                        'type'           => 'int',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Allow Comment',
                        'input'          => 'select',
                        'source'         => 'mageplaza_kb/adminhtml_source_yesnodefault',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '2',
                        'unique'         => false,
                        'position'       => '110',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),

                )
         );
        return $entities;
    }
}
