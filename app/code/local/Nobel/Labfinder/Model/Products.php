<?php

/** INTELLECTUAL PROPERTY OF NOBEL BIOCARE
 *
 * * Class Nobel_Labfinder_Model_Products
 * * Date: 1/10/2017
 * @category  Nobel
 * @package   Nobel_Labfinder
 * @author    Samuel Carvalho <samuel.carvalho@nobelbiocare.com>
 * @copyright 2016 Nobel Biocare
 */
class Nobel_Labfinder_Model_Products extends Mage_Core_Model_Abstract
{
    public function loadBy($product)
    {
        $collection = $this->getCollection()
            ->addFieldToSelect('lab_id')
            ->addFieldToFilter($product, 1)
            ->toArray();
        return $collection;
    }

    public function loadProducts($labID)
    {
        $collection = $this->getCollection()
            ->addFieldToSelect("crowns_and_bridges")
            ->addFieldToSelect("implant_crowns_and_abutments")
            ->addFieldToSelect("implant_bridges")
            ->addFieldToSelect("implant_bars")
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
        $this->_init('nobel_labfinder/products');
    }
}
