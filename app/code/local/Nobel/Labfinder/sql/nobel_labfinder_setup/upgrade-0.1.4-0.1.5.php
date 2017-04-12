<?php

/** @var Mage_Catalog_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->truncateTable($installer->getTable('nobel_labfinder/lab'));
$installer->getConnection()->truncateTable($installer->getTable('nobel_labfinder/services'));
$installer->getConnection()->truncateTable($installer->getTable('nobel_labfinder/products'));


$installer->getConnection()->addIndex(
    $installer->getTable('nobel_labfinder/services'),
    $installer->getIdxName($installer->getTable('nobel_labfinder/services'), 'lab_id', Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
    'lab_id',
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);


$installer->getConnection()->addIndex(
    $installer->getTable('nobel_labfinder/products'),
    $installer->getIdxName($installer->getTable('nobel_labfinder/products'), 'lab_id', Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
    'lab_id',
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->endSetup();