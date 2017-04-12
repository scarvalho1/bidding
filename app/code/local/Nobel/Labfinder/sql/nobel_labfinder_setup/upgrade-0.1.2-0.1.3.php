<?php
/**
 * INTELLECTUAL PROPERTY OF NOBEL BIOCARE
 *
 * @var $installer Mage_Core_Model_Resource_Upgrade
 * Upgrade Version 0.1.3
 *
 * * Date: 11/23/2016
 * @category  Nobel
 * @package   Nobel_Labfinder
 * @author    Samuel Carvalho <samuel.carvalho@nobelbiocare.com>
 * @copyright 2016 Nobel Biocare
 */

$installer = $this;
$installer->startSetup();

/** create new products table */
$products = $installer->getConnection()->newTable($installer->getTable('nobel_labfinder/products'))
    ->addColumn('id',
        Varien_Db_Ddl_Table::TYPE_BIGINT, null,
        array(
            'auto_increment' => true,
            'nullable' => false,
            'primary' => true
        ), 'Primary key for attribute')
    ->addColumn('lab_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned' => true,
            'unique' => true,
            'nullable' => false
        ), 'Foreign key for Lab')
    ->addColumn('crowns_and_bridges',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(), 'Crowns and bridges')
    ->addColumn('implant_crowns_and_abutments',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(), 'Implant crowns and abutments')
    ->addColumn('implant_bridges',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(), 'Implant bridges')
    ->addColumn('implant_bars',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(), 'Implant bars')
    ->addIndex(
        $installer->getIdxName(
            'nobel_labfinder/products',
            array('lab_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        ),
        "lab_id",
        array("type" => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
    );

$installer->getConnection()->createTable($products);

/** Create new services table */
$services = $installer->getConnection()->newTable($installer->getTable('nobel_labfinder/services'))
    ->addColumn('id',
        Varien_Db_Ddl_Table::TYPE_BIGINT, null,
        array(
            'auto_increment' => true,
            'nullable' => false,
            'primary' => true
        ), 'Primary key for attribute')
    ->addColumn('lab_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned' => true,
            'unique' => true,
            'nullable' => false,
        ), 'Foreign key for Lab')
    ->addColumn('nobelclinician_smartfusion',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'NobelClinician SmartFusion')
    ->addColumn('restorative_planning',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Restorative planning')
    ->addColumn('all-on-4_conversions',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'All-on-4 conversions')
    ->addColumn('accept_intraoral_scan_files',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Accept intraoral scan files')
    ->addIndex(
        $installer->getIdxName(
            'nobel_labfinder/services',
            array('lab_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        ),
        "lab_id",
        array("type" => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
    );

$installer->getConnection()->createTable($services);

/** Clean Up old table */
$installer->run('ALTER TABLE labfinder_labs DROP services');
$installer->run('ALTER TABLE labfinder_labs DROP products');

$installer->endSetup();