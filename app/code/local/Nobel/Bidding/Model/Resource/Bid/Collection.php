<?php
/**
 * Created by PhpStorm.
 * User: ussaca
 * Date: 4/10/17
 * Time: 8:38 PM
 */

/**
 * @author Haydar Ciftci <haydar.ciftci@nobelbiocare.com>
 * @package Nobel\Labfinder
 */
class Nobel_Bidding_Model_Resource_Bid_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('nobel_bidding/bid');
    }

}