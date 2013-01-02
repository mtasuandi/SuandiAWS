<?php

    /**
     * Class to access Amazons Product Advertising API
     * @author Sameer Borate
     * @link http://www.codediesel.com
     * @version 1.0
     * All requests are not implemented here. You can easily
     * implement the others from the ones given below.
     */
    
    
    /*
    Permission is hereby granted, free of charge, to any person obtaining a
    copy of this software and associated documentation files (the "Software"),
    to deal in the Software without restriction, including without limitation
    the rights to use, copy, modify, merge, publish, distribute, sublicense,
    and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
    THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
    DEALINGS IN THE SOFTWARE.
    */
    
    require_once 'aws_signed_request.php';
		
    class AmazonProductAPI
    {
	
		private $options;
		
		private $publicKey;
		private $secretKey;
		private $associateKey;
		
		function __construct()
		{
			$this->options 			= get_option('suandiaws_aws_settings');
			
			$this->publicKey 		= $this->options['suandiaws_aws_settings_access_key'];
			$this->secretKey 		= $this->options['suandiaws_aws_settings_secret_key'];
			$this->associateKey 	= $this->options['suandiaws_aws_settings_associate_key'];
		}
        
        /*
            Only three categories are listed here. 
            More categories can be found here:
            http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/APPNDX_SearchIndexValues.html
        */
        public function getAssTag()
		{
			return $this->associateKey;
		}
		
		const MUSIC 				= "Music";
        const DVD   				= "DVD";
        const GAMES 				= "VideoGames";
		const BOOKS 				= "Books";
		const APPAREL 				= "Apparel";
		const AUTOMOTIVE 			= "Automotive";
		const ELECTRONICS		 	= "Electronics";
		const GOURMETFOOD 			= "GourmetFood";
		const KITCHEN 				= "Kitchen";
		const PCHARDWARE 			= "PCHardware";
		const PETSUPPLIES 			= "PetSupplies";
		const SOFTWARE 				= "Software";
		const SOFTWAREVIDEOGAMES 	= "SoftwareVideogames";
		const SPORTINGGOODS 		= "SportingGoods";
		const TOOLS 				= "Tools";
		const TOYS 					= "Toys";
		const VHS 					= "VHS";
                
        private function verifyXmlResponse($response)
        {
            if ($response === False)
            {
                throw new Exception("Could not connect to Amazon");
            }
            else
            {
                if (isset($response))
                {
                    return ($response);
                }
                else
                {
                    throw new Exception("Invalid, Please check your Access Key, Security Key and Associate Key.");
                }
            }
        }
        
        
        /**
         * Query Amazon with the issued parameters
         * 
         * @param array $parameters parameters to query around
         * @return simpleXmlObject xml query response
         */

        private function queryAmazon($parameters)
        {
            return aws_signed_request("com", $parameters, $this->publicKey, $this->secretKey, $this->associateKey);
        }
        
        
        public function getItemByKeyword($keyword, $product_type)
        {
            $parameters = array("Operation"  	 	=> "ItemSearch",
                                "Keywords"    		=> $keyword,
                                "SearchIndex" 		=> $product_type,
								"ResponseGroup" 	=> "Large");
                                
            $xml_response = $this->queryAmazon($parameters);
            
            return $this->verifyXmlResponse($xml_response);
        }
		
		
    }

?>
