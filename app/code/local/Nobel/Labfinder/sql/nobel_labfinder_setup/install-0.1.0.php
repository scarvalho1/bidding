<?php
/**
 * @author Haydar Ciftci <haydar.ciftci@nobelbiocare.com>
 * @package Nobel\Labfinder
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('nobel_labfinder/lab'))
    ->addColumn('lab_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Primary key for Lab')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable' => false,
    ), 'Lab name')
    ->addColumn('address1', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Address line 1')
    ->addColumn('address2', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Address line 2')
    ->addColumn('zipcode', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(), 'ZipCode')
    ->addColumn('city', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'City')
    ->addColumn('state', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'State')
    ->addColumn('phone', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(), 'Phone')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'E-Mail')
    ->addColumn('website', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Webiste')
    ->addColumn('services', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Services')
    ->addColumn('products', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(), 'Products');

$installer->getConnection()->createTable($table);

$installer->endSetup();