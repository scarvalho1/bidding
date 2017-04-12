<?php
require_once(Mage::getBaseDir('lib') . '/Pixafy/salesforce/soapclient/SforceEnterpriseClient.php');

/**
 * @author Haydar Ciftci <haydar.ciftci@nobelbiocare.com>
 * @package Nobel\Labfinder
 */
class Nobel_Labfinder_Helper_Salesforce extends Mage_Core_Helper_Abstract
{

    /**
     * @var array Mappings of SalesForce fields to model fields.
     */
    protected $fieldsMapping = array();
    /**
     * @var array List of field to select with each SalesForce query.
     */
    protected $fieldList = array();

    /**
     * @var SforceEnterpriseClient
     */
    protected $salesForceClient = null;

    /**
     * SalesForce prepare.
     */
    public function init()
    {
        $this->fieldsMapping = array(
            'Id' => 'salesforce_id',
            'SAP_ID__c' => 'customer_number',
            'LF_Account_Name__c' => 'name',
            'LF_Street__c' => 'address1',
            'LF_Zip_Code__c' => 'zipcode',
            'LF_City__c' => 'city',
            'LF_State__c' => 'state',
            'LF_Country__c' => 'country',
            'LF_Email__c' => 'email',
            'LF_Phone__c' => 'phone',
            'LF_Website__c' => 'website',
            'LF_Contact_First_Name__c' => 'first_name',
            'LF_Contact_Last_Name__c' => 'last_name',
            'LF_Authentic_NobelProcera_products__c' => 'products',
            'LF_Services_offered__c' => 'services',
            'LF_Publish_lab_on_web__c' => 'publish',
            'LF_Lab_sync_request__c' => 'sync_request',
            'LF_Last_sync_date__c' => 'sync_date',
            'Account_is_a_lab__c' => 'is_lab'

        );

        $this->fieldsList = implode(', ', array_keys($this->fieldsMapping));

        $this->salesForceClient = Mage::helper('salesforce/client')->getClient();
    }


    /**
     * Get all records that request to be synchronized with Magento and set the requires_sync flag to false and updates
     * the processing dates of the records.
     *
     * @return array|null
     */
    public function getSyncRequests()
    {
        $query = sprintf(
            "SELECT %s FROM ACCOUNT WHERE LF_Lab_sync_request__c = TRUE",
            $this->fieldsList
        );

        $resultSet = $this->salesForceClient->query($query);

        if ($resultSet->size == 0) {
            return null;
        }

        $records = $resultSet->records;

        return $records;
    }

    /**
     * Set the sync flag to "synced" and updates the date.
     *
     * @param Nobel_Labfinder_Model_Lab $lab
     * @return mixed
     */
    public function setIsSynchronized(Nobel_Labfinder_Model_Lab $lab)
    {
        $dataArray = $this->convertModelToArray($lab);
        $dataArray['LF_Lab_sync_request__c'] = false;
        $dataArray['LF_Last_sync_date__c'] = gmdate(DATE_ATOM, time());
        $result = Mage::helper('salesforce/utility')->updateSalesforceData(
            $this->salesForceClient,
            $dataArray['Id'],
            'Account',
            $dataArray
        );

        return $result;
    }

    /**
     * Convert Nobel_Labfinder_Model_Lab into an salesforce array.
     *
     * @return array An array with SalesForce field keys.
     */
    public function convertModelToArray(Nobel_Labfinder_Model_Lab $lab)
    {
        $dataArray = array();

        foreach ($this->fieldsMapping as $key => $value) {
            $fieldName = $this->fieldsMapping[$key];

            if (!$fieldName) continue;

            if ($lab->getData($fieldName) !== null) {
                $dataArray[$key] = $lab->getData($fieldName);
            }
        }
        return $dataArray;
    }

    /**
     * Returns a single lab based on the SalesForce id.
     *
     * @param string $salesForceId
     * @return Varien_Object Object representation of the lab.
     */
    public function getLab($salesForceId)
    {
        $query = sprintf(
            "SELECT %s FROM ACCOUNT WHERE Id = '%s'",
            $this->fieldsList,
            $salesForceId
        );

        $resultSet = $this->salesForceClient->query($query);

        if ($resultSet->size == 0) {
            return null;
        }

        $record = $resultSet->records[0];
        return $this->mapToObject($record);
    }

    /**
     * Map SalesForce object to a Magento object.
     *
     * @param SObject $record SalesForce record.
     * @return Varien_Object
     */
    public function mapToObject(SObject $record)
    {
        $obj = new Varien_Object();

        foreach ($this->fieldsMapping as $key => $value) {
            $fieldName = $this->fieldsMapping[$key];

            if (!$fieldName || !key_exists($key, $record)) continue;

            $obj->setData($fieldName, $record->{$key});
        }

        return $obj;
    }

