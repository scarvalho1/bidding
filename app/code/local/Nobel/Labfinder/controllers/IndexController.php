<?php
/**
 * @author Haydar Ciftci <haydar.ciftci@nobelbiocare.com>
 * @package Nobel\Labfinder
 */
class Nobel_Labfinder_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Find a Lab'));
        $this->getLayout()->getBlock('labfinder_labs_index_search');
        $this->renderLayout();
    }

    public function partnerAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Find a Lab'));
        $this->getLayout()->getBlock('labfinder_labs_index_partner');
        $this->renderLayout();
    }

    public function searchAction()
    {
        $products = $this->getRequest()->getParam('products', null);
        $services = $this->getRequest()->getParam('services', null);
        $lng = $this->getRequest()->getParam('lng', null);
        $lat = $this->getRequest()->getParam('lat', null);
        $radius = $this->getRequest()->getParam('radius', 75);
        $radius = intval($radius) == 0 ? 75 : intval($radius);

        $productsFields = Mage::helper('nobel_labfinder')->getProductsFields();
        $servicesFields = Mage::helper('nobel_labfinder')->getServicesFields();
        $productsLabels = Mage::helper('nobel_labfinder')->getProducts();
        $servicesLabels = Mage::helper('nobel_labfinder')->getServices();

        $productsFieldsFlipped = array_flip($productsFields);
        $servicesFieldsFlipped = array_flip($servicesFields);

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $labsTable = $resource->getTableName('nobel_labfinder/lab');
        $productsTable = $resource->getTableName('nobel_labfinder/products');
        $servicesTable = $resource->getTableName('nobel_labfinder/services');

        $query = sprintf("SELECT *, (3959
                            * acos(cos(radians('%s')) 
                            * cos(radians(l.latitude)) 
                            * cos(radians(l.longitude) 
                            - radians('%s')) 
                            + sin(radians('%s')) 
                            * sin(radians(l.latitude))))
                             AS distance FROM $labsTable l ", $lat, $lng, $lat);

        $queryWhereElements = array();

        if (!is_null($products)) {
            $selectedProductsArray = explode(',', $products);
            foreach ($selectedProductsArray as $selectedProduct) {
                if (!is_null($productsFields[$selectedProduct])) {
                    $queryWhereElements[] = sprintf(" `%s` = 1", $productsFields[$selectedProduct]);
                }
            }

            $query .= " LEFT JOIN $productsTable p ON l.lab_id = p.lab_id ";
        }

        if (!is_null($services)) {
            $selectedServicesArray = explode(',', $services);

            foreach ($selectedServicesArray as $selectedService) {
                if (!is_null($servicesFields[$selectedService])) {
                    $queryWhereElements[] = sprintf(" `%s` = 1", $servicesFields[$selectedService]);
                }
            }

            $query .= " LEFT JOIN $servicesTable s ON l.lab_id = s.lab_id ";
        }

        if (count($queryWhereElements) > 0) {
            $query .= ' WHERE ' . implode(' AND ', $queryWhereElements);
        }

        $query .= sprintf(" HAVING distance < %s ORDER BY distance", $radius);
        $data = $readConnection->fetchAll($query);

        for ($i = 0; $i < count($data); $i++) {
            foreach ($productsFields as $productFieldName) {
                if ($data[$i][$productFieldName] == 1) {
                    $data[$i]['products'][] = array(
                        'fieldName' => $productFieldName,
                        'displayName' => Mage::helper('nobel_labfinder')->__($productsLabels[$productsFieldsFlipped[$productFieldName]]),
                    );
                }
            }

            foreach ($servicesFields as $serviceFieldName) {
                if ($data[$i][$serviceFieldName] == 1) {
                    $data[$i]['services'][] = array(
                        'fieldName' => $serviceFieldName,
                        'displayName' => Mage::helper('nobel_labfinder')->__($servicesLabels[$servicesFieldsFlipped[$serviceFieldName]]),
                    );
                }
            }
        }

        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($data));
    }

    public function emailAction()
    {
        $vars = array(
            'message' => $this->getRequest()->getParam('message'),
            'practiceName' => $this->getRequest()->getParam('company'),
            'labName' => $this->getRequest()->getParam('labName'),
            'firstName' => $this->getRequest()->getParam('firstName'),
            'lastName' => $this->getRequest()->getParam('lastName'),
            'email' => $this->getRequest()->getParam('email'),
            'labEmail' => $this->getRequest()->getParam('labEmail'),
            'phone' => $this->getRequest()->getParam('phone'),
            'labid' => $this->getRequest()->getParam('labID'));
        $mailSent = Mage::helper('nobel_labfinder/Email')->contactEmail($vars);

        if ($mailSent) {
            Mage::helper('nobel_labfinder/Salesforce')->createActivity($vars);
            $this->_redirect('findalab/index/thankyou');
        }
    }

    public function partnersubmitAction()
    {

        $sfHelper = Mage::helper('nobel_labfinder/Salesforce');
        $sfHelper->init();

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Find a Lab'));
        /** @var Nobel_Labfinder_Block_Thankyou $blockPostSubmit */
        $blockPostSubmit = $this->getLayout()->getBlock('labfinder_labs_index_thankyou');
        $blockPostSubmit->setTitle($this->__('Find a Lab'));

        $lab = Mage::getModel('nobel_labfinder/lab');
        $servs = $this->getRequest()->getParam('services');
        $prods = $this->getRequest()->getParam('products');

        $products = implode(";", $prods);
        $services = implode(";", $servs);
        $dataArray = array(
            'name' => $this->getRequest()->getParam('labName'),
            'first_name' => $this->getRequest()->getParam('firstName'),
            'last_name' => $this->getRequest()->getParam('lastName'),
            'customer_number' => $this->getRequest()->getParam('customerNumber'),
            'address1' => $this->getRequest()->getParam('address'),
            'zipcode' => $this->getRequest()->getParam('zip'),
            'city' => $this->getRequest()->getParam('city'),
            'state' => $this->getRequest()->getParam('state'),
            'country' => $this->getRequest()->getParam('country'),
            'email' => $this->getRequest()->getParam('email'),
            'website' => $this->getRequest()->getParam('website'),
            'phone' => $this->getRequest()->getParam('phone'),
            'services' => $services,
            'products' => $products
        );
        $lab->setData($dataArray);

        try {
            $mailSent = Mage::helper('nobel_labfinder/Email')->partnerRequest($dataArray);

        } catch (Exception $e) {

            Mage::logException($e);
        }

        try {
            $updated = $sfHelper->createLab($lab);

            if ($updated) {
                $blockPostSubmit->setIsError(false);
                $blockPostSubmit->setIsPartner(true);
            } else {

                $blockPostSubmit->setIsError(true);
            }

        } catch (Exception $e) {

            Mage::logException($e);
            $blockPostSubmit->setIsError(true);

        }
        $this->renderLayout();
    }

    public function thankyouAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head');
        $this->getLayout()->getBlock('labfinder_labs_index_thankyou')->setIsContact(true);
        $this->renderLayout();
    }
}