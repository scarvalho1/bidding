<?php
/**
 * Created by PhpStorm.
 * User: ussaca
 * Date: 4/10/17
 * Time: 8:29 PM
 */


/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('nobel_bidding/bid'))
    ->addColumn('bid_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Primary key for bid')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'bid type')
    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(),'bid amount')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'userid')
    ->addColumn('username', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'username')
    ->addColumn('productID', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'product bidding on')
//    ->addColumn('time',    Varien_Db_Ddl_Table::TYPE_DATE,null, null)
;

$installer->getConnection()->createTable($table);

$installer->endSetup();