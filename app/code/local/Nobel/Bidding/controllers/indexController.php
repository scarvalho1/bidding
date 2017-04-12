<?php
/**
 * Created by PhpStorm.
 * User: ussaca
 * Date: 4/10/17
 * Time: 8:46 PM
 */


class Nobel_Bidding_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('View All Bids'));
        $this->getLayout()->getBlock('Bidding_bid_index_bids');
        $this->renderLayout();
    }

    public function bidsByProductIDAction()
    {
        if($this->getRequest()->isAjax())
        {
            $product = $this->getRequest()->getParams('productID');
            if (($product !== null) && ($product !== " ")) {

                try {
                    $result = Mage::getModel('nobel_bidding/bid')->loadBidsByProductID($product);
                    $this->getResponse()->setHeader('Content-Type', 'application/json');
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                } catch (Exception $er) {
                    Mage::log('error.log', $er);
                }
            }
        }
    }

    public function getAllBidsAction()
    {
        $allBids = Mage::getModel('nobel_bidding/bid')->loadAllHighestValue();
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($products));

    }

    public function getAllProducts()
    {
        $prodsArray = array();
        $bidModel = Mage::getModel('nobel_bidding/bid');
        if($this->getRequest()->isAjax())
        {
            $products = $bidModel->getAllProducts();
            $size = sizeof($products);
            $index = 0;
            foreach($products as $product)
            {
                $index++;
                $prodID = $product['productID'];

                $bidsOnProduct = $bidModel->getHighestBidbyProduct($prodID);

                $product[$prodID] = array(
                    "bids"      => $bidsOnProduct,
                    "product"   => $product);
                array_push($prodsArray,$product);
            }
            if($index==$size) {
                $this->getResponse()->setHeader('Content-Type', 'application/json');
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($prodsArray));
            }
        }
    }

}