<?php

/**
 * Nobel Labfinder Search Helper
 *
 * * Date: 12/22/16
 * @category  Nobel
 * @package   Nobel_Labfinder
 * @author    Samuel Carvalho <samuel.carvalho@nobelbiocare.com>
 * @copyright 2016 Nobel Biocare
 */
class Nobel_Labfinder_Helper_Search extends Mage_Core_Helper_Abstract
{
    public function Search($productsInput, $servicesInput, $lat, $lng, $radius)
    {
        $labIDarry = Array();
        $filteredLabs = Array();
        $services = null;
        $products = null;
        if (($servicesInput !== null) && ($servicesInput != "")) {

            $services = urldecode($servicesInput);
            $servCollect = $this->loopArray($services, 'services');
            $labIDarry = $labIDarry + $servCollect;
        }

        if (($productsInput !== null) && ($productsInput != "")) {
            $products = urldecode($productsInput);
            $prodsCollect = $this->loopArray($products, 'products');
            $labIDarry = $labIDarry + $prodsCollect;
        }

        $labIDarry = array_unique($labIDarry);
        $collection = null;
        $searchCoordinates = Array(
            "longitude" => $lng,
            "latitude" => $lat
        );
        if (sizeof($labIDarry) == 0) {

            $collection = Mage::getModel('nobel_labfinder/lab')->getCollection()->addFieldToSelect('*')->toArray();

        } else {

            $collection = Mage::getModel('nobel_labfinder/lab')->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('lab_id', array("in" => $labIDarry))
                ->toArray();
        }

        foreach ($collection['items'] as $item) {
            $lng = $item['longitude'];
            $lat = $item['latitude'];
            $labCoordinates = Array(
                "longitude" => $lng,
                "latitude" => $lat
            );
            $result = $this->inRadius($labCoordinates, $searchCoordinates, $radius);

            if ($result != false) {
                /**Builds an array of labs matching filters & within radius */
                array_push($filteredLabs, $item);
            }
        }
        return $filteredLabs;
    }

    /** Simply loops through comma-separated string, formats the values into column names, then gets results into a neat array
     * @param $arry is exploded comma-separated string of products/services
     * @param $type defines if array contains list of products OR services
     * @return array returns the Lab IDs that match (after getting input back from function above getLabIDsBy(tableName,ColumnName)
     */
    function loopArray($arr, $type)
    {
        $labIdArray = Array();
        $arry = explode(",", $arr);
        $index = 0;
        foreach ($arry as $item) {

            $index++;
            $column = strtolower(str_replace(' ', '_', $item));
            $labIDs = $this->getLabsIDsBy($column, $type);
            $labIdArray = $labIdArray + $labIDs;
        }
        return $labIdArray;
    }

    /**
     * Gets Labs' IDs that have matching products/services (@param $column ) from a table,
     * @param $Path table variable is either Products OR Services table
     */
    function getLabsIDsBy($column, $entity)
    {
        $path = 'nobel_labfinder/' . $entity;
        $collection = Mage::getSingleton($path)->loadBy($column);
        return $collection;
    }

    /** Receives a lab's coordinates, search coordindates & radius to calculate distance
     * @param $lab
     * @param $search
     * @param $radius
     * @return bool
     */
    function inRadius($lab, $search, $radius)
    {
        $distance = (
            3959 * acos
            (cos(deg2rad(intval($search['latitude'])))
                * cos(deg2rad(intval($lab['latitude'])))
                * cos(deg2rad(intval($lab['longitude']))
                    - deg2rad(intval($search['longitude'])))
                + sin(deg2rad(intval($search['latitude'])))
                * sin(deg2rad(intval($lab['latitude']))))
        );
        if ($distance < $radius) {

            return true;
        } else {

            return false;
        }
    }


}