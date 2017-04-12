<?php

/**
 * INTELLECTUAL PROPERTY OF NOBEL BIOCARE
 *
 * * Class Nobel_Labfinder_Helper_Email
 *
 * * Date: 1/10/2017
 * @category  Nobel
 * @package   Nobel_Labfinder
 * @author    Samuel Carvalho <samuel.carvalho@nobelbiocare.com>
 * @copyright 2016 Nobel Biocare
 */
class Nobel_Labfinder_Helper_Email extends Mage_Core_Helper_Abstract
{

    public function contactEmail($vars)
    {
        $receiveName = $vars['labName'];
        $emails['emails'] = $vars['labEmail'];
        $replyTo = $vars['email'];
        $vars['senderEmail'] = $vars['email'];
        $template = Mage::getStoreConfig('nobel_labfinder/general/contact_email');
        $emailTemplate = Mage::getModel('core/email_template')->load($template);
        $emailTemplate->getProcessedTemplate($vars);
        $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_support/email', Mage::app()->getStore()->getId()));
        $emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_support/name', Mage::app()->getStore()->getId()));
        $emailTemplate->setReplyTo($replyTo);
        try {
            $result = $emailTemplate->send($emails, $receiveName, $vars);
            if (($result == 1) || ($result == true)) {

                return true;

            } else {

                return false;
            }
        } catch (Exception $e) {

            Mage::logException($e);
            return false;
        }
    }

    public function partnerRequest($vars)
    {
        $template = Mage::getStoreConfig('nobel_labfinder/general/new_lab_email');
        $emailTemplate = Mage::getModel('core/email_template')->load($template);
        $emailTemplate->getProcessedTemplate($vars);
        $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_support/email', Mage::app()->getStore()->getId()));
        $emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_support/name', Mage::app()->getStore()->getId()));

        $receiveName = $vars['firstName'] . " " . $vars['lastName'];
        $emails['emails'] = $vars['email'];

        try {
            $result = $emailTemplate->send($emails, $receiveName, $vars);
            if (($result == 1) || ($result == true)) {

                return true;

            } else {

                return false;
            }
        } catch (Exception $e) {

            Mage::logException($e);
            return false;

        }

    }
}