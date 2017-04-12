<?php
/**
 * INTELLECTUAL PROPERTY OF NOBEL BIOCARE
 *
 * @var $installer Mage_Core_Model_Resource_Upgrade
 * Upgrade Version 0.1.4
 *
 * * Date: 1/11/2017
 * @category  Nobel
 * @package   Nobel_Labfinder
 * @author    Samuel Carvalho <samuel.carvalho@nobelbiocare.com>
 * @copyright 2016 Nobel Biocare
 */

$installer = $this;
$installer->startSetup();


$installer->getConnection()->addColumn($installer->getTable('nobel_labfinder/lab'),
    'salesforce_id',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'length' => 30,
        'comment' => 'SF id'
    )
);
$installer->getConnection()->addColumn($installer->getTable('nobel_labfinder/lab'),
    'customer_number',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'length' => 30,
        'comment' => 'SF Customer Number'
    )
);
$installer->getConnection()->addColumn($installer->getTable('nobel_labfinder/lab'),
    'publish',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => false,
        'length' => 1,
        'comment' => 'Is a Lab Published or not'
    )
);
$installer->getConnection()->addColumn($installer->getTable('nobel_labfinder/lab'),
    'is_lab',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => true,
        'length' => 1,
        'comment' => 'Is account a Lab'
    )
);
$installer->getConnection()->addColumn($installer->getTable('nobel_labfinder/lab'),
    'sync_request',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => true,
        'length' => 1,
        'comment' => 'lab sync pending'
    )
);
$installer->getConnection()->addColumn($installer->getTable('nobel_labfinder/lab'),
    'sync_date',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_DATETIME,
        'comment' => 'last sync date'
    )
);
$installer->getConnection()->addColumn($installer->getTable('nobel_labfinder/lab'),
    'sapid',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'length' => 30,
        'comment' => 'sap id'
    )
);


$installer->endSetup();