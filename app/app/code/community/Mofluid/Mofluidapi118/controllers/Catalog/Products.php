<?php
/*
   Mofluidapi118_Catalog_Products v0.0.1
   (c) 2009-2013 by Mofluid. All rights reserved.
   Shashi Badhuk   
*/

/**
   * Mofluidapi118_Products
   * 
   * 
   * @package    Mofluid
   * @subpackage Mofluidapi118
   * @author     Shashi Badhuk <shashi.badhuk@ebizontek.com>
   */
class Mofluidapi118_Products {
    public $_store, $_service,  $_product,  $_product_id, $_currency, $_base_currency;
    /**
       * 
       * Initialize Mofluidapi118_Catalog_Products Class
       *
       * @param integer $store  store id
       * @param string $service  name of the service api
       * @param integer $productid  entity id of the product
       * @param string $currency  currency code in which price is required
       */
    function __construct($store, $service, $productid, $currency) {
         $this->_store = $store;
         $this->_service = $service;
         $this->_product_id = $productid;
         $this->_currency = $currency;
         $this->_base_currency = Mage::app()->getStore()->getBaseCurrencyCode();
         $this->_product = Mage::getModel('catalog/product')->load($this->_product_id);
    }
    /**
       * 
       * Return complete product information
       * @name : getCompleteProductInfo
       * @access : public
       *
       * @param None
       * @return array
       */
   public function getCompleteProductInfo() {
	     $productDescription = array();
		$product_type = $this->_product->getTypeId();
		switch($product_type) {
		    case 'simple':
		        $productDescription = $this->getSimpleProductInfo();
		    break;
		    case 'configurable':
		        $productDescription = $this->getConfigurableProductInfo();
		    break;
		    case 'bundle':
		        $productDescription = $this->getBundleProductInfo();
		    break;
		    case 'grouped':
		        $productDescription = $this->getGroupedProductInfo();
		    break;
		    case 'virtual':
		        $productDescription = $this->getVirtualProductInfo();
		    break;
		    case 'downloadable':
		        $productDescription = $this->getDownloadableProductInfo();
		    break;
		    default:
		        $productDescription = $this->getSimpleProductInfo();
		    break;
		}
		return $productDescription;
	}
	/**
       * 
       * Return basic product information
       * @name : getBasicProductInfo
       * @access : public
       *
       * @param integer $id entity id of the product
       * @return array
       */
     public function getBasicProductInfo($id) {
        $productDescription = array();
        $_product = Mage::getModel('catalog/product')->load($id);
       
        $productDescription["pro_status"] =  $_product->status;
        $productDescription["id"] =  $_product->getId();
        $productDescription["sku"] =  $_product->getSku();
        $productDescription["type"] =  $_product->getTypeId();       
	   $productDescription["url"] =  $_product->getProductUrl();
	   $productDescription["visibility"] =  $_product->isVisibleInSiteVisibility();//getVisibility(); 
	   $productDescription["weight"] =  $_product->getWeight();
	   $productDescription["status"] =  $_product['status']; 
	   $productDescription["category"] =  $_product->getCategoryIds(); 
	   $productDescription["general"]["name"] = $_product->getName();
	   $productDescription["general"]["sku"] =  $_product->getSku();
	   $productDescription["general"]["weight"] = number_format((float)$_product->getWeight(), 2);
	   $productDescription["price"] =  $this->getProductPrice($id); 
	   $productDescription["image"] = $this->getMofluidMediaGalleryImages($id);
	   $productDescription["reviews"] = $this->getProductReview($id); 
	  
	 
	   return $productDescription;
    }
    /**
	 * 
	 * Return product information of simple type of product
	 * @name : getSimpleProductInfo
	 * @access : public
	 *
	 * @param None
	 * @return array
	 */
    public function getSimpleProductInfo() {
          $productDescription = array();
          $productDescription =  $this->getBasicProductInfo($this->_product_id);
          $productDescription["description"]['short'] =  base64_encode($this->_product->getShortDescription());
	      $productDescription["description"]['full'] =  base64_encode($this->_product->getDescription());
	      $productDescription["stock"] = $this->getProductStockInfo();
          $productDescription["custom"]["options"] = $this->getProductCustomOptions();
          $productDescription["custom"]["attributes"] = $this->getProductCustomAttributes();
          $productDescription["products"]["related"] = $this->getRelatedProducts();
       		
       	//	echo "<pre>"; print_r($productDescription); die;
       		return $productDescription;
    } 
    /**
	* 
	* Return product information of configurable type of product
	* @name : getConfigurableProductInfo
	* @access : public
	*
	* @param None
	* @return array
	*/
    public function getConfigurableProductInfo() {
           $productDescription = array();
          $configurable_products = array();
          $custom_attr = array();
          $attribute_dropdown = array();
          $simple_product_counter  = 0;
          $productDescription =  $this->getSimpleProductInfo();
          $conf = Mage::getModel('catalog/product_type_configurable')->setProduct($this->_product);
          
          $simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
         
          foreach($simple_collection as $simple_product){
          
				$tax_type=Mage::getStoreConfig('tax/calculation/price_includes_tax');
                //start Custom code by sumit
				 $a = Mage::getModel('catalog/product')->load($simple_product->getId());
    			 $taxClassId = $a->getData("tax_class_id");
    			 $taxClasses = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
    			 $taxRate = $taxClasses["value_".$taxClassId];
    			 $tax_price = (($taxRate)/100) *  ($simple_product->getPrice());
    				
    			$fprice=$a->getFinalPrice();	
    		
    			//End custom code by sumit
              
              
              $configurable_products[$simple_product_counter] =  $this->getBasicProductInfo($simple_product->getId());
              
                if($tax_type==0)
    			{
    				$configurable_products[$simple_product_counter]['price']['final']=$fprice;
    			}
    			else
    			{
    				$configurable_products[$simple_product_counter]['price']['final']=$fprice-$tax_price;
    			} 
            
              $configurable_products[$simple_product_counter]["stock"] = $this->getProductStockInfoById($simple_product->getId());
              $attributes =  $simple_product->setPositionOrder('desc')->getAttributes();
              $custom_attr_count = 0;
              foreach ($attributes as $attribute) {    
              	if($attribute->is_user_defined && $attribute->is_visible && $attribute->is_configurable ){
			       $attribute_value = $attribute->getFrontend()->getValue($simple_product);
			       //echo "<br />".$attribute->is_configurable." ".$attribute->getAttributeCode()." => ".$attribute_value; 
			       if($attribute_value == null || $attribute_value == "" || $attribute_value == "No") {
			           continue;
			       }
			       else {
			             $product_attribute_set = array();
			             
			             $configProd=Mage::getModel('catalog/product')->load($this->_product_id);
                            $AllowAttributes=$configProd->getTypeInstance(true)->getConfigurableAttributes($configProd);
                            $basePrice = $configProd->getFinalPrice();
                            
                            $optionData = array();
                            $_attribute_id =  $attribute->getId();
                		   foreach ($AllowAttributes as $current_attribute) {
                		       $productAttribute = $current_attribute->getProductAttribute();
                		       
                			  $attributeId = $productAttribute->getId();
                			  // run rest of when  color attribute is looped. 
                			  if($productAttribute->getId()!=$_attribute_id){
                				continue; 
                			  } 
			                 $prices = $current_attribute->getPrices();
               			  if (is_array($prices)) {
                    		      foreach ($prices as $value) {
							     if($value['value_index'] == $attribute->getSource()->getOptionId($attribute_value)) {
								    $optionData = $value; 
                        				}
                    			 }
                			  }
		                  }
                            try {
						  $current_option_price = $optionData['pricing_value'];
					 	
						  if($optionData['is_percent']) {
						  // $current_option_price = $this->getFormattedProductPrice((floatval($optionData['pricing_value'])*floatval($optionData['is_percent']))/100, 1);
						  	$current_option_price = $this->getFormattedProductPrice(((floatval($optionData['pricing_value'])*floatval($optionData['is_percent']))* $basePrice)/100, 1);
						  	
						  } 
						   
						  else {
							  $current_option_price = $this->getFormattedProductPrice($optionData['pricing_value'], 1);
						  }
                            }
                            catch(Exception $ex) {
                                $current_option_price = 0;
                            }
                            
                         $current_option_tax_price=(($taxRate)/100) *  ($current_option_price);
                         $product_attribute_set["id"] = $attribute->getId();
			             $product_attribute_set["code"]  = $attribute->getAttributeCode();
			             $product_attribute_set["label"] = $attribute->getStoreLabel($simple_product); 
			             $product_attribute_set["value"] = $attribute_value;
			             $product_attribute_set["value_id"] = $attribute->getSource()->getOptionId($attribute_value); 
			            
			             if($tax_type==0)
			             {
			             	$product_attribute_set["price"] = $current_option_price; 
			             }
			             else
			             {
			             	$product_attribute_set["price"] = $current_option_price-$current_option_tax_price;
			             }
			         
			             
			              $custom_attr["options"][$custom_attr_count] = $product_attribute_set;
				       $attribute_dropdown[$simple_product_counter][]= $product_attribute_set;
				       
				       ++$custom_attr_count;
				   }
			    }
			   
              }
              $i++;
              $configurable_products[$simple_product_counter]["attributes"] = $custom_attr;
              
              $simple_product_counter++;
          }
          $productDescription["products"]["associated"]["childs"] = $configurable_products;
          $productDescription["products"]["associated"]["attributes"] = $attribute_dropdown;
          //echo "<pre>"; print_r($productDescription["products"]); exit;
          return $productDescription;
     }
     /**
	* 
	* Return product information of bundle type of product
	* @name : getBundleProductInfo
	* @access : public
	*
	* @param None
	* @return array
	*/
    public function getBundleProductInfo() {
        //Get Product Bundle Items 
        $productDescription = array();
        $product_counter = 0;
        $bundled_products =  $this->_product->getTypeInstance(true);
        $productDescription = $this->getBasicProductInfo($this->_product_id);
        $productDescription["description"]['short'] =  base64_encode($this->_product->getShortDescription());
	   $productDescription["description"]['full'] =  base64_encode($this->_product->getDescription());
	   $productDescription["stock"] = $this->getProductStockInfo();
        $productDescription["custom"]["options"] = $this->getProductCustomOptions();
        $productDescription["custom"]["attributes"] = $this->getProductCustomAttributes();
        $productDescription["products"]["related"] = $this->getRelatedProducts();
        $all_bundled_products = $bundled_products->getSelectionsCollection($bundled_products->getOptionsIds($this->_product),  $this->_product);
        foreach($all_bundled_products as $current_bundled_product){
            $productDescription["products"]["bundle"][$product_counter] = $this->getBasicProductInfo($current_bundled_product->getId());
            $product_counter++;
        }
        return $productDescription;
    }
    /**
	* 
	* Return product information of grouped type of product
	* @name : getGroupedProductInfo
	* @access : public
	*
	* @param None
	* @return array
	*/
    public function getGroupedProductInfo() {
    	   //Get Associated Product for Grouped Type
    	
		$productDescription = array();
		$product_counter = 0;
		$stock_counter=0;
		
		$productDescription = $this->getBasicProductInfo($this->_product_id);
		$group = Mage::getModel('catalog/product_type_grouped')->setProduct($this->_product);
		$productDescription["description"]['short'] =  base64_encode($this->_product->getShortDescription());
	  $productDescription["description"]['full'] =  base64_encode($this->_product->getDescription());
	  $productDescription["stock"] = $this->getProductStockInfo();
	  $productDescription["custom"]["options"] = $this->getProductCustomOptions();
      $productDescription["custom"]["attributes"] = $this->getProductCustomAttributes();
      $productDescription["products"]["related"] = $this->getRelatedProducts();
      $group_collection = $group->getAssociatedProductCollection();
        
      
        foreach ($group_collection as $group_product) {
            $productDescription["products"]["grouped"][$product_counter] = $this->getBasicProductInfo($group_product->getId());
            $productDescription["products"]["grouped"][$product_counter]["stock"] = $this->getProductStockInfoById($group_product->getId());
       		if($productDescription["products"]["grouped"][$product_counter]["stock"]["is_in_stock"]>0 && $productDescription["products"]["grouped"][$product_counter]["stock"]["qty"]>0)
       		{
       			$stock_counter=1;	
       		}
       		$product_counter++;
        }
        
        if($stock_counter)
        {
        	$productDescription["show_stock_status"]=1;
        }
        else
        {
        	$productDescription["show_stock_status"]=0;
        }
        
        
        
     //   $productDescription["show_stock_status"]=$count;
        return $productDescription;
    }
     /**
	* 
	* Return product information of virtual type of product
	* @name : getVirtualProductInfo
	* @access : public
	*
	* @param None
	* @return array
	*/
    public function getVirtualProductInfo() {
        //Get Virtual Product Info
    	   $productDescription = array();
        $productDescription = $this->getSimpleProductInfo();
        return $productDescription;
    }
    /**
	* 
	* Return product information of downloadable type of product
	* @name : getDownloadableProductInfo
	* @access : public
	*
	* @param None
	* @return array
	*/
    public function getDownloadableProductInfo() {
        $productDescription = array();
        $productDescription = $this->getBasicProductInfo($this->_product_id);
        $download_product_info = array();
        $link_counter =0;
        $download_product_info["default"]["id"] = $this->_product->getId();
        $download_product_info["default"]["links_title"] = $this->_product->getLinksTitle();
        $download_product_info["default"]["samples_title"] = $this->_product->getSamplesTitle();
        $download_product_info["default"]["links_purchased_separately"] = $this->_product->getLinksPurchasedSeparately();
	   $product_links = Mage::getModel('downloadable/product_type')->getLinks($this->_product);
	   $product_samples = Mage::getModel('downloadable/product_type')->getSamples($this->_product);
	    foreach ($product_samples as $sample){
		   $download_product_info["samples"][$link_counter]["id"] = $sample->getId();
		   $download_product_info["samples"][$link_counter]["product_id"] = $sample->getProductId();
		   $download_product_info["samples"][$link_counter]["sample"]["url"] = $sample->getSampleUrl();
		   $download_product_info["samples"][$link_counter]["sample"]["file"]= $sample->getSampleFile();
		   $download_product_info["samples"][$link_counter]["sample"]["type"] = $sample->getSampleType();
		   $download_product_info["samples"][$link_counter]["sort_order"] = $sample->getSortOrder();
		   $download_product_info["samples"][$link_counter]["title"] = $sample->getTitle();
		   $download_product_info["samples"][$link_counter]["default_title"] = $sample->getDefaultTitle();
		   $download_product_info["samples"][$link_counter]["store_title"] = $sample->getStoreTitle();
		   $link_counter++;
	    }
	    $link_counter = 0;
	    foreach ($product_links as $link){
		   $download_product_info["links"][$link_counter]["id"] = $link->getId();
		   $download_product_info["links"][$link_counter]["product_id"] = $link->getProductId();
		   $download_product_info["links"][$link_counter]["number_of_downloads"] = $link->getNumberOfDownloads();
		   $download_product_info["links"][$link_counter]["is_shareable"] = $link->getIsShareable();
		   $download_product_info["links"][$link_counter]["sort_order"] = $link->getSortOrder();
		   $download_product_info["links"][$link_counter]["title"] = $link->getTitle();
		   $download_product_info["links"][$link_counter]["default_title"] = $link->getDefaultTitle();
		   $download_product_info["links"][$link_counter]["store_title"] = $link->getStoreTitle();
		   $download_product_info["links"][$link_counter]["link"]["url"] = $link->getLinkUrl();
		   $download_product_info["links"][$link_counter]["link"]["file"] = $link->getLinkFile();
		   $download_product_info["links"][$link_counter]["link"]["type"] = $link->getLinkType();
		   $download_product_info["links"][$link_counter]["sample"]["url"] = $link->getSampleUrl();
		   $download_product_info["links"][$link_counter]["sample"]["file"] = $link->getSampleFile();
		   $download_product_info["links"][$link_counter]["sample"]["type"] = $link->getSampleType();
		   $download_product_info["links"][$link_counter]["price"]["regular"] = $link->getPrice();
		   $download_product_info["links"][$link_counter]["price"]["default"] = $link->getDefaultPrice();
		   $download_product_info["links"][$link_counter]["price"]["website"] = $link->getWebsitePrice();
		   $link_counter++;
	    }
	    $productDescription["products"]["download"]["info"] = $download_product_info;
	    return $productDescription;
     }
     /**
	* 
	* Return product custom options 
	* @name : getProductCustomOptions
	* @access : public
	*
	* @param None
	* @return array
	*/
    public function getProductCustomOptions() {
         //Get Product Custom Options 
	    $has_custom_option = 0;
	    $all_custom_option_array = array();
	    $basecurrencycode = Mage::app()->getStore()->getBaseCurrencyCode();
	     $this->_product = Mage::getModel('catalog/product')->load( $this->_product_id);
         $product_custom_options =  $this->_product->getOptions();
         $optStr = ""; $inc=0;
           foreach($product_custom_options as $optionKey => $optionVal) {
              $has_custom_option = 1;
              $all_custom_option_array[$inc]['all'] = $optionVal->getData();
              $all_custom_option_array[$inc]['custom_option_name']= $all_custom_option_array[$inc]['all']["default_title"];
              $all_custom_option_array[$inc]['custom_option_id']=$all_custom_option_array[$inc]['all']["option_id"];
              $all_custom_option_array[$inc]['custom_option_is_required']=$all_custom_option_array[$inc]['all']["is_require"];
              $all_custom_option_array[$inc]['custom_option_type']=$all_custom_option_array[$inc]['all']["type"];
              $all_custom_option_array[$inc]['sort_order'] = $all_custom_option_array[$inc]['all']["sort_order"];
              if($all_custom_option_array[$inc]['all']['default_price_type'] == "percent") {
                  $all_custom_option_array[$inc]['all']['price'] = number_format((($this->_product->getFinalPrice()*round($all_custom_option_array[$inc]['all']['price']*10,2)/10)/100),2);
              }
              else {
                  $all_custom_option_array[$inc]['all']['price'] = number_format($all_custom_option_array[$inc]['all']['price'],2);
              }
              $inner_inc =0;
		    foreach($optionVal->getValues() as $valuesKey => $valuesVal) {
                 $options_values = $valuesVal->getData();
                 $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['id'] = $options_values["option_type_id"];
                 $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['title'] = $options_values["title"];
                 $defaultcustomprice = str_replace(",","", $options_values["price"]); 
	  	 	  $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = $defaultcustomprice;
			  $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price_type'] = $options_values["price_type"];
                 $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sku'] = $options_values["sku"];
                 $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sort_order'] = $options_values["sort_order"];
                 if($options_values["price_type"] == "percent") {
			      $defaultcustomprice = $this->_product->getFinalPrice(); //$valuesVal->getPrice(); 
				 $customproductprice =$defaultcustomprice;
				 $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = str_replace(",","", round((floatval($customproductprice)  * floatval(round(floatval($options_values["price"]),1))/100),2));    
                 }
                 $inner_inc++;
              } 
          $inc++;
        }
        $custom_options_results = array();
        $custom_options_results["status"] = $has_custom_option;
        if($has_custom_option) {
            $custom_options_results["data"] = $all_custom_option_array;
        }
        return  $custom_options_results;
     }
     /**
	* 
	* Return array of all images of a product 
	* @name : getMofluidMediaGalleryImages
	* @access : public
	*
	* @param None
	* @return array
	*/
     public function getMofluidMediaGalleryImages($_product_id) {
         $mofluid_all_product_images = array();
         $mofluid_non_def_images = array();
         $mofluid_product = Mage::getModel('catalog/product')->load($_product_id);
         $mofluid_baseimage =  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/catalog/product'.$mofluid_product->getImage();
	    foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
	     	$mofluid_imagecame =   $mofluid_image->getUrl();
	     	if($mofluid_baseimage == $mofluid_imagecame){
	     	    $mofluid_all_product_images[] = $mofluid_image->getUrl();
         	     }
         	     else{
         		    $mofluid_non_def_images[] =  $mofluid_image->getUrl();
         	     }
         }
         $mofluid_all_product_images = array_merge($mofluid_all_product_images,$mofluid_non_def_images);      
         return $mofluid_all_product_images; 
     }
     /**
	* 
	* Return product's stock information 
	* @name : getProductStockInfo
	* @access : public
	*
	* @param None
	* @return array
	*/
     public function getProductStockInfo() {
         $stock_data = array();
         $stock_product = Mage::getModel('cataloginventory/stock_item')->loadByProduct($this->_product_id);
         $stock_data = $stock_product->getData();
         return $stock_data;
	}
	 /**
	* 
	* Return product's stock information 
	* @name : getProductStockInfoById
	* @access : public
	*
	* @param None
	* @return array
	*/
     public function getProductStockInfoById($product_id) {
         $stock_data = array();
         $config_manage_stock = Mage::getStoreConfig('cataloginventory/item_options/manage_stock');
        $config_max_sale_qty=Mage::getStoreConfig('cataloginventory/item_options/max_sale_qty');
         $stock_product = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product_id);
         $stock_data = $stock_product->getData();
         
         
          if($stock_data['use_config_manage_stock']==0)
                {
                 if($stock_data['manage_stock']==0)
                	{
                	
                			if($stock_data['use_config_max_sale_qty']==1)
                			{ 
                			 $stock_data['qty'] = $config_max_sale_qty;
                			}
                	}
                	
                }
                else
                {
                
                	if($config_manage_stock==0){ $stock_data['qty'] = $config_max_sale_qty; } 
                }
               
         
         
         
         return $stock_data;
	}
    /**
	* 
	* Return product custom attributes 
	* @name : getProductCustomAttributes
	* @access : public
	*
	* @param None
	* @return array
	*/ 
	 public function getProductCustomAttributes() {
	     $custom_attr = array();
		$attributes = $this->_product->getAttributes();
		$custom_attr_count = 0;
		foreach ($attributes as $attribute) {    
			if($attribute->is_user_defined && $attribute->is_visible){
			     $attribute_value = $attribute->getFrontend()->getValue($this->_product);
			     if($attribute_value == null || $attribute_value == "" ) {
			         continue;
			     }
			     else {
				    $custom_attr["data"][$custom_attr_count]["code"] = $attribute->getAttributeCode();
				    $custom_attr["data"][$custom_attr_count]["label"] = $attribute->getStoreLabel($this->_product); 
				    $custom_attr["data"][$custom_attr_count]["value"] = $attribute_value;
				    ++$custom_attr_count;
				}
			}
		}
		$custom_attr["total"] = $custom_attr_count;  
		return  $custom_attr;
	}
   /**
	* 
	* Return related products information 
	* @name : getRelatedProducts
	* @access : public
	*
	* @param None
	* @return array
	*/ 
	public function getRelatedProducts() {
		// Get all related product ids of $product.
		$relatedproducts = array();
		$results = array();
		$incr = 0;
		$allRelatedProductIds = $this->_product->getRelatedProductIds();
		$product_model = Mage::getModel('catalog/product');
		foreach ($allRelatedProductIds as $id) {
			$relatedproducts[$incr] =  $this->getBasicProductInfo($id);
            	$incr++;
        }
        if($incr) {
        	 $results["status"] = 1;
         	 $results["all"] = $relatedproducts;
        }
        else {
         	 $results["status"] = 0;
        }
        return $results;
	}
   /**
	* 
	* Return array of price of a product 
	* @name : getProductPrice
	* @access : public
	*
	* @param integer $id entity id of the product
	* @return array
	*/ 
	public function getProductPrice($id) {
	    $_product = Mage::getModel('catalog/product')->load($id);
	    $prices = array();
	    
	    $tax_type=Mage::getStoreConfig('tax/calculation/price_includes_tax');
	    $taxClassId = $_product->getData("tax_class_id");
    	$taxClasses = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
    	$taxRate = $taxClasses["value_".$taxClassId];
    	
    	
    	$tax_price_regular = (($taxRate)/100) *  ($this->getFormattedProductPrice($_product->getPrice()));
	    
	    if($tax_type==0)
 		{
 			$prices["regular"] = $this->getFormattedProductPrice($_product->getPrice());
 		}
 		else
 		{
 			$prices["regular"] = $this->getFormattedProductPrice($_product->getPrice())-$tax_price_regular;
 		}
	    
	  //   echo "<pre>"; print_r( $tax_price_regular); exit;
	    
	    if($prices["regular"] <= 0) {
	        
	        $tax_price_regular_1 = (($taxRate)/100) *  ($this->prepareGroupedProductPrice($_product));
	        
	       	if($tax_type==0)
 			{
	    		$prices["regular"] = $this->prepareGroupedProductPrice($_product);
	    	}
	    	else
	    	{
	    	$prices["regular"] = $this->prepareGroupedProductPrice($_product)- $tax_price_regular_1;
	    	}
	    }
	    $tax_price_final = (($taxRate)/100) *  ($this->getFormattedProductPrice($_product->getFinalPrice()));
	    
	    if($tax_type==0)
 		{
	    	$prices["final"] = $this->getFormattedProductPrice($_product->getFinalPrice());
	    }
	    else
	    {
	    	$prices["final"] = $this->getFormattedProductPrice($_product->getFinalPrice())-$tax_price_final;
	    }
	   return $prices;
	    
	   
	    
	}
	 /**
	* 
	* Return converted price according to the currency 
	* @name : getProductPrice
	* @access : public
	*
	* @param float $price price of the product
	* @param integer $round flag for price in round or not defult is 0
	* @return float $converted_price converted price based on currency
	*/ 
	public function prepareGroupedProductPrice($groupedProduct) {
        $aProductIds = $groupedProduct->getTypeInstance()->getChildrenIds($groupedProduct->getId());
        $prices = array();
        foreach ($aProductIds as $ids) {
            foreach ($ids as $id) {
                $aProduct = Mage::getModel('catalog/product')->load($id);
                $prices[] =  $this->getFormattedProductPrice($aProduct->getPriceModel()->getPrice($aProduct));
            }
        }
        sort($prices);
        return $prices[0];
    }

   /**
	* 
	* Return converted price according to the currency 
	* @name : getProductPrice
	* @access : public
	*
	* @param float $price price of the product
	* @param integer $round flag for price in round or not defult is 0
	* @return float $converted_price converted price based on currency
	*/ 
	public function getFormattedProductPrice($price, $round = 0) {
        // Currency conversion rates have to be available in the target currency 
        $converted_price = Mage::helper('directory')->currencyConvert($price, $this->_base_currency, $this->_currency);  
        // if you want it rounded:
        if($round) {
        	$converted_price = Mage::app()->getStore()->roundPrice($converted_price);
        }
        return $converted_price;   
    }
    /**
	* 
	* Getting Product Reviews & Ratings 
	* @name : getProductReview
	* @access : public
	*
	* @param integer $round flag for price in round or not defult is 0
	* @return array $product_review product reviews array
	*/ 
	public function getProductReview($productId) {
	    $product_review = array();
	    $reviews = Mage::getModel('review/review')
				->getResourceCollection()
				->addStoreFilter($this->_store)
				->addEntityFilter('product', $productId)
				->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
				->setDateOrder()
				->addRateVotes();
		/**
		 * Getting average of ratings/reviews
		 */
		$avg = 0;
		$review_counter = 0;
		$ratings = array();
		$product_review["total"] = count($reviews);
		if (count($reviews) > 0) {
			foreach ($reviews->getItems() as $review) {
			     $product_review["all"][$review_counter]["id"] = $review->getId();
			     $product_review["all"][$review_counter]["createdat"] = $review->getCreatedAt();
			     $product_review["all"][$review_counter]["statusid"] = $review->getStatusId();
			     $product_review["all"][$review_counter]["detailid"] = $review->getDetailId();
			     $product_review["all"][$review_counter]["detail"] = $review->getDetail();
			     $product_review["all"][$review_counter]["title"] = $review->getTitle();
			     $product_review["all"][$review_counter]["nickname"] = $review->getNickname();
			     $vote_counter =0;
				foreach( $review->getRatingVotes() as $vote ) {
					$ratings[] = $vote->getPercent();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["id"] =  $vote->getId();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["name"]=  $vote->getRatingCode();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["percent"] =  $vote->getPercent();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["value"]=  $vote->getValue();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["option_id"]=  $vote->getOptionId();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["remote_ip"]=  $vote->getRemoteIp();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["store_id"]=  $vote->getStoreId();
					$vote_counter ++;   
				}
				$review_counter++;
			}
			$avg = array_sum($ratings)/count($ratings);
			$product_review["average"] = $avg;
		}
		return $product_review;
	}
}   