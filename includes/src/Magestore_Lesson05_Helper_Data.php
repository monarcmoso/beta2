<?php

class Magestore_Lesson05_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getUserAge($x,$y,$ages){
           
   
    foreach($ages as $res){
       $birthDate = $res;

 
        //explode the date to get month, day and year
        if(!empty($birthDate)){
         $birthDate1 = explode("-", $birthDate);
         //print_r($birthDate1);
         //echo "<br/>";
    //get age from date or birthdate
        $age =   date("Y")-$birthDate1[0];
        //echo "<br/>";
         //$age = (date("md", date("U", mktime(0, 0, 0,  $birthDate1[0],$birthDate1[1],$birthDate1[2]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate1[0]));
   
        if($age >=$x && $age <=$y) {
                $age_array[] = $age;
    //all values in array are less than 5
            }
        }
    }


    return $age_array;

    }
    public function ordinal_number($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
  }
    public function aasort(&$array, $key) {
        $sorter=array();
        $ret=array();
        $aret=array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        arsort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }

        $array=$ret;
    }
}