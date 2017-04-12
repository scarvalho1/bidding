<?php

/**
 * @author Haydar Ciftci <haydar.ciftci@nobelbiocare.com>
 * @package Nobel\Labfinder
 */
class Nobel_Labfinder_Model_Lab extends Mage_Core_Model_Abstract
{

    public function loadByName($searchTerm)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter('name', array(
                array('like' => '% ' . $searchTerm . ' %'), //spaces on each side
                array('like' => '% ' . $searchTerm),        //space before and ends with $searchTerm
                array('like' => $searchTerm . ' %')         // starts with $searchTerm and space after
            ));
        return $collection->toArray();
    }

    public function loadByCountry($country)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter('country', $country);
        return $collection->getFirstItem();
    }

    public function loadByID($id)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter('lab_id', $id);
        return $collection->getFirstItem();
    }

    public function loadBySFID($id)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter('salesforce_id', $id);
        return $collection->getFirstItem();
    }

    public function getLabAttributes($labID)
    {

        $pArry = Mage::getSingleton("nobel_labfinder/products")->loadProducts($labID);
        $sArry = Mage::getSingleton("nobel_labfinder/services")->loadServices($labID);
        $ptm = $pArry['items'][0];
        $stm = $sArry['items'][0];
        $products = array();
        $services = array();

        foreach ($ptm as $key => $val) {
            $prod = strtolower(str_replace('_', ' ', $key));
            $prod = ucfirst($prod);
            if ($val == 1) {

                array_push($products, $prod);

            }
        }

        foreach ($stm as $key => $val) {

            $serv = strtolower(str_replace('_', ' ', $key));
            $serv = ucfirst($serv);

            if ($val == 1) {

                array_push($services, $serv);

            }
        }
        return array('products' => $products, 'services' => $services, 'labid' => $labID);
    }

    /**
     * @return array List of product ids.
     */
    public function getProducts()
    {
        $products = $this->getData('products');

        if (!is_null($products)) {

            return explode(',', $products);
        }

        return array();
    }

    /**
     * @param $products array List of product ids.
     */
    public function setProducts($products)
    {
        if (!is_array($products)) {

            Mage::log('Provided product list is not an array', Zend_Log::ALERT);
            return;
        }

        $this->setData('products', implode(',', $products));
    }

    /**
     * @return array List of service ids.
     */
    public function getServices()
    {
        $services = $this->getData('services');

        if (!is_null($services)) {

            return explode(',', $services);
        }

        return array();
    }

    /**
     * @param array $services List of service ids.
     */
    public function setServices($services)
    {
        if (!is_array($services)) {

            Mage::log('Provided service list is not an array', Zend_Log::ALERT);
            return;
        }

        $this->setData('services', implode(',', $services));
    }

    protected function _construct()
    {
        $this->_init('nobel_labfinder/lab');
    }


}