    /**
     * Create a new laboratory on sales.
     *
     * @param Nobel_Labfinder_Model_Lab $lab
     * @return mixed
     * @throws Exception
     */
    public function createLab(Nobel_Labfinder_Model_Lab $lab)
    {

        $query = sprintf("SELECT %s FROM ACCOUNT WHERE SAP_ID__c = '%s'", $this->fieldsList, $lab->getCustomerNumber());

        $resultSet = $this->salesForceClient->query($query);

        if ($resultSet->size == 0) {

            $errorMessage = 'Could not find `Account` with customer number :"' . $lab->getCustomerNumber() . '"';
            Mage::log($errorMessage);
            throw new Exception($errorMessage);
        }

        $record = $resultSet->records[0];

        if ($record->LF_Publish_lab_on_web__c || property_exists($record, "LF_Account_Name__c")) {

            $errorMessage = 'Customer number "' . $lab->getCustomerNumber() . '"' . ' already in use as a lab';
            Mage::log($errorMessage);
            throw new Exception($errorMessage);

        } else {

            $labDataArray = $this->convertModelToArray($lab);
            $SFID = $record->Id;

            $updated = Mage::helper('salesforce/utility')->updateSalesforceData(
                $this->salesForceClient,
                $SFID,
                'Account',
                $labDataArray
            );

            return $updated[0]->success;
        }
    }

    /** Add Lab's Products & Services. see testAction() in index controller for working example */
    public function insertLabsAttributes($lab)
    {
        $keys = array('products', 'services');

        foreach ($keys as $value) {
            $newID = $this->insertAttributesFlow($lab, $value);
        }
        return $newID;
    }

    /** Dynamically inserts Attributes for a lab: both Products & Services */
    public function insertAttributesFlow($lab, $value)
    {
        $labID = $lab['lab_id'];
        $result = array('lab_id' => $labID);
        $data = $lab[$value];
        $attributes = array();
        if (!is_array($data)) {
            $attributes = explode(";", $data);

        } else {

            $attributes = $data;
        }

        $productMapping = array(
            '001' => 'crowns_and_bridges',
            '002' => 'implant_crowns_and_abutments',
            '003' => 'implant_bridges',
            '004' => 'implant_bars'
        );

        $serviceMapping = array(
            '001' => 'nobelclinician_smartfusion',
            '002' => 'restorative_planning',
            '003' => 'all-on-4_conversions',
            '004' => 'accept_intraoral_scan_files'
        );

        switch ($value) {
            case "products":
                $mapping = $productMapping;
                $model = Mage::getModel('nobel_labfinder/products');
                break;

            case "services":
                $mapping = $serviceMapping;
                $model = Mage::getModel('nobel_labfinder/services');
                break;

            default:
                throw new Exception("Unknown value");
                break;
        }

        foreach ($mapping as $key => $value) {
            $tmp = $mapping[$key];
            $result[$tmp] = 0;
        }

        foreach ($attributes as $key) {
            $tmp = $mapping[$key];
            $result[$tmp] = 1;
        }

        try {
            $newID = $model
                ->loadByLabId($labID);

            $newID
                ->setData($result)
                ->save();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $newID;
    }

    public function createActivity($lab)
    {
        $this->salesForceClient = Mage::helper('salesforce/client')->getClient();
        $id = $lab['labid'];
        $labDetails = Mage::getModel('nobel_labfinder/lab')->loadByID($id);
        $SFID = $labDetails['salesforce_id'];

        $dataArray = array(
            'Activity_type__c ' => 'Email',
            'Account__c' => $SFID
        );

        $result = Mage::helper('salesforce/utility')->createSalesforceData(
            $this->salesForceClient,
            'Online_Activity__c',
            $dataArray
        );
        return $result;
    }

    /** Array to Varien Object
     * @param $array
     * @return Varien_Object
     */
    public function arrayToObject($array)
    {
        $obj = new Varien_Object();

        foreach ($this->fieldsMapping as $key => $value) {
            $fieldName = $this->fieldsMapping[$key];

            if (!$fieldName || !key_exists($key, $array)) continue;

            $obj->setData($fieldName, $array[$key]);
        }

        return $obj;
    }

    /**
     * Merge the model to a data array while validating the fields, so that they exists on SalesForce side.
     *
     * @param Nobel_Labfinder_Model_Lab $lab
     * @param SObject $record
     * @return array An array with SalesForce field keys.
     */
    public function mergeToArray(Nobel_Labfinder_Model_Lab $lab, SObject $record)
    {
        $dataArray = array();

        foreach ($this->fieldsMapping as $key => $value) {
            $fieldName = $this->fieldsMapping[$key];

            if (!$fieldName || !key_exists($key, $record)) continue;

            $dataArray[$record->{$key}] = $lab->getData($fieldName);
        }

        return $dataArray;
    }

    /**
     * Convert SObject into an array.
     *
     * @param SObject $record
     * @return array An array with SalesForce field keys.
     */
    public function convertObjectToArray(SObject $record)
    {
        $dataArray = array();

        foreach ($this->fieldsMapping as $key => $value) {
            $fieldName = $this->fieldsMapping[$key];

            if (!$fieldName || !key_exists($key, $record)) continue;

            $dataArray[$key] = $record->{$key};
        }

        return $dataArray;
    }

    /**
     * Convert SObject into a model data array.
     *
     * @param SObject $record
     * @return array An array with Model field keys.
     */
    public function convertObjectToModelArray(SObject $record)
    {
        $dataArray = array();

        foreach ($this->fieldsMapping as $key => $value) {
            $dataArray[$value] = $record->{$key};
        }

        return $dataArray;
    }
}