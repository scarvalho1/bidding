<?php
/**
 * @category  Nobel
 * @package   Nobel_Labfinder
 * @author    Samuel Carvalho <samuel.carvalho@nobelbiocare.com>
 * @copyright 2016 Nobel Biocare
 */
class Nobel_Labfinder_Block_Thankyou extends Mage_Core_Block_Template
{
    protected $isContact = null;
    protected $isError = null;
    protected $isPartner = null;

    /** Used to toggle different thank you pages: contacting a lab versus partner form
     * @return : returns either boolean or null
     */
    public function isContact()
    {
        return $this->isContact;
    }

    /**
     * function parameters: type Boolean
     * @param null $isContact
     */
    public function setIsContact($isContact)
    {
        $this->isContact = $isContact;
    }

    /** function parameters: type Boolean
     * @param $isPartner
     */
    public function setIsPartner($isPartner)
    {
        $this->isPartner = $isPartner;
    }

    /** Getter for toggling partner success message on thank you page
     * @return: boolean or null
     * */
    public function isPartner()
    {
        return $this->isPartner;
    }

    /** Error getter
     * @return boolean or Null
     */
    public function isError()
    {
        return $this->isError;
    }

    /** To be used to display Error to the user on thank you page
     * function parameters: type Boolean
     * @param null $isError
     */
    public function setIsError($isError)
    {
        $this->isError = $isError;
    }

}