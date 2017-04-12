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

$connection->addColumn(
    $tableName,
    'country',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'length' => 255,
        'comment' => 'Country'
    )
);

$connection->addColumn(
    $tableName,
    'longitude',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'scale' => 8,
        'precision' => 11,
        'comment' => 'Longitude'
    )
);

$connection->addColumn(
    $tableName,
    'latitude',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'scale' => 8,
        'precision' => 10,
        'comment' => 'Latitude'
    )
);

$installer->endSetup();