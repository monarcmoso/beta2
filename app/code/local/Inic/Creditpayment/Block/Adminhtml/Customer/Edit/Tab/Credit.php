<?php 


class Inic_Creditpayment_Block_Adminhtml_Customer_Edit_Tab_Credit extends Mage_Adminhtml_Block_Template    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    public function __construct()
    {
       
        $this->setTemplate('creditpayment/creditpayment.phtml');
    }

    public function getCreditData()
    {
        $customer = Mage::registry('current_customer');
        $creditLimit   = $customer->getCreditLimit();
        return $creditLimit;
     }

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Customer Credit Limit');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Credit Tab');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        $customer = Mage::registry('current_customer');
        return (bool)$customer->getId();
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
     $customer =Mage::registry('current_customer');
    $customerGroup = $customer->getGroupId();
    $SelectedCustomerGroups =   Mage::getStoreConfig('payment/creditpayment/specificcustomers'); 
    $enabled_module =   Mage::getStoreConfig('payment/creditpayment/active'); 
    $SelectedCustomerGroupsArray = explode(",", $SelectedCustomerGroups);
    if($enabled_module){
    if($SelectedCustomerGroups != ""){
         if(in_array($customerGroup, $SelectedCustomerGroupsArray)) {
            return false;
         }
         return true;
    }
    else 
     {
         return false;
     }
    }
    else{
         return true;
    }
        
    }
    public function selectedGroup(){
        $SelectedCustomerGroups =   Mage::getStoreConfig('payment/creditpayment/specificcustomers'); 
        return $SelectedCustomerGroups;
    }

     /**
     * Defines after which tab, this tab should be rendered
     *
     * @return string
     */
    public function getAfter()
    {
        return 'account';
    }
   

}
?>