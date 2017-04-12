<?php

/** INTELLECTUAL PROPERTY OF NOBEL BIOCARE
 *
 * * Class Nobel_Labfinder_Model_Resource_Products
 * * Date: 1/10/2017
 * @category  Nobel
 * @package   Nobel_Labfinder
 * @author    Samuel Carvalho <samuel.carvalho@nobelbiocare.com>
 * @copyright 2016 Nobel Biocare
 */
class Nobel_Labfinder_Model_Resource_Products extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('nobel_labfinder/products', 'id');
    }

}