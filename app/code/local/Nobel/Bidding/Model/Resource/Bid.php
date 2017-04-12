<?php
/**
 * Created by PhpStorm.
 * User: ussaca
 * Date: 4/10/17
 * Time: 8:39 PM
*/

class Nobel_Bidding_Model_Resource_Bid extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('nobel_bidding/bid', 'bid_id');
    }

}