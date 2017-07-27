<?php

/*
Mofluidapi117_Catalog_Products v0.0.1
(c) 2009-2013 by Mofluid. All rights reserved.
Shashi Badhuk
*/
include_once('Catalog/Products.php');

class Service
{
    
    /** Function : ws_category
     * Service Name : Category
     * @param $store : Store Id for Magento Stores
     * @param $service : Name of the Webservice
     * @return JSON Array
     * Description : Service to fetch all category
     * */
    public $CACHE_EXPIRY = 300; //in Seconds
    
    public function ws_sidecategory($store, $service)
    {
        
    }
    
    
    function getChildCategories($id){
		$cat = Mage::getModel('catalog/category')->load($id);
		$subcats = $cat->getChildren();
		$all_child = array();
		$counter = 0;
		foreach(explode(',',$subcats) as $subCatid)
		{
		 $_category = Mage::getModel('catalog/category')->load($subCatid);
		 if($_category->getIsActive()) {
			$sub_cat = Mage::getModel('catalog/category')->load($_category->getId());
			$all_child[$counter]["id"]   = $sub_cat->getId();
            $all_child[$counter]["name"] = $sub_cat->getName();
			$sub_subcats = $sub_cat->getChildren();
			$setcount = 0;
			foreach(explode(',',$sub_subcats) as $sub_subCatid)
			{
				 $_sub_category = Mage::getModel('catalog/category')->load($sub_subCatid);
				 if($_sub_category->getIsActive()) {
					 $all_child[$counter]["children"][$setcount]["id"] = $_sub_category->getId();
					 $all_child[$counter]["children"][$setcount]["name"] = $_sub_category->getName();
					// echo '<li class="sub_cat"><a href="'.$_sub_category->getURL().'" title="View the products for the "'.$_sub_category->getName().'" category">'.$_sub_category->getName().'</a></li>';
				 }
				 $setcount++;
			}
		 }
		 $counter++;
		}
		
		return $all_child;
	}
    
    function getChildCategories_old($id)
    {
        $category  = Mage::getModel('catalog/category')->load($id);
        $all_child = array();
        if ($category->hasChildren()) {
            $children = Mage::getModel('catalog/category')->getCategories($category->getId());
            $counter  = 0;
            foreach ($children as $child) {
                $all_child[$counter]["id"]   = $child->getId();
                $all_child[$counter]["name"] = $child->getName();
                if ($child->hasChildren()) {
                    $all_child[$counter]["children"] = $this->getChildCategories($child->getId());
                }
                $counter++;
            }
        }
        
        return $all_child;
    }
    
    /*   * *fetch initial data** */
    
    public function fetchInitialData($store, $service, $currency)
    {
        
        
        $result    = array();
        $rootcatId = Mage::app()->getStore()->getRootCategoryId();
        
        $result["categories"] = $this->getChildCategories($rootcatId);
        // $result["cms"]=  $this->getCMSBlocks();
        //$result["theme"] = $this->getBannerSlider("elegant");
        return $result;
    }
    
    public function ws_category($store, $service)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_" . $service . "_store" . $store;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        
        $res = array();
        try {
            $storecategoryid = Mage::app()->getStore($store)->getRootCategoryId();
            $total           = 0;
            $category        = Mage::getModel('catalog/category');
            $tree            = $category->getTreeModel();
            $tree->load();
            
            $ids = $tree->getCollection()->getAllIds();
            $arr = array();
            
            $storecategoryid = Mage::app()->getStore($store)->getRootCategoryId();
            $cat             = Mage::getModel('catalog/category');
            $cat->load($storecategoryid);
            $categories = $cat->getCollection()->addAttributeToSelect(array(
                'name',
                'thumbnail',
                'image',
                'description',
                'store'
            ))->addIdFilter($cat->getChildren());
            try {
                foreach ($categories as $tmp) {
                    $res[] = array(
                        "id" => $tmp->getId(),
                        "name" => $tmp->getName(),
                        "image" => Mage::getModel('catalog/category')->load($tmp->getId())->getImageUrl(),
                        "thumbnail" => Mage::getBaseUrl('media') . 'catalog/category/' . Mage::getModel('catalog/category')->load($tmp->getId())->getThumbnail()
                    );
                    $total = $total + 1;
                }
            }
            catch (Exception $ex) {
                $res = $this->ws_subcategory($store, 'subcategory', $storecategoryid);
            }
            array_push($arr, $cat);
            //  $res = $res + '<br><br><br><center><b>Total Category : '.$total.'</b></center><br>';
        }
        catch (Exception $ex) {
            //$res = $res + 'Exception Problem : '.$ex;
        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        
        return ($res);
    }
    
    /** Function : ws_subcategory
     * Service Name : SubCategory
     * @param $store : Store Id for Magento Stores
     * @param $service : Name of the Webservice
     * @param $categoryid : Category Id for the App
     * @return JSON Array
     * Description : Service to fetch all category
     * */
    public function ws_subcategory($store_id, $service, $categoryid)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_" . $service . "_store" . $store . "_category" . $categoryid;
        //if($cache->load($cache_key))
        //  return json_decode($cache->load($cache_key));
        
        Mage::app()->setCurrentStore($store_id);
        $res      = array();
        $children = Mage::getModel('catalog/category')->getCategories($categoryid);
        foreach ($children as $current_category) {
            $category = Mage::getModel('catalog/category')->load($current_category->getId());
            $res[]    = array(
                "id" => $category->getId(),
                "name" => $category->getName(),
                "image" => $category->getImageUrl(),
                "thumbnail" => Mage::getBaseUrl('media') . 'catalog/category/' . $category->getThumbnail()
            );
        }
        $result["id"]         = $categoryid;
        $result["title"]      = Mage::getModel('catalog/category')->load($categoryid)->getName();
        $result["images"]     = Mage::getModel('catalog/category')->load($categoryid)->getImageUrl();
        $result["thumbnail"]  = Mage::getBaseUrl('media') . 'catalog/category/' . Mage::getModel('catalog/category')->load($categoryid)->getThumbnail();
        $result["categories"] = $res;
        
