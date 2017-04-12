<?php

/** INTELLECTUAL PROPERTY OF NOBEL BIOCARE
 *
 * * Class Nobel_Labfinder_Block_Partner
 * * Date: 11/22/2016
 * @category  Nobel
 * @package   Nobel_Labfinder
 * @author    Samuel Carvalho <samuel.carvalho@nobelbiocare.com>
 * @copyright 2016 Nobel Biocare
 */
class Nobel_Labfinder_Block_Partner extends Mage_Core_Block_Template
{
    protected $isSuccess = null;
    protected $isError = null;

    /**
     * @return null
     */
    public function isSuccess()
    {
        return $this->isSuccess;
    }

    /**
     * @param null $isSuccess
     */
    public function setIsSuccess($isSuccess)
    {
        $this->isSuccess = $isSuccess;
    }

    /**
     * @return null
     */
    public function isError()
    {
        return $this->isError;
    }

    /**
     * @param null $isError
     */
    public function setIsError($isError)
    {
        $this->isError = $isError;
    }
}