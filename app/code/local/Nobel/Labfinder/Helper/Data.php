<?php
/**
 * @author Haydar Ciftci <haydar.ciftci@nobelbiocare.com>
 * @package Nobel\Labfinder
 */
class Nobel_Labfinder_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getProducts()
    {
        return array(
            0 => 'Crowns and bridges',
            1 => 'Implant crowns and abutments',
            2 => 'Implant bridges',
            3 => 'Implant bars'
        );
    }

    public function getServices()
    {
        return array(
            0 => 'NobelClinician SmartFusion',
            1 => 'Restorative planning',
            2 => 'All-on-4 conversions',
            3 => 'Accept intraoral scan files'
        );
    }

    public function getProductsFields()
    {
        return array(
            0 => 'crowns_and_bridges',
            1 => 'implant_crowns_and_abutments',
            2 => 'implant_bridges',
            3 => 'implant_bars'
        );
    }

    public function getServicesFields()
    {
        return array(
            0 => 'nobelclinician_smartfusion',
            1 => 'restorative_planning',
            2 => 'all-on-4_conversions',
            3 => 'accept_intraoral_scan_files'
        );
    }
}