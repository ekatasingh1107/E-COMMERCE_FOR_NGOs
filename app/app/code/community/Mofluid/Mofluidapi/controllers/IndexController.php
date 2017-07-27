<?php
/****************************************************************************
-----------------------------------------------------------------------------
 *  File            :     IndexController.php
 *  Subpackage      :     Mofluidapi
 *  Package         :     Mofluid
 *  Description     :     Mofluidapi is plugin for magento to communicate with mofluid Application
 *  Developer       :     Shashi Badhuk
 *  Team Lead       :     Shashank Singh
 *  Organization    :     Ebizon Net Info Pvt Ltd 
 *  Org URL         :     http://www.ebizontek.com
-----------------------------------------------------------------------------
 *****************************************************************************/
include_once('Service.php');
class Mofluid_Mofluidapi_IndexController extends Mage_Core_Controller_Front_Action {
    /*==============      Invoke First When Web Service Access     ================*/
    public function indexAction() {
       	try {
            $store = $_GET["store"];
            if($store == null || $store =='') {
                $store = 1;
            }
            $service = $_GET["service"];
            $categoryid = $_GET["categoryid"];
			$firstname = $_GET["firstname"];
			$lastname = $_GET["lastname"];
			$email = $_GET["email"];
			$password = $_GET["password"];
			$oldpassword = $_GET["oldpassword"];
			$newpassword = $_GET["newpassword"];
			$productid = $_GET["productid"];
			$custid = $_GET["customerid"];
			$billAdd = $_GET["billaddress"];
			$shippAdd = $_GET["shippaddress"];
			$pmethod = $_GET["paymentmethod"];
			$smethod = $_GET["shipmethod"];
			$transid = $_GET["transactionid"];
			$product = $_GET["product"];
			$shippCharge = $_GET["shippcharge"];
			$search_data = $_GET["search_data"];
			$username = $_GET["username"];
			// Get Requested Data for Push Notification Request
			$deviceid = $_GET["deviceid"];
			$pushtoken = $_GET["pushtoken"];
			$platform = $_GET["platform"];
			$appname = $_GET["appname"];
			$description = $_GET["description"];
			$profile = $_GET["profile"];
			$paymentgateway = $_GET["paymentgateway"];
			$couponCode = $_GET["couponCode"];
			$orderid = $_GET["orderid"];
			$pid = $_GET["pid"];
			$products = $_GET["products"];
			$address=$_GET["address"];
			$country = $_GET["country"];
			$grand_amount = $_GET["grandamount"];
			$order_sub_amount = $_GET["subtotal_amount"];
			$discount_amount = $_GET["discountamount"];
			$mofluidpayaction = $_GET["mofluidpayaction"];
			$postdata = $_POST;
			$mofluid_payment_mode = $_GET["mofluid_payment_mode"];
			$product_id = $_GET["product_id"];
			$gift_message = $_GET["message"];
		    $mofluid_paymentdata  = $_GET["mofluid_paymentdata"];
			$mofluid_ebs_pgdata = $_GET["DR"];
			$curr_page = $_GET["currentpage"];
			$page_size = $_GET["pagesize"];
	        $sortType = $_GET["sorttype"];
	        $sortOrder = $_GET["sortorder"];
	        $saveaction = $_GET["saveaction"];
	        $mofluid_orderid_unsecure = $_GET["mofluid_order_id"];	  
            $currency = $_GET["currency"]; 
            $price = $_GET["price"];
	        $from = $_GET["from"];
	        $to = $_GET["to"];
            $is_create_quote = $_GET["is_create_quote"];
            $find_shipping = $_GET["find_shipping"];
            $messages = $_GET["messages"];
            $theme = $_GET["theme"];
            $billshipflag = $_GET["shipbillchoice"];
            $ws_service = new Service();
            if($store == -1 || $store == null || $store == "" ) {
                $this->ws_store404($store);
        	}
            else {
                if($service == "category") {
                	$res = $ws_service->ws_category($store, $service);
                    echo $_GET["callback"].'('.json_encode($res).');';
				}
                elseif($service == "subcategory") {
					$res = $ws_service->ws_subcategory($store, $service, $categoryid);
                    echo $_GET["callback"].'('.json_encode($res).');';
				}
                elseif($service == "products") {
                    $res = $ws_service->ws_products($store, $service, $categoryid, $curr_page ,$page_size, $sortType, $sortOrder,  $currency);
                    echo $_GET["callback"].'('.json_encode($res).');';
                }
                else if($service == "validate_currency") {
           			$res = $ws_service->ws_validatecurrency($store, $service, $currency, $paymentgateway);
           			echo $_GET["callback"].'('.json_encode($res).');';
				} 
		        elseif($service == "createuser") {
                    $res = $ws_service->ws_createuser($store, $service, $firstname, $lastname, $email, $password);
                    echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "productdetail") {
                    $res = $ws_service->ws_productdetail($store, $service, $productid,  $currency);
                    echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "productinfo") {
				try {
                    $res = $ws_service->ws_productinfo($store, $service, $productid,  $currency);
                    echo $_GET["callback"].'('.json_encode($res).');';
				}
				catch(Exception $ex) {
				  echo 'Error'.$ex->getMessage();
				}
				}
				elseif($service == "currency") {
                    $res = $ws_service->ws_currency($store, $service);
                    echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "createorder") {
                    $res = $ws_service->ws_createorder($store, $service, $custid, $pmethod, $smethod, $transid, $product, $shippCharge, $couponCode, $grand_amount, $order_sub_amount, $discount_amount,$gift_message);
                    echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "setaddress") {
				    $res = $ws_service->ws_setaddress($store, $service, $custid, $address, $email, $saveaction);
                    echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "storedetails") {
					$res = $ws_service->ws_storedetails($store, $service, $theme, $currency);
                    echo $_GET["callback"].'('.json_encode($res).');';    
				}
				elseif($service == "search") {
					$res = $ws_service->ws_search($store, $service,$search_data, $curr_page ,$page_size,$sortType,$sortOrder,  $currency);
			   		echo $_GET["callback"].'('.json_encode($res).');';
			   	}
				elseif($service == "getcustomer") {
					$res = $ws_service->ws_getCustomerId($store, $service, $email);
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "verifylogin") {
					$res = $ws_service->ws_verifyLogin($store, $service, $username, $password);
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "orderinfo") {
					$res = $ws_service->orderInfo($cust_id, $orderid, $store, $currency); 
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "orderupdate") {
					$res = $ws_service->updateOrderStatus($cust_id, $orderid, $store, $currency); 
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "myorders") {
					$res = $ws_service->ws_myOrder($custid, $curr_page, $page_size, $store, $currency);
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "myprofile") {
					$res = $ws_service->ws_myProfile($custid);
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "changeprofilepassword") {
					$res = $ws_service->ws_changeProfilePassword($custid, $username, $oldpassword, $newpassword, $store);
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "productsearchhelp") {
					$res = $ws_service->ws_productSearchHelp($store);
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "register_push") {
					$res = $ws_service->mofluid_register_push($store, $deviceid, $pushtoken, $platform, $appname, $description);
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "setprofile") {
					$res = $ws_service->ws_setprofile($store, $service, $custid, $billAdd, $shippAdd,$profile);
					echo $_GET["callback"].'('.json_encode($res).');';
				} 
				elseif($service == "forgotPassword") {                                               
					 $res = $ws_service->ws_forgotPassword($email);
					 echo  $_GET["callback"].'('.json_encode($res).');';
				 } 
				elseif($service == "termCondition") {  
					 $res = $ws_service->ws_termCondition($store);
					 echo $_GET["callback"].'('.json_encode($res).');';
				 }
				 elseif($service == "productQuantity") { 												 
					 $res = $ws_service->ws_productQuantity($product);
					 echo $_GET["callback"].'('.json_encode($res).');';
				 }  
				 elseif($service == "countryList") {  
					 $res = $ws_service->ws_countryList($store,$paymentgateway,$pmethod);
					 echo $_GET["callback"].'('.json_encode($res).');';
				 }
				 elseif($service == "listShipping") {
					 $res = $ws_service->ws_listShipping();
					 echo $_GET["callback"].'('.json_encode($res).');';
				} 
				elseif($service == "shipmyidenabled") {
					 $res = $ws_service->ws_shipmyidenabled();
					 echo $_GET["callback"].'('.json_encode($res).');';
				}
				else if($service == "preparequote") {
				     $res = $ws_service->prepareQuote($custid, $products, $store, $address, $smethod, $couponCode, $currency, $is_create_quote, $find_shipping, $theme);
					 echo $_GET["callback"].'('.json_encode($res).');';   
				}
				else if($service == "placeorder") {
				      $res = $ws_service->placeorder($custid, $products, $store, $address, $couponCode, $is_create_quote, $transid, $pmethod, $smethod, $currency, $messages, $theme);
					 echo $_GET["callback"].'('.json_encode($res).');';   
				}
				elseif($service == "validateCoupon") {
					 $res = $ws_service->ws_validateCoupon($couponCode, $custid);
					 echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "couponDetails") {
					 $res = $ws_service->ws_couponDetails($couponCode, $store,  $currency);
					 echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "sendorderemail") {
					 $res = $ws_service->ws_sendorderemail($orderid);
					 echo $_GET["callback"].'('.json_encode($res).');';
				}  
				elseif($service == "getShippingDetail") {
					 $res = $ws_service->ws_getShippingDetail($pid,$address, $currency);
					 echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "getFeaturedProducts") {  					 
					 $res = $ws_service->ws_getFeaturedProducts($currency, $service, $store);
					 echo $_GET["callback"].'('.json_encode($res).');';
				} 
				else if($service == "mofluidappcountry") {
					 $res = $ws_service->ws_mofluidappcountry($store);
					 echo $_GET["callback"].'('.json_encode($res).');';
				}
				else if($service == "paymentresponse") {
					$res = $ws_service->ws_printpaymentresponse($store, $mofluidpayaction, $postdata,$mofluid_payment_mode, $mofluid_orderid_unsecure); 
				}
                else if($service == "authorizepaymentresponse") {
                    $res = $ws_service->ws_authorizepaymentresponse($store, $service, $mofluid_orderid_unsecure, $postdata);
                }
				elseif($service == "checkGiftMessage") {
					$res = $ws_service->ws_checkGiftMessage($store);
					echo $_GET["callback"].'('.json_encode($res).');';
				}  
				elseif($service == "checkProductGiftMessage") {
					$res = $ws_service->ws_checkProductGiftMessage($store, $product_id);
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				else if($service == "mofluidappstates") {
					$res = $ws_service->ws_mofluidappstates($store, $country);
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				elseif($service == "countryStateList") {    
					$res = $ws_service->ws_countryStateList($store,$country);
					echo $_GET["callback"].'('.json_encode($res).');';
				} 
				elseif($service == "getmofluidextension") {
					$res = $ws_service->ws_getmofluidextension();
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				else if($service == "getpaymentmethod") {
					$res = $ws_service->ws_getpaymentmethod();
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				else if($service == "paymentresponse") {
					$res = $ws_service->ws_printpaymentresponse($store, $mofluidpayaction, $postdata, $mofluid_payment_mode); 
					echo $_GET["callback"].'('.json_encode($res).');';
				}
				else if($service == "ebspayment") {
					$res = $ws_service->ws_ebspayment($store, $service, $mofluid_paymentdata); 
				}
				else if($service == "mofluid_ebs_pgresponse") {
					$res = $ws_service->ws_mofluid_ebs_pgresponse($store, $service, $mofluid_ebs_pgdata); 
				}
                else if($service == "mofluid_sendorder_mail") {
					$res = $ws_service->ws_sendorderemail($orderid);  
                    echo $_GET["callback"].'('.json_encode($res).');';          
                }
                else if($service == "get_configurable_product_details") {
					$res = $ws_service->get_configurable_products($productid, $currency);  
                    echo $_GET["callback"].'('.json_encode($res).');';          
                }
                else if($service == "get_currency") {
					$res = $ws_service->get_currency($store, $service);  
					echo $_GET["callback"].'('.json_encode($res).');';    
				}
else if($service == "mofluid_reorder") {
					$res = $ws_service->ws_mofluid_reorder($store, $service, $pid, $orderid, $currency);  
                    echo $_GET["callback"].'('.json_encode($res).');';          
  }
elseif($service == "mofluidUpdateProfile") {
	$res = $ws_service->mofluidUpdateProfile($store, $service, $custid, $billAdd, $shippAdd,$profile, $billshipflag);
	echo $_GET["callback"].'('.json_encode($res).');';
} 



				else if($service == "convert_currency") {
					$res = $ws_service->convert_currency($price,$from,$to);
					echo $_GET["callback"].'('.json_encode($res).');';    
				}
				else {
					$this->ws_service404($service);
				}
			}
        }
        catch(Exception $exc) {
            echo 'Exception : '.$exc;
        }
    }

   /*=====================      Handle When Store Not Found      =========================*/
    public function ws_store404($store) {
          echo 'Store 404 Error :  Store '.$store.' is not found on your host ';
    }
   /*=====================      Handle When Service Not Found      =========================*/
    public function ws_service404($service){
         if($service == "" || $service == null)
             echo 'Service 404 Error :  No Such Web Service found under Mofluid APIs at your domain';
         else
             echo 'Service 404 Error : '.$service.' Web Service is not found under Mofluid APIs at your domain';
    }
}
