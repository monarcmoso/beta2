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
.style8 {background-color: #f4f4f4; border: 1px dotted #CEDCEA; text-align: left; font-family: Tahoma; font-size: large; color: #404040; }
.style9 {font-size: large}
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
   <!--  <td colspan="2" class="servBodL3"> -->
<!-- <script language="JavaScript">
TargetDate = "08/20/2008 12:00 AM";
BackColor = "white";
ForeColor = "blue";
CountActive = true;
CountStepper = -1;
LeadingZero = true;
DisplayFormat = "%%D%% Days, %%H%% Hours, %%M%% Minutes, %%S%% Seconds, to our next HOT deal!.";
FinishMessage = "It is finally here!";
</script>
<script language="JavaScript" src="http://scripts.hashemian.com/js/countdown.js"></script>&nbsp;</td> -->
  </tr>
  <tr>
    <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><img src="http://www.fr3b.com/sample/ProductImages/Sansho/SkinRenewalGel.gif">
          <p class="style8 style9">*NON Acidic / No scrub exfoliation</p>
          <p class="style8">*NO AHA , NO BHA</p>
          <p class="style8">*100% Made in Japan</p>
          <p><span class="style8">*SALON FORMULA</span> </p></td>
        <td align="left" valign="top"><table width="600" cellspacing="0" class="servicesT" summary="Services, or Links box template">
          <tr>
            <td colspan="2" class="servBodL2">

</td>
          </tr>
          <tr>
            <td colspan="2" class="servBodL2"><div align="center">SANSHO Skin Renewal Gel </div></td>
          </tr>
          <tr>
            <td class="servBodL">Duration of promotion:</td>
            <td class="servBodL">21st October -4th November 2008 <br>
              [Back by popular demand and Fr3b'rs request!]</td>
          </tr>
          <tr>
            <td class="servBodL">Original Retail Price: </td>
            <td class="servBodL">- / New Exclusive Packaging</td>
          </tr>
          <tr>
            <td class="servBodL">NOW:</td>
            <td class="servBodL2">S$17.00</td>
          </tr>
          <tr>
            <td valign="top" class="servBodL">Discount:</td>
            <td valign="top" class="servBodL"><strong>FREE Registered Postage!<br>
              </strong><span class="style5">exclusively brought to you by SANSHO Cosmetics</span></td>
          </tr>
          <tr>
            <td colspan="2" class="servBodL"><p>Description:</p>
              <center>
<!-- saved from url=(0013)about:internet -->
<p align="left" class="servBodL3"><strong>Watch video <a href="http://www.sanshocosmetics.com/en/product/index.php">HERE</a> </strong></p>
<p align="left" class="servBodL3"><strong>Read reviews <a href="http://www.fr3b.com/sample/sample_details_review.php?product_id=250508SanshoSkinRenewalGel&pg=1">HERE</a> </strong></p>
<p align="left" class="servBodL3"><strong>Get FREE Samples <a href="http://www.fr3b.com/sample/sample_details_review.php?product_id=250508SanshoSkinRenewalGel&pg=1">HERE</a>! Try before you buy! </strong></p>
<p align="left"><strong>Restores your skin simply, quickly, effectively</strong></p>
<p align="left">In pursuit of smoother, brighter skin? Seeking a solution to banish dry skin or refine pores? You're not alone. Many women continue to try different brands without success or put their trust in expensive cosmetics, hoping that the price will be borne out by results if they use the products long enough. Skin Renewal Gel is different. Just as the name suggests, Skin Renewal Gel removes old keratin to renew and restore the youthful vitality of your complexion. Without this base, expensive cosmetics just don't work the way they're meant to!</p>
              <p align="left"><strong>Benefits</strong></p>
              <p align="left">Simply apply the gel and massage in gently. Old keratin is removed before your eyes. Unlike other products that rely on acids or scrubs, the ingredients in Skin Renewal Gel stick fast to old keratin and impurities to remove them without causing stress to the skin. Once this old keratin is removed, it is easier for lotions and essences to be absorbed, and skin feels firm and moisturized as cell turnover is improved. Experience the new life given to your skin as the active ingredients in Skin Renewal Gel even out pigmentation and reduce discoloration.</p>
              <p align="left"><strong>Active Ingredients</strong></p>
              <p align="left">Evening Primrose extract Antioxidant/Whitening properties<br>
                Seaweed extract Moisturizing/Whitening properties</p>
              <p align="left"><strong>Usage</strong></p>
              <p align="left"> 1. Wash your face with water and dry well before using.<br>
  2. Place a proper amount of gel on your palm and spread over your face.<br>
  3. Massage your entire face lightly with a circular motion by fingers.<br>
  4. Keratin, which is the insoluble protein substance on your skin, will then surface naturally.<br>
  5. Rinse off with water.<br>
  6. Tone your face with lotion or essence as finishing. </p>
              </center             ></td>
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
            <td width="177" class="alertHd">Product Name: </td>
            <td width="227" class="alertHd">Quantity:</td>
            <td width="157" class="alertHd">Price:</td>
            <td width="160" class="alertHd">Shipping fee: </td>
            <td width="167" class="alertHd"><strong>Total Price payable:</strong></td>
          </tr>
          <tr>
            <td rowspan="5" valign="top" class="alertBod">SANSHO Skin Renewal Gel 45ml </td>
	    
            <td valign="top" class="alertBod">
              <label>1<br>
                </label>
            </p></td>
	    <td class="alertBod"><label></label>
	     S$ 17.00 <br>
	      </td>
            <td valign="top" class="alertBod">FREE</td>
            <td valign="top" class="alertBod"><strong>S$ 17<strong>.00 </strong></strong></td>
          </tr>
          <tr>
            <td class="alertBod">2</td>
            <td valign="top" class="alertBod">S$ 34.00 </td>
            <td valign="top" class="alertBod">FREE</td>
            <td valign="top" class="alertBod"><strong>S$ 34<strong>.00 </strong></strong></td>
          </tr>
          <tr>
            <td valign="top" class="alertBod">3</td>
            <td valign="top" class="alertBod">S$ 51.00 </td>
            <td valign="top" class="alertBod">FREE</td>
            <td valign="top" class="alertBod"><strong>S$ 51<strong>.00 </strong></strong></td>
          </tr>
          <tr>
            <td class="alertBod">4</td>
            <td valign="top" class="alertBod">S$ 68.00 </td>
            <td valign="top" class="alertBod">FREE</td>
            <td valign="top" class="alertBod"><strong>S$ 68<strong>.00 </strong></strong></td>
          </tr>
          <tr>
            <td class="alertBod"><label>5
              </label></td>
            <td valign="top" class="alertBod">S$ 85.00</td>
            <td valign="top" class="alertBod">FREE</td>
            <td valign="top" class="alertBod"><strong>S$ 85.00 </strong></td>
          </tr>
          <tr>
            <td colspan="5" class="alertBod">&nbsp;</td>
            </tr>
          <tr bgcolor="#FFFFCC">
            <td colspan="5" class="alertBod"><strong>Delivery service agreement: </strong></td>
          </tr>
          <tr>
            <td colspan="5" class="alertBod">All products will be mailed using Singpost Registered article postage. <br>
Mailing date will range from 3rd -5th day after the promotion date ends. <br>
You will expect to recieve the products within 4-7days from the end of the promotion date. </td>
          </tr>
          <tr bgcolor="#FFFFCC">
            <td colspan="5" class="alertBod"><strong> 
Payment details(Payable to):</strong></td>
            </tr>
          <tr>
            <td colspan="5" class="alertBod"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr class="alertBod">
                <td width="25%">Mode of payment accepted: </td>
                <td width="75%">ONLY DBS IBANKING </td>
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
            <td class="alertBod">Name:</td>
            <td class="alertBod">Delivery address:</td>
            <td class="alertBod">Email address: </td>
            <td class="alertBod">Ib nick: </td>
            <td class="alertBod">Transaction reference:</td>
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
            <td colspan="2" class="alertBod">Qty of SANSHO skin renewal gel ordered: </td>
            <td colspan="2" rowspan="2" align="right" valign="baseline" class="alertBod"><div align="center"><span class="style6"><!-- SOLD OUT  --></span><br>
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
            <td colspan="5" class="alertBod">Upon payment and successfully check out,you will receive a payment confirmation email before promotion ends(4th November 2008)<br>
              If in any case you do not receive this email, you can contact Fr3b.com at helpdesk@fr3b.com<br></td>
            </tr>
          <tr>
            <td colspan="5" class="alertBod"><label>
              </label>              <div align="center">
                <p>
                  <label class="servicesT style5"></label>
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
