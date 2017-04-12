<?php

/**
 * @category  Nobel
 * @package   Nobel_Labfinder
 * @author    Samuel Carvalho <samuel.carvalho@nobelbiocare.com>
 * @copyright 2016 Nobel Biocare
 */
class Nobel_Labfinder_Model_Observer extends Mage_Core_Model_Abstract
{

    public function runCron()
    {
        $sfClient = Mage::helper('nobel_labfinder/salesforce');
        $sfClient->init();
        $records = $sfClient->getSyncRequests();
        $recordsCount = count($records);

        Mage::log('Labfinder: received ' . count($records), Zend_Log::DEBUG);

        if ($recordsCount == 0) {
            Mage::log('Labfinder: No records to synchronize', Zend_Log::DEBUG);
            return;
        }

        foreach ($records as $record) {
            $lab = Mage::getModel('nobel_labfinder/lab');
            $nobelLab = $lab->loadBySFID($record->Id);
            $size = sizeof($nobelLab->getData());
            /** IF Lab Current does NOT exist in our Magento Database, then do this: */
            if (!$size) {
                $lab->setData($sfClient->convertObjectToModelArray($record));
                try {
                    $coordinates = $this->getGpsCoordinates($lab);
                    $lab->setData('longitude', $coordinates['longitude']);
                    $lab->setData('latitude', $coordinates['latitude']);
                } catch (Exception $e) {
                    Mage::log($e->getMessage());
                }
                $lab->save();
                $sfClient->insertLabsAttributes($lab);

            } else {

                $labData = $nobelLab->getData();
                $lab->setData($sfClient->convertObjectToModelArray($record));
                $lab->setData("lab_id", $labData['lab_id']);
                try {
                    $coordinates = $this->getGpsCoordinates($lab);
                    $lab->setData('longitude', $coordinates['longitude']);
                    $lab->setData('latitude', $coordinates['latitude']);
                    Mage::log($lab->getData());
                } catch (Exception $e) {
                    Mage::log($e->getMessage());
                }
                $lab->save();
                $sfClient->insertLabsAttributes($lab);

            }
            $sfClient->setIsSynchronized($lab);
        }
        /** END FOREACH LOOP */
    }

    private function getGpsCoordinates($lab)
    {
        $array = array();
        $address = "";

        if ($lab->getData('address1') != null) {
            $address = $lab->getData('address1');
        }
        if ($lab->getData('address2') != null) {
            $address = $address . " " . $lab->getData('address2');
        }
        if ($lab->getData('state') != null) {
            $address = $address . " " . $lab->getData('state');
        }
        if ($lab->getData('country') != null) {
            $address = $address . " " . $lab->getData('country');
        }

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $geo = curl_exec($ch);
        curl_close($ch);
        $geo = json_decode($geo, true);
        if ($geo['status'] = 'OK') {
            $latitude = $geo['results'][0]['geometry']['location']['lat'];
            $longitude = $geo['results'][0]['geometry']['location']['lng'];
            $array = array('latitude' => $latitude, 'longitude' => $longitude);
        }
        return $array;
    }

}