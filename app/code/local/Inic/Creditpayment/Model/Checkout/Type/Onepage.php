<?php

class Inic_Creditpayment_Model_Checkout_Type_Onepage extends  Mage_Checkout_Model_Type_Onepage
{
  
    public function savePayment($data)
    {
        if (empty($data)) {
            return array('error' => -1, 'message' => Mage::helper('checkout')->__('Invalid data.'));
        }
      
         /* Added For Validation By IndiaNIC*/
        $quote              =   $this->getQuote();
        $_totalData         =   $quote->getData();
        $_grand             =   $_totalData['base_grand_total'];
        $customerSession    =   $this->getCustomerSession();
        $customer           =   $customerSession->getCustomer();
        $creditLimit        =   $customer->getCreditLimit();
      
        if($data['method']=="creditpayment" && $creditLimit < $_grand  ){
         /* return array('error' => '-1', 'message' => Mage::helper('checkout')->__("You don't have sufficient credit balance. "));*/
         //Mage::throwException
            Mage::throwException(Mage::helper('checkout')->__("You don't have sufficient credit."));
        }
        /* Added For Validation By IndiaNIC*/
        if ($quote->isVirtual()) {
            $quote->getBillingAddress()->setPaymentMethod(isset($data['method']) ? $data['method'] : null);
        } else {
            $quote->getShippingAddress()->setPaymentMethod(isset($data['method']) ? $data['method'] : null);
        }

        // shipping totals may be affected by payment method
        if (!$quote->isVirtual() && $quote->getShippingAddress()) {
            $quote->getShippingAddress()->setCollectShippingRates(true);
        }

        $payment = $quote->getPayment();
        $payment->importData($data);

        $quote->save();

        $this->getCheckout()
            ->setStepData('payment', 'complete', true)
            ->setStepData('review', 'allow', true);

        return array();
    }

  }
