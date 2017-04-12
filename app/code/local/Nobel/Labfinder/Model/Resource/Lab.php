<?php

/**
 * @author Haydar Ciftci <haydar.ciftci@nobelbiocare.com>
 * @package Nobel\Labfinder
 */
class Nobel_Labfinder_Model_Resource_Lab extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('nobel_labfinder/lab', 'lab_id');
    }

}