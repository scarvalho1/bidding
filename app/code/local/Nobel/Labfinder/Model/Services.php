<?php

/** INTELLECTUAL PROPERTY OF NOBEL BIOCARE
 *
 * * Class Nobel_Labfinder_Model_Services
 * * Date: 1/10/2017
 * @category  Nobel
 * @package   Nobel_Labfinder
 * @author    Samuel Carvalho <samuel.carvalho@nobelbiocare.com>
 * @copyright 2016 Nobel Biocare
 */
class Nobel_Labfinder_Model_Services extends Mage_Core_Model_Abstract
{
    public function loadBy($service)
    {
        $collection = $this->getCollection()
            ->addFieldToSelect('lab_id')
            ->addFieldToFilter($service, 1)
            ->toArray();
        return $collection;
    }

    public function loadServices($labID)
    {
        $collection = $this->getCollection()
            ->addFieldToSelect("nobelclinician_smartfusion")
            ->addFieldToSelect("restorative_planning")
            ->addFieldToSelect("all-on-4_conversions")
            ->addFieldToSelect("accept_intraoral_scan_files")
            ->addFieldToFilter("lab_id", $labID)
            ->toArray();
        return $collection;
    }

    public function loadByLabId($labid)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter("lab_id", $labid);
        return $collection->getFirstItem();
    }

    protected function _construct()
    {
        $this->_init('nobel_labfinder/services');
    }
}