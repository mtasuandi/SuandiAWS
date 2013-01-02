<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
function suandiaws_aws_generate_text()
{

}

function suandiaws_aws_generate_keyword()
{
	$options = get_option('suandiaws_aws_generate');
	echo "<input id='suandiaws_aws_generate_keyword' name='suandiaws_aws_generate[suandiaws_aws_generate_keyword]' value='{$options['suandiaws_aws_generate_keyword']}' size='50'/>";
}

function suandiaws_aws_generate_category()
{
	$options = get_option('suandiaws_aws_generate');
	
	echo "
	<select id='suandiaws_aws_generate[suandiaws_aws_generate_category]' name='suandiaws_aws_generate[suandiaws_aws_generate_category]'>
      <option value='Blended'>ALL</option>
      <option value='Books'>Books</option>
      <option value='DVD'>DVD</option>
      <option value='Apparel'>Apparel</option>
      <option value='Automotive'>Automotive</option>
      <option value='Electronics'>Electronics</option>
      <option value='GourmetFood'>Gourmet Food</option>
      <option value='Kitchen'>Kitchen</option>
      <option value='Music'>Music</option>
      <option value='PCHardware'>PC Hardware</option>
      <option value='PetSupplies'>Pet Supplies</option>
      <option value='Software'>Software</option>
      <option value='SoftwareVideoGames'>Software Video Games</option>
      <option value='SportingGoods'>Sporting Goods</option>
      <option value='Tools'>Tools</option>
      <option value='Toys'>Toys</option>
      <option value='VHS'>VHS</option>
      <option value='VideoGames'>Video Games</option>
    </select>
	";

}

function suandiaws_aws_generate_poststatus(){
	$options = get_option('suandiaws_aws_generate');
		echo "
	<select id='suandiaws_aws_generate[suandiaws_aws_generate_poststatus]' name='suandiaws_aws_generate[suandiaws_aws_generate_poststatus]' >
      <option value='publish'>Publish</option>
      <option value='draft'>Draft</option>
	</select>
	";
}

function suandiaws_aws_generate_totalpost(){
	
	$options = get_option('suandiaws_aws_generate');
	echo "
	<select id='suandiaws_aws_generate[suandiaws_aws_generate_totalpost]' name='suandiaws_aws_generate[suandiaws_aws_generate_totalpost]' >
      <option value='9'>1</option>
      <option value='8'>2</option>
	  <option value='7'>3</option>
      <option value='6'>4</option>
      <option value='5'>5</option>
      <option value='4'>6</option>
      <option value='3'>7</option>
      <option value='2'>8</option>
      <option value='1'>9</option>
	  <option value='0'>10</option>
    </select>
	";
}

function suandiaws_aws_generate_postcategory(){
	$options = get_option('suandiaws_aws_generate');
	
	$args = array('hide_empty' => 0);
	$categories = get_categories($args);
	
	echo "<select id='suandiaws_aws_generate[suandiaws_aws_generate_postcategory]' name='suandiaws_aws_generate[suandiaws_aws_generate_postcategory]'>";
	foreach ($categories as $cat){
	$val = $cat->cat_ID;
	$ct	 = $cat->cat_name;
	echo "<option value='$val'>"."$ct"."</option>";
	}
	echo "</select>";
	
	submit_button('Generate Post');
}

function suandiaws_aws_generate_validate($input)
{
	$options = get_option('suandiaws_aws_generate');
		
	$options['suandiaws_aws_generate_keyword']			= ucwords(trim($input['suandiaws_aws_generate_keyword']));
	$options['suandiaws_aws_generate_category']			= $input['suandiaws_aws_generate_category'];
	$options['suandiaws_aws_generate_poststatus']		= $input['suandiaws_aws_generate_poststatus'];
	$options['suandiaws_aws_generate_totalpost'] 		= $input['suandiaws_aws_generate_totalpost'];
	$options['suandiaws_aws_generate_postcategory']		= $input['suandiaws_aws_generate_postcategory'];
	
	$totalpost		= $options['suandiaws_aws_generate_totalpost'];
	$poststatus		= $options['suandiaws_aws_generate_poststatus'];
	$postcategory 	= $options['suandiaws_aws_generate_postcategory'];
	
	include_once 'amazon_api_class.php';
	
	$obj = new AmazonProductAPI();
	$assTag = $obj->getAssTag();
	
		try{
			$result = $obj->getItemByKeyword($options['suandiaws_aws_generate_keyword'],$options['suandiaws_aws_generate_category']);
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
					$a = $result->Items->Item;
					$b = count($a)-$totalpost;
					$i = 0;
					
			while($i < $b):
			$asin 			= $result->Items->Item[$i]->ASIN;
			$detailURL		= $result->Items->Item[$i]->DetailPageURL;
			$getTitle 		= $result->Items->Item[$i]->ItemAttributes->Title;
			$imageURL		= $result->Items->Item[$i]->ImageSets->ImageSet->MediumImage->URL;
			$image			= "<img style='float: left; margin: 0 20px 10px 0;'src=$imageURL></img>";
			$price			= $result->Items->Item[$i]->Offers->Offer->OfferListing->Price->FormattedPrice;
			$er				= $result->Items->Item[$i]->EditorialReviews->EditorialReview->Content;
		
			if($er != null){
				$review 	= $result->Items->Item[$i]->EditorialReviews->EditorialReview->Content;
			}else{
				$review 	= 'No review available<p/>';			
			}
			
			$newPost = array('post_status' 	=> $poststatus, 
					'post_title' 	=> $getTitle, 
					'post_type' 	=> 'post',
					'post_category'	=> array($postcategory),
					'comment_status' => 'open',
					'post_content'	=> $image.'<strong>Price : '.$price.'</strong><p/>'.'<h4>Product Description</h4>'.$review);
					
			wp_insert_post($newPost);
			
			$i++;
			endwhile;
			/*
			if(!empty($post_ID))
			{
				echo '	
					<div class="updated">
					<p>Posts has been fetched!.</p>
					</div>
				 ';
			}
			else
			{
				echo '	
					<div class="error">
					<p>Something went wrong or we can not find any posts.</p>
					</div>
				 ';
			}
			*/
	return null;
}

?>