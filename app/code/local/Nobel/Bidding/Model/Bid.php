<?php
/**
 * Created by PhpStorm.
 * User: ussaca
 * Date: 4/10/17
 * Time: 8:20 PM
 */

class Nobel_Bidding_Model_Bid extends Mage_Core_Model_Abstract{


    protected function _construct()
    {
        $this->_init('nobel_bidding/bid');
    }

    public function loadByType($searchTerm)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter('type')
            ->addFieldToSelect("price")
            ->addFieldToSelect("bid_id");
        return $collection->toArray();
    }


    public function loadAllHighestValue()
    {
        $collection = $this->getCollection()
            ->addFieldToSelect("*")
            ->setOrder('value', 'DESC');
        return $collection->toArray();
    }

    public function loadBidsByProductID($productID)
    {
        $collection = $this->getCollection()
                ->addFieldToFilter("productID",$productID)
                ->addFieldToSelect("*");
        return $collection->getAllIds();
    }

    public function getAllProducts()
    {
        $collection = $this->getCollection()
            ->addFieldToSelect("*")
            //->group('productID')
            ->setOrder('time','DESC');

        return $collection->toArray();
    }

    public function getHighestBidbyProduct($productID)
    {
        $collection = $this->getCollection()
            ->addFieldToSelect("bid_id")
            ->setOrder('value', 'DESC');
        return $collection->getAllIds();
    }

}

