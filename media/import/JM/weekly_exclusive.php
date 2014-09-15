<?php 

$user_name = "fr3b_devking";
$password = "24thmay01";
$database = "fr3b_db";
$server = "db-server";
require_once('include/sql_param_check.php');

$db_handle = mysql_connect($server, $user_name, $password);
$db_found = mysql_select_db($database, $db_handle);
if ($db_found) {

		if($_GET['submit']=="true")
		{
			if(empty($_POST['textfield1']) OR 
				empty($_POST['textfield2']) OR 
				empty($_POST['textfield3']) OR 
				empty($_POST['textfield4']) OR  
				empty($_POST['textfield5']) OR  
				empty($_POST['textfield6']) OR  
				empty($_POST['RadioGroup1']))
				{
  		 			echo "All fields must be filled in/selected." ;
				}
			else
			{
				$Ans1 = sql_param_check($_POST['textfield1']);
				$Ans2 = sql_param_check($_POST['textfield2']);
				$Ans3 = sql_param_check($_POST['textfield3']);
				$Ans4 = sql_param_check($_POST['textfield4']);
				$Ans5 = sql_param_check($_POST['textfield5']);
				$Ans6 = sql_param_check($_POST['textfield6']);
				$Ans7 = sql_param_check($_POST['RadioGroup1']);
				
		
				
				echo "Thank you, your payment will be verified shortly. Upon verification, an email will be sent to you";
				
$together='Name:'. $Ans1 .'Address:'. $Ans2 .'Email:'. $Ans3 .'IBnick:'. $Ans4 . 'Refno:'. $Ans5 .'Amtpaid:'. $Ans6 .'Qty:'. $Ans7;


    mysql_query("INSERT INTO exclusive_offer_bbcream_tb(name) values('".$together."')");


mysql_close($db_handle);

			}
		}
		}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Fr3b.com: Exclusive weekly HOT deal!</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.servBodL {border: 1px dotted #CEDCEA; 
text-align: left;
font-family: Tahoma;
font-size: 14px;
color: #404040;
}
.servBodL2 {border: 1px dotted #CEDCEA; 
text-align: left;
font-family: Tahoma;
font-weight: bold;
font-size: 30px;
color: #404040;
}
.servBodL1 {border-left: 1px dotted #CEDCEA; 
text-align: left;
font-family: Tahoma;
font-size: 14px;
color: #404040;
}
.servBodL3 {border: 1px dotted #CEDCEA; 
text-align: center;
font-family: Tahoma;
font-size: 24px;
color: #0099ff;
}
.alertBod {background-color: #ffffff;
border: 1px dotted #CEDCEA; 
text-align: left;
font-family: Tahoma;
font-size: 14px;
color: #404040;}
.alertHd {background-color: #f4f4f4;
border: 1px dotted #CEDCEA; 
text-align: left;
font-family: Tahoma;
font-size: 14px;
color: #404040;}
.style4 {color: #FF0000}
.style5 {font-size: 9px}
.style6 {
	font-size: xx-large;
	font-weight: bold;
	color: #FF0000;
}
.style8 {font-size: xx-small}
.style9 {color: #FF0000; font-weight: bold; }
.style10 {background-color: #f4f4f4; border: 1px dotted #CEDCEA; text-align: left; font-family: Tahoma; font-size: large; color: #404040; }
-->
</style>
</head>

<body oncontextmenu="return false">
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="Images/Fr3bLogo.gif" width="113" height="62"></td>
    <td align="right"><img src="Images/hotdeal.gif" width="239" height="60"></td>
  </tr>
  <tr>
<!--     <td colspan="2" class="servBodL3">
<script language="JavaScript">
TargetDate = "08/25/2008 1:00 AM";
BackColor = "white";
ForeColor = "blue";
CountActive = true;
CountStepper = -1;
LeadingZero = true;
DisplayFormat = "%%D%% Days, %%H%% Hours, %%M%% Minutes, %%S%% Seconds, to end of promotion!";
FinishMessage = "It is finally here!";
</script>
<script language="JavaScript" src="http://scripts.hashemian.com/js/countdown.js"></script>&nbsp;</td> -->
  </tr>
  <tr>
    <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top">          <span class="style10"><img src="Images/eucerin_2.gif" width="300" height="700"></span>          <p class="style10">*
  

   Eucerin&reg;: All products are fragrance-free and ideal for use under make-up. </p>
          <p class="style10">*With water-binding Isomerine </p>
          <p class="style10">*Non-comedogenic </p>
          <p class="style10">*Alcohol-free </p>
          <p class="style10">*Colorant-free <br>          
          </p>          </td>
        <td align="left" valign="top"><table width="600" cellspacing="0" class="servicesT" summary="Services, or Links box template">
          <tr>
            <td colspan="2" class="servBodL2"><div align="center">Eucerin Hydro-Balance Refreshing Hydrating Cream &amp; Balancing Toner</div></td>
          </tr>
          <tr>
            <td class="servBodL">Duration of promotion:</td>
            <td class="servBodL"> 17th September 2008 - 24th September 2008</td>
          </tr>
          <tr>
            <td class="servBodL">Original Retail Price: </td>
            <td class="servBodL"><strong><Strike>S$48.30 per set </strike></strong></td>
          </tr>
          <tr>
            <td class="servBodL">NOW:</td>
            <td class="servBodL2">S$29.00<span class="style8">per set (1Eucerin Hydro-Balance Refreshing Hydrating Cream &amp; 1 Balancing Toner ) </span></td>
          </tr>
          <tr>
            <td valign="top" class="servBodL"><p>Discount:</p>
              <p>&nbsp;</p></td>
            <td align="center" valign="middle" class="servBodL"><span class="style9"><img src="Images/discountlogo.gif" width="120" height="120"><span class="style6">SAVE S$19.30</span></span></td>
          </tr>
          <tr>
            <td colspan="2" class="servBodL">Description:<br>
              <p align="left" class="servBodL3"><strong>Read reviews <a href="http://www.fr3b.com/sample/sample_details_review.php?product_id=250708EucerinHydroBalance&pg=1">HERE</a> </strong></p>
              <p align="left" class="servBodL3"><strong>Get FREE Samples <a href="http://www.fr3b.com/sample/sample_details_review.php?product_id=250708EucerinHydroBalance&pg=1">HERE</a>! Try before you buy! </strong></p>                            <a href="/weekly_exclusive_eucerin.php"><img src="Images/eucerin_1.gif" width="600" height="450" border="0"></a></td>
            </tr>
          <br />
        </table>          </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td height="99" colspan="2"><div align="left">
      <form name="form1" method="post" action="weekly_exclusive.php?submit=true">
        <table width="900" cellspacing="0" class="alerts" >
          <tr>
            <td valign="top" class="alertHd">Product Name: </td>
            <td valign="top" class="alertHd">Sets:</td>
            <td valign="top" class="alertHd">Price:</td>
            <td valign="top" class="alertHd">Shipping fee: </td>
            <td valign="top" class="alertHd"><strong>Total Price payable:</strong></td>
          </tr>
          <tr>
            <td rowspan="5" valign="top" class="alertBod">1 Eucerin Balancing Toner (200ml)  &amp;<br>
    1 Eucerin Hydro-Balance Refreshing Hydrating Cream (Light Texture) (50ml)</td>
            <td valign="top" class="alertBod">1</td>
            <td valign="top" class="alertBod">S$ 29</td>
            <td valign="top" class="alertBod">S$ 3.78 </td>
            <td valign="top" class="alertBod"><strong>S$ 32.78</strong></td>
          </tr>
          <tr>
            <td valign="top" class="alertBod">2</td>
            <td valign="top" class="alertBod">S$ 58 </td>
            <td valign="top" class="alertBod">S$ 4.90 </td>
            <td valign="top" class="alertBod"><strong>S$ 62.90 </strong></td>
          </tr>
          <tr>
            <td valign="top" class="alertBod">3</td>
            <td valign="top" class="alertBod">S$ 87 </td>
            <td valign="top" class="alertBod">S$ 4.90 </td>
            <td valign="top" class="alertBod"><strong>S$ 91.90 </strong></td>
          </tr>
          <tr>
            <td class="alertBod">4</td>
            <td valign="top" class="alertBod">S$ 116</td>
            <td valign="top" class="alertBod">S$ 5.70</td>
            <td valign="top" class="alertBod"><strong>S$ 121.70</strong></td>
          </tr>
          <tr>
            <td class="alertBod"><label>5 </label></td>
            <td valign="top" class="alertBod">S$ 145</td>
            <td valign="top" class="alertBod">S$ 5.70 </td>
            <td valign="top" class="alertBod"><strong>S$ 150.70 </strong></td>
          </tr>
          <tr>
            <td colspan="5" class="alertBod">&nbsp;</td>
            </tr>
          <tr bgcolor="#FFFFCC">
            <td colspan="5" class="alertBod"><strong>Delivery service agreement: </strong></td>
          </tr>
          <tr>
            <td colspan="5" class="alertBod">All products will be mailed using Singpost Registered article postage. <br>
Mailing date will range from 3rd -4th day after the promotion date ends. <br>
You will expect to recieve the products within 3-6days after the promotion date ends. </td>
          </tr>
          <tr bgcolor="#FFFFCC">
            <td colspan="5" class="alertBod"><strong> 
Payment details(Payable to):</strong></td>
            </tr>
          <tr>
            <td colspan="5" class="alertBod"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr class="alertBod">
                <td width="25%">Mode of payment accepted: </td>
                <td width="75%">DBS IBANKING / Inter-bank transfer / ATM </td>
              </tr>
              <tr class="alertBod">
                <td>Account type: </td>
                <td>POSB SAVING </td>
              </tr>
              <tr class="alertBod">
                <td>Account number:</td>
                <td>194-16923-0 </td>
              </tr>
              <tr class="alertBod">
                <td colspan="2">Upon payment &amp; checking out successfully, you will receive a confirmation email in less than 24 hours. </td>
                </tr>
            </table>              </td>
            </tr>
          <tr>
            <td colspan="5" class="alertBod"><img src="Images/purchase.gif" width="400" height="40"></td>
          </tr>
          <tr>
            <td colspan="5" class="alertBod"><div align="center" class="style4">***Please fill in the below only after payment***</div></td>
            </tr>
          <tr>
            <td width="200" class="alertBod">Name:</td>
            <td width="242" class="alertBod">Delivery address:</td>
            <td width="146" class="alertBod">Email address: </td>
            <td width="154" class="alertBod">Ib nick: </td>
            <td width="146" class="alertBod">Transaction reference:</td>
          </tr>
          <tr>
            <td class="alertBod"><input name="textfield1" type="text" id="textfield1"></td>
            <td class="alertBod"><input name="textfield2" type="text" size="40"></td>
            <td class="alertBod"><input type="text" name="textfield3"></td>
            <td class="alertBod"><input type="text" name="textfield4"></td>
            <td class="alertBod"><input type="text" name="textfield5"></td>
          </tr>
          <tr>
            <td class="alertBod">Amount paid: </td>
            <td colspan="2" class="alertBod">Qty of Eucerin sets ordered: </td>
            <td colspan="2" rowspan="2" align="right" valign="baseline" class="alertBod"><div align="center"><span class="style6"> </span><br>
                          <input type="image" src="Images/confirmbutton.gif"  width="256" height="59" name="image" value="Submit">
              </div></td>
          </tr>
          <tr>
            <td class="alertBod"><input type="text" name="textfield6"></td>
            <td colspan="2" class="alertBod"><label>
              <input type="radio" name="RadioGroup1" value="1">
1</label>
              <input type="radio" name="RadioGroup1" value="2">
2
<input type="radio" name="RadioGroup1" value="3">
3
<label>
<input type="radio" name="RadioGroup1" value="4">
4
<input type="radio" name="RadioGroup1" value="5">
5</label></td>
            </tr>
          <tr>
            <td colspan="5" class="alertBod"><strong>Payment confirmation receipt : </strong></td>
            </tr>
          <tr>
            <td colspan="5" class="alertBod">Upon payment and successfully check out,you will receive a payment confirmation email before promotion ends(25th September 2008)<br>
If in any case you do not receive this email, you can contact Fr3b.com at helpdesk@fr3b.com<br></td>
            </tr>
          <tr>
            <td colspan="5" class="alertBod"><div align="center"><p><label class="servicesT style5"></label>
                  <label class="servicesT style5"><a href="/terms&conditions.php">RETURN POLICY</a> FOR MORE ENQURIES PLEASE EMAIL TO: helpdesk@fr3b.com<br>
                    COPYRIGHT 2008 FR3B. ALL RIGHTS RESERVED. BIZ REGISTRATION NO: 53114398L <br>
                    </label>
                </p>
                </div></td>
            </tr>
        </table>
        </form>
    </div></td>
  </tr>
</table>
</body>
</html>