        //$cache->save(json_encode($result), $cache_key, array("mofluid"), $this->CACHE_EXPIRY); 
        return ($result);
    }
    
    public function ws_products($store_id, $service, $categoryid, $curr_page, $page_size, $sortType, $sortOrder, $currentcurrencycode)
    {
        /*$cache              = Mage::app()->getCache();
        $cache_key          = "mofluid_" . $service . "_store" . $store_id . "_category" . $categoryid . "_" . $curr_page . $page_size . $sortType . $sortOrder . $currentcurrencycode;
           if($cache->load($cache_key)) {
        return json_decode($cache->load($cache_key));
        
        } */
        Mage::app()->setCurrentStore($store_id);
        $res                = array();
        $show_out_of_stock  = Mage::getStoreConfig('cataloginventory/options/show_out_of_stock');
        $is_in_stock_option = $show_out_of_stock ? 0 : 1;
        //get base currency from magento
        $basecurrencycode   = Mage::app()->getStore($store_id)->getBaseCurrencyCode();
        Mage::app()->setCurrentStore($store_id);
        $c_id     = $categoryid;
        $category = new Mage_Catalog_Model_Category();
        $category->load($c_id);
        $children1 = $category->getProductCollection()->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')->addStoreFilter($store_id)->addAttributeToSelect('*')->addAttributeToFilter('type_id', array(
            'in' => array(
                Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
                Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE,
                Mage_Catalog_Model_Product_Type::TYPE_GROUPED
            )
        ))->addAttributeToFilter('visibility', 4)->addAttributeToFilter('is_in_stock', array(
            'in' => array(
                $is_in_stock_option,
                1
            )
        ))->addAttributeToFilter('status', 1);
        
        
        $res["total"] = count($children1);
        $collection   = $category->getProductCollection()->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')->addStoreFilter($store_id)->addAttributeToFilter('type_id', array(
            'in' => array(
                Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
                Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE,
                Mage_Catalog_Model_Product_Type::TYPE_GROUPED
            )
        ));
        $collection->addAttributeToSelect('*')->addAttributeToFilter('type_id', array(
            'in' => array(
                Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
                Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE,
                Mage_Catalog_Model_Product_Type::TYPE_GROUPED
            )
        ))->addAttributeToFilter('visibility', 4)->addAttributeToFilter('is_in_stock', array(
            'in' => array(
                $is_in_stock_option,
                1
            )
        ))->addAttributeToFilter('status', 1)->addAttributeToSort($sortType, $sortOrder);
        
        
        //if(!$show_out_of_stock)
        //      $collection->addAttributeToFilter(’is_in_stock’, 1);
        
        $collection->setPage($curr_page, $page_size);
        
        foreach ($collection as $_product) {
            
            $mofluid_all_product_images = array();
            $mofluid_non_def_images     = array();
            
            
            $mofluid_all_product_images = array();
            $mofluid_non_def_images     = array();
            $mofluid_product            = Mage::getModel('catalog/product')->load($_product->getId());
            $mofluid_baseimage          = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_product->getImage();
            foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
                $mofluid_imagecame = $mofluid_image->getUrl();
                if ($mofluid_baseimage == $mofluid_imagecame) {
                    $mofluid_all_product_images[0] = $mofluid_image->getUrl();
                    break;
                } else {
                    $mofluid_non_def_images[] = $mofluid_image->getUrl();
                }
            }
            
            $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
            
            //code added by sumit for the saprate tax feature
            /*
            $tax_type = Mage::getStoreConfig('tax/calculation/price_includes_tax');
            $_product = Mage::getModel('catalog/product')->load($_product->getId());
            $taxClassId = $_product->getData("tax_class_id");
            $taxClasses = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
            $taxRate = $taxClasses["value_" . $taxClassId];
            //$tax_price = (($taxRate)/100) *  ($_product->getPrice());
            $tax_price = str_replace(",", "", number_format(((($taxRate) / 100) * ($_product->getPrice())), 2));
            
            if ($tax_type == 0) {
            $defaultprice = str_replace(",", "", number_format($_product->getPrice(), 2));
            } else {
            $defaultprice = str_replace(",", "", number_format(($_product->getPrice() - $tax_price), 2));
            }
            */
            
            $defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
            $defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));
            
            try {
                $custom_option_product = Mage::getModel('catalog/product')->load($_product->getId());
                $custom_options        = $custom_option_product->getOptions();
                $has_custom_option     = 0;
                foreach ($custom_options as $optionKey => $optionVal) {
                    $has_custom_option = 1;
                }
            }
            catch (Exception $ee) {
                $has_custom_option = 0;
            }
            if ($_product->getTypeID() == 'grouped') {
                $defaultprice = $this->getGroupedProductPrice($_product->getId(), $currentcurrencycode);
            }
            // Get the Special Price
            $specialprice         = Mage::getModel('catalog/product')->load($_product->getId())->getSpecialPrice();
            // Get the Special Price FROM date
            $specialPriceFromDate = Mage::getModel('catalog/product')->load($_product->getId())->getSpecialFromDate();
            // Get the Special Price TO date
            $specialPriceToDate   = Mage::getModel('catalog/product')->load($_product->getId())->getSpecialToDate();
            // Get Current date
            $today                = time();
            /*echo "from date".$specialPriceFromDate."<br>";
            echo "end date".$specialPriceToDate."<br>";
            echo "custom".$_product->getName()."<br>";
            
            
            
            echo "price =".$_product->getPrice()."<br>";
            echo "special price =".$_product->getSpecialPrice()."<br>";
            echo "finalprice =".$_product->getFinalPrice()."<br>";
            */
            if ($specialprice) {
                
                
                if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
                    
                    $specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                } else {
                    $specialprice = 0;
                }
            } else {
                $specialprice = 0;
            }
            //tax price for special price 
            
            /*   if ($tax_type == 0) {
            $specialprice = $specialprice;
            } else {
            $specialprice = $specialprice - $tax_price_for_special;
            } */
            /*Added by Mofluid team to resolve spcl price issue in 1.17*/
            $defaultprice =  number_format($_product->getPrice(), 2, '.', '');
            $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
            if($defaultprice == $specialprice)
                $specialprice = number_format(0, 2, '.', '');
            
            $res["data"][] = array(
                "id" => $_product->getId(),
                "name" => $_product->getName(),
                "imageurl" => $mofluid_all_product_images[0],
                "sku" => $_product->getSku(),
                "type" => $_product->getTypeID(),
                "spclprice" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                "currencysymbol" => Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol(),
                "price" => number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                "created_date" => $_product->getCreatedAt(),
                "is_in_stock" => $_product->getStockItem()->getIsInStock(),
                "hasoptions" => $has_custom_option,
                "stock_quantity" => Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product->getId())->getQty()
            );
        }
        // $cache->save(json_encode($res), $cache_key, array("mofluid"), $this->CACHE_EXPIRY); 
        //echo "<pre>"; print_r($res); die;
        return ($res);
    }
    
    /*   * *Convert Currency** */
    
    public function convert_currency($price, $from, $to)
    {
        $newPrice = Mage::helper('directory')->currencyConvert($price, $from, $to);
        return $newPrice;
    }
    
    /*   * **********************get featured products*************** */
    
    public function ws_getFeaturedProducts($currentcurrencycode, $service, $store)
    {
        /*$cache     = Mage::app()->getCache();
        $cache_key = "mofluid_" . $service . base64_encode("_store" . $store . "_currency" . $currentcurrencycode);
        if ($cache->load($cache_key)) {
            return json_decode($cache->load($cache_key));
        }*/
        //get base currency from magento
        Mage::app()->setCurrentStore($store);
        $basecurrencycode   = Mage::app()->getStore()->getBaseCurrencyCode();
        $show_out_of_stock  = Mage::getStoreConfig('cataloginventory/options/show_out_of_stock');
        $is_in_stock_option = $show_out_of_stock ? 0 : 1;
        
        $store_id  = Mage::app()->getStore()->getId();
        $_products = Mage::getModel('catalog/product')->getCollection()->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')->addStoreFilter($store)->setOrder('created_at', 'desc');
        $_products->addAttributeToSelect('*');
        $_products->addFieldToFilter(array(
            array(
                'attribute' => 'featured',
                'eq' => true
            )
        ));
        $_products->addAttributeToFilter('type_id', array(
            'in' => array(
                Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
                Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE
            )
        ))->addAttributeToFilter('is_in_stock', array(
            'in' => array(
                $is_in_stock_option,
                1
            )
        ));
        
        $featuredProducts = array();
        $i                = 0;
        if ($_products->getSize()) {
            foreach ($_products->getItems() as $_product) {
                
                $mofluid_all_product_images = array();
                $mofluid_non_def_images     = array();
                $mofluid_product            = Mage::getModel('catalog/product')->load($_product->getId());
                $mofluid_baseimage          = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_product->getImage();
                foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
                    $mofluid_imagecame = $mofluid_image->getUrl();
                    if ($mofluid_baseimage == $mofluid_imagecame) {
                        $mofluid_all_product_images[0] = $mofluid_image->getUrl();
                        break;
                    } else {
                        $mofluid_non_def_images[] = $mofluid_image->getUrl();
                    }
                }
                
                
                $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
                
                
                $product_id   = $_product->getId();
                $productName  = $_product->getName();
                $productImage = $mofluid_all_product_images[0];
                //echo 'Price : '.$_product->getPrice();
                
                $productPrice         = number_format($_product->getPrice(), 2);
                //echo 'New Price '.$productPrice;			
                $specialPriceFromDate = Mage::getModel('catalog/product')->load($_product->getId())->getSpecialFromDate();
                $specialPriceToDate   = Mage::getModel('catalog/product')->load($_product->getId())->getSpecialToDate();
                // Get Current date
                $today                = time();
                
                if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate))
                    $productSprice = number_format($_product->getSpecialprice(), 2);
                else
                    $productSprice = "0.00";
                
                $productStatus  = $_product->getStockItem()->getIsInStock();
                $stock_quantity = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product->getId())->getQty();
                if ($productStatus == 1 && $stock_quantity < 0)
                    $productStatus == 1;
                else
                    $productStatus == 0;
                
                //convert price from base currency to current currency
                $currencysymbol = Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol();
                //echo PHP_EOL.$productPrice;
                $tax_type       = Mage::getStoreConfig('tax/calculation/price_includes_tax');
                $_product       = Mage::getModel('catalog/product')->load($_product->getId());
                $taxClassId     = $_product->getData("tax_class_id");
                $taxClasses     = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
                $taxRate        = $taxClasses["value_" . $taxClassId];
                //$tax_price = (($taxRate)/100) *  ($_product->getPrice());
                $tax_price      = str_replace(",", "", number_format(((($taxRate) / 100) * ($_product->getPrice())), 2));
                
                if ($tax_type == 0) {
                    $defaultprice = str_replace(",", "", $productPrice);
                } else {
                    $defaultprice = str_replace(",", "", $productPrice) - $tax_price;
                    //$defaultprice = str_replace(",","",number_format(($_product->getPrice()-$tax_price),2)); 
                }
                
                
                //$defaultprice = str_replace(",","",$productPrice); 
                $actualprice   = strval(round($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2));
                $defaultsprice = str_replace(",", "", $productSprice);
                $splsprice     = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                
                // Get the Special Price
                $specialprice         = Mage::getModel('catalog/product')->load($product_id)->getSpecialPrice();
                // Get the Special Price FROM date
                $specialPriceFromDate = Mage::getModel('catalog/product')->load($product_id)->getSpecialFromDate();
                // Get the Special Price TO date
                $specialPriceToDate   = Mage::getModel('catalog/product')->load($product_id)->getSpecialToDate();
                // Get Current date
                $today                = time();
                
                if ($specialprice) {
                    if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
                        $specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                    } else {
                        $specialprice = 0;
                    }
                } else {
                    $specialprice = 0;
                }
                
                $tax_price_for_special = (($taxRate) / 100) * ($specialprice);
                if ($tax_type == 0) {
                    $specialprice = $specialprice;
                } else {
                    $specialprice = $specialprice - $tax_price_for_special;
                }
                
                /*Added by Mofluid team to resolve spcl price issue in 1.17*/
                $actualprice =  number_format($_product->getPrice(), 2, '.', '');
                $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
                if($actualprice == $specialprice)
                    $specialprice = number_format(0, 2, '.', '');
                
                
                $featuredProducts["products_list"][$i++] = array(
                    'id' => $product_id,
                    'name' => $productName,
                    'image' => $productImage,
                    "type" => $_product->getTypeID(),
                    'price' => number_format($this->convert_currency($actualprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                    'special_price' => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                    'currency_symbol' => $currencysymbol,
                    'is_stock_status' => $productStatus
                );
            }
            $featuredProducts["status"][0] = array(
                'Show_Status' => "1"
            );
        } else
            $featuredProducts["status"][0] = array(
                'Show_Status' => "0"
            );
        
        /*$cache->save(json_encode($featuredProducts), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);*/
        return ($featuredProducts);
    }
    
    public function ws_getNewProducts($currentcurrencycode, $service, $store)
    {
        /*$cache     = Mage::app()->getCache();
        $cache_key = "mofluid_" . $service . base64_encode("_store" . $store . "_currency" . $currentcurrencycode);
        if ($cache->load($cache_key)) {
            return json_decode($cache->load($cache_key));
        }*/
        //get base currency from magento
        Mage::app()->setCurrentStore($store);
        $basecurrencycode   = Mage::app()->getStore()->getBaseCurrencyCode();
        $show_out_of_stock  = Mage::getStoreConfig('cataloginventory/options/show_out_of_stock');
        $is_in_stock_option = $show_out_of_stock ? 0 : 1;
        
        $store_id  = Mage::app()->getStore()->getId();
        $_products = Mage::getModel('catalog/product')->getCollection()->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')->addStoreFilter($store)->setOrder('created_at', 'desc')->addAttributeToFilter('visibility', 4);
        $_products->addAttributeToSelect('*');
        
        $_products->addAttributeToFilter('type_id', array(
            'in' => array(
                Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
                Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE
            )
        ))->addAttributeToFilter('is_in_stock', array(
            'in' => array(
                $is_in_stock_option,
                1
            )
        ));
        
        $featuredProducts = array();
        $i                = 0;
        if ($_products->getSize()) {
            $count = 0;
            foreach ($_products->getItems() as $_product) {
                if ($count == 10)
                    break;
                $count++;
                $mofluid_all_product_images = array();
                $mofluid_non_def_images     = array();
                $mofluid_product            = Mage::getModel('catalog/product')->load($_product->getId());
                $mofluid_baseimage          = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_product->getImage();
                foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
                    $mofluid_imagecame = $mofluid_image->getUrl();
                    if ($mofluid_baseimage == $mofluid_imagecame) {
                        $mofluid_all_product_images[0] = $mofluid_image->getUrl();
                        break;
                    } else {
                        $mofluid_non_def_images[] = $mofluid_image->getUrl();
                    }
                }
                
                
                $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
                
                
                $product_id   = $_product->getId();
                $productName  = $_product->getName();
                $productImage = $mofluid_all_product_images[0];
                //echo 'Price : '.$_product->getPrice();
                
                $productPrice         = number_format($_product->getPrice(), 2);
                //echo 'New Price '.$productPrice;			
                $specialPriceFromDate = Mage::getModel('catalog/product')->load($_product->getId())->getSpecialFromDate();
                $specialPriceToDate   = Mage::getModel('catalog/product')->load($_product->getId())->getSpecialToDate();
                // Get Current date
                $today                = time();
                
                if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate))
                    $productSprice = number_format($_product->getSpecialprice(), 2);
                else
                    $productSprice = "0.00";
                
                $productStatus  = $_product->getStockItem()->getIsInStock();
                $stock_quantity = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product->getId())->getQty();
                if ($productStatus == 1 && $stock_quantity < 0)
                    $productStatus == 1;
                else
                    $productStatus == 0;
                
                //convert price from base currency to current currency
                $currencysymbol = Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol();
                //echo PHP_EOL.$productPrice;
                $tax_type       = Mage::getStoreConfig('tax/calculation/price_includes_tax');
                $_product       = Mage::getModel('catalog/product')->load($_product->getId());
                $taxClassId     = $_product->getData("tax_class_id");
                $taxClasses     = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
                $taxRate        = $taxClasses["value_" . $taxClassId];
                //$tax_price = (($taxRate)/100) *  ($_product->getPrice());
                $tax_price      = str_replace(",", "", number_format(((($taxRate) / 100) * ($_product->getPrice())), 2));
                
                if ($tax_type == 0) {
                    $defaultprice = str_replace(",", "", $productPrice);
                } else {
                    $defaultprice = str_replace(",", "", $productPrice) - $tax_price;
                    //$defaultprice = str_replace(",","",number_format(($_product->getPrice()-$tax_price),2)); 
                }
                
                
                //$defaultprice = str_replace(",","",$productPrice); 
                $actualprice   = strval(round($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2));
                $defaultsprice = str_replace(",", "", $productSprice);
                $splsprice     = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                
                // Get the Special Price
                $specialprice         = Mage::getModel('catalog/product')->load($product_id)->getSpecialPrice();
                // Get the Special Price FROM date
                $specialPriceFromDate = Mage::getModel('catalog/product')->load($product_id)->getSpecialFromDate();
                // Get the Special Price TO date
                $specialPriceToDate   = Mage::getModel('catalog/product')->load($product_id)->getSpecialToDate();
                // Get Current date
                $today                = time();
                
                if ($specialprice) {
                    if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
                        $specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                    } else {
                        $specialprice = 0;
                    }
                } else {
                    $specialprice = 0;
                }
                
                $tax_price_for_special = (($taxRate) / 100) * ($specialprice);
                if ($tax_type == 0) {
                    $specialprice = $specialprice;
                } else {
                    $specialprice = $specialprice - $tax_price_for_special;
                }
                /*Added by Mofluid team to resolve spcl price issue in 1.17*/
                $actualprice =  number_format($_product->getPrice(), 2, '.', '');
                $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
                if($actualprice == $specialprice)
                    $specialprice = number_format(0, 2, '.', '');
                $featuredProducts["products_list"][$i++] = array(
                    'id' => $product_id,
                    'name' => $productName,
                    'image' => $productImage,
                    "type" => $_product->getTypeID(),
                    'price' => number_format($this->convert_currency($actualprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                    'special_price' => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                    'currency_symbol' => $currencysymbol,
                    'is_stock_status' => $productStatus
                );
                
            }
            $featuredProducts["products_list"] = array_reverse($featuredProducts["products_list"]);
            $featuredProducts["status"][0]     = array(
                'Show_Status' => "1"
            );
        } else
            $featuredProducts["status"][0] = array(
                'Show_Status' => "0"
            );
        
        
        /*$cache->save(json_encode($featuredProducts), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);*/
        
        $featuredProducts["products_list"] = array_reverse($featuredProducts["products_list"]);
        return ($featuredProducts);
    }
    
    public function ws_getCustomerId($store, $service, $email)
    {
        $customer  = Mage::getModel('customer/customer');
        $websiteId = Mage::getModel('core/store')->load($store)->getWebsiteId();
        if ($store) {
            $customer->setCurrentStore($store);
            $customer->website_id = $websiteId;
        }
        $customer->loadByEmail($email);
        if ($customer->getId()) {
            $res       = array();
            $res["id"] = $customer->getId();
            return $res;
        }
        return -1;
    }
    
    public function getGroupedProductPrice($product_id, $currency)
    {
        $group            = Mage::getModel('catalog/product_type_grouped')->setProduct(Mage::getModel('catalog/product')->load($product_id));
        $base_currency    = Mage::app()->getStore()->getBaseCurrencyCode();
        $group_collection = $group->getAssociatedProductCollection();
        $prices           = array();
        foreach ($group_collection as $group_product) {
            $_product = Mage::getModel('catalog/product')->load($group_product->getId());
            $prices[] = round(floatval(Mage::helper('directory')->currencyConvert($_product->getFinalPrice(), $base_currency, $currency)), 2);
        }
        krsort($prices);
        $prices = array_shift($prices);
        return $prices;
    }
    
    public function ws_verifyLogin($store, $service, $username, $password)
    {
        $websiteId       = Mage::getModel('core/store')->load($store)->getWebsiteId();
        $res             = array();
        $res["username"] = $username;
        $res["password"] = base64_decode($password);
        $login_status    = 1;
        try {
            $login_customer_result = Mage::getModel('customer/customer')->setWebsiteId($websiteId)->authenticate($username, base64_decode($password));
            $login_customer        = Mage::getModel('customer/customer')->setWebsiteId($websiteId);
            $login_customer->loadByEmail($username);
            $res["firstname"] = $login_customer->firstname;
            $res["lastname"]  = $login_customer->lastname;
            $res["id"]        = $login_customer->getId();
            //$res["password"]
            //$res["password"]
        }
        catch (Exception $e) {
            $login_status = 0;
        }
        $res["login_status"] = $login_status;
        return $res;
    }
    
    public function ws_createuser($store, $service, $firstname, $lastname, $email, $password)
    {
        // Website and Store details
        $res                  = array();
        $websiteId            = Mage::getModel('core/store')->load($store)->getWebsiteId();
        $customer             = Mage::getModel("customer/customer");
        $customer->website_id = $websiteId;
        $customer->setCurrentStore($store);
        //  echo 'Phase 2';
        try {
            // If new, save customer information
            $customer->firstname     = $firstname;
            $customer->lastname      = $lastname;
            $customer->email         = $email;
            $customer->password_hash = md5(base64_decode($password));
            $res["email"]            = $email;
            $res["firstname"]        = $firstname;
            $res["lastname"]         = $lastname;
            $res["password"]         = $password;
            $res["status"]           = 0;
            $res["id"]               = 0;
            $cust                    = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);
            
            //check exists email address of users  
            if ($cust->getId()) {
                $res["id"]     = $cust->getId();
                $res["status"] = 0;
            } else {
                //echo 'Phase 2.5';
                if ($customer->save()) {
                    $customer->sendNewAccountEmail('confirmed');
                    $this->send_Password_Mail_to_NewUser($firstname, base64_decode($password), $email);
                    $res["id"]     = $customer->getId();
                    $res["status"] = 1;
                } else {
                    //echo "Already Exist";
                    $exist_customer = Mage::getModel("customer/customer");
                    $exist_customer->setWebsiteId($websiteId);
                    $exist_customer->setCurrentStore($store);
                    $exist_customer->loadByEmail($email);
                    $res["id"]     = $exist_customer->getId();
                    $res["status"] = 1;
                    
                    //echo "An error occured while saving customer";
                }
            }
            //echo 'Phase 3';
        }
        catch (Exception $e) {
            
            //echo "Already Exist Exception";
            try {
                $exist_customer = Mage::getModel("customer/customer");
                $exist_customer->setWebsiteId($websiteId);
                
                $exist_customer->setCurrentStore($store);
                $exist_customer->loadByEmail($email);
                
                $res["id"]     = $exist_customer->getId();
                $res["status"] = 1;
            }
            catch (Exception $ex) {
                $res["id"]     = -1;
                $res["status"] = 0;
            }
        }
        return $res;
    }
    
    //Older API to get Product detail
    public function ws_productdetail($store_id, $service, $productid, $currentcurrencycode)
    {
        //$cache = Mage::app()->getCache();
        // $cache_key = "mofluid_".$service."_store".$store_id."_productid".$productid."_currency".$currentcurrencycode;
        //if($cache->load($cache_key))
        //  return json_decode($cache->load($cache_key));
        Mage::app()->setCurrentStore($store_id);
        $custom_attr       = array();
        $product           = Mage::getModel('catalog/product')->load($productid);
        $attributes        = $product->getAttributes();
        //echo count($attributes);
        $custom_attr_count = 0;
        foreach ($attributes as $attribute) {
            if ($attribute->is_user_defined && $attribute->is_visible) {
                $attribute_value = $attribute->getFrontend()->getValue($product);
                if ($attribute_value == null || $attribute_value == "") {
                    continue;
                } else {
                    $custom_attr["data"][$custom_attr_count]["attr_code"]  = $attribute->getAttributeCode();
                    $custom_attr["data"][$custom_attr_count]["attr_label"] = $attribute->getStoreLabel($product);
                    $custom_attr["data"][$custom_attr_count]["attr_value"] = $attribute_value;
                    ++$custom_attr_count;
                }
            }
        }
        $custom_attr["total"] = $custom_attr_count;
        $res                  = array();
        $productsCollection   = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('entity_id', array(
            'in' => $productid
        ))->addAttributeToSelect('*');
        
        
        $mofluid_all_product_images = array();
        $mofluid_non_def_images     = array();
        $mofluid_product            = Mage::getModel('catalog/product')->load($productid);
        $mofluid_baseimage          = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_product->getImage();
        
        foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
            $mofluid_imagecame = $mofluid_image->getUrl();
            if ($mofluid_baseimage == $mofluid_imagecame) {
                $mofluid_all_product_images[] = $mofluid_image->getUrl();
            } else {
                $mofluid_non_def_images[] = $mofluid_image->getUrl();
            }
        }
        $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
        //get base currency from magento
        $basecurrencycode           = Mage::app()->getStore()->getBaseCurrencyCode();
        foreach ($productsCollection as $product) {
            $a          = Mage::getModel('catalog/product')->load($product->getId());
            $taxClassId = $a->getData("tax_class_id");
            $taxClasses = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
            $taxRate    = $taxClasses["value_" . $taxClassId];
            $b          = (($taxRate) / 100) * ($product->getPrice());
            $product    = Mage::getModel('catalog/product')->load($productid);
            
            $all_custom_option_array = array();
            $attVal                  = $product->getOptions();
            $optStr                  = "";
            $inc                     = 0;
            $has_custom_option       = 0;
            foreach ($attVal as $optionKey => $optionVal) {
                
                $has_custom_option                                          = 1;
                $all_custom_option_array[$inc]['custom_option_name']        = $optionVal->getTitle();
                $all_custom_option_array[$inc]['custom_option_id']          = $optionVal->getId();
                $all_custom_option_array[$inc]['custom_option_is_required'] = $optionVal->getIsRequire();
                $all_custom_option_array[$inc]['custom_option_type']        = $optionVal->getType();
                $all_custom_option_array[$inc]['sort_order']                = $optionVal->getSortOrder();
                $all_custom_option_array[$inc]['all']                       = $optionVal->getData();
                if ($all_custom_option_array[$inc]['all']['default_price_type'] == "percent") {
                    $all_custom_option_array[$inc]['all']['price'] = number_format((($product->getFinalPrice() * round($all_custom_option_array[$inc]['all']['price'] * 10, 2) / 10) / 100), 2);
                    //$all_custom_option_array[$inc]['all']['price'] = number_format((($product->getFinalPrice()*$all_custom_option_array[$inc]['all']['price'])/100),2);
                } else {
                    $all_custom_option_array[$inc]['all']['price'] = number_format($all_custom_option_array[$inc]['all']['price'], 2);
                }
                
                $all_custom_option_array[$inc]['all']['price'] = str_replace(",", "", $all_custom_option_array[$inc]['all']['price']);
                $all_custom_option_array[$inc]['all']['price'] = strval(round($this->convert_currency($all_custom_option_array[$inc]['all']['price'], $basecurrencycode, $currentcurrencycode), 2));
                
                $all_custom_option_array[$inc]['custom_option_value_array'];
                $inner_inc = 0;
                foreach ($optionVal->getValues() as $valuesKey => $valuesVal) {
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['id']    = $valuesVal->getId();
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['title'] = $valuesVal->getTitle();
                    
                    $defaultcustomprice                                                              = str_replace(",", "", ($valuesVal->getPrice()));
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = strval(round($this->convert_currency($defaultcustomprice, $basecurrencycode, $currentcurrencycode), 2));
                    
                    //$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = number_format($valuesVal->getPrice(),2);
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price_type'] = $valuesVal->getPriceType();
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sku']        = $valuesVal->getSku();
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sort_order'] = $valuesVal->getSortOrder();
                    if ($valuesVal->getPriceType() == "percent") {
                        
                        $defaultcustomprice                                                              = str_replace(",", "", ($product->getFinalPrice()));
                        $customproductprice                                                              = strval(round($this->convert_currency($defaultcustomprice, $basecurrencycode, $currentcurrencycode), 2));
                        $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = str_replace(",", "", round((floatval($customproductprice) * floatval(round($valuesVal->getPrice(), 1)) / 100), 2));
                        //$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = number_format((($product->getPrice()*$valuesVal->getPrice())/100),2);
                    }
                    $inner_inc++;
                }
                $inc++;
            }
            
            $res["id"]          = $product->getId();
            $res["sku"]         = $product->getSku();
            $res["name"]        = $product->getName();
            $res["category"]    = $product->getCategoryIds(); //'category';
            $res["image"]       = $mofluid_all_product_images; // Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'/media/catalog/product'.$product->getImage();
            $res["url"]         = $product->getProductUrl();
            $res["description"] = $product->getDescription();
            $res["shortdes"]    = $product->getShortDescription();
            $res["quantity"]    = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productid)->getQty(); //$product->getQty(); 
            $res["visibility"]  = $product->isVisibleInSiteVisibility(); //getVisibility(); 
            $res["type"]        = $product->getTypeID();
            $res["weight"]      = $product->getWeight();
            $res["status"]      = $product->getStatus();
            
            //convert price from base currency to current currency
            $res["currencysymbol"] = Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol();
            
            //$defaultprice = str_replace(",","",($product->getPrice())); 
            /*    $tax_type = Mage::getStoreConfig('tax/calculation/price_includes_tax');
            $product = Mage::getModel('catalog/product')->load($product->getId());
            $taxClassId = $product->getData("tax_class_id");
            $taxClasses = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
            $taxRate = $taxClasses["value_" . $taxClassId];
            //$tax_price = (($taxRate)/100) *  ($_product->getPrice());
            $tax_price = str_replace(",", "", number_format(((($taxRate) / 100) * ($product->getPrice())), 2));
            */
            
            /*
            if ($tax_type == 0) {
            $defaultprice = str_replace(",", "", number_format($product->getPrice(), 2));
            //$discountprice = str_replace(",","",number_format($product->getFinalPrice(),2)); 
            } else {
            // $discountprice = str_replace(",","",number_format(($product->getFinalPrice()-$tax_price),2)); 
            $defaultprice = str_replace(",", "", number_format(($product->getPrice() - $tax_price), 2));
            }
            
            */
            $defaultprice  = str_replace(",", "", ($product->getPrice()));
            $discountprice = str_replace(",", "", number_format($product->getFinalPrice(), 2));
            //  $discountprice = str_replace(",","",($product->getFinalPrice()));
            
            $res["discount"] = strval(round($this->convert_currency($discountprice, $basecurrencycode, $currentcurrencycode), 2));
            
            
            $defaultshipping = Mage::getStoreConfig('carriers/flatrate/price');
            $res["shipping"] = strval(round($this->convert_currency($defaultshipping, $basecurrencycode, $currentcurrencycode), 2));
            
            $defaultsprice = str_replace(",", "", ($product->getSpecialprice()));
            
            
            // Get the Special Price
            $specialprice         = Mage::getModel('catalog/product')->load($product->getId())->getSpecialPrice();
            // Get the Special Price FROM date
            $specialPriceFromDate = Mage::getModel('catalog/product')->load($product->getId())->getSpecialFromDate();
            // Get the Special Price TO date
            $specialPriceToDate   = Mage::getModel('catalog/product')->load($product->getId())->getSpecialToDate();
            // Get Current date
            $today                = time();
            
            if ($specialprice) {
                if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
                    $specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                } else {
                    $specialprice = 0;
                }
            } else {
                $specialprice = 0;
            }
            //tax price for special price 
            /*  $tax_price_for_special = (($taxRate) / 100) * ($specialprice);
            if ($tax_type == 0) {
            $specialprice = $specialprice;
            } else {
            $specialprice = $specialprice - $tax_price_for_special;
            } */
            
            
            if (floatval($discountprice)) {
                if (floatval($discountprice) < floatval($defaultprice)) {
                    $defaultprice = floatval($discountprice);
                }
            }
            
            /*Added by Mofluid team to resolve spcl price issue in 1.17*/
            $defaultprice =  number_format($_product->getPrice(), 2, '.', '');
            $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
            if($defaultprice == $specialprice)
                $specialprice = number_format(0, 2, '.', '');


            $res["price"]    =  number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
            $res["sprice"]   = number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
            $res["tax"]      = number_format($b, 2);
            $res["tax_type"] = $tax_type;
            
            $res["has_custom_option"] = $has_custom_option;
            if ($has_custom_option) {
                $res["custom_option"] = $all_custom_option_array;
            }
        }
        $res["custom_attribute"] = $custom_attr;
        //$cache->save(json_encode($res), $cache_key, array("mofluid"), $this->CACHE_EXPIRY); 
        return ($res);
    }
    
    /*   * ******************************************************************************************************************************** */
    
    //Older API to get Product detail
    public function ws_productdetailDescription($store_id, $service, $productid, $currentcurrencycode)
    {
        // Below code should be commented in actual mode:
     /*   $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_" . $service . "_store" . $store_id . "_productid" . $productid . "_currency" . $currentcurrencycode;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));*/
         Mage::app()->setCurrentStore($store_id);
        $custom_attr       = array();
        $product           = Mage::getModel('catalog/product')->load($productid);
        //echo "<pre>"; print_r($product); die;
        $attributes        = $product->getAttributes();
        //echo count($attributes);
        $custom_attr_count = 0;
        foreach ($attributes as $attribute) {
            if ($attribute->is_user_defined && $attribute->is_visible) {
                $attribute_value = $attribute->getFrontend()->getValue($product);
                if ($attribute_value == null || $attribute_value == "") {
                    continue;
                } else {
                    $custom_attr["data"][$custom_attr_count]["attr_code"]  = $attribute->getAttributeCode();
                    $custom_attr["data"][$custom_attr_count]["attr_label"] = $attribute->getStoreLabel($product);
                    $custom_attr["data"][$custom_attr_count]["attr_value"] = $attribute_value;
                    ++$custom_attr_count;
                }
            }
        }
        $custom_attr["total"] = $custom_attr_count;
        $res                  = array();
        $productsCollection   = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('entity_id', array(
            'in' => $productid
        ))->addAttributeToSelect('*');
        
        
        /* $mofluid_all_product_images = array();
        $mofluid_non_def_images = array();
        $mofluid_product = Mage::getModel('catalog/product')->load($productid);
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
        $mofluid_all_product_images = array_merge($mofluid_all_product_images,$mofluid_non_def_images); */
        //get base currency from magento
        $basecurrencycode = Mage::app()->getStore()->getBaseCurrencyCode();
        foreach ($productsCollection as $product) {
            $a          = Mage::getModel('catalog/product')->load($product->getId());
            $taxClassId = $a->getData("tax_class_id");
            $taxClasses = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
            $taxRate    = $taxClasses["value_" . $taxClassId];
            $b          = (($taxRate) / 100) * ($product->getPrice());
            $product    = Mage::getModel('catalog/product')->load($productid);
            
            $all_custom_option_array = array();
            $attVal                  = $product->getOptions();
            $optStr                  = "";
            $inc                     = 0;
            $has_custom_option       = 0;
            foreach ($attVal as $optionKey => $optionVal) {
                
                $has_custom_option                                          = 1;
                $all_custom_option_array[$inc]['custom_option_name']        = $optionVal->getTitle();
                $all_custom_option_array[$inc]['custom_option_id']          = $optionVal->getId();
                $all_custom_option_array[$inc]['custom_option_is_required'] = $optionVal->getIsRequire();
                $all_custom_option_array[$inc]['custom_option_type']        = $optionVal->getType();
                $all_custom_option_array[$inc]['sort_order']                = $optionVal->getSortOrder();
                $all_custom_option_array[$inc]['all']                       = $optionVal->getData();
                if ($all_custom_option_array[$inc]['all']['default_price_type'] == "percent") {
                    $all_custom_option_array[$inc]['all']['price'] = number_format((($product->getFinalPrice() * round($all_custom_option_array[$inc]['all']['price'] * 10, 2) / 10) / 100), 2);
                    //$all_custom_option_array[$inc]['all']['price'] = number_format((($product->getFinalPrice()*$all_custom_option_array[$inc]['all']['price'])/100),2);
                } else {
                    $all_custom_option_array[$inc]['all']['price'] = number_format($all_custom_option_array[$inc]['all']['price'], 2);
                }
                
                $all_custom_option_array[$inc]['all']['price'] = str_replace(",", "", $all_custom_option_array[$inc]['all']['price']);
                $all_custom_option_array[$inc]['all']['price'] = strval(round($this->convert_currency($all_custom_option_array[$inc]['all']['price'], $basecurrencycode, $currentcurrencycode), 2));
                
                $all_custom_option_array[$inc]['custom_option_value_array'];
                $inner_inc = 0;
                foreach ($optionVal->getValues() as $valuesKey => $valuesVal) {
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['id']    = $valuesVal->getId();
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['title'] = $valuesVal->getTitle();
                    
                    $defaultcustomprice                                                              = str_replace(",", "", ($valuesVal->getPrice()));
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = strval(round($this->convert_currency($defaultcustomprice, $basecurrencycode, $currentcurrencycode), 2));
                    
                    //$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = number_format($valuesVal->getPrice(),2);
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price_type'] = $valuesVal->getPriceType();
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sku']        = $valuesVal->getSku();
                    $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sort_order'] = $valuesVal->getSortOrder();
                    if ($valuesVal->getPriceType() == "percent") {
                        
                        $defaultcustomprice                                                              = str_replace(",", "", ($product->getFinalPrice()));
                        $customproductprice                                                              = strval(round($this->convert_currency($defaultcustomprice, $basecurrencycode, $currentcurrencycode), 2));
                        $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = str_replace(",", "", round((floatval($customproductprice) * floatval(round($valuesVal->getPrice(), 1)) / 100), 2));
                        //$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = number_format((($product->getPrice()*$valuesVal->getPrice())/100),2);
                    }
                    $inner_inc++;
                }
                $inc++;
            }
            
            $res["id"]          = $product->getId();
            $res["sku"]         = $product->getSku();
            $res["name"]        = $product->getName();
            $res["category"]    = $product->getCategoryIds(); //'category';
            //$res["image"] = $mofluid_all_product_images; // Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'/media/catalog/product'.$product->getImage();
            $res["url"]         = $product->getProductUrl();
            $res["description"] = $product->getDescription();
            $res["shortdes"]    = $product->getShortDescription();
            $res["quantity"]    = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productid)->getQty(); //$product->getQty(); 
            $res["visibility"]  = $product->isVisibleInSiteVisibility(); //getVisibility(); 
            $res["type"]        = $product->getTypeID();
            $res["weight"]      = $product->getWeight();
            $res["status"]      = $product->getStatus();
            
            //convert price from base currency to current currency
            $res["currencysymbol"] = Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol();
            
            //$defaultprice = str_replace(",","",($product->getPrice())); 
            $tax_type   = Mage::getStoreConfig('tax/calculation/price_includes_tax');
            $product    = Mage::getModel('catalog/product')->load($product->getId());
            $taxClassId = $product->getData("tax_class_id");
            $taxClasses = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
            $taxRate    = $taxClasses["value_" . $taxClassId];
            //$tax_price = (($taxRate)/100) *  ($_product->getPrice());
            $tax_price  = str_replace(",", "", number_format(((($taxRate) / 100) * ($product->getPrice())), 2));
            
            if ($tax_type == 0) {
                $defaultprice = str_replace(",", "", number_format($product->getPrice(), 2));
                //$discountprice = str_replace(",","",number_format($product->getFinalPrice(),2)); 
            } else {
                // $discountprice = str_replace(",","",number_format(($product->getFinalPrice()-$tax_price),2)); 
                $defaultprice = str_replace(",", "", number_format(($product->getPrice() - $tax_price), 2));
            }
            
            
            
            //  $discountprice = str_replace(",","",($product->getFinalPrice()));
            
            $res["discount"] = strval(round($this->convert_currency($discountprice, $basecurrencycode, $currentcurrencycode), 2));
            
            
            $defaultshipping = Mage::getStoreConfig('carriers/flatrate/price');
            $res["shipping"] = strval(round($this->convert_currency($defaultshipping, $basecurrencycode, $currentcurrencycode), 2));
            
            $defaultsprice = str_replace(",", "", ($product->getSpecialprice()));
            
            
            // Get the Special Price
            $specialprice         = Mage::getModel('catalog/product')->load($product->getId())->getSpecialPrice();
            // Get the Special Price FROM date
            $specialPriceFromDate = Mage::getModel('catalog/product')->load($product->getId())->getSpecialFromDate();
            // Get the Special Price TO date
            $specialPriceToDate   = Mage::getModel('catalog/product')->load($product->getId())->getSpecialToDate();
            // Get Current date
            $today                = time();
            
            if ($specialprice) {
                if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
                    $specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                } else {
                    $specialprice = 0;
                }
            } else {
                $specialprice = 0;
            }
            //tax price for special price 
            $tax_price_for_special = (($taxRate) / 100) * ($specialprice);
            if ($tax_type == 0) {
                $specialprice = $specialprice;
            } else {
                $specialprice = $specialprice - $tax_price_for_special;
            }
            if ($specialprice == 0) {
                if (floatval($discountprice)) {
                    if (floatval($discountprice) < floatval($defaultprice)) {
                        $defaultprice = floatval($discountprice);
                    }
                }
            }
            
            
           // $res["price"] = strval(round($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2));
            
           // $res["sprice"]   = $specialprice;
           

            /*Added by Mofluid team to resolve spcl price issue in 1.17*/
            $defaultprice =  number_format($product->getPrice(), 2, '.', '');
            $specialprice =  number_format($product->getFinalPrice(), 2, '.', '');
            if($defaultprice == $specialprice)
                $specialprice = number_format(0, 2, '.', '');


            $res["price"]    =  number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
            $res["sprice"]   = number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
            
            $res["tax"]      = number_format($b, 2);
            $res["tax_type"] = $tax_type;
            
            $res["has_custom_option"] = $has_custom_option;
            if ($has_custom_option) {
                $res["custom_option"] = $all_custom_option_array;
            }
        }
        $res["custom_attribute"] = $custom_attr;
        // Below code should be commented in actual mode:
        /*$cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);*/
        
        return ($res);
    }
    
    /*   * ************************************************************************************************************************** */
    
    //Older API to get Product detail
    public function ws_productdetailImage($store_id, $service, $productid, $currentcurrencycode)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_" . $service . "_store" . $store_id . "_productid_img" . $productid . "_currency" . $currentcurrencycode;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        
        $custom_attr       = array();
        $product           = Mage::getModel('catalog/product')->load($productid);
        $attributes        = $product->getAttributes();
        //echo count($attributes);
        $custom_attr_count = 0;
        
        $res                = array();
        $productsCollection = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('entity_id', array(
            'in' => $productid
        ))->addAttributeToSelect('*');
        
        
        $mofluid_all_product_images = array();
        $mofluid_non_def_images     = array();
        $mofluid_product            = Mage::getModel('catalog/product')->load($productid);
        $mofluid_baseimage          = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_product->getImage();
        
        foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
            $mofluid_imagecame = $mofluid_image->getUrl();
            if ($mofluid_baseimage == $mofluid_imagecame) {
                $mofluid_all_product_images[] = $mofluid_image->getUrl();
            } else {
                $mofluid_non_def_images[] = $mofluid_image->getUrl();
            }
        }
        $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
        //get base currency from magento
        $basecurrencycode           = Mage::app()->getStore()->getBaseCurrencyCode();
        foreach ($productsCollection as $product) {
            $res["id"]     = $product->getId();
            $res["image"]  = $mofluid_all_product_images; // Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'/media/catalog/product'.$product->getImage();
            $res["status"] = $product->getStatus();
        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return ($res);
    }
    
    /*   * ************************************************************************************************************************** */
    
    //Latest Method to get product detail
    public function ws_productinfo($store_id, $service, $productid, $currentcurrencycode)
    {
        $product = new Mofluidapi117_Products($store_id, $service, $productid, $currentcurrencycode);
        return $product->getCompleteProductInfo();
    }
    
    public function ws_currency($store_id, $service)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_currency_store" . $store_id;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        $res                    = array();
        $res["currentcurrency"] = Mage::app()->getStore($storeID)->getCurrentCurrencyCode();
        $res["basecurrency"]    = Mage::app()->getStore($storeID)->getBaseCurrencyCode();
        $res["currentsymbol"]   = Mage::app()->getLocale()->currency($res["currentcurrency"])->getSymbol();
        $res["basesymbol"]      = Mage::app()->getLocale()->currency($res["basecurrency"])->getSymbol();
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return ($res);
    }
    
    public function ws_setaddress($store, $service, $customerId, $Jaddress, $user_mail, $saveaction)
    {
        //----------------------------------------------------------------------
        if ($customerId == "notlogin") {
            $result                 = array();
            $result['billaddress']  = 1;
            $result['shippaddress'] = 1;
        } else {
            
            $customer               = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($user_mail);
            $Jaddress               = str_replace(" ", "+", $Jaddress);
            $address                = json_decode(base64_decode($Jaddress));
            $billAdd                = $address->billing;
            $shippAdd               = $address->shipping;
            $result                 = array();
            $result['billaddress']  = 0;
            $result['shippaddress'] = 0;
            $_bill_address          = array(
                'firstname' => $billAdd->firstname,
                'lastname' => $billAdd->lastname,
                'street' => array(
                    '0' => $billAdd->street
                ),
                'city' => $billAdd->city,
                'region_id' => '',
                'region' => $billAdd->region,
                'postcode' => $billAdd->postcode,
                'country_id' => $billAdd->country,
                'telephone' => $billAdd->phone
            );
            $_shipp_address         = array(
                'firstname' => $shippAdd->firstname,
                'lastname' => $shippAdd->lastname,
                'street' => array(
                    '0' => $shippAdd->street
                ),
                'city' => $shippAdd->city,
                'region_id' => '',
                'region' => $shippAdd->region,
                'postcode' => $shippAdd->postcode,
                'country_id' => $shippAdd->country,
                'telephone' => $shippAdd->phone
            );
            if ($saveaction == 1 || $saveaction == "1") {
                $billAddress = Mage::getModel('customer/address');
                $billAddress->setData($_bill_address)->setCustomerId($customerId)->setIsDefaultBilling('1')->setSaveInAddressBook('1');
                
                $shippAddress = Mage::getModel('customer/address');
                $shippAddress->setData($_shipp_address)->setCustomerId($customerId)->setIsDefaultShipping('1')->setSaveInAddressBook('1');
            } else {
                $billAddress  = Mage::getModel('customer/address');
                $shippAddress = Mage::getModel('customer/address');
                if ($defaultBillingId = $customer->getDefaultBilling()) {
                    $billAddress->load($defaultBillingId);
                    $billAddress->addData($_bill_address);
                } else {
                    $billAddress->setData($_bill_address)->setCustomerId($customerId)->setIsDefaultBilling('1')->setSaveInAddressBook('1');
                }
                if ($defaultShippingId = $customer->getDefaultShipping()) {
                    $shippAddress->load($defaultShippingId);
                    $shippAddress->addData($_shipp_address);
                } else {
                    $shippAddress->setData($_shipp_address)->setCustomerId($customerId)->setIsDefaultShipping('1')->setSaveInAddressBook('1');
                }
            }
            
            try {
                
                if (count($billAdd) > 0) {
                    if ($billAddress->save())
                        $result['billaddress'] = 1;
                }
                if (count($shippAdd) > 0) {
                    if ($shippAddress->save())
                        $result['shippaddress'] = 1;
                }
            }
            catch (Exception $ex) {
                //Zend_Debug::dump($ex->getMessage());
            }
        }
        return $result;
        
        //---------------------------------------------------------------------
    }
    
    public function rootCategoryData($store, $service)
    {
        $res               = array();
        $res["categories"] = $this->ws_category($store, "category");
        return $res;
    }
    
    public function getStore($store, $service, $currentcurrencycode)
    {
        //Cache data for app
        try {
            
            $cache_data = Mage::getModel('mofluid_mofluidcache/mofluidcache')->load(25);
            
            if ($cache_data['mofluid_cs_accountid'] == '') {
                
                $cache_array = array(
                    'status' => $cache_data['mofluid_cs_status'],
                    'cache_time' => 15
                );
            } else {
                $cache_array = array(
                    'status' => $cache_data['mofluid_cs_status'],
                    'cache_time' => $cache_data['mofluid_cs_accountid']
                );
            }
        }
        catch (Exception $ex) {
            
        }
    }
    
    public function ws_checkout($store, $service, $theme, $currentcurrencycode)
    {
        
        $res             = array();
        $checkout_type   = Mage::getStoreConfig('checkout/options/guest_checkout');
        $res['checkout'] = $checkout_type;
        return $res;
        
    }
    
    public function ws_storedetails($store, $service, $theme, $currentcurrencycode)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_" . $service . "_store" . $store;
        // if($cache->load($cache_key))
        // return json_decode($cache->load($cache_key));
        $res       = array();
        
        
        $date        = Mage::app()->getLocale()->date();
        $timezone    = $date->getTimezone();
        $offset      = $date->getGmtOffset($date->getTimezone());
        $offset_hour = (int) ($date->getGmtOffset($date->getTimezone()) / 3600);
        $offset_min  = ($date->getGmtOffset($date->getTimezone()) % 3600) / 60;
        
        // $modern_theme_data = Mage::getModel('mofluid_thememofluidmodern/mofluid_themes_core')->load(11);
        
        
        //get data from mofluid_themes table
        
        $resource       = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query          = 'SELECT * FROM mofluid_themes';
        $results        = $readConnection->fetchAll($query);
        
        foreach ($results as $gdata) {
            if ($gdata['mofluid_theme_code'] == 'modern') {
                $google_client_id = $gdata['google_ios_clientid'];
                $google_login     = $gdata['google_login'];
            }
        }
        
        /**
         * Print out the results
         */
        
        
        //Getting data from mofluid cache 
        try {
            
            $cache_data = Mage::getModel('mofluid_mofluidcache/mofluidcache')->load(25);
            
            if ($cache_data['mofluid_cs_accountid'] == '') {
                
                $cache_array = array(
                    'status' => $cache_data['mofluid_cs_status'],
                    'cache_time' => 15
                );
            } else {
                $cache_array = array(
                    'status' => $cache_data['mofluid_cs_status'],
                    'cache_time' => $cache_data['mofluid_cs_accountid']
                );
            }
        }
        catch (Exception $ex) {
            
        }
        
        
        
        /* Get Guest Checkout status */
        //$checkout_type = Mage::getStoreConfig('checkout/options/guest_checkout');
        
        
        // echo "<pre>"; print_r($cache_array); exit; 
        $mofluid_theme_data = array();
        Mage::app()->setCurrentStore($store);
        try {
            $res["store"]                        = array();
            $res["store"]                        = Mage::app()->getStore($store)->getData();
            $res["store"]["frontname"]           = Mage::app()->getStore($store)->getFrontendName(); //getLogoSrc()		     
            $res["store"]["cache_setting"]       = $cache_array;
            $res["store"]["logo"]                = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . 'frontend/default/default/' . Mage::getStoreConfig('design/header/logo_src');
            $res["store"]["banner"]              = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . 'frontend/default/default/images/banner.png';
            $res["store"]["adminname"]           = Mage::getStoreConfig('trans_email/ident_sales/name');
            $res["store"]["email"]               = Mage::getStoreConfig('trans_email/ident_sales/email');
            $res["store"]["checkout"]            = Mage::getStoreConfig('trans_email/ident_sales/email');
            $res["store"]["google_ios_clientid"] = $google_client_id;
            $res["store"]["google_login_flag"]   = $google_login;
            
            $res["timezone"]                    = array();
            $res["timezone"]["name"]            = $timezone;
            $res["timezone"]["offset"]          = array();
            $res["timezone"]["offset"]["value"] = $offset;
            $res["timezone"]["offset"]["hour"]  = $offset_hour;
            $res["timezone"]["offset"]["min"]   = $offset_min;
            
            $res["url"]            = array();
            $res["url"]["current"] = Mage::helper('core/url')->getCurrentUrl();
            $res["url"]["media"]   = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
            $res["url"]["skin"]    = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN);
            $res["url"]["js"]      = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS);
            $res["url"]["root"]    = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
            $res["url"]["store"]   = Mage::helper('core/url')->getHomeUrl();
            
            $res["currency"]                   = array();
            $res["currency"]["base"]["code"]   = Mage::app()->getStore($store)->getBaseCurrencyCode();
            $res["currency"]["base"]["name"]   = Mage::app()->getLocale()->currency(Mage::app()->getStore($store)->getBaseCurrencyCode())->getName();
            $res["currency"]["base"]["symbol"] = Mage::app()->getLocale()->currency(Mage::app()->getStore($store)->getBaseCurrencyCode())->getSymbol();
            
            $res["currency"]["current"]["code"]        = Mage::app()->getStore($store)->getCurrentCurrencyCode();
            $res["currency"]["current"]["name"]        = Mage::app()->getLocale()->currency(Mage::app()->getStore($store)->getCurrentCurrencyCode())->getName();
            $res["currency"]["current"]["symbol"]      = Mage::app()->getLocale()->currency(Mage::app()->getStore($store)->getCurrentCurrencyCode())->getSymbol();
            $res["currency"]["allow"]                  = Mage::getStoreConfig('currency/options/allow');
            $res["configuration"]                      = array();
            $res["configuration"]["show_out_of_stock"] = Mage::getStoreConfig('cataloginventory/options/show_out_of_stock');
            //  $res["categories"] = $this->ws_category($store, "category");
            $mofluid_theme_id                          = "1";
            if ($theme == null || $theme == "") {
                $theme = 'elegant';
            }
            $mofluid_elegant_config_model_settings = Mage::getModel('mofluid_thememofluidelegant/thememofluidelegant')->getCollection()->addFieldToFilter('mofluid_theme_code', $theme)->getData();
            $mofluid_theme_id                      = $mofluid_elegant_config_model_settings[0]['mofluid_theme_id'];
            $mofluid_theme_elegant_model           = Mage::getModel('mofluid_thememofluidelegant/images');
            $mofluid_theme_elegant_banner          = $mofluid_theme_elegant_model->getCollection()->addFieldToFilter('mofluid_theme_id', $mofluid_theme_id)->addFieldToFilter('mofluid_image_type', 'banner');
            $mofluid_theme_elegant_banner_all_data = $mofluid_theme_elegant_banner->setOrder('mofluid_image_sort_order', 'ASC')->getData();
            $mofluid_theme_banner_image_type       = $mofluid_elegant_config_model_settings[0]['mofluid_theme_banner_image_type'];
            if ($mofluid_theme_banner_image_type == "1") {
                foreach ($mofluid_theme_elegant_banner_all_data as $banner_key => $banner_value) {
                    try {
                        $mofluid_image_action = json_decode(base64_decode($banner_value['mofluid_image_action']));
                        if ($mofluid_image_action->base == 'product') {
                            $_products = Mage::getModel('catalog/product')->getCollection()->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')->addStoreFilter($store)->addAttributeToFilter('entity_id', $mofluid_image_action->id);
                            foreach ($_products as $_product) {
                                $productStatus  = $_product->getStockItem()->getIsInStock();
                                $stock_quantity = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product->getId())->getQty();
                                if ($productStatus == 1 && $stock_quantity < 0)
                                    $productStatus == 1;
                                else
                                    $productStatus == 0;
                                break;
                            }
                            $mofluid_image_action->status         = $productStatus;
                            $banner_value['mofluid_image_action'] = base64_encode(json_encode($mofluid_image_action));
                        }
                    }
                    catch (Exception $ex) {
                        echo $ex->getMessage();
                    }
                    if ($banner_value['mofluid_store_id'] == $store) {
                        $mofluid_theme_elegant_banner_data[] = $banner_value;
                    } else if ($banner_value['mofluid_store_id'] == 0) {
                        $mofluid_theme_elegant_banner_data[] = $banner_value;
                    } else {
                        continue;
                    }
                }
            } else {
                foreach ($mofluid_theme_elegant_banner_all_data as $banner_key => $banner_value) {
                    try {
                        $mofluid_image_action = json_decode(base64_decode($banner_value['mofluid_image_action']));
                        if ($mofluid_image_action->base == 'product') {
                            $_products = Mage::getModel('catalog/product')->getCollection()->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')->addStoreFilter($store)->addAttributeToFilter('entity_id', $mofluid_image_action->id);
                            foreach ($_products as $_product) {
                                $productStatus  = $_product->getStockItem()->getIsInStock();
                                $stock_quantity = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product->getId())->getQty();
                                if ($productStatus == 1 && $stock_quantity < 0)
                                    $productStatus == 1;
                                else
                                    $productStatus == 0;
                                break;
                            }
                            $mofluid_image_action->status         = $productStatus;
                            $banner_value['mofluid_image_action'] = base64_encode(json_encode($mofluid_image_action));
                        }
                    }
                    catch (Exception $ex) {
                        
                    }
                    if ($banner_value['mofluid_image_isdefault'] == '1' && $banner_value['mofluid_store_id'] == $store) {
                        $mofluid_theme_elegant_banner_data[] = $banner_value;
                        break;
                    } else if ($banner_value['mofluid_image_isdefault'] == '1' && $banner_value['mofluid_store_id'] == 0) {
                        $mofluid_theme_elegant_banner_data[] = $banner_value;
                        break;
                    } else {
                        continue;
                    }
                }
                if (count($mofluid_theme_elegant_banner_data) <= 0) {
                    $mofluid_theme_elegant_banner_data[] = $mofluid_theme_elegant_banner_all_data[0]; //$banner_value;
                }
            }
            
            $mofluid_theme_elegant_logo      = $mofluid_theme_elegant_model->getCollection()->addFieldToFilter('mofluid_image_type', 'logo')->addFieldToFilter('mofluid_theme_id', $mofluid_theme_id);
            $mofluid_theme_elegant_logo_data = $mofluid_theme_elegant_logo->getData();
            
            $mofluid_theme_data["code"]            = $theme;
            $mofluid_theme_data["logo"]["image"]   = $mofluid_theme_elegant_logo_data;
            $mofluid_theme_data["logo"]["alt"]     = Mage::getStoreConfig('design/header/logo_alt');
            $mofluid_theme_data["banner"]["image"] = $mofluid_theme_elegant_banner_data;
            $res["theme"]                          = $mofluid_theme_data;
            
            
            
            
            //get google analytics
            $modules      = Mage::getConfig()->getNode('modules')->children();
            $modulesArray = (array) $modules;
            
            if (isset($modulesArray['Mofluid_Ganalyticsm'])) {
                $google_analytics              = array();
                $mofluid_google_analytics      = Mage::getModel('mofluid_ganalyticsm/ganalyticsm')->load(23);
                $google_analytics["accountid"] = $mofluid_google_analytics->getData('mofluid_ga_accountid');
                $google_analytics["status"]    = $mofluid_google_analytics->getData('mofluid_ga_status');
                if (!$google_analytics["status"]) {
                    $google_analytics["status"] = 0;
                }
                $res["analytics"] = $google_analytics;
            }
            
            
            
            //  $cache_time=array('status'=>1 , 'cache_time'=>12);
        }
        catch (Exception $ex) {
            echo $ex;
        }
        
        //$cache->save(json_encode($res), $cache_key, array("mofluid"), $this->CACHE_EXPIRY*2); 
        
        return $res;
    }
    
    public function ws_search($store, $service, $search_data, $curr_page, $page_size, $sortType, $sortOrder, $currentcurrencycode)
    {
        /*$cache                      = Mage::app()->getCache();
        $cache_key                  = "mofluid_" . $service . base64_encode($store . $search_data . $curr_page . $page_size . $sortType . $sortOrder . $currentcurrencycode);
        //if($cache->load($cache_key))
        //return json_decode($cache->load($cache_key));*/
        Mage::app()->setCurrentStore($store);
        $basecurrencycode           = Mage::app()->getStore()->getBaseCurrencyCode();
        $res                        = array();
        $show_out_of_stock          = Mage::getStoreConfig('cataloginventory/options/show_out_of_stock');
        $is_in_stock_option         = $show_out_of_stock ? 0 : 1;
        $search_condition           = array();
        /* $all_search_word = explode(' ',$search_data);
        foreach($all_search_word as $key=>$value) {
        $search_condition[]['like'] = '%'.$value.'%';
        } */
        $search_condition[]['like'] = '%' . $search_data . '%';
        
        try {
            //Code to Search Product by $searchstring and get Product IDs
            $total_product_collection = Mage::getResourceModel('catalog/product_collection')->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')->addAttributeToSelect('*')->addAttributeToFilter('name', $search_condition)->addStoreFilter($store)->addAttributeToFilter('visibility', 4)->addAttributeToFilter('type_id', array(
                'in' => array(
                    'configurable',
                    'grouped',
                    'simple'
                )
            ))->addFieldToFilter('status', 1)->addAttributeToFilter('is_in_stock', array(
                'in' => array(
                    $is_in_stock_option,
                    1
                )
            ))->load();
            $res["total"]             = count($total_product_collection);
            $product_collection       = Mage::getResourceModel('catalog/product_collection')->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')->addAttributeToSelect('*')->addAttributeToFilter('name', $search_condition)->addStoreFilter($store)->addFieldToFilter('status', 1)->addAttributeToFilter('visibility', 4)->addAttributeToFilter('type_id', array(
                'in' => array(
                    'configurable',
                    'grouped',
                    'simple'
                )
            ))->addAttributeToSort($sortType, $sortOrder)->addAttributeToFilter('is_in_stock', array(
                'in' => array(
                    $is_in_stock_option,
                    1
                )
            ))->setPage($curr_page, $page_size)->load();
            foreach ($product_collection as $_product) {
                if (in_array($store, $_product->getStoreIds())) {
                    $mofluid_all_product_images = array();
                    $mofluid_non_def_images     = array();
                    $mofluid_product            = Mage::getModel('catalog/product')->load($_product->getId());
                    $mofluid_baseimage          = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_product->getImage();
                    foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
                        $mofluid_imagecame = $mofluid_image->getUrl();
                        if ($mofluid_baseimage == $mofluid_imagecame) {
                            $mofluid_all_product_images[0] = $mofluid_image->getUrl();
                            break;
                        } else {
                            $mofluid_non_def_images[] = $mofluid_image->getUrl();
                        }
                    }
                    $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
                    
                    
                    
                    
                    //"comment by sumit" $defaultprice = str_replace(",","",number_format($_product->getFinalPrice(),2));
                    /*     $tax_type = Mage::getStoreConfig('tax/calculation/price_includes_tax');
                    $_product = Mage::getModel('catalog/product')->load($_product->getId());
                    $taxClassId = $_product->getData("tax_class_id");
                    $taxClasses = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
                    $taxRate = $taxClasses["value_" . $taxClassId];
                    //$tax_price = (($taxRate)/100) *  ($_product->getPrice());
                    $tax_price = str_replace(",", "", number_format(((($taxRate) / 100) * ($_product->getPrice())), 2));
                    
                    if ($tax_type == 0) {
                    $defaultprice = str_replace(",", "", number_format($_product->getPrice(), 2));
                    } else {
                    $defaultprice = str_replace(",", "", number_format(($_product->getPrice() - $tax_price), 2));
                    }
                    
                    */
                    $defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
                    $defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));
                    
                    
                    try {
                        $custom_option_product = Mage::getModel('catalog/product')->load($_product->getId());
                        $custom_options        = $custom_option_product->getOptions();
                        $has_custom_option     = 0;
                        foreach ($custom_options as $optionKey => $optionVal) {
                            $has_custom_option = 1;
                        }
                    }
                    catch (Exception $ee) {
                        $has_custom_option = 0;
                    }
                    // Get the Special Price
                    $specialprice         = Mage::getModel('catalog/product')->load($_product->getId())->getSpecialPrice();
                    // Get the Special Price FROM date
                    $specialPriceFromDate = Mage::getModel('catalog/product')->load($_product->getId())->getSpecialFromDate();
                    // Get the Special Price TO date
                    $specialPriceToDate   = Mage::getModel('catalog/product')->load($_product->getId())->getSpecialToDate();
                    // Get Current date
                    $today                = time();
                    
                    if ($specialprice) {
                        if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
                            $specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                        } else {
                            $specialprice = 0;
                        }
                    } else {
                        $specialprice = 0;
                    }
                    
                    $tax_price_for_special = (($taxRate) / 100) * ($specialprice);
                    /* if ($tax_type == 0) {
                    $specialprice = $specialprice;
                    } else {
                    $specialprice = $specialprice - $tax_price_for_special;
                    }
                    */
                    
                    
                    $original_price = 0;
                    if ($_product->getTypeID() == "grouped") {
                        $grouped_productIds = $_product->getTypeInstance()->getChildrenIds($_product->getId());
                        $grouped_prices     = array();
                        foreach ($grouped_productIds as $grouped_ids) {
                            foreach ($grouped_ids as $grouped_id) {
                                $grouped_product  = Mage::getModel('catalog/product')->load($grouped_id);
                                $grouped_prices[] = $grouped_product->getPriceModel()->getPrice($grouped_product);
                            }
                        }
                        sort($grouped_prices);
                        $original_price = strval(round($this->convert_currency($grouped_prices[0], $basecurrencycode, $currentcurrencycode), 2));
                    } else {
                        $original_price = strval(round($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2));
                    }
                    
                    /*Added by Mofluid team to resolve spcl price issue in 1.17*/
                    $original_price =  number_format($_product->getPrice(), 2, '.', '');
                    $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
                    if($original_price == $specialprice)
                        $specialprice = number_format(0, 2, '.', '');

                    $res["data"][] = array(
                        "id" => $_product->getId(),
                        "name" => $_product->getName(),
                        "imageurl" => $mofluid_all_product_images[0],
                        "sku" => $_product->getSku(),
                        "type" => $_product->getTypeID(),
                        "hasoptions" => $has_custom_option,
                        "currencysymbol" => Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol(),
                        "price" => number_format($this->convert_currency($original_price, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                        "spclprice" =>number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                        "created_date" => $_product->getCreatedAt(),
                        "is_in_stock" => $_product->getStockItem()->getIsInStock(),
                        "stock_quantity" => Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product->getId())->getQty()
                    );
                }
            }
        }
        catch (Exception $ex) {
            echo $ex;
        }
        /*$cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);*/
        return $res;
    }
    
    public function send_Password_Mail_to_NewUser($user, $pswd, $email)
    {
        
        //load the custom template to the email							   
        $emailTemplate                      = Mage::getModel('core/email_template')->loadDefault('mofluid_password');
        // it depends on the template variables
        $emailTemplateVariables             = array();
        $emailTemplateVariables['user']     = $user;
        $emailTemplateVariables['password'] = $pswd;
        $emailTemplateVariables['email']    = $email;
        $websitename                        = Mage::app()->getWebsite()->getName();
        $emailTemplate->setSenderName($websitename);
        $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email'));
        $emailTemplate->setType('html');
        $emailTemplate->setTemplateSubject($websitename . ' New Account Password');
        $emailTemplate->send($email, $websitename, $emailTemplateVariables);
    }
    
    public function updateOrderStatus($cust_id, $orderid, $store, $currency)
    {
        $res = array();
        try {
            $this->ws_sendorderemail($orderid);
            $res["id"]     = $orderid;
            $res["status"] = "PROCESSING";
        }
        catch (Exception $err) {
            $res["id"]      = $orderid;
            $res["status"]  = "error";
            $res["message"] = $err->getMessage();
        }
        return $res;
    }
    
    public function orderInfo($cust_id, $orderid, $store, $currency)
    {
        $basecurrencycode = Mage::app()->getStore($store)->getBaseCurrencyCode();
        $res              = array();
        $order            = Mage::getModel('sales/order')->loadByIncrementId($orderid);
        $shippingAddress  = $order->getShippingAddress();
        $billingAddress   = $order->getBillingAddress();
        if (is_object($shippingAddress)) {
            $shippadd = array(
                "prefix" => $shippingAddress->getPrefix(),
                "firstname" => $shippingAddress->getFirstname(),
                "lastname" => $shippingAddress->getLastname(),
                "company" => $shippingAddress->getCompany(),
                "street" => $shippingAddress->getStreetFull(),
                "region" => $shippingAddress->getRegion(),
                "city" => $shippingAddress->getCity(),
                "postcode" => $shippingAddress->getPostcode(),
                "countryid" => $shippingAddress->getCountry_id(),
                "country" => $shippingAddress->getCountry(),
                "phone" => $shippingAddress->getTelephone(),
                "email" => $shippingAddress->getEmail(),
                "shipmyid" => $flag
            );
        }
        if (is_object($billingAddress)) {
            $billadd = array(
                "prefix" => $billingAddress->getPrefix(),
                "firstname" => $billingAddress->getFirstname(),
                "lastname" => $billingAddress->getLastname(),
                "company" => $billingAddress->getCompany(),
                "street" => $billingAddress->getStreetFull(),
                "region" => $billingAddress->getRegion(),
                "city" => $billingAddress->getCity(),
                "postcode" => $billingAddress->getPostcode(),
                "countryid" => $billingAddress->getCountry_id(),
                "country" => $billingAddress->getCountry(),
                "phone" => $billingAddress->getTelephone(),
                "email" => $billingAddress->getEmail()
            );
        }
        $payment        = array();
        $payment_result = array();
        $payment        = $order->getPayment();
        try {
            $payment_result = array(
                "title" => $payment->getMethodInstance()->getTitle(),
                "code" => $payment->getMethodInstance()->getCode()
            );
            if ($payment->getMethodInstance()->getCode() == "banktransfer") {
                $payment_result["description"] = $payment->getMethodInstance()->getInstructions();
            }
        }
        catch (Exception $ex2) {
            
        }
        $items       = $order->getAllItems();
        $itemcount   = count($items);
        $itemcounter = 0;
        $product     = array();
        foreach ($items as $itemId => $item) {
            $mofluid_all_product_images = array();
            $mofluid_non_def_images     = array();
            $mofluid_product            = Mage::getModel('catalog/product')->load($item->getProductId());
            if ($mofluid_product->getTypeID() == "simple") {
                $parentIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($mofluid_product->getId()); // check for grouped product
                if (!$parentIds) {
                    $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($mofluid_product->getId()); //check for config product
                }
            }
            if ($parentIds[0]) {
                $mofluid_parent_product = Mage::getModel('catalog/product')->load($parentIds[0]);
                $mofluid_baseimage      = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_parent_product->getImage();
            } else {
                $mofluid_baseimage = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_product->getImage();
            }
            if (!$mofluid_baseimage) {
                foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
                    
                    $mofluid_imagecame = $mofluid_image->getUrl();
                    if ($mofluid_baseimage == $mofluid_imagecame) {
                        $mofluid_all_product_images[0] = $mofluid_image->getUrl();
                        break;
                    } else {
                        $mofluid_non_def_images[] = $mofluid_image->getUrl();
                    }
                }
                
                $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
            } else {
                $mofluid_all_product_images[0] = $mofluid_baseimage;
            }
            $product[$itemcounter]["id"]    = $item->getId();
            $product[$itemcounter]["sku"]   = $item->getSku();
            $product[$itemcounter]["name"]  = $item->getName();
            $product[$itemcounter]["qty"]   = number_format($item->getQtyOrdered(), 2, '.', '');
            //$product[$itemcounter]["image"]  = (string)Mage::helper('catalog/image')->init(Mage::getModel('catalog/product')->load($item->getId()), 'thumbnail');
            $product[$itemcounter]["image"] = $mofluid_all_product_images[0];
            $product[$itemcounter]["price"] = number_format($item->getPrice(), 2, '.', '');
            $itemcounter++;
        }
        $coupon     = array();
        $couponCode = $order->getCouponCode();
        if ($couponCode != "") {
            $coupon["applied"] = 1;
            $coupon["code"]    = $couponCode;
            $coupon["amount"]  = number_format($order->getDiscountAmount() * -1, 2, '.', '');
        }
        $res = array(
            "id" => $order->getId(),
            "order_id" => $order->getRealOrderId(),
            "status" => $order->getStatus(),
            "state" => $order->getState(),
            "order_date" => $order->getCreatedAtStoreDate() . "",
            "payment" => $payment_result,
            "products" => $product,
            "currency" => array(
                "code" => $order->getOrderCurrencyCode(),
                "symbol" => Mage::app()->getLocale()->currency($order->getOrderCurrencyCode())->getSymbol(),
                "current" => $currency
            ),
            "address" => array(
                "shipping" => $shippadd,
                "billing" => $billadd
            ),
            "amount" => array(
                "total" => number_format($order->getGrandTotal(), 2, '.', ''),
                "tax" => number_format($order->getTaxAmount(), 2, '.', '')
            ),
            "coupon" => $coupon,
            "shipping" => array(
                "method" => $order->getShippingDescription(),
                "amount" => number_format($order->getShippingAmount(), 2, '.', '')
            )
        );
        //echo "<pre>"; print_r($res); die;
        return $res;
    }
    
    public function ws_myOrder($cust_id, $curr_page, $page_size, $store, $currency)
    {
        
        $basecurrencycode = Mage::app()->getStore($store)->getBaseCurrencyCode();
        $res              = array();
        $totorders        = Mage::getResourceModel('sales/order_collection')->addFieldToSelect('*')->addFieldToFilter('customer_id', $cust_id);
        $res["total"]     = count($totorders);
        $orders           = Mage::getResourceModel('sales/order_collection')->addFieldToSelect('*')->addFieldToFilter('customer_id', $cust_id)->setOrder('created_at', 'desc')->setPage($curr_page, $page_size);
        //$this->setOrders($orders); 
        foreach ($orders as $order) {
            
            $shippingAddress = $order->getShippingAddress();
            if (is_object($shippingAddress)) {
                $shippadd = array();
                $flag     = 0;
                if (count($orderData) > 0)
                    $flag = 1;
                $shippadd = array(
                    "firstname" => $shippingAddress->getFirstname(),
                    "lastname" => $shippingAddress->getLastname(),
                    "company" => $shippingAddress->getCompany(),
                    "street" => $shippingAddress->getStreetFull(),
                    "region" => $shippingAddress->getRegion(),
                    "city" => $shippingAddress->getCity(),
                    "pincode" => $shippingAddress->getPostcode(),
                    "countryid" => $shippingAddress->getCountry_id(),
                    "contactno" => $shippingAddress->getTelephone(),
                    "shipmyid" => $flag
                );
            }
            $billingAddress = $order->getBillingAddress();
            if (is_object($billingAddress)) {
                $billadd = array();
                $billadd = array(
                    "firstname" => $billingAddress->getFirstname(),
                    "lastname" => $billingAddress->getLastname(),
                    "company" => $billingAddress->getCompany(),
                    "street" => $billingAddress->getStreetFull(),
                    "region" => $billingAddress->getRegion(),
                    "city" => $billingAddress->getCity(),
                    "pincode" => $billingAddress->getPostcode(),
                    "countryid" => $billingAddress->getCountry_id(),
                    "contactno" => $billingAddress->getTelephone()
                );
            }
            $payment = array();
            $payment = $order->getPayment();
            
            
            
            try {
                $payment_result = array(
                    "payment_method_title" => $payment->getMethodInstance()->getTitle(),
                    "payment_method_code" => $payment->getMethodInstance()->getCode()
                );
                if ($payment->getMethodInstance()->getCode() == "banktransfer") {
                    
                    $payment_result["payment_method_description"] = $payment->getMethodInstance()->getInstructions();
                }
            }
            catch (Exception $ex2) {
                
            }
            
            //$order = Mage::getModel('sales/order')->load($order_id);
            $items                       = $order->getAllItems();
            $itemcount                   = count($items);
            $name                        = array();
            $unitPrice                   = array();
            $sku                         = array();
            $ids                         = array();
            $qty                         = array();
            $images                      = array();
            $test_p                      = array();
            $itemsExcludingConfigurables = array();
            foreach ($items as $itemId => $item) {
                $name[] = $item->getName();
                //echo $item->getName();
                if ($item->getOriginalPrice() > 0) {
                    $unitPrice[] = number_format($this->convert_currency(floatval($item->getOriginalPrice()), $basecurrencycode, $currency), 2, '.', '');
                } else {
                    $unitPrice[] = number_format($this->convert_currency(floatval($item->getPrice()), $basecurrencycode, $currency), 2, '.', '');
                }
                
                $sku[]    = $item->getSku();
                $ids[]    = $item->getProductId();
                //$qty[]=$item->getQtyToInvoice();
                $qty[]    = $item->getQtyOrdered();
                $products = Mage::getModel('catalog/product')->load($item->getProductId());
                $images[] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . '/media/catalog/product' . $products->getThumbnail();
            }
            $product = array();
            $product = array(
                "name" => $name,
                "sku" => $sku,
                "id" => $ids,
                "quantity" => $qty,
                "unitprice" => $unitPrice,
                "image" => $images,
                "total_item_count" => $itemcount,
                "price_org" => $test_p,
                "price_based_curr" => 1
            );
            
            $order_date = $order->getCreatedAtStoreDate() . '';
            $orderData  = array(
                "id" => $order->getId(),
                "order_id" => $order->getRealOrderId(),
                "status" => $order->getStatus(),
                "order_date" => $order_date,
                "grand_total" => number_format($this->convert_currency(floatval($order->getGrandTotal()), $basecurrencycode, $currency), 2, '.', ''),
                "shipping_address" => $shippadd,
                "billing_address" => $billadd,
                "shipping_message" => $order->getShippingDescription(),
                "shipping_amount" => number_format($this->convert_currency(floatval($order->getShippingAmount()), $basecurrencycode, $currency), 2, '.', ''),
                "payment_method" => $payment_result,
                "tax_amount" => number_format($this->convert_currency(floatval($order->getTaxAmount()), $basecurrencycode, $currency), 2, '.', ''),
                "product" => $product,
                "order_currency" => $order->getOrderCurrencyCode(),
                "order_currency_symbol" => Mage::app()->getLocale()->currency($order->getOrderCurrencyCode())->getSymbol(),
                "currency" => $currency,
                "couponUsed" => 0
            );
            $couponCode = $order->getCouponCode();
            if ($couponCode != "") {
                $orderData["couponUsed"]      = 1;
                $orderData["couponCode"]      = $couponCode;
                $orderData["discount_amount"] = floatval(number_format($this->convert_currency(floatval($order->getDiscountAmount()), $basecurrencycode, $currency), 2, '.', '')) * -1;
            }
            
            $res["data"][] = $orderData;
        }
        return $res;
    }
    
    public function ws_myProfile($cust_id)
    {
        try {
            $customer                    = Mage::getModel('customer/customer')->load($cust_id);
            $customerData                = $customer->getData();
            $customerData['membersince'] = Mage::getModel('core/date')->date("Y-m-d h:i:s A", $customerData['created_at']);
            $shippingAddress             = $customer->getDefaultShippingAddress();
        }
        catch (Exception $ex2) {
            echo $ex2;
        }
        $shippadd = array();
        $billadd  = array();
        try {
            if ($shippingAddress != null) {
                $shippadd = array(
                    "firstname" => $shippingAddress->getFirstname(),
                    "lastname" => $shippingAddress->getLastname(),
                    "company" => $shippingAddress->getCompany(),
                    "street" => $shippingAddress->getStreetFull(),
                    "region" => $shippingAddress->getRegion(),
                    "city" => $shippingAddress->getCity(),
                    "pincode" => $shippingAddress->getPostcode(),
                    "countryid" => $shippingAddress->getCountry_id(),
                    "contactno" => $shippingAddress->getTelephone()
                );
            }
            $billingAddress = $customer->getDefaultBillingAddress();
            if ($billingAddress != null) {
                $billadd = array(
                    "firstname" => $billingAddress->getFirstname(),
                    "lastname" => $billingAddress->getLastname(),
                    "company" => $billingAddress->getCompany(),
                    "street" => $billingAddress->getStreetFull(),
                    "region" => $billingAddress->getRegion(),
                    "city" => $billingAddress->getCity(),
                    "pincode" => $billingAddress->getPostcode(),
                    "countryid" => $billingAddress->getCountry_id(),
                    "contactno" => $billingAddress->getTelephone()
                );
            }
        }
        catch (Exception $ex) {
            echo $ex;
        }
        $res = array();
        $res = array(
            "CustomerInfo" => $customerData,
            "BillingAddress" => $billadd,
            "ShippingAddress" => $shippadd
        );
        return $res;
    }
    
    public function ws_changeProfilePassword($custid, $username, $oldpassword, $newpassword, $store)
    {
        $res         = array();
        $oldpassword = base64_decode($oldpassword);
        $newpassword = base64_decode($newpassword);
        $validate    = 0;
        $websiteId   = Mage::getModel('core/store')->load($store)->getWebsiteId();
        try {
            $login_customer_result = Mage::getModel('customer/customer')->setWebsiteId($websiteId)->authenticate($username, $oldpassword);
            $validate              = 1;
        }
        catch (Exception $ex) {
            $validate = 0;
        }
        if ($validate == 1) {
            try {
                $customer = Mage::getModel('customer/customer')->load($custid);
                $customer->setPassword($newpassword);
                $customer->save();
                $res = array(
                    "customerid" => $custid,
                    "oldpassword" => $oldpassword,
                    "newpassword" => $newpassword,
                    "change_status" => 1,
                    "message" => 'Your Password has been Changed Successfully'
                );
            }
            catch (Exception $ex) {
                $res = array(
                    "customerid" => $custid,
                    "oldpassword" => $oldpassword,
                    "newpassword" => $newpassword,
                    "change_status" => -1,
                    "message" => 'Error : ' . $ex->getMessage
                );
            }
        } else {
            $res = array(
                "customerid" => $custid,
                "oldpassword" => $oldpassword,
                "newpassword" => $newpassword,
                "change_status" => 0,
                "message" => 'Incorrect Old Password.'
            );
        }
        return $res;
    }
    
    public function ws_setprofile($store, $service, $customerId, $JbillAdd, $JshippAdd, $profile)
    {
        
        $billAdd  = json_decode($JbillAdd);
        $shippAdd = json_decode($JshippAdd);
        $profile  = json_decode($profile);
        
        $result                 = array();
        $result['billaddress']  = 0;
        $result['shippaddress'] = 0;
        $result['userprofile']  = 0;
        
        /* Update User Profile Data */
        
        $customer = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($profile->email);
        
        //check exists email address of users  
        if ($customer->getId() && $customer->getId() != $customerId) {
            return $result;
        } else {
            $_bill_address  = array(
                'firstname' => $billAdd->billfname,
                'lastname' => $billAdd->billlname,
                'street' => array(
                    '0' => $billAdd->billstreet1,
                    '1' => $billAdd->billstreet2
                ),
                'city' => $billAdd->billcity,
                'region_id' => '',
                'region' => $billAdd->billstate,
                'postcode' => $billAdd->billpostcode,
                'country_id' => $billAdd->billcountry,
                'telephone' => $billAdd->billphone
            );
            $_shipp_address = array(
                'firstname' => $shippAdd->shippfname,
                'lastname' => $shippAdd->shipplname,
                'street' => array(
                    '0' => $shippAdd->shippstreet1,
                    '1' => $shippAdd->shippstreet2
                ),
                'city' => $shippAdd->shippcity,
                'region_id' => '',
                'region' => $shippAdd->shippstate,
                'postcode' => $shippAdd->shipppostcode,
                'country_id' => $shippAdd->shippcountry,
                'telephone' => $shippAdd->shippphone
            );
            
            
            $billAddress = Mage::getModel('customer/address');
            if ($defaultBillingId = $customer->getDefaultBilling()) {
                $billAddress->load($defaultBillingId);
                $billAddress->addData($_bill_address);
            } else {
                $billAddress->setData($_bill_address)->setCustomerId($customerId)->setIsDefaultBilling('1')->setSaveInAddressBook('1');
            }
            $shippAddress = Mage::getModel('customer/address');
            if ($defaultShippingId = $customer->getDefaultShipping()) {
                $shippAddress->load($defaultShippingId);
                $shippAddress->addData($_shipp_address);
            } else {
                $shippAddress->setData($_shipp_address)->setCustomerId($customerId)->setIsDefaultShipping('1')->setSaveInAddressBook('1');
            }
            
            
            try {
                if ($billAddress->save())
                    $result['billaddress'] = 1;
                if ($shippAddress->save())
                    $result['shippaddress'] = 1;
                
                $tab_prefix      = Mage::getConfig()->getTablePrefix();
                $write           = Mage::getSingleton("core/resource")->getConnection("core_write");
                $sql1            = "update `" . $tab_prefix . "customer_entity` set `email` = '" . $profile->email . "' where`entity_id` =" . $customerId;
                $attributeModel1 = Mage::getModel('eav/entity_attribute')->loadByCode("customer", "firstname");
                $firstnameId     = $attributeModel1->getAttributeId();
                $attributeModel2 = Mage::getModel('eav/entity_attribute')->loadByCode("customer", "lastname");
                $lastnameId      = $attributeModel2->getAttributeId();
                $sql2            = "update `" . $tab_prefix . "customer_entity_varchar` set `value` = '" . $profile->fname . "' where `entity_type_id` =1 AND `attribute_id`=" . $firstnameId . " AND `entity_id`=" . $customerId;
                $sql3            = "update `" . $tab_prefix . "customer_entity_varchar` set `value` = '" . $profile->lname . "' where `entity_type_id` =1 AND `attribute_id`=" . $lastnameId . " AND `entity_id`=" . $customerId;
                
                if ($write->query($sql1) && $write->query($sql2) && $write->query($sql3)) {
                    $result['userprofile'] = 1;
                }
            }
            catch (Exception $ex) {
                Zend_Debug::dump($ex->getMessage());
            }
            return $result;
        }
        //---------------------------------------------------------------------
    }
    
    public function ws_forgotPassword($email = "")
    {
        $res             = array();
        $res["response"] = "error";
        
        if ($email) {
            /** @var $customer Mage_Customer_Model_Customer */
            $customer = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);
            
            if ($customer->getId()) {
                try {
                    $newResetPasswordLinkToken = Mage::helper('customer')->generateResetPasswordLinkToken();
                    $customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
                    $customer->sendPasswordResetConfirmationEmail();
                    $res["response"] = "success";
                }
                catch (Exception $exception) {
                    // $this->_getSession()->addError($exception->getMessage());        
                }
            }
        }
        return ($res);
    }
    
    public function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
    
    /* Function call to login user from Email address */
    
    public function ws_loginwithsocial($store, $username, $firstname, $lastname)
    {
        $websiteId       = Mage::getModel('core/store')->load($store)->getWebsiteId();
        $res             = array();
        $res["username"] = $username;
        $login_status    = 1;
        try {
            // $login_customer_result = Mage::getModel('customer/customer')->setWebsiteId($websiteId)->authenticate($username);
            $login_customer = Mage::getModel('customer/customer')->setWebsiteId($websiteId);
            $login_customer->loadByEmail($username);
            if ($login_customer->getId()) {
                $res["firstname"] = $login_customer->firstname;
                $res["lastname"]  = $login_customer->lastname;
                $res["id"]        = $login_customer->getId();
            } else {
                $login_status = 0;
                $res          = $this->ws_registerwithsocial($store, $username, $firstname, $lastname);
                if ($res["status"] == 1) {
                    $login_status = 1;
                }
            }
        }
        catch (Exception $e) {
            $login_status = 0;
            $res          = $this->ws_registerwithsocial($store, $username, $firstname, $lastname);
            if ($res["status"] == 1) {
                $login_status = 1;
            }
        }
        $res["login_status"] = $login_status;
        return $res;
    }
    
    /* Function call to register user from its Email address */
    
    public function ws_registerwithsocial($store, $email, $firstname, $lastname)
    {
        $res                  = array();
        $websiteId            = Mage::getModel('core/store')->load($store)->getWebsiteId();
        $customer             = Mage::getModel("customer/customer");
        $customer->website_id = $websiteId;
        $customer->setCurrentStore($store);
        try {
            // If new, save customer information
            $customer->firstname     = $firstname;
            $customer->lastname      = $lastname;
            $customer->email         = $email;
            $password                = base64_encode(rand(11111111, 99999999));
            $customer->password_hash = md5(base64_decode($password));
            $res["email"]            = $email;
            $res["firstname"]        = $firstname;
            $res["lastname"]         = $lastname;
            $res["password"]         = $password;
            $res["status"]           = 0;
            $res["id"]               = 0;
            $cust                    = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);
            
            //check exists email address of users  
            if ($cust->getId()) {
                $res["id"]     = $cust->getId();
                $res["status"] = 0;
            } else {
                if ($customer->save()) {
                    $customer->sendNewAccountEmail('confirmed');
                    $this->send_Password_Mail_to_NewUser($firstname, base64_decode($password), $email);
                    $res["id"]     = $customer->getId();
                    $res["status"] = 1;
                } else {
                    $exist_customer = Mage::getModel("customer/customer");
                    $exist_customer->setWebsiteId($websiteId);
                    $exist_customer->setCurrentStore($store);
                    $exist_customer->loadByEmail($email);
                    $res["id"]     = $exist_customer->getId();
                    $res["status"] = 1;
                }
            }
        }
        catch (Exception $e) {
            try {
                $exist_customer = Mage::getModel("customer/customer");
                $exist_customer->setWebsiteId($websiteId);
                $exist_customer->setCurrentStore($store);
                $exist_customer->loadByEmail($email);
                $res["id"]     = $exist_customer->getId();
                $res["status"] = 1;
            }
            catch (Exception $ex) {
                $res["id"]     = -1;
                $res["status"] = 0;
            }
        }
        return $res;
    }
    
    function mofluid_register_push($store, $deviceid, $pushtoken, $platform, $appname, $description)
    {
        $res        = array();
        $tab_prefix = Mage::getConfig()->getTablePrefix();
        try {
            $mofluid_push = Mage::getSingleton('core/resource')->getConnection('core_write');
            $readresult   = $mofluid_push->query("SELECT * FROM  " . $tab_prefix . "mofluidpush WHERE device_id = '" . $deviceid . "' AND app_name = '" . $appname . "' AND platform ='" . $platform . "'");
            $row          = $readresult->fetch();
            $readresult2  = $mofluid_push->query("SELECT * FROM  " . $tab_prefix . "mofluidpush WHERE push_token_id = '" . $pushtoken . "' AND app_name = '" . $appname . "' AND platform ='" . $platform . "'");
            $row          = $readresult->fetch();
            $row2         = $readresult2->fetch();
            
            if ($row["device_id"]) {
                $mofluid_push->query("DELETE FROM  " . $tab_prefix . "mofluidpush WHERE device_id = '" . $deviceid . "' AND app_name = '" . $appname . "' AND platform ='" . $platform . "'");
                $mofluid_push->query("insert into " . $tab_prefix . "mofluidpush (mofluidadmin_id, device_id, push_token_id, platform, app_name, description) 
                                                                      values (1,'" . $deviceid . "','" . $pushtoken . "','" . $platform . "','" . $appname . "','" . $description . "')");
                $res = array(
                    "status" => "update",
                    "deviceid" => $deviceid,
                    "pushtoken" => $pushtoken,
                    "message" => "Update token for the existing device id."
                );
            } else if ($row2["push_token_id"]) {
                $mofluid_push->query("DELETE FROM  " . $tab_prefix . "mofluidpush WHERE push_token_id = '" . $pushtoken . "' AND app_name = '" . $appname . "' AND platform ='" . $platform . "'");
                $mofluid_push->query("insert into " . $tab_prefix . "mofluidpush (mofluidadmin_id, device_id, push_token_id, platform, app_name, description) 
                                                                      values (1,'" . $deviceid . "','" . $pushtoken . "','" . $platform . "','" . $appname . "','" . $description . "')");
                $res = array(
                    "status" => "update",
                    "deviceid" => $deviceid,
                    "pushtoken" => $pushtoken,
                    "message" => "Update Device for the existing token id."
                );
            } else {
                $mofluid_push->query("insert into " . $tab_prefix . "mofluidpush (mofluidadmin_id, device_id, push_token_id, platform, app_name, description) 
                                                                      values (1,'" . $deviceid . "','" . $pushtoken . "','" . $platform . "','" . $appname . "','" . $description . "')");
                $res = array(
                    "status" => "register",
                    "deviceid" => $deviceid,
                    "pushtoken" => $pushtoken,
                    "message" => "register device id with new token."
                );
            }
        }
        catch (Exception $ex) {
            $res = array(
                "status" => "error",
                "deviceid" => $deviceid,
                "pushtoken" => $pushtoken,
                "message" => $ex->getMessage()
            );
        }
        return $res;
    }
    
    public function ws_termCondition($store)
    {
        $flag = Mage::getStoreConfigFlag('checkout/options/enable_agreements');
        if ($flag) {
            $agreements = Mage::getModel('checkout/agreement')->getCollection()->addStoreFilter($store)->addFieldToFilter('is_active', 1);
            $data       = $agreements->getData('agreements');
            return $data;
        }
    }
    
    public function ws_productQuantity($product)
    {
        $pqty    = array();
        $product = json_decode($product);
        foreach ($product as $key => $val) {
            try {
                $model      = Mage::getModel('catalog/product');
                $_product   = $model->load($val);
                $stocklevel = (int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product)->getQty();
                $pqty[$val] = $stocklevel;
            }
            catch (Exception $ex) {
                
            }
        }
        return $pqty;
    }
    
    public function ws_countryList($store_id, $paymentgateway, $pmethod)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_country_store" . $store_id . "_paymentgateway" . $paymentgateway . "_pmethod" . $pmethod;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        
        $country = array();
        if ($paymentgateway == "paypal" && $pmethod == "standard") {
            $allowspecific = Mage::getConfig()->getNode('default/payment/paypal_standard/allowspecific');
            
            // get specific countries of standard paypal method from config table
            if ($allowspecific == 1) {
                $_countries = Mage::getConfig()->getNode('default/payment/paypal_standard/specificcountry');
                $data       = explode(",", $_countries[0]);
                if (count($data) > 0) {
                    foreach ($data as $key => $country_code) {
                        $country[$country_code] = Mage::app()->getLocale()->getCountryTranslation($country_code);
                    }
                }
            } else {
                $_countries = Mage::getResourceModel('directory/country_collection')->loadData()->toOptionArray(false);
                
                if (count($_countries) > 0) {
                    foreach ($_countries as $_country) {
                        $country[$_country['value']] = $_country['label'];
                    }
                }
            }
        } // end of outer if 
        $cache->save(json_encode($country), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return ($country);
    }
    
    //************ list all enable shipping method ********** //
    
    public function ws_listShipping()
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_shipping_method";
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        
        $methods  = Mage::getSingleton('shipping/config')->getActiveCarriers();
        $shipping = array();
        foreach ($methods as $_ccode => $_carrier) {
            if ($_methods = $_carrier->getAllowedMethods()) {
                if (!$_title = Mage::getStoreConfig("carriers/$_ccode/title"))
                    $_title = $_ccode;
                foreach ($_methods as $_mcode => $_method) {
                    $_code            = $_ccode . '_' . $_mcode;
                    $shipping[$_code] = array(
                        'method' => $_method,
                        'title' => $_title
                    );
                }
            }
        }
        $cache->save(json_encode($shipping), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return $shipping;
    }
    
    function ws_validatecurrency($store, $service, $currency, $paymentgateway)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_service" . $service . "_store" . $store . "_currency" . $currency . "_paymentmethod" . $paymentgateway;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        if ($paymentgateway == 'secureebs_standard' || $paymentgateway == 'paypal_standard' || $paymentgateway == 'authorizenet' || $paymentgateway == 'authorize' || $paymentgateway == 'moto' || $paymentgateway == 'moneris' || $paymentgateway == 'banorte' || $paymentgateway == 'payucheckout_shared' || $paymentgateway == 'sisowde' || $paymentgateway == 'sisow_ideal') {
            $payment_types['paypal']              = array(
                "0" => 'AUD',
                "1" => 'BRL',
                "2" => 'CAD',
                "3" => 'CZK',
                "4" => 'DKK',
                "5" => 'EUR',
                "6" => 'HKD',
                "7" => 'HUF',
                "8" => 'ILS',
                "9" => 'JPY',
                "10" => 'MYR',
                "11" => 'MXN',
                "12" => 'NOK',
                "13" => 'NZD',
                "14" => 'PHP',
                "15" => 'PLN',
                "16" => 'GBP',
                "17" => 'RUB',
                "18" => 'SGD',
                "19" => 'SEK',
                "20" => 'CHF',
                "21" => 'TWD',
                "22" => 'TRY',
                "23" => 'THB',
                "24" => 'USD'
            );
            $payment_types['paypal_standard']     = $payment_types['paypal'];
            $payment_types['authorizenet']        = array(
                "0" => 'GBP',
                "1" => 'USD',
                "2" => 'EUR',
                "3" => 'AUD'
            );
            $payment_types['secureebs_standard']  = array(
                "0" => 'INR'
            );
            $payment_types['moto']                = array(
                "0" => 'INR'
            );
            $payment_types['moneris']             = array(
                "0" => 'USD'
            );
            $payment_types['banorte']             = array(
                "0" => 'MXN'
            );
            $payment_types['payucheckout_shared'] = array(
                "0" => 'INR'
            );
            $payment_types['sisowde']             = array(
                "0" => 'EUR'
            );
            $payment_types['sisow_ideal']         = array(
                "0" => 'EUR'
            );
            $size_of_array                        = sizeof($payment_types[$paymentgateway]);
            if ($size_of_array > 0) {
                if (in_array($currency, $payment_types[$paymentgateway]))
                    $status = "1";
                else {
                    $msg    = "Currency Code " . $currency . " is not supported with this Payment Type. Please Select different Payment Mode.";
                    $status = "0";
                }
            }
        } else
            $status = "1";
        $res["status"] = $status;
        $res["msg"]    = $msg;
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return $res;
    }
    
    /**
     * Method : prepareQuote
     * @param : $custid => Customer Id of the Logged In User
     * @param : $Jproduct => Cart Products data in json
     * @param : $store => Store Id of the Magento Store
     * @param : $address => Address for current request
     * @param : $couponCode  => Applied Coupon code
     */
    public function prepareQuote($custid, $Jproduct, $store, $address, $shipping_code, $couponCode, $currency, $is_create_quote, $find_shipping)
    {
        $Jproduct         = str_replace(" ", "+", $Jproduct);
        $orderproduct     = json_decode(base64_decode($Jproduct));
        $address          = str_replace(" ", "+", $address);
        $address          = json_decode(base64_decode($address));
        $basecurrencycode = Mage::app()->getStore($store)->getBaseCurrencyCode();
        try {
            $customerObj     = Mage::getModel('customer/customer')->load($custid);
            // get billing and shipping address of customer
            $shippingAddress = array(
                'prefix' => $address->shipping->prefix,
                'firstname' => $address->shipping->firstname,
                'lastname' => $address->shipping->lastname,
                'company' => $address->shipping->company,
                'street' => $address->shipping->street,
                'city' => $address->shipping->city,
                'postcode' => $address->shipping->postcode,
                'telephone' => $address->shipping->phone,
                'country_id' => $address->shipping->country,
                'region' => $address->shipping->region
            );
            $billingAddress  = array(
                'prefix' => $address->billing->prefix,
                'firstname' => $address->billing->firstname,
                'lastname' => $address->billing->lastname,
                'company' => $address->billing->company,
                'street' => $address->billing->street,
                'city' => $address->billing->city,
                'postcode' => $address->billing->postcode,
                'telephone' => $address->billing->phone,
                'country_id' => $address->billing->country,
                'region' => $address->billing->region
            );
            //Setting Region ID In case of Country is US
            if ($address->billing->country == "US" || $address->billing->country == "USA") {
                $regionModel                 = Mage::getModel('directory/region')->loadByCode($address->billing->region, $address->billing->country);
                $regionId                    = $regionModel->getId();
                $billingAddress["region_id"] = $regionId;
            }
            if ($address->shipping->country == "US" || $address->shipping->country == "USA") {
                $regionModelShipping          = Mage::getModel('directory/region')->loadByCode($address->shipping->region, $address->shipping->country);
                $regionIdShipp                = $regionModelShipping->getId();
                $shippingAddress["region_id"] = $regionIdShipp;
            }
            $quote    = Mage::getModel('sales/quote');
            $customer = Mage::getModel('customer/customer')->load($custid);
            $quote->assignCustomer($customer);
            Mage::app()->setCurrentStore($store);
            /* Added by Prafull to assign store to Mage */
            $quote->setStore(Mage::app()->getStore());
            $res           = array();
            $stock_counter = 0;
            foreach ($orderproduct as $key => $item) {
                $product_stock          = $this->getProductStock($item->id);
                $product_stock_quantity = $product_stock['qty'];
                $manage_stock           = $product_stock['manage_stock'];
                $is_in_stock            = $product_stock['is_in_stock'];
                if ($item->quantity > $product_stock_quantity) {
                    $res["status"]                              = "error";
                    $res["type"]                                = "quantity";
                    $res["product"][$stock_counter]["id"]       = $item->id;
                    $res["product"][$stock_counter]["name"]     = Mage::getModel('catalog/product')->load($item->id)->getName();
                    $res["product"][$stock_counter]["sku"]      = Mage::getModel('catalog/product')->load($item->id)->getSku();
                    $res["product"][$stock_counter]["quantity"] = $product_stock_quantity;
                    $stock_counter++;
                }
                $product = Mage::getModel('catalog/product');
                $product->load($item->id);
                $productType = $product->getTypeID();
                $quoteItem   = Mage::getModel('sales/quote_item')->setProduct($product);
                $quoteItem->setQuote($quote);
                $quoteItem->setQty($item->quantity);
                /* $parent = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
                $parent_product_id = $parent[0];
                if($parent_product_id) {
                $parent_product =  Mage::getModel('catalog/product')->load($parent_product_id);
                echo 'Parent ID : '.$parent_product_id;
                echo '<br>Parent Product Price : '.$parent_product->getPrice();
                echo '<br>Current Product Price : '.$product->getPrice();
                $total = $product->getPrice()+$parent_product->getPrice();
                echo '<br>Converted Price '.strval($this->convert_currency($product->getPrice(),$basecurrencycode,$currency));
                echo '<br>Total '.$total.'   Converted : '.strval($this->convert_currency($total ,$basecurrencycode,$currency));
                
                die;
                
                } */
                if ($item->options) {
                    foreach ($item->options as $ckey => $cvalue) {
                        $custom_option_ids_arr[] = $ckey;
                    }
                    $option_ids = implode(",", $custom_option_ids_arr);
                    $quoteItem->addOption(new Varien_Object(array(
                        'product' => $quoteItem->getProduct(),
                        'code' => 'option_ids',
                        'value' => $option_ids
                    )));
                    foreach ($item->options as $ckey => $cvalue) {
                        if (is_array($cvalue)) {
                            $all_ids = implode(",", array_unique($cvalue));
                        } else {
                            $all_ids = $cvalue;
                        }
                        //Handle Custom Option Time depending upon Timezone
                        if (preg_match('/(2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]/', $all_ids)) {
                            $currentTimestamp = Mage::getModel('core/date')->timestamp(time());
                            $currentDate      = date('Y-m-d', $currentTimestamp);
                            $test             = new DateTime($currentDate . ' ' . $all_ids);
                            $all_ids          = $test->getTimeStamp();
                        }
                        try {
                            $quoteItem->addOption(new Varien_Object(array(
                                'product' => $quoteItem->getProduct(),
                                'code' => 'option_' . $ckey,
                                'value' => $all_ids
                            )));
                        }
                        catch (Exception $eee) {
                            echo 'Error ' . $eee->getMessage();
                        }
                    } //end inner foreach
                    $quote->addItem($quoteItem);
                } //end if
                else {
                    $quote->addItem($quoteItem);
                    continue;
                }
            } //end outer foreach
            if ($stock_counter > 0 && $is_create_quote == 1) {
                return $res;
            }
            $addressForm = Mage::getModel('customer/form');
            $addressForm->setFormCode('customer_address_edit')->setEntityType('customer_address');
            foreach ($addressForm->getAttributes() as $attribute) {
                if (isset($shippingAddress[$attribute->getAttributeCode()])) {
                    $quote->getShippingAddress()->setData($attribute->getAttributeCode(), $shippingAddress[$attribute->getAttributeCode()]);
                }
            }
            foreach ($addressForm->getAttributes() as $attribute) {
                if (isset($billingAddress[$attribute->getAttributeCode()])) {
                    $quote->getBillingAddress()->setData($attribute->getAttributeCode(), $billingAddress[$attribute->getAttributeCode()]);
                }
            }
            $quote->setBaseCurrencyCode($basecurrencycode);
            $quote->setQuoteCurrencyCode($currency);
            if ($find_shipping) {
                $quote->getShippingAddress()->setCollectShippingRates(true);
                $quote->save();
            } else {
                $quote->getShippingAddress()->setShippingMethod($shipping_code)->setCollectShippingRates(true);
            }
            //Check if applied for coupon
            if (!empty($couponCode)) {
                $quote->setCouponCode($couponCode);
                $coupon_status = 1;
            } else {
                $coupon_status = 0;
            }
            $quote->setTotalsCollectedFlag(false)->collectTotals();
            $totals = $quote->getTotals();
            try {
                $test                = $quote->getShippingAddress();
                $shipping_tax_amount = number_format(Mage::helper('directory')->currencyConvert($test['shipping_tax_amount'], $basecurrencycode, $currency), 2, ".", "");
            }
            catch (Exception $ex) {
                $shipping_tax_amount = 0;
            }
            if ($find_shipping) {
                $shipping                 = $quote->getShippingAddress()->getGroupedAllShippingRates();
                $shipping_methods         = array();
                $index                    = 0;
                $shipping_dropdown_option = '';
                foreach ($shipping as $shipping_method_id => $shipping_method) {
                    foreach ($shipping_method as $current_shipping_method) {
                        $shipping_methods[$index]["id"]            = $shipping_method_id;
                        $shipping_methods[$index]["code"]          = str_replace(" ", "%20", $current_shipping_method->getCode());
                        $shipping_methods[$index]["method_title"]  = $current_shipping_method->getMethodTitle();
                        $shipping_methods[$index]["carrier_title"] = $current_shipping_method->getCarrierTitle();
                        $shipping_methods[$index]["carrier"]       = $current_shipping_method->getCarrier();
                        $shipping_methods[$index]["price"]         = Mage::helper('directory')->currencyConvert($current_shipping_method->getPrice(), $basecurrencycode, $currency);
                        $shipping_methods[$index]["description"]   = $current_shipping_method->getMethodDescription();
                        $shipping_methods[$index]["error_message"] = $current_shipping_method->getErrorMessage();
                        $shipping_methods[$index]["address_id"]    = $current_shipping_method->getAddressId();
                        $shipping_methods[$index]["created_at"]    = $current_shipping_method->getCreatedAt();
                        $shipping_methods[$index]["updated_at"]    = $current_shipping_method->getUpdatedAt();
                        $shipping_option_title                     = $shipping_methods[$index]["carrier_title"];
                        if ($shipping_methods[$index]["method_title"]) {
                            $shipping_option_title .= ' (' . $shipping_methods[$index]["method_title"] . ')';
                        }
                        if ($shipping_methods[$index]["price"]) {
                            $shipping_option_title .= ' + ' . Mage::app()->getLocale()->currency($currency)->getSymbol() . number_format($shipping_methods[$index]["price"], 2);
                        }
                        $shipping_dropdown_option .= '<option id=' . $shipping_methods[$index]["id"] . ' value= ' . $shipping_methods[$index]["code"] . ' price =' . $shipping_methods[$index]["price"] . ' description=' . $shipping_method[0]->getMethodDescription() . '>' . $shipping_option_title . '</option>';
                        $index++;
                    }
                }
                $res["available_shipping_method"] = base64_encode($shipping_dropdown_option);
            }
            $dis = 0;
            
            
            //Find Applied Tax
            if (isset($totals['tax']) && $totals['tax']->getValue()) {
                $tax_amount = number_format(Mage::helper('directory')->currencyConvert($totals['tax']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            } else {
                $tax_amount = 0;
            }
            if (isset($totals['shipping']) && $totals['shipping']->getValue()) {
                $shipping_amount = number_format(Mage::helper('directory')->currencyConvert($totals['shipping']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            } else {
                $shipping_amount = 0;
            }
            if ($shipping_tax_amount) {
                $shipping_amount += $shipping_tax_amount;
            }
            //Find Applied Discount
            if (isset($totals['discount']) && $totals['discount']->getValue()) {
                $coupon_status   = 1;
                $coupon_discount = number_format(Mage::helper('directory')->currencyConvert($totals['discount']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            } else {
                $coupon_discount = 0;
                $coupon_status   = 0;
            }
            $quoteData              = $quote->getData();
            $dis                    = $quoteData['grand_total'];
            $grandTotal             = number_format(Mage::helper('directory')->currencyConvert($totals['grand_total']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            $res["coupon_discount"] = $coupon_discount;
            $res["coupon_status"]   = $coupon_status;
            $res["tax_amount"]      = $tax_amount;
            $res["total_amount"]    = $grandTotal;
            $res["currency"]        = $currency;
            $res["status"]          = "success";
            $res["shipping_amount"] = $shipping_amount;
            if ($is_create_quote == 1) {
                $quote->save();
                $res["quote_id"] = $quote->getId();
            }
            return $res;
        }
        catch (Exception $ex) {
            $res["coupon_discount"] = 0;
            $res["coupon_status"]   = 0;
            $res["tax_amount"]      = 0;
            $res["total_amount"]    = 0;
            $res["currency"]        = $currency;
            $res["status"]          = "error";
            $res["type"]            = $ex->getMessage();
            $res["shipping_amount"] = $shipping_amount;
            return $res;
        }
    }
    
    public function getProductStock($product_id)
    {
        $stock_data    = array();
        $stock_product = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product_id);
        $stock_data    = $stock_product->getData();
        return $stock_data;
    }
    
    public function setQuoteGiftMessage($quote, $message, $custid)
    {
        $message_id = array();
        $message    = json_decode($message, true);
        foreach ($message as $key => $value) {
            $giftMessage = Mage::getModel('giftmessage/message');
            $giftMessage->setCustomerId($custid);
            $giftMessage->setSender($value["sender"]);
            $giftMessage->setRecipient($value["receiver"]);
            $giftMessage->setMessage($value["message"]);
            $giftObj                 = $giftMessage->save();
            $message_id["msg_id"][]  = $giftObj->getId();
            $message_id["prod_id"][] = $value["product_id"];
            $quote->setGiftMessageId($giftObj->getId());
            $quote->save();
        }
        return $quote;
    }
    
    public function setQuotePayment($quote, $pmethod, $transid)
    {
        $quotePayment = $quote->getPayment();
        $quotePayment->setMethod($pmethod)->setIsTransactionClosed(1)->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array(
            'TransactionID' => $transid,
            'key2' => 'value2'
        ));
        $quotePayment->setCustomerPaymentId($transid);
        $quote->setPayment($quotePayment);
        return $quote;
    }
    
    public function updateQuantityAfterOrder($Jproduct)
    {
        $error    = array();
        $Jproduct = str_replace(" ", "+", $Jproduct);
        
        $orderproduct = json_decode(base64_decode($Jproduct));
        try {
            foreach ($orderproduct as $key => $item) {
                $productId = $item->id;
                $orderQty  = $item->quantity;
                //get total quantity
                $totalqty  = (int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId)->getQty();
                //calculate new quantity
                $newqty    = $totalqty - $orderQty;
                //update new quantity
                try {
                    $product = Mage::getModel('catalog/product')->load($productId);
                    $product->setStockData(array(
                        'is_in_stock' => $newqty ? 1 : 0, //Stock Availability
                        'qty' => $newqty //qty
                    ));
                    $product->save();
                }
                catch (Exception $ee) {
                    $error[] = $ee->getMessage();
                }
            }
        }
        catch (Exception $ex) {
            $error[] = $ex->getMessage();
        }
        return $error;
    }
    
    /* ====================      Service to create order with Coupon Code  ================= */
    
    public function placeorder($custid, $Jproduct, $store, $address, $couponCode, $is_create_quote, $transid, $payment_code, $shipping_code, $currency, $message)
    {
        $res            = array();
        $quantity_error = array();
        try {
            $quote_data = $this->prepareQuote($custid, $Jproduct, $store, $address, $shipping_code, $couponCode, $currency, 1, 0);
            if ($quote_data["status"] == "error") {
                return $quote_data;
            }
            $quote        = Mage::getModel('sales/quote')->load($quote_data['quote_id']);
            //$quote->setInventoryProcessed(true);
            $quote        = $this->setQuoteGiftMessage($quote, $message, $custid);
            $quote        = $this->setQuotePayment($quote, $payment_code, $transid);
            $convertQuote = Mage::getSingleton('sales/convert_quote');
            try {
                $order = $convertQuote->addressToOrder($quote->getShippingAddress());
            }
            catch (Exception $Exc) {
                echo $Exc->getMessage();
            }
            $items = $quote->getAllItems();
            foreach ($items as $item) {
                $orderItem = $convertQuote->itemToOrderItem($item);
                if ($item->getParentItem()) {
                    $orderItem->setParentItem($order->getItemByQuoteItemId($item->getParentItem()->getId()));
                }
                $order->addItem($orderItem);
            }
            try {
                $decode_address = json_decode(base64_decode($address));
                $order->setCustomer_email($decode_address->billing->email);
                $order->setCustomerFirstname($decode_address->billing->firstname)->setCustomerLastname($decode_address->billing->lastname);
            }
            catch (Exception $e) {
                
            }
            $order->setBillingAddress($convertQuote->addressToOrderAddress($quote->getBillingAddress()));
            $order->setShippingAddress($convertQuote->addressToOrderAddress($quote->getShippingAddress()));
            $order->setPayment($convertQuote->paymentToOrderPayment($quote->getPayment()));
            $order->save();
            $quantity_error         = $this->updateQuantityAfterOrder($Jproduct);
            $res["status"]          = 1;
            $res["id"]              = $order->getId();
            $res["orderid"]         = $order->getIncrementId();
            $res["transid"]         = $order->getPayment()->getTransactionId();
            $res["shipping_method"] = $shipping_code;
            $res["payment_method"]  = $payment_code;
            $res["quantity_error"]  = $quantity_error;
            $order->addStatusHistoryComment("Order was placed using Mobile App")->setIsVisibleOnFront(false)->setIsCustomerNotified(false);
            if ($res["orderid"] > 0 && ($payment_code == "cashondelivery" || $payment_code == "banktransfer" || $payment_code == "free")) {
                $this->ws_sendorderemail($res["orderid"]);
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true)->save();
                $res["order_status"] = "PROCESSING";
            } else {
                $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true)->save();
                $res["order_status"] = "PENDING_PAYMENT";
            }
        }
        catch (Exception $except) {
            $res["status"]          = 0;
            $res["shipping_method"] = $shipping_code;
            $res["payment_method"]  = $payment_code;
        }
        
        return $res;
    }
    
    /* ====================      Service to check ship2myid module availability   ================= */
    
    public function ws_shipmyidenabled()
    {
        $res           = array();
        $res["result"] = 0;
        $modules       = array_keys((array) Mage::getConfig()->getNode('modules')->children());
        if (Mage::getStoreConfig('clm24core/shippings/enabled') && in_array("Mofluid_MofluidShipMyId", $modules)) {
            $res["result"] = 1;
        }
        return $res;
    }
    
    /* ====================      Service to send order email after successfull payment of paypal   ================= */
    
    public function ws_sendorderemail($orderid)
    {
        $result["result"] = 0;
        if ($orderid > 0) {
            try {
                $order = Mage::getModel('sales/order');
                $order->loadByIncrementId($orderid);
                if ($order->email_sent != 1) {
                    $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Gateway has authorized the payment.');
                    $order->sendNewOrderEmail();
                    $order->setEmailSent(true);
                    $result["result"] = 1;
                } else {
                    $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Gateway has authorized the payment.');
                }
                $order->save();
            }
            catch (Exception $ex) {
                //echo $ex->getMessage(); 
            }
        }
        return $result;
    }
    
    /* ====================  Service to fetch all product of Store ================= */
    
    public function ws_productSearchHelp($store_id)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_search_autosuggestion_store" . $store_id;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        $res                = array();
        $show_out_of_stock  = Mage::getStoreConfig('cataloginventory/options/show_out_of_stock');
        $is_in_stock_option = $show_out_of_stock ? 0 : 1;
        try {
            $collection = Mage::getResourceModel('catalog/product_collection')->joinField('is_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')->addAttributeToSelect('name')->addAttributeToSelect('id')->addStoreFilter($store_id)->addAttributeToFilter('status', 1)->addAttributeToFilter('type_id', array(
                'in' => array(
                    Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
                    Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE
                )
            ))->addAttributeToFilter('is_in_stock', array(
                'in' => array(
                    $is_in_stock_option,
                    1
                )
            ))->addAttributeToFilter('visibility', 4)->load();
            foreach ($collection as $_product) {
                $stock_status   = 1;
                $is_in_stock    = $_product->getStockItem()->getIsInStock();
                $stock_quantity = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product->getId())->getQty();
                //Uncomment if prevent uncategorized products
                /* if(!count($_product->getCategoryIds()) ){
                continue;
                } */
                
                
                if ($is_in_stock <= 0 || $stock_quantity <= 0) {
                    $stock_status = 0;
                }
                $res[] = array(
                    "id" => $_product->getId(),
                    "name" => $_product->getName(),
                    "stock_status" => $stock_status
                );
            }
        }
        catch (Exception $ex) {
            $res = $ex->getMessage();
        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return ($res);
    }
    
    public function ws_countryStateList($store_id, $country)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_country_statelist_store" . $store_id . "_country" . $country;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        
        $state = array();
        if ($country != "") {
            try {
                $_states = $us = Mage::getModel('directory/region_api')->items($country);
                
                if (count($_states) > 0) {
                    foreach ($_states as $_state) {
                        if ($_state['code'] != "")
                            $state[$_state['code']] = $_state['name'];
                    }
                } else {
                    $state["result"] = "0";
                }
            }
            catch (Exception $exception) {
                $state["result"] = "0";
            }
        }
        $cache->save(json_encode($state), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return ($state);
    }
    
    /* =======================get all mofluid extensions===================== */
    
    public function ws_getmofluidextension()
    {
        $connection      = Mage::getSingleton('core/resource')->getConnection('core_read');
        $resource        = Mage::getSingleton('core/resource');
        $modules         = Mage::getConfig()->getNode('modules')->children();
        $modulesArray    = (array) $modules;
        $module_name_arr = array();
        foreach ($modulesArray as $key => $val) {
            if ($val->active) {
                try {
                    $module_name_arr[] = $key;
                }
                catch (Exception $ex) {
                    
                }
            }
        }
        $selectresource              = $connection->select()->from(Mage::getSingleton('core/resource')->getTableName('mofluidadmin/mofluidresource'), array(
            '*'
        ));
        $MofluidResourcedata         = $connection->fetchAll($selectresource);
        $mofluid_available_resource  = array();
        $mofluid_final_resource_data = array();
        $mofluid_final_resource      = array();
        $found                       = 0;
        foreach ($module_name_arr as $mkey => $mval) {
            foreach ($MofluidResourcedata as $mrkey => $mrval) {
                if ($mrval['module'] == $mval && $mrval['sendbuildmode'] != 0) {
                    $mofluid_available_resource[] = $mrval['resource'];
                    $found                        = 1;
                }
            }
        }
        return ($mofluid_available_resource);
    }
    
    /* =====================get CMS Pages================== */
    
    public function getallCMSPages($store, $pageId)
    {
        $page_data            = array();
        $page                 = Mage::getModel('cms/page')->load($pageId);
        //    	$page_data=$page->getData();
        $page_data["title"]   = $page->getTitle();
        //    		$page_data["content"] = $page->getContent();
        $page_data["content"] = Mage::helper('cms')->getPageTemplateProcessor()->filter($page->getContent());
        //	$page_data = $this->formatpage_data($page_data);
        return ($page_data);
        // echo"<pre>"; print_r($page_data);
    }
    
    function deliver_timeslot($store, $timeslot, $custid)
    {
        
        
        
        $res        = array();
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $connection->beginTransaction();
        
        $select    = $connection->select()->from('mofluid_delivery_time', array(
            '*'
        )) // select * from tablename or use array('id','title') selected values
            ->where('user_id=?', $custid); // where id =$custid
        $rowsArray = $connection->fetchAll($select); // return all rows
        //$rowArray =$connection->fetchRow($select);
        
        if (count($rowsArray) > 0) {
            $__fields                  = array();
            $__fields['user_id']       = $custid;
            $__fields['delivery_time'] = $timeslot;
            $__where                   = $connection->quoteInto('user_id =?', $custid);
            $dat                       = $connection->update('mofluid_delivery_time', $__fields, $__where);
            if ($dat) {
                $res['dstatus'] = 1;
            } else {
                $res['dstatus'] = 0;
            }
        } else {
            
            $__fields                  = array();
            $__fields['user_id']       = $custid;
            $__fields['delivery_time'] = $timeslot;
            $dat                       = $connection->insert('mofluid_delivery_time', $__fields);
            if ($dat) {
                $res['dstatus'] = 1;
            } else {
                $res['dstatus'] = 0;
            }
        }
        $connection->commit();
        return $res;
    }
    
    function getdeliver_timeslot($store, $custid)
    {
        $res        = array();
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $connection->beginTransaction();
        
        $select    = $connection->select()->from('mofluid_delivery_time', array(
            '*'
        )) // select * from tablename or use array('id','title') selected values
            ->where('user_id=?', $custid); // where id =$custid
        $rowsArray = $connection->fetchAll($select); // return all rows
        $rowArray  = $connection->fetchRow($select);
        $abc       = explode(",", $rowArray['delivery_time']);
        
        foreach ($abc as $a) {
            array_push($res, trim($a));
        }
        
        $connection->commit();
        return $res;
    }
    
    /* =====================get payment method================== */
    
    public function ws_getpaymentmethod()
    {
        //Get all payment method values and status from mofluid payment module 
        $mofluid_pay_connection     = Mage::getSingleton('core/resource')->getConnection('core_read');
        $mofluid_pay_selectresource = $mofluid_pay_connection->select()->from(Mage::getSingleton('core/resource')->getTableName('mofluid_paymentcod/payment'), array(
            '*'
        ));
        $mofluid_pay_data           = $mofluid_pay_connection->fetchAll($mofluid_pay_selectresource);
        
        $connection      = Mage::getSingleton('core/resource')->getConnection('core_read');
        $resource        = Mage::getSingleton('core/resource');
        $modules         = Mage::getConfig()->getNode('modules')->children();
        $modulesArray    = (array) $modules;
        $module_name_arr = array();
        foreach ($modulesArray as $key => $val) {
            if ($val->active) {
                if (strpos($key, "Mofluid_Payment") !== false || $key == "Mage_Secureebs" || strpos($key, "MofluidCustom_Payment") !== false || strpos($key, "MofluidExtra_Payment") !== false) {
                    try {
                        $payment_key       = str_replace("MofluidCustom", "", $key);
                        $payment_key       = str_replace("MofluidExtra", "", $payment_key);
                        $payment_key       = str_replace("Mofluid", "", $payment_key);
                        $payment_key       = str_replace("_Payment", "", $payment_key);
                        $module_name_arr[] = $payment_key;
                    }
                    catch (Exception $ex) {
                        
                    }
                }
            }
        }
        //Verify all payment extensions exists on magento site 
        foreach ($mofluid_pay_data as $paykey => $payvalue) {
            $mofluid_pg_code = $payvalue["payment_method_code"];
            if (!$this->check_pay_method_in_array($module_name_arr, $mofluid_pg_code)) {
                $mofluid_pay_data[$paykey]["payment_method_status"] = "0";
            }
            //Check Dependency of EBS Payment Method with Mage_Secureebs module
            if ($payvalue["payment_method_code"] == "ebs") {
                if (!$this->check_pay_method_in_array($module_name_arr, "Mage_Secureebs")) {
                    $mofluid_pay_data[$paykey]["payment_method_status"] = "0";
                }
            }
            //Get Title and Instructions for Bank Transfer payment Method and update the array 
            if ($payvalue["payment_method_code"] == "banktransfer") {
                try {
                    $mofluid_pay_data[$paykey]["payment_method_title"]               = Mage::getModel("payment/method_banktransfer")->getTitle();
                    $mofluid_pay_data[$paykey]["payment_method_display_description"] = str_replace("\n", "<br>", Mage::getModel("payment/method_banktransfer")->getInstructions());
                }
                catch (Exception $ex) {
                    echo $ex->getMessage();
                }
            }
        }
        return ($mofluid_pay_data);
    }
    
    private function check_pay_method_in_array($pay_method_all_arr, $pay_method_single)
    {
        foreach ($pay_method_all_arr as $key => $value) {
            if (strpos($pay_method_single, $value) !== false) {
                return 1;
            }
        }
        return 0;
    }
    
    /* =======================get all mofluid app countries===================== */
    
    public function ws_mofluidappcountry($mofluid_store)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_country_store" . $mofluid_store;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        
        
        $res                = array();
        $country_sort_array = array();
        try {
            $collection = Mage::getModel('directory/country')->getCollection()->loadByStore($mofluid_store);
            foreach ($collection as $country) {
                $mofluid_country["country_id"]   = $country->getId();
                $mofluid_country["country_name"] = $country->getName();
                $mofluid_country_arr[]           = $mofluid_country;
                $country_sort_array[]            = $country->getName();
            }
            
            array_multisort($country_sort_array, SORT_ASC, $mofluid_country_arr);
            $res["mofluid_countries"] = $mofluid_country_arr;
            
            $res["mofluid_default_country"]["country_id"] = Mage::getStoreConfig('general/country/default', $mofluid_store);
            return $res;
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return $res;
    }
    
    /* =======================get all mofluid app states===================== */
    
    public function ws_mofluidappstates($mofluid_store, $countryid)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_states_store" . $mofluid_store . "_countryid" . $countryid;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        
        $res = array();
        try {
            $collection = Mage::getModel('directory/region')->getResourceCollection()->addCountryFilter($countryid)->load();
            foreach ($collection as $region) {
                $mofluid_region["region_id"]   = $region->code;
                $mofluid_region["region_name"] = $region->default_name;
                $res["mofluid_regions"][]      = $mofluid_region;
            }
            return $res;
        }
        catch (Exception $ex) {
            
        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return $res;
    }
    
    /* ============================gift message======================== */
    
    public function ws_checkGiftMessage($store)
    {
        $res["status"] = 0;
        $myGiftMessage = array();
        if ($store < 1) {
            $res["msg"] = "Store not valid";
            return $res;
        }
        $gift_message_type = Mage::getStoreConfig('sales/gift_options');
        if ($gift_message_type["allow_order"] == 0)
            $res["allow_order"] = 0;
        else if ($gift_message_type["allow_order"] == 1)
            $res["allow_order"] = 1;
        if ($gift_message_type["allow_items"] == 0)
            $res["allow_items"] = 0;
        else if ($gift_message_type["allow_items"] == 1)
            $res["allow_items"] = 1;
        return $res;
    }
    
    /* =================================check product============== */
    
    public function ws_checkProductGiftMessage($store, $Jproduct)
    {
        $res["status"] = "0";
        $productid     = json_decode($Jproduct, true);
        if (count($productid) == 0)
            return $res;
        $res["status"] = "1";
        foreach ($productid as $key => $value) {
            $product = Mage::getModel('catalog/product');
            $product->load($value);
            if ($product->getGift_message_available() == 1 || $product->getGift_message_available() == "")
                $res["data"][$value] = "1";
            else if ($product->getGift_message_available() == 0)
                $res["data"][$value] = "0";
        }
        return ($res);
    }
    
    /* =========================ebs payment=========================== */
    
    public function ws_ebspayment($store, $service, $paymentdata)
    {
        
        $mofluid_ebs_data = json_decode($paymentdata);
        
        //var_dump($mofluid_ebs_data);
        
        $mofluid_ebs_hash       = $mofluid_ebs_data->hash;
        $mofluid_ebs_account_id = $mofluid_ebs_data->account_id;
        try {
            $mofluid_ebs_return_url = base64_decode($mofluid_ebs_data->return_url);
        }
        catch (Exception $ex) {
            
        }
        
        $mofluid_ebs_mode         = $mofluid_ebs_data->mode;
        $mofluid_ebs_reference_no = $mofluid_ebs_data->reference_no;
        $mofluid_ebs_amount       = $mofluid_ebs_data->amount;
        $mofluid_ebs_description  = $mofluid_ebs_data->description;
        $mofluid_ebs_name         = $mofluid_ebs_data->name;
        $mofluid_ebs_address      = $mofluid_ebs_data->address;
        $mofluid_ebs_city         = $mofluid_ebs_data->city;
        $mofluid_ebs_state        = $mofluid_ebs_data->state;
        $mofluid_ebs_postal_code  = $mofluid_ebs_data->postal_code;
        $mofluid_ebs_country      = $mofluid_ebs_data->country;
        $mofluid_ebs_phone        = $mofluid_ebs_data->phone;
        $mofluid_ebs_email        = $mofluid_ebs_data->email;
        
        $mofluid_ebs_form = '<center><h2>Please wait, your order is being processed and you will be redirected to the EBS website.</h2></center>
							<center><br/><br/>If you are not automatically redirected to EBS within 5 seconds...<br/><br/>
						   	<form  method="post" action="https://secure.ebs.in/pg/ma/sale/pay" name="frmTransaction" id="frmTransaction">
							   <input name="account_id" type="hidden" value="' . $mofluid_ebs_account_id . '">
							   <input name="return_url" type="hidden" size="60" value="' . $mofluid_ebs_return_url . '" />
							   <input name="mode" type="hidden" size="60" value="' . $mofluid_ebs_mode . '" />
							   <input name="reference_no" type="hidden" value="' . $mofluid_ebs_reference_no . '" />
							   <input name="amount" type="hidden" value="' . $mofluid_ebs_amount . '"/>
							   <input name="description" type="hidden" value="' . $mofluid_ebs_description . '" /> 
							   <input name="name" type="hidden" maxlength="255" value="' . $mofluid_ebs_name . '" />
							   <input name="address" type="hidden" maxlength="255" value="' . $mofluid_ebs_address . '" />
							   <input name="city" type="hidden" maxlength="255" value="' . $mofluid_ebs_city . '" />
							   <input name="state" type="hidden" maxlength="255" value="' . $mofluid_ebs_state . '" />
							   <input name="postal_code" type="hidden" maxlength="255" value="' . $mofluid_ebs_postal_code . '" />
							   <input name="country" type="hidden" maxlength="255" value="' . $mofluid_ebs_country . '" />
							   <input name="phone" type="hidden" maxlength="255" value="' . $mofluid_ebs_phone . '" />
							   <input name="email" type="hidden" size="60" value="' . $mofluid_ebs_email . '" />
							   <input name="ship_name" type="hidden" maxlength="255" value="' . $mofluid_ebs_name . '" />
							   <input name="ship_address" type="hidden" maxlength="255" value="' . $mofluid_ebs_address . '" />
							   <input name="ship_city" type="hidden" maxlength="255" value="' . $mofluid_ebs_city . '" />
							   <input name="ship_state" type="hidden" maxlength="255" value="' . $mofluid_ebs_state . '" />
							   <input name="ship_postal_code" type="hidden" maxlength="255" value="' . $mofluid_ebs_postal_code . '" />
							   <input name="ship_country" type="hidden" maxlength="255" value="' . $mofluid_ebs_country . '" />
							   <input name="ship_phone" type="hidden" maxlength="255" value="' . $mofluid_ebs_phone . '" />
							   <input name="secure_hash" type="hidden" size="60" value="' . $mofluid_ebs_hash . '" />
							   <input name="submitted" value="Click here" type="submit" />
						    </form></center><script>document.frmTransaction.submit();</script>';
        
        echo $mofluid_ebs_form;
    }
    
    /* ===========================================mofluid eb response===================== */
    
    public function ws_mofluid_ebs_pgresponse($store, $service, $mofluid_paymentdata, $mofluid_app_platform)
    {
        $model           = Mage::getModel('mofluid_paymentebs/paymentebs');
        $mofluid_pay_ebs = $model->load(4);
        $secret_key      = $mofluid_pay_ebs->getData('payment_method_account_key');
        try {
            
            if ($mofluid_paymentdata) {
                require('Rc43.php');
                $DR          = preg_replace("/\s/", "+", $mofluid_paymentdata);
                $rc4         = new Crypt_RC4($secret_key);
                $QueryString = base64_decode($DR);
                $rc4->decrypt($QueryString);
                $QueryString = explode('&', $QueryString);
                $response    = array();
                foreach ($QueryString as $param) {
                    $param               = explode('=', $param);
                    $response[$param[0]] = urldecode($param[1]);
                }
                
                
                try {
                    $mofluid_orderid = $response["MerchantRefNo"];
                    if ($mofluid_orderid == "" || $mofluid_orderid == null) {
                        
                    } else {
                        $this->ws_sendorderemail($mofluid_orderid);
                    }
                }
                catch (Exception $ex) {
                    
                }
                
                
                
                
                
                $ebs_print_response = "<center><h3>" . $response["ResponseMessage"] . "</h3><br />Order ID : " . $response["MerchantRefNo"] . "<br />Transaction ID : " . $response["TransactionID"] . "<br />Payment ID : " . $response["PaymentID"] . "<br />Amount : " . $response["Amount"] . "<br/><br/> Click on Close/Done button to close this window..</center>";
                echo $ebs_print_response;
            }
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
    
    public function ws_authorizepaymentresponse($store, $service, $mofluid_orderid, $payment_response)
    {
        try {
            // $mofluid_orderid = $response["MerchantRefNo"];
            if ($mofluid_orderid == "" || $mofluid_orderid == null) {
                $mofluid_orderid = $payment_response["x_invoice_num"];
                $this->ws_sendorderemail($mofluid_orderid);
            } else {
                $this->ws_sendorderemail($mofluid_orderid);
            }
        }
        catch (Exception $ex) {
            
        }
        
        $response_text = "<center><h3>" . $payment_response["x_response_reason_text"] . "</h3><br />Order ID : " . $mofluid_orderid . "<br /><br/> Click on Close/Done button to close this window..</center>";
        echo $response_text;
    }
    
    /* ============================print payment response=================== */
    
    public function ws_printpaymentresponse($store, $mofluidpayaction, $postdata, $mofluid_payment_mode, $mofluid_order_id_unsecure)
    {
        
        $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
        if (strtolower($mofluid_payment_mode) == "test") {
            $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
        if ($mofluidpayaction == "success") {
            try {
                if ($postdata['mofluid_order_id'] == "" || $postdata['mofluid_order_id'] == null) {
                    
                    $mofluid_order_id = $mofluid_order_id_unsecure;
                    $this->ws_sendorderemail($mofluid_order_id);
                } else {
                    $mofluid_order_id = $postdata['mofluid_order_id'];
                    $this->ws_sendorderemail($mofluid_order_id);
                }
            }
            catch (Exception $ex) {
                
            }
            
            
            echo '<html>
							 <head>
								 <title>Success</title>
								 <meta name="viewport" content="width = 100%" />
								 <meta name="viewport" content="initial-scale=2.5, user-scalable=no" />
							 </head>
							 <body>
								 <center>
									 <h3>Thank you for your order.</h3>
								 </center>';
            
            if (strstr($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'], 'iPod') || strstr($_SERVER['HTTP_USER_AGENT'], 'Android') || strstr($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
                $dis_body = "<br><br><b>Your transaction was successfull.</b><br><br><br>See Your mail for more details";
            } else {
                $dis_body = "<br><b>Payment Details :- </b><br>";
                $dis_body .= "<br>Payment Status : <b>" . $postdata['payment_status'] . "</b>";
                $dis_body .= "<br>Transaction ID : <b>" . $postdata['txn_id'] . "</b>";
                $dis_body .= "<br>Order ID : " . $postdata['item_name'];
                $dis_body .= "<br>Payment Date : " . $postdata['payment_date'];
                $dis_body .= "<br>Amount : " . $postdata['mc_gross'] . $postdata['mc_currency'];
                $dis_body .= "<br><br>See Your mail " . $postdata['payer_email'] . " for more details";
            }
            
            $dis_body .= "<br><br><br><br>Please close this window.";
            echo '<center>' . $dis_body . '</center></body></html>';
        } else if ($mofluidpayaction == "ipn") {
            $this->validate_ipn($paypal_url, $postdata);
        } else if ($mofluidpayaction == "cancel") {
            echo "<html><head><title>Canceled</title></head><body><center><h3>The order was canceled.</h3></center>";
            echo "<br><br><br><center>Please Close this window</center></body></html>";
        } else {
            echo "<br>Unknown Response<br>";
            //print_r($postdata);
        }
    }
    
    function validate_ipn($paypal_url, $postdata)
    {
        $ipn_response;
        $log_ipn_results;
        // parse the paypal URL
        $url_parsed  = parse_url($paypal_url);
        $post_string = '';
        foreach ($postdata as $field => $value) {
            $ipn_data["$field"] = $value;
            $post_string .= $field . '=' . urlencode(stripslashes($value)) . '&';
        }
        
        $post_string .= "cmd=_notify-validate"; // append ipn command
        $fp = fsockopen("ssl://" . $url_parsed['host'], "443", $err_num, $err_str, 30);
        if (!$fp) {
            $last_error = "fsockopen error no. $errnum: $errstr";
            return false;
        } else {
            fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
            fputs($fp, "Host: $url_parsed[host]\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: " . strlen($post_string) . "\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $post_string . "\r\n\r\n");
            while (!feof($fp)) {
                $ipn_response .= fgets($fp, 1024);
            }
            fclose($fp); // close connection
        }
        
        if (eregi("VERIFIED", $ipn_response)) {
            return true;
        } else {
            $last_error = 'IPN Validation Failed.';
            return false;
        }
    }
    
    public function subscribeNewsletter($user_mail)
    {
        $subscriber = Mage::getModel(‘newsletter / subscriber’);
        $subscriber->subscribe($user_mail);
    }
    
    function get_configurable_products($productid, $currentcurrencycode)
    {
        /* $cache = Mage::app()->getCache();
        $cache_key = "mofluid_configurable_products_productid".$productid."_currency".$currentcurrencycode;
        if($cache->load($cache_key))
        return json_decode($cache->load($cache_key));
        */
        $basecurrencycode = Mage::app()->getStore()->getBaseCurrencyCode();
        try {
            $product_data = Mage::getModel('catalog/product')->load($productid);
            if ($product_data->getTypeID() == "configurable") {
                $productAttributeOptions      = $product_data->getTypeInstance(true)->getConfigurableAttributes($product_data);
                $conf                         = Mage::getModel('catalog/product_type_configurable')->setProduct($product_data);
                $simple_collection            = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
                $configurable_array_selection = array();
                $configurable_array           = array();
                $configurable_count           = 0;
                $relation_count               = 0;
                //load data for children 
                foreach ($simple_collection as $product) {
                    $a                          = Mage::getModel('catalog/product')->load($product->getId());
                    $taxClassId                 = $a->getData("tax_class_id");
                    $taxClasses                 = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
                    $taxRate                    = $taxClasses["value_" . $taxClassId];
                    $b                          = (($taxRate) / 100) * ($product->getPrice());
                    $product_for_custom_options = Mage::getModel('catalog/product')->load($product->getId());
                    $all_custom_option_array    = array();
                    $attVal                     = $product_for_custom_options->getOptions();
                    $optStr                     = "";
                    $inc                        = 0;
                    
                    $configurable_count = 0;
                    foreach ($productAttributeOptions as $attribute) {
                        $productAttribute                                              = $attribute->getProductAttribute();
                        $productAttributeId                                            = $productAttribute->getId();
                        $attributeValue                                                = $product->getData($productAttribute->getAttributeCode());
                        $attributeLabel                                                = $product->getData($productAttribute->getValue());
                        $configurable_array[$configurable_count]["productAttributeId"] = $productAttributeId;
                        $configurable_array[$configurable_count]["selected_value"]     = $attributeValue;
                        $configurable_array[$configurable_count]["label"]              = $attribute->getLabel();
                        $configurable_array[$configurable_count]["is_required"]        = $productAttribute->getIsRequired();
                        $configurable_array[$configurable_count]["id"]                 = $product->getId();
                        $configurable_array[$configurable_count]["sku"]                = $product->getSku();
                        $configurable_array[$configurable_count]["name"]               = $product->getName();
                        $configurable_array[$configurable_count]["image"]              = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $product->getImage();
                        $defaultsplprice                                               = str_replace(",", "", number_format($product->getSpecialprice(), 2));
                        $configurable_array[$configurable_count]["spclprice"]          = strval($this->convert_currency($defaultsplprice, $basecurrencycode, $currentcurrencycode));
                        $configurable_array[$configurable_count]["price"]              = number_format($product->getPrice(), 2);
                        $configurable_array[$configurable_count]["currencysymbol"]     = Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol();
                        $configurable_array[$configurable_count]["created_date"]       = $product->getCreatedAt();
                        $configurable_array[$configurable_count]["is_in_stock"]        = $product->getStockItem()->getIsInStock();
                        $configurable_array[$configurable_count]["stock_quantity"]     = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId())->getQty();
                        $configurable_array[$configurable_count]["type"]               = $product->getTypeID();
                        $configurable_array[$configurable_count]["shipping"]           = Mage::getStoreConfig('carriers/flatrate/price');
                        $configurable_array[$configurable_count]["data"]               = $this->ws_get_configurable_option_attributes($attributeValue, $attribute->getLabel(), $productid, $currentcurrencycode);
                        $configurable_array[$configurable_count]["tax"]                = number_format($b, 2);
                        try {
                            $configurable_curr_arr = (array) $configurable_array[$configurable_count]["data"];
                            if ($configurable_relation[$relation_count]) {
                                $configurable_relation[$relation_count] = $configurable_relation[$relation_count] . ', ' . str_replace(',', '', str_replace(' ', '', $configurable_curr_arr["label"]));
                            } else {
                                $configurable_relation[$relation_count] = str_replace(',', '', str_replace(' ', '', $configurable_curr_arr["label"]));
                            }
                        }
                        catch (Exception $err) {
                            echo 'Error : ' . $err->getMessage();
                        }
                        $configurable_count++;
                    }
                    $relation_count++;
                    $configurable_array_selection[] = $configurable_array;
                }
                $configurable_array_selection['relation'] = $configurable_relation;
                //load data for parent
                $mofluid_all_product_images               = array();
                $mofluid_non_def_images                   = array();
                $mofluid_product                          = Mage::getModel('catalog/product')->load($productid);
                $mofluid_baseimage                        = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_product->getImage();
                
                foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
                    $mofluid_imagecame = $mofluid_image->getUrl();
                    if ($mofluid_baseimage == $mofluid_imagecame) {
                        $mofluid_all_product_images[] = $mofluid_image->getUrl();
                    } else {
                        $mofluid_non_def_images[] = $mofluid_image->getUrl();
                    }
                }
                $mofluid_all_product_images  = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
                $configurable_product_parent = array();
                $parent_a                    = Mage::getModel('catalog/product')->load($product_data->getId());
                $parent_taxClassId           = $parent_a->getData("tax_class_id");
                $parent_taxClasses           = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
                $parent_taxRate              = $parent_taxClasses["value_" . $parent_taxClassId];
                $parent_b                    = (($parent_taxRate) / 100) * ($product_data->getPrice());
                
                
                $parent_all_custom_option_array = array();
                $parent_attVal                  = $product_data->getOptions();
                $parent_optStr                  = "";
                $parent_inc                     = 0;
                $has_custom_option              = 0;
                foreach ($parent_attVal as $parent_optionKey => $parent_optionVal) {
                    $parent_all_custom_option_array[$parent_inc]['custom_option_name']        = $parent_optionVal->getTitle();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_id']          = $parent_optionVal->getId();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_is_required'] = $parent_optionVal->getIsRequired();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_type']        = $parent_optionVal->getType();
                    $parent_all_custom_option_array[$parent_inc]['sort_order']                = $parent_optionVal->getSortOrder();
                    $parent_all_custom_option_array[$parent_inc]['all']                       = $parent_optionVal->getData();
                    
                    if ($parent_all_custom_option_array[$parent_inc]['all']['default_price_type'] == "percent") {
                        $parent_all_custom_option_array[$parent_inc]['all']['price'] = number_format((($product->getPrice() * $parent_all_custom_option_array[$parent_inc]['all']['price']) / 100), 2);
                    } else {
                        $parent_all_custom_option_array[$parent_inc]['all']['price'] = number_format($parent_all_custom_option_array[$inc]['all']['price'], 2);
                    }
                    
                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'];
                    $parent_inner_inc  = 0;
                    $has_custom_option = 1;
                    foreach ($parent_optionVal->getValues() as $parent_valuesKey => $parent_valuesVal) {
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['id']         = $parent_valuesVal->getId();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['title']      = $parent_valuesVal->getTitle();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['price']      = number_format($parent_valuesVal->getPrice(), 0);
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['price_type'] = $parent_valuesVal->getPriceType();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['sku']        = $parent_valuesVal->getSku();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['sort_order'] = $parent_valuesVal->getSortOrder();
                        
                        $parent_inner_inc++;
                    }
                    $parent_inc++;
                }
                $configurable_product_parent["id"]       = $product_data->getId();
                $configurable_product_parent["sku"]      = $product_data->getSku();
                $configurable_product_parent["name"]     = $product_data->getName();
                $configurable_product_parent["category"] = $product_data->getCategoryIds();
                $configurable_product_parent["discount"] = number_format($product_data->getFinalPrice(), 2);
                $configurable_product_parent["shipping"] = Mage::getStoreConfig('carriers/flatrate/price');
                $configurable_product_parent["image"]    = $mofluid_all_product_images; // Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/catalog/product'.$product_data->getImage();
                //$defaultprice = str_replace(",","", number_format($product_data->getPrice(),2)); 
                //$configurable_product_parent["price"] = strval($this->convert_currency($defaultprice,$basecurrencycode,$currentcurrencycode));
                
                $defaultprice                          = str_replace(",", "", ($product_data->getFinalPrice()));
                $configurable_product_parent["price"]  = strval(round($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2));
                $defaultsprice                         = str_replace(",", "", ($product_data->getSpecialprice()));
                $configurable_product_parent["sprice"] = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                
                //$defaultsprice =  str_replace(",","",number_format($product_data->getSpecialprice(),2)); 
                //$configurable_product_parent["sprice"] = strval($this->convert_currency($defaultsprice,$basecurrencycode,$currentcurrencycode));
                $configurable_product_parent["currencysymbol"]    = Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol();
                $configurable_product_parent["url"]               = $product_data->getProductUrl();
                $configurable_product_parent["description"]       = $product_data->getDescription();
                $configurable_product_parent["shortdes"]          = $product_data->getShortDescription();
                $configurable_product_parent["type"]              = $product_data->getTypeID();
                $configurable_product_parent["created_date"]      = $product_data->getCreatedAt();
                $configurable_product_parent["is_in_stock"]       = $product_data->getStockItem()->getIsInStock();
                $configurable_product_parent["quantity"]          = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product_data->getId())->getQty();
                $configurable_product_parent["visibility"]        = $product_data->isVisibleInSiteVisibility();
                $configurable_product_parent["weight"]            = $product_data->getWeight();
                $configurable_product_parent["status"]            = $product_data->getStatus();
                $configurable_product_parent["variation"]         = $product_data->getColor();
                $configurable_product_parent["custom_option"]     = $parent_all_custom_option_array;
                $configurable_product_parent["tax"]               = number_format($parent_b, 2);
                $configurable_product_parent["has_custom_option"] = $has_custom_option;
                
                $configurable_array_selection["parent"] = $configurable_product_parent;
                $configurable_array_selection["size"]   = sizeof($configurable_array_selection);
                
                // Add code for custom attribute start:
                $custom_attr       = array();
                //$product = $product_data;
                $attributes        = $product_data->getAttributes();
                //echo count($attributes);
                $custom_attr_count = 0;
                foreach ($attributes as $attribute) {
                    if ($attribute->is_user_defined && $attribute->is_visible) {
                        $attribute_value = $attribute->getFrontend()->getValue($product);
                        if ($attribute_value == null || $attribute_value == "") {
                            continue;
                        } else {
                            $custom_attr["data"][$custom_attr_count]["attr_code"]  = $attribute->getAttributeCode();
                            $custom_attr["data"][$custom_attr_count]["attr_label"] = $attribute->getStoreLabel($product);
                            $custom_attr["data"][$custom_attr_count]["attr_value"] = $attribute_value;
                            ++$custom_attr_count;
                        }
                    }
                }
                $custom_attr["total"]                             = $custom_attr_count;
                $configurable_array_selection["custom_attribute"] = $custom_attr;
                // Add code for custom attribute end:
                // $cache->save(json_encode($configurable_array_selection), $cache_key, array("mofluid"), $this->CACHE_EXPIRY); 
                return $configurable_array_selection;
                //echo "<pre>"; print_r(json_encode($configurable_array_selection)); die;
            } else
                return "Product Id " . $productid . " is not a Configurable Product";
        }
        catch (Exception $ex) {
            return "Error";
        }
    }
    
    /*   * ************************************************************************************************************************************** */
    
    function get_configurable_products_description($productid, $currentcurrencycode,$store)
    {
        /*$cache     = Mage::app()->getCache();
        $cache_key = "mofluid_configurable_products_productid" . $productid . "_currency" . $currentcurrencycode;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));*/
        Mage::app()->setCurrentStore($store);
        $basecurrencycode = Mage::app()->getStore()->getBaseCurrencyCode();
        try {
            $product_data = Mage::getModel('catalog/product')->load($productid);
            if ($product_data->getTypeID() == "configurable") {
                $productAttributeOptions      = $product_data->getTypeInstance(true)->getConfigurableAttributes($product_data);
                $conf                         = Mage::getModel('catalog/product_type_configurable')->setProduct($product_data);
                $simple_collection            = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
                $configurable_array_selection = array();
                $configurable_array           = array();
                $configurable_count           = 0;
                $relation_count               = 0;
                //load data for children 
                //print_r($product_data); die;
                foreach ($simple_collection as $product) {
                    $a                          = Mage::getModel('catalog/product')->load($product->getId());
                    $taxClassId                 = $a->getData("tax_class_id");
                    $taxClassId                 = $product_data->getData("tax_class_id");
                    $taxClasses                 = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
                    $taxRate                    = $taxClasses["value_" . $taxClassId];
                    $b                          = (($taxRate) / 100) * ($product->getPrice());
                    $product_for_custom_options = Mage::getModel('catalog/product')->load($product->getId());
                    $all_custom_option_array    = array();
                    $attVal                     = $product_for_custom_options->getOptions();
                    $optStr                     = "";
                    $inc                        = 0;
                    
                    $configurable_count = 0;
                    foreach ($productAttributeOptions as $attribute) {
                        $productAttribute                                              = $attribute->getProductAttribute();
                        $productAttributeId                                            = $productAttribute->getId();
                        $attributeValue                                                = $product->getData($productAttribute->getAttributeCode());
                        $attributeLabel                                                = $product->getData($productAttribute->getValue());
                        $configurable_array[$configurable_count]["productAttributeId"] = $productAttributeId;
                        $configurable_array[$configurable_count]["selected_value"]     = $attributeValue;
                        $configurable_array[$configurable_count]["label"]              = $attribute->getLabel();
                        $configurable_array[$configurable_count]["is_required"]        = $productAttribute->getIsRequired();
                        $configurable_array[$configurable_count]["id"]                 = $product->getId();
                        $configurable_array[$configurable_count]["sku"]                = $product->getSku();
                        $configurable_array[$configurable_count]["name"]               = $product->getName();
                        //$configurable_array[$configurable_count]["image"] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/catalog/product'.$product->getImage();
                        $defaultsplprice                                               = str_replace(",", "", number_format($product->getFinalPrice(), 2));
                        $configurable_array[$configurable_count]["spclprice"]          = strval($this->convert_currency($defaultsplprice, $basecurrencycode, $currentcurrencycode));
                        $configurable_array[$configurable_count]["price"]              = number_format($product->getPrice(), 2);
                        $configurable_array[$configurable_count]["currencysymbol"]     = Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol();
                        $configurable_array[$configurable_count]["created_date"]       = $product->getCreatedAt();
                        $configurable_array[$configurable_count]["is_in_stock"]        = $product->getStockItem()->getIsInStock();
                        $configurable_array[$configurable_count]["stock_quantity"]     = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId())->getQty();
                        $configurable_array[$configurable_count]["type"]               = $product->getTypeID();
                        $configurable_array[$configurable_count]["shipping"]           = Mage::getStoreConfig('carriers/flatrate/price');
                        $configurable_array[$configurable_count]["data"]               = $this->ws_get_configurable_option_attributes($attributeValue, $attribute->getLabel(), $productid, $currentcurrencycode,$store);
                        $configurable_array[$configurable_count]["tax"]                = number_format($b, 2);
                        try {
                            $configurable_curr_arr = (array) $configurable_array[$configurable_count]["data"];
                            if ($configurable_relation[$relation_count]) {
                                $configurable_relation[$relation_count] = $configurable_relation[$relation_count] . ', ' . str_replace(',', '', str_replace(' ', '', $configurable_curr_arr["label"]));
                            } else {
                                $configurable_relation[$relation_count] = str_replace(',', '', str_replace(' ', '', $configurable_curr_arr["label"]));
                            }
                        }
                        catch (Exception $err) {
                            echo 'Error : ' . $err->getMessage();
                        }
                        $configurable_count++;
                    }
                    $relation_count++;
                    $configurable_array_selection[] = $configurable_array;
                }
                $configurable_array_selection['relation'] = $configurable_relation;
                //load data for parent
                /*$mofluid_all_product_images = array();
                $mofluid_non_def_images = array();
                $mofluid_product = Mage::getModel('catalog/product')->load($productid);
                $mofluid_baseimage = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_product->getImage();
                
                foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
                $mofluid_imagecame = $mofluid_image->getUrl();
                if ($mofluid_baseimage == $mofluid_imagecame) {
                $mofluid_all_product_images[] = $mofluid_image->getUrl();
                } else {
                $mofluid_non_def_images[] = $mofluid_image->getUrl();
                }
                }
                $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
                */
                $configurable_product_parent              = array();
                $parent_a                                 = Mage::getModel('catalog/product')->load($product_data->getId());
                $parent_taxClassId                        = $parent_a->getData("tax_class_id");
                $parent_taxClasses                        = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
                $parent_taxRate                           = $parent_taxClasses["value_" . $parent_taxClassId];
                $parent_b                                 = (($parent_taxRate) / 100) * ($product_data->getPrice());
                
                
                $parent_all_custom_option_array = array();
                $parent_attVal                  = $product_data->getOptions();
                $parent_optStr                  = "";
                $parent_inc                     = 0;
                $has_custom_option              = 0;
                foreach ($parent_attVal as $parent_optionKey => $parent_optionVal) {
                    $parent_all_custom_option_array[$parent_inc]['custom_option_name']        = $parent_optionVal->getTitle();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_id']          = $parent_optionVal->getId();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_is_required'] = $parent_optionVal->getIsRequired();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_type']        = $parent_optionVal->getType();
                    $parent_all_custom_option_array[$parent_inc]['sort_order']                = $parent_optionVal->getSortOrder();
                    $parent_all_custom_option_array[$parent_inc]['all']                       = $parent_optionVal->getData();
                    
                    if ($parent_all_custom_option_array[$parent_inc]['all']['default_price_type'] == "percent") {
                        $parent_all_custom_option_array[$parent_inc]['all']['price'] = number_format((($product->getPrice() * $parent_all_custom_option_array[$parent_inc]['all']['price']) / 100), 2);
                    } else {
                        $parent_all_custom_option_array[$parent_inc]['all']['price'] = number_format($parent_all_custom_option_array[$inc]['all']['price'], 2);
                    }
                    
                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'];
                    $parent_inner_inc  = 0;
                    $has_custom_option = 1;
                    foreach ($parent_optionVal->getValues() as $parent_valuesKey => $parent_valuesVal) {
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['id']         = $parent_valuesVal->getId();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['title']      = $parent_valuesVal->getTitle();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['price']      = number_format($parent_valuesVal->getPrice(), 0);
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['price_type'] = $parent_valuesVal->getPriceType();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['sku']        = $parent_valuesVal->getSku();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['sort_order'] = $parent_valuesVal->getSortOrder();
                        
                        $parent_inner_inc++;
                    }
                    $parent_inc++;
                }
                $configurable_product_parent["id"]       = $product_data->getId();
                $configurable_product_parent["sku"]      = $product_data->getSku();
                $configurable_product_parent["name"]     = $product_data->getName();
                $configurable_product_parent["category"] = $product_data->getCategoryIds();
                $configurable_product_parent["discount"] = number_format($product_data->getFinalPrice(), 2);
                $configurable_product_parent["shipping"] = Mage::getStoreConfig('carriers/flatrate/price');
                //$configurable_product_parent["image"] = $mofluid_all_product_images; // Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/catalog/product'.$product_data->getImage();
                //$defaultprice = str_replace(",","", number_format($product_data->getPrice(),2)); 
                //$configurable_product_parent["price"] = strval($this->convert_currency($defaultprice,$basecurrencycode,$currentcurrencycode));
                
                $defaultprice                          = str_replace(",", "", ($product_data->getPrice()));
                $configurable_product_parent["price"]  = strval(round($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2));
                $defaultsprice                         = str_replace(",", "", ($product_data->getFinalPrice()));
                if($defaultprice == $defaultsprice){
                	$defaultsprice                         = 0;
                }
                $configurable_product_parent["sprice"] = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                
                //$defaultsprice =  str_replace(",","",number_format($product_data->getSpecialprice(),2)); 
                //$configurable_product_parent["sprice"] = strval($this->convert_currency($defaultsprice,$basecurrencycode,$currentcurrencycode));
                $configurable_product_parent["currencysymbol"]    = Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol();
                $configurable_product_parent["url"]               = $product_data->getProductUrl();
                $configurable_product_parent["description"]       = $product_data->getDescription();
                $configurable_product_parent["shortdes"]          = $product_data->getShortDescription();
                $configurable_product_parent["type"]              = $product_data->getTypeID();
                $configurable_product_parent["created_date"]      = $product_data->getCreatedAt();
                $configurable_product_parent["is_in_stock"]       = $product_data->getStockItem()->getIsInStock();
                $configurable_product_parent["quantity"]          = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product_data->getId())->getQty();
                $configurable_product_parent["visibility"]        = $product_data->isVisibleInSiteVisibility();
                $configurable_product_parent["weight"]            = $product_data->getWeight();
                $configurable_product_parent["status"]            = $product_data->getStatus();
                $configurable_product_parent["variation"]         = $product_data->getColor();
                $configurable_product_parent["custom_option"]     = $parent_all_custom_option_array;
                $configurable_product_parent["tax"]               = number_format($parent_b, 2);
                $configurable_product_parent["has_custom_option"] = $has_custom_option;
                
                $configurable_array_selection["parent"] = $configurable_product_parent;
                $configurable_array_selection["size"]   = sizeof($configurable_array_selection);
                
                // Add code for custom attribute start:
                $custom_attr       = array();
                //$product = $product_data;
                $attributes        = $product_data->getAttributes();
                //echo count($attributes);
                $custom_attr_count = 0;
                foreach ($attributes as $attribute) {
                    if ($attribute->is_user_defined && $attribute->is_visible) {
                        $attribute_value = $attribute->getFrontend()->getValue($product);
                        if ($attribute_value == null || $attribute_value == "") {
                            continue;
                        } else {
                            $custom_attr["data"][$custom_attr_count]["attr_code"]  = $attribute->getAttributeCode();
                            $custom_attr["data"][$custom_attr_count]["attr_label"] = $attribute->getStoreLabel($product);
                            $custom_attr["data"][$custom_attr_count]["attr_value"] = $attribute_value;
                            ++$custom_attr_count;
                        }
                    }
                }
                $custom_attr["total"]                             = $custom_attr_count;
                $configurable_array_selection["custom_attribute"] = $custom_attr;
                // Add code for custom attribute end:
                /*$cache->save(json_encode($configurable_array_selection), $cache_key, array(
                    "mofluid"
                ), $this->CACHE_EXPIRY);*/
                return $configurable_array_selection;
                //echo "<pre>"; print_r(json_encode($configurable_array_selection)); die;
            } else
                return "Product Id " . $productid . " is not a Configurable Product";
        }
        catch (Exception $ex) {
            return "Error";
        }
    }
    
    
    /*   * ********************************************************************************************************************************** */
    
    function get_configurable_products_image($productid, $currentcurrencycode)
    {
        $cache     = Mage::app()->getCache();
        $cache_key = "mofluid_configurable_products_productidimg" . $productid . "_currency" . $currentcurrencycode;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        
        //$basecurrencycode = Mage::app()->getStore()->getBaseCurrencyCode();
        try {
            $product_data = Mage::getModel('catalog/product')->load($productid);
            if ($product_data->getTypeID() == "configurable") {
                $productAttributeOptions      = $product_data->getTypeInstance(true)->getConfigurableAttributes($product_data);
                $conf                         = Mage::getModel('catalog/product_type_configurable')->setProduct($product_data);
                $simple_collection            = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
                $configurable_array_selection = array();
                $configurable_array           = array();
                $configurable_count           = 0;
                $relation_count               = 0;
                //load data for children 
                foreach ($simple_collection as $product) {
                    /*$a = Mage::getModel('catalog/product')->load($product->getId());
                    $taxClassId = $a->getData("tax_class_id");
                    $taxClasses = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
                    $taxRate = $taxClasses["value_" . $taxClassId];
                    $b = (($taxRate) / 100) * ($product->getPrice());
                    $product_for_custom_options = Mage::getModel('catalog/product')->load($product->getId());
                    $all_custom_option_array = array();
                    $attVal = $product_for_custom_options->getOptions();
                    $optStr = "";
                    $inc = 0;*/
                    
                    $configurable_count = 0;
                    foreach ($productAttributeOptions as $attribute) {
                        /* $productAttribute = $attribute->getProductAttribute();
                        $productAttributeId = $productAttribute->getId();
                        $attributeValue = $product->getData($productAttribute->getAttributeCode());
                        $attributeLabel = $product->getData($productAttribute->getValue());*/
                        //$configurable_array[$configurable_count]["productAttributeId"] = $productAttributeId;
                        //$configurable_array[$configurable_count]["selected_value"] = $attributeValue;
                        //$configurable_array[$configurable_count]["label"] = $attribute->getLabel();
                        //$configurable_array[$configurable_count]["is_required"] = $productAttribute->getIsRequired();
                        $configurable_array[$configurable_count]["id"]    = $product->getId();
                        //$configurable_array[$configurable_count]["sku"] = $product->getSku();
                        $configurable_array[$configurable_count]["name"]  = $product->getName();
                        $configurable_array[$configurable_count]["image"] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $product->getImage();
                        $defaultsplprice                                  = str_replace(",", "", number_format($product->getSpecialprice(), 2));
                        //$configurable_array[$configurable_count]["spclprice"] = strval($this->convert_currency($defaultsplprice,$basecurrencycode,$currentcurrencycode));
                        //$configurable_array[$configurable_count]["price"] = number_format($product->getPrice(),2);
                        //$configurable_array[$configurable_count]["currencysymbol"] = Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol();
                        //$configurable_array[$configurable_count]["created_date"] = $product->getCreatedAt();
                        //$configurable_array[$configurable_count]["is_in_stock"] = $product->getStockItem()->getIsInStock();
                        //$configurable_array[$configurable_count]["stock_quantity"] = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId())->getQty();
                        /* $configurable_array[$configurable_count]["type"] = $product->getTypeID();
                        $configurable_array[$configurable_count]["shipping"] = Mage::getStoreConfig('carriers/flatrate/price');
                        $configurable_array[$configurable_count]["data"] = $this->ws_get_configurable_option_attributes($attributeValue, $attribute->getLabel(), $productid, $currentcurrencycode);
                        $configurable_array[$configurable_count]["tax"] = number_format($b,2) ; */
                        /* try {
                        $configurable_curr_arr = (array) $configurable_array[$configurable_count]["data"];
                        if ($configurable_relation[$relation_count]) {
                        $configurable_relation[$relation_count] = $configurable_relation[$relation_count] . ', ' . str_replace(',', '', str_replace(' ', '', $configurable_curr_arr["label"]));
                        } else {
                        $configurable_relation[$relation_count] = str_replace(',', '', str_replace(' ', '', $configurable_curr_arr["label"]));
                        }
                        } catch (Exception $err) {
                        echo 'Error : ' . $err->getMessage();
                        }*/
                        $configurable_count++;
                    }
                    $relation_count++;
                    $configurable_array_selection[] = $configurable_array;
                }
                // $configurable_array_selection['relation'] = $configurable_relation;
                //load data for parent
                $mofluid_all_product_images = array();
                $mofluid_non_def_images     = array();
                $mofluid_product            = Mage::getModel('catalog/product')->load($productid);
                $mofluid_baseimage          = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_product->getImage();
                
                foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
                    $mofluid_imagecame = $mofluid_image->getUrl();
                    if ($mofluid_baseimage == $mofluid_imagecame) {
                        $mofluid_all_product_images[] = $mofluid_image->getUrl();
                    } else {
                        $mofluid_non_def_images[] = $mofluid_image->getUrl();
                    }
                }
                $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
                /* $configurable_product_parent = array();
                $parent_a = Mage::getModel('catalog/product')->load($product_data->getId());
                $parent_taxClassId = $parent_a->getData("tax_class_id");
                $parent_taxClasses = Mage::helper("core")->jsonDecode(Mage::helper("tax")->getAllRatesByProductClass());
                $parent_taxRate = $parent_taxClasses["value_" . $parent_taxClassId];
                $parent_b = (($parent_taxRate) / 100) * ($product_data->getPrice());*/
                
                
                $parent_all_custom_option_array = array();
                $parent_attVal                  = $product_data->getOptions();
                $parent_optStr                  = "";
                $parent_inc                     = 0;
                $has_custom_option              = 0;
                foreach ($parent_attVal as $parent_optionKey => $parent_optionVal) {
                    /*$parent_all_custom_option_array[$parent_inc]['custom_option_name'] = $parent_optionVal->getTitle();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_id'] = $parent_optionVal->getId();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_is_required'] = $parent_optionVal->getIsRequired();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_type'] = $parent_optionVal->getType();
                    $parent_all_custom_option_array[$parent_inc]['sort_order'] = $parent_optionVal->getSortOrder();
                    $parent_all_custom_option_array[$parent_inc]['all'] = $parent_optionVal->getData();
                    
                    if ($parent_all_custom_option_array[$parent_inc]['all']['default_price_type'] == "percent") {
                    $parent_all_custom_option_array[$parent_inc]['all']['price'] = number_format((($product->getPrice() * $parent_all_custom_option_array[$parent_inc]['all']['price']) / 100), 2);
                    } else {
                    $parent_all_custom_option_array[$parent_inc]['all']['price'] = number_format($parent_all_custom_option_array[$inc]['all']['price'], 2);
                    }*/
                    
                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'];
                    $parent_inner_inc  = 0;
                    $has_custom_option = 1;
                    /*foreach ($parent_optionVal->getValues() as $parent_valuesKey => $parent_valuesVal) {
                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['id'] = $parent_valuesVal->getId();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['title'] = $parent_valuesVal->getTitle();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['price'] = number_format($parent_valuesVal->getPrice(), 0);
                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['price_type'] = $parent_valuesVal->getPriceType();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['sku'] = $parent_valuesVal->getSku();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['sort_order'] = $parent_valuesVal->getSortOrder();
                    
                    $parent_inner_inc++;
                    }*/
                    $parent_inc++;
                }
                $configurable_product_parent["id"]    = $product_data->getId();
                //$configurable_product_parent["sku"] = $product_data->getSku();
                $configurable_product_parent["name"]  = $product_data->getName();
                /* $configurable_product_parent["category"] = $product_data->getCategoryIds();
                $configurable_product_parent["discount"] = number_format($product_data->getFinalPrice(),2);
                $configurable_product_parent["shipping"] = Mage::getStoreConfig('carriers/flatrate/price'); */
                $configurable_product_parent["image"] = $mofluid_all_product_images; // Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'media/catalog/product'.$product_data->getImage();
                //$defaultprice = str_replace(",","", number_format($product_data->getPrice(),2)); 
                //$configurable_product_parent["price"] = strval($this->convert_currency($defaultprice,$basecurrencycode,$currentcurrencycode));
                
                $defaultprice  = str_replace(",", "", ($product_data->getFinalPrice()));
                //$configurable_product_parent["price"] = strval(round($this->convert_currency($defaultprice,$basecurrencycode,$currentcurrencycode),2));						
                $defaultsprice = str_replace(",", "", ($product_data->getSpecialprice()));
                //$configurable_product_parent["sprice"] = strval(round($this->convert_currency($defaultsprice,$basecurrencycode,$currentcurrencycode),2));
                //$defaultsprice =  str_replace(",","",number_format($product_data->getSpecialprice(),2)); 
                //$configurable_product_parent["sprice"] = strval($this->convert_currency($defaultsprice,$basecurrencycode,$currentcurrencycode));
                /* $configurable_product_parent["currencysymbol"] = Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol();
                $configurable_product_parent["url"] = $product_data->getProductUrl();
                $configurable_product_parent["description"] = $product_data->getDescription();
                $configurable_product_parent["shortdes"] = $product_data->getShortDescription();
                $configurable_product_parent["type"] = $product_data->getTypeID();
                $configurable_product_parent["created_date"] = $product_data->getCreatedAt();
                $configurable_product_parent["is_in_stock"] = $product_data->getStockItem()->getIsInStock();
                $configurable_product_parent["quantity"] = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product_data->getId())->getQty();
                $configurable_product_parent["visibility"] = $product_data->isVisibleInSiteVisibility();
                $configurable_product_parent["weight"] = $product_data->getWeight();
                $configurable_product_parent["status"] = $product_data->getStatus();
                $configurable_product_parent["variation"] = $product_data->getColor();
                $configurable_product_parent["custom_option"] = $parent_all_custom_option_array;
                $configurable_product_parent["tax"] =  number_format($parent_b,2) ;
                $configurable_product_parent["has_custom_option"] = $has_custom_option; */
                
                $configurable_array_selection["parent"] = $configurable_product_parent;
                $configurable_array_selection["size"]   = sizeof($configurable_array_selection);
                
                // Add code for custom attribute start:
                // $custom_attr = array();
                //$product = $product_data;
                //$attributes = $product_data->getAttributes();
                //echo count($attributes);
                // $custom_attr_count = 0;
                /* foreach ($attributes as $attribute) {
                if ($attribute->is_user_defined && $attribute->is_visible) {
                $attribute_value = $attribute->getFrontend()->getValue($product);
                if ($attribute_value == null || $attribute_value == "") {
                continue;
                } else {
                $custom_attr["data"][$custom_attr_count]["attr_code"] = $attribute->getAttributeCode();
                $custom_attr["data"][$custom_attr_count]["attr_label"] = $attribute->getStoreLabel($product);
                $custom_attr["data"][$custom_attr_count]["attr_value"] = $attribute_value;
                ++$custom_attr_count;
                }
                }
                }*/
                $custom_attr["total"] = $custom_attr_count;
                //$configurable_array_selection["custom_attribute"] = $custom_attr;  
                // Add code for custom attribute end:
                $cache->save(json_encode($configurable_array_selection), $cache_key, array(
                    "mofluid"
                ), $this->CACHE_EXPIRY);
                return $configurable_array_selection;
                //echo "<pre>"; print_r(json_encode($configurable_array_selection)); die;
            } else
                return "Product Id " . $productid . " is not a Configurable Product";
        }
        catch (Exception $ex) {
            return "Error";
        }
    }
    
    /*   * ********************************************************************************************************************************** */
    
    function ws_get_configurable_option_attributes($selectedValue, $label, $productid, $currentcurrencycode,$store)
    {
        /*$cache     = Mage::app()->getCache();
        $cache_key = "mofluid_configurable_options_productid" . $productid . "_currency" . $currentcurrencycode . "_selectedValue" . $selectedValue . "_label" . $label;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));*/
        
        
        //get base currency from magento
        Mage::app()->setCurrentStore($store);
       
        $basecurrencycode = Mage::app()->getStore()->getBaseCurrencyCode();
        
        $product_data            = Mage::getModel('catalog/product')->load($productid);
        $productAttributeOptions = $product_data->getTypeInstance(true)->getConfigurableAttributesAsArray($product_data);
        $attributeOptions        = array();
        $count                   = 0;
        foreach ($productAttributeOptions as $productAttribute) {
            $count = 0;
            foreach ($productAttribute['values'] as $attribute) {
                $attributeOptions[$productAttribute['label']][$attribute['value_index']]["product_super_attribute_id"] = $attribute['product_super_attribute_id'];
                $attributeOptions[$productAttribute['label']][$attribute['value_index']]["value_index"]                = $attribute['value_index'];
                $attributeOptions[$productAttribute['label']][$attribute['value_index']]["label"]                      = $attribute['label'];
                $attributeOptions[$productAttribute['label']][$attribute['value_index']]["store_label"]                = $attribute['store_label'];
                $attributeOptions[$productAttribute['label']][$attribute['value_index']]["is_percent"]                 = $attribute['is_percent'];
                
                //$defaultprice = str_replace(",","", number_format($attribute['pricing_value'],2)); 
                //$attributeOptions[$productAttribute['label']][$attribute['value_index']]["pricing_value"] = str_replace(",","", strval($this->convert_currency($defaultprice,$basecurrencycode,$currentcurrencycode)));	
                
                $defaultprice                                                                             = str_replace(",", "", ($attribute['pricing_value']));
                $attributeOptions[$productAttribute['label']][$attribute['value_index']]["pricing_value"] = str_replace(",", "", strval(round($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2)));
                
                if ($attribute['is_percent'] == 1) {
                    /*if ($product_data->getSpecialprice() > 0 && $product_data->getSpecialprice() < $product_data->getPrice()) {
                        $defaultproductprice                                                                      = str_replace(",", "", ($product_data->getSpecialprice()));
                        $productprice                                                                             = strval(round($this->convert_currency($defaultproductprice, $basecurrencycode, $currentcurrencycode), 2));
                        $attributeOptions[$productAttribute['label']][$attribute['value_index']]["pricing_value"] = str_replace(",", "", round(((floatval($productprice) * floatval($attribute['pricing_value'])) / 100), 2));
                    } else {
                        $defaultproductprice                                                                      = str_replace(",", "", ($product_data->getPrice()));
                        $productprice                                                                             = strval(round($this->convert_currency($defaultproductprice, $basecurrencycode, $currentcurrencycode), 2));
                        $attributeOptions[$productAttribute['label']][$attribute['value_index']]["pricing_value"] = str_replace(",", "", round(((floatval($productprice) * floatval($attribute['pricing_value'])) / 100), 2));
                    }*/
                        $defaultproductprice                                                                      = str_replace(",", "", ($product_data->getFinalPrice()));
                        $productprice                                                                             = strval(round($this->convert_currency($defaultproductprice, $basecurrencycode, $currentcurrencycode), 2));
                        $attributeOptions[$productAttribute['label']][$attribute['value_index']]["pricing_value"] = str_replace(",", "", round(((floatval($productprice) * floatval($attribute['pricing_value'])) / 100), 2));
                    
                }
                
                
                $attributeOptions[$productAttribute['label']][$attribute['value_index']]["use_default_value"] = $attribute['use_default_value'];
                $attributeOptions[$productAttribute['label']][$attribute['value_index']]["value_id"]          = $attribute['value_id'];
                $attributeOptions[$productAttribute['label']][$attribute['value_index']]["frontend_label"]    = $productAttribute['frontend_label'];
                $attributeOptions[$productAttribute['label']][$attribute['value_index']]["attribute_code"]    = $productAttribute['attribute_code'];
                $attributeOptions[$productAttribute['label']][$attribute['value_index']]["attribute_id"]      = $productAttribute['attribute_id'];
                $count++;
            }
        }
        /*$cache->save(json_encode($attributeOptions[$label][$selectedValue]), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);*/
        return ($attributeOptions[$label][$selectedValue]);
    }
    
    function ws_mofluid_reorder($store, $service, $jproduct, $orderId, $currentcurrencycode)
    {
        $productids = json_decode($jproduct);
        $countres   = 0;
        $res        = array();
        $order      = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        #get all items
        $items      = $order->getAllItems();
        $itemcount  = count($items);
        $data       = array();
        $i          = 0;
        #loop for all order items
        foreach ($items as $itemId => $product) {
            $current_product_id         = $product->getProductId();
            $current_product_index      = $itemId;
            $has_custom_option          = 0;
            $custom_attr                = array();
            $current_product            = Mage::getModel('catalog/product')->load($current_product_id);
            $mofluid_all_product_images = array();
            $mofluid_non_def_images     = array();
            $mofluid_baseimage          = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $current_product->getImage();
            foreach ($current_product->getMediaGalleryImages() as $mofluid_image) {
                $mofluid_imagecame = $mofluid_image->getUrl();
                if ($mofluid_baseimage == $mofluid_imagecame) {
                    $mofluid_all_product_images[] = $mofluid_image->getUrl();
                } else {
                    $mofluid_non_def_images[] = $mofluid_image->getUrl();
                }
            }
            $mofluid_all_product_images              = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
            //get base currency from magento
            $basecurrencycode                        = Mage::app()->getStore()->getBaseCurrencyCode();
            $res[$countres]["id"]                    = $current_product->getId();
            $res[$countres]["sku"]                   = $current_product->getSku();
            $res[$countres]["name"]                  = $current_product->getName();
            $res[$countres]["category"]              = $current_product->getCategoryIds(); //'category';
            $res[$countres]["image"]                 = $mofluid_all_product_images[0]; // Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'/media/catalog/product'.$product->getImage();
            $res[$countres]["url"]                   = $current_product->getProductUrl();
            $res[$countres]["description"]["full"]   = base64_encode($current_product->getDescription());
            $res[$countres]["description"]["short"]  = base64_encode($current_product->getShortDescription());
            $res[$countres]["quantity"]["available"] = Mage::getModel('cataloginventory/stock_item')->loadByProduct($current_product->getId())->getQty();
            $res[$countres]["quantity"]["order"]     = $product->getQtyOrdered();
            // $current_product->getQty(); //Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId())->getQty();//$product->getQty(); 
            $res[$countres]["visibility"]            = $current_product->isVisibleInSiteVisibility(); //getVisibility(); 
            $res[$countres]["type"]                  = $current_product->getTypeID();
            $res[$countres]["weight"]                = $current_product->getWeight();
            $res[$countres]["status"]                = $current_product->getStatus();
            //convert price from base currency to current currency
            $res[$countres]["currencysymbol"]        = Mage::app()->getLocale()->currency($currentcurrencycode)->getSymbol();
            $defaultprice                            = str_replace(",", "", ($product->getPrice()));
            $res[$countres]["price"]                 = strval(round($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2));
            $discountprice                           = str_replace(",", "", ($product->getFinalPrice()));
            $res[$countres]["discount"]              = strval(round($this->convert_currency($discountprice, $basecurrencycode, $currentcurrencycode), 2));
            $defaultshipping                         = Mage::getStoreConfig('carriers/flatrate/price');
            $res[$countres]["shipping"]              = strval(round($this->convert_currency($defaultshipping, $basecurrencycode, $currentcurrencycode), 2));
            $defaultsprice                           = str_replace(",", "", ($product->getSpecialprice()));
            // Get the Special Price
            $specialprice                            = $current_product->getSpecialPrice();
            // Get the Special Price FROM date
            $specialPriceFromDate                    = $current_product->getSpecialFromDate();
            // Get the Special Price TO date
            $specialPriceToDate                      = $current_product->getSpecialToDate();
            // Get Current date
            $today                                   = time();
            if ($specialprice) {
                if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
                    $specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                } else {
                    $specialprice = 0;
                }
            } else {
                $specialprice = 0;
            }
            $current_product_options  = array();
            $res[$countres]["sprice"] = $specialprice;
            $has_custom_option        = 0;
            foreach ($product->getProductOptions() as $opt) {
                $has_custom_option       = 1;
                $current_product_options = $opt['options'];
                if (!$current_product_options) {
                    foreach ($opt as $opt_key => $opt_val) {
                        $current_product_options[$opt_val['option_id']] = $opt_val['option_value'];
                    }
                }
                break;
            } //foreach  
            $res[$countres]["has_custom_option"] = $has_custom_option;
            if ($has_custom_option == 1) {
                $res[$countres]["custom_option"] = $current_product_options;
            }
            $res[$countres]["custom_attribute"] = $custom_attr;
            $countres++;
        }
        //echo "<br / ><pre>"; print_r($res); die;      
        return ($res);
    }
    
    public function mofluidUpdateProfile($store, $service, $customerId, $JbillAdd, $JshippAdd, $profile, $billshipflag)
    {
        $billAdd  = json_decode($JbillAdd);
        $shippAdd = json_decode($JshippAdd);
        $profile  = json_decode($profile);
        
        $result                 = array();
        $result['billaddress']  = 0;
        $result['shippaddress'] = 0;
        $result['userprofile']  = 0;
        
        /* Update User Profile Data */
        
        $customer = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($profile->email);
        
        //check exists email address of users  
        if ($customer->getId() && $customer->getId() != $customerId) {
            return $result;
        } else {
            if ($billshipflag == "billingaddress") {
                $_bill_address = array(
                    'firstname' => $billAdd->billfname,
                    'lastname' => $billAdd->billlname,
                    'street' => array(
                        '0' => $billAdd->billstreet1,
                        '1' => $billAdd->billstreet2
                    ),
                    'city' => $billAdd->billcity,
                    'region_id' => '',
                    'region' => $billAdd->billstate,
                    'postcode' => $billAdd->billpostcode,
                    'country_id' => $billAdd->billcountry,
                    'telephone' => $billAdd->billphone
                );
                $billAddress   = Mage::getModel('customer/address');
                if ($defaultBillingId = $customer->getDefaultBilling()) {
                    $billAddress->load($defaultBillingId);
                    $billAddress->addData($_bill_address);
                } else {
                    $billAddress->setData($_bill_address)->setCustomerId($customerId)->setIsDefaultBilling('1')->setSaveInAddressBook('1');
                }
                try {
                    if ($billAddress->save())
                        $result['billaddress'] = 1;
                }
                catch (Exception $ex) {
                    Zend_Debug::dump($ex->getMessage());
                }
            } else {
                $_shipp_address = array(
                    'firstname' => $shippAdd->shippfname,
                    'lastname' => $shippAdd->shipplname,
                    'street' => array(
                        '0' => $shippAdd->shippstreet1,
                        '1' => $shippAdd->shippstreet2
                    ),
                    'city' => $shippAdd->shippcity,
                    'region_id' => '',
                    'region' => $shippAdd->shippstate,
                    'postcode' => $shippAdd->shipppostcode,
                    'country_id' => $shippAdd->shippcountry,
                    'telephone' => $shippAdd->shippphone
                );
                $shippAddress   = Mage::getModel('customer/address');
                if ($defaultShippingId = $customer->getDefaultShipping()) {
                    $shippAddress->load($defaultShippingId);
                    $shippAddress->addData($_shipp_address);
                } else {
                    $shippAddress->setData($_shipp_address)->setCustomerId($customerId)->setIsDefaultShipping('1')->setSaveInAddressBook('1');
                }
                try {
                    if ($shippAddress->save())
                        $result['shippaddress'] = 1;
                }
                catch (Exception $ex) {
                    Zend_Debug::dump($ex->getMessage());
                }
            }
            
            
            
            return $result;
        }
    }
    
}
