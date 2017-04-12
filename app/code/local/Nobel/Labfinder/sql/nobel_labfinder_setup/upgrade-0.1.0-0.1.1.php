<?php
/**
 * @author Haydar Ciftci <haydar.ciftci@nobelbiocare.com>
 * @package Nobel\Labfinder
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$tableName = $installer->getTable('nobel_labfinder/lab');


$connection = $installer->getConnection();
/*
$connection->addIndex(
    $tableName,
    $installer->getIdxName($tableName, 'services', Varien_Db_Adapter_Interface::INDEX_TYPE_FULLTEXT),
    'services',
    Varien_Db_Adapter_Interface::INDEX_TYPE_FULLTEXT
);

$connection->addIndex(
    $tableName,
    $installer->getIdxName($tableName, 'products', Varien_Db_Adapter_Interface::INDEX_TYPE_FULLTEXT),
    'products',
    Varien_Db_Adapter_Interface::INDEX_TYPE_FULLTEXT
); */

$installer->endSetup();