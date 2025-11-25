<?php
/**
 * @package castawaystravel
 * Edit My Account File
 */

 namespace Inc\Base;

 use \Inc\Base\BaseController;

 class MyAccountController extends BaseController
 {
    public $myAccount;
	public $query;
	public  $featuredProductsCS;

    public function register(){
       	add_shortcode( 'myAccountShortCode', array($this, 'myAccountDashboard') );
		add_action('wp_head', array($this, 'addStylesMyAccount'));
		add_shortcode( 'myFeaturedImageShortCode', array($this, 'myFeaturedProductImage') );
		add_shortcode( 'my_donwloads', array($this, 'MyDownloads'));
		add_shortcode( 'my_orders', array($this, 'MyOrders'));
		
		
    }

    public function myAccountDashboard(){

        $currentUserAvatar =  get_avatar( get_current_user_id(), 57 );
        $logoAvatar = '<img src="'.$this->plugin_url.'assets/images/my-account.webp">';
        $myAccount = [];
        $myAccount = '<div class="row row-my_account">';
            // My account
            $myAccount .= '<a class="my_account_link" href="'.home_url().'/my-account/edit-account/">';
                $myAccount .= '<div class="col-content">';
                    $myAccount .= '<div class=" col col-my_account_avatar">'.($currentUserAvatar) ? $currentUserAvatar :  $logoAvatar.'</div>';
                    $myAccount .= '<div class=" col col-account-text col-my_account_avatar">';
                        $myAccount .=  '<p>My Account <span>Edit your name or change <br>your password.</span></p>';
                    $myAccount .=  '</div>';
                    $myAccount .= '<div class=" col col-my_account_my_billing_address"><img src="'.$this->plugin_url.'assets/images/arrow.webp"></div>';
                $myAccount .=  '</div>';
            $myAccount .= '</a>';
            
            // Billing Address
            $myAccount .= '<a class="my_account_link" href="'.home_url().'/my-account/edit-address/billing/">';
                $myAccount .= '<div class="col-content">';
                    $myAccount .= '<div class=" col col-my_account_my_billing_address"><img src="'.$this->plugin_url.'assets/images/billing-address.webp"></div>';
                    $myAccount .= '<div class=" col col-account-text col-my_account_my_billing_address">';
                        $myAccount .=  '<p>Billing Address <span> Setup your billing <br> address.</span></p>';
                    $myAccount .=  '</div>';
                    $myAccount .= '<div class=" col col-my_account_my_billing_address"><img src="'.$this->plugin_url.'assets/images/arrow.webp"></div>';
                $myAccount .=  '</div>';
            $myAccount .= '</a>';
        $myAccount .=  '</div>';


        return  $myAccount;
    }
	
	 public function addStylesMyAccount(){
		 if(is_page('my-account')){ ?>
			<style>
				#Wrapper{
					background-color :transparent;
					background-image: url(<?php echo $this->plugin_url;?>assets/images/header-illustraions-2-svg.webp);
				   	background-repeat : no-repeat;
				   	background-size : 1440px;
				   	background-position-x : center;
				   	background-position-y : top;
				}
				#Top_bar.is-sticky {
					background-color: #ffffff !important;
				}
				
				#Top_bar{
					background-color: transparent !important;
				}
				#Header_wrapper{
					background-color: transparent !important;
				}
				#menu{
					background-color: transparent !important;
				}
				body{
					background: #e0ecfa !important;
				}
				.woocommerce {
					display: flex;
				}
				
			</style>
			<?php 
		 }
    
	 }
	 
	//Dashboard section

	 public function myFeaturedProductImage(){
		 $queries = [];
		 $base_path = $this->plugin_url.'wp-content/uploads/';
		 
		 $args = [
			 'post_type' => 'product',
			 'posts_per_page' => 8,
			 'orderby'=> 'post_date', 
    		'order' => 'DESC',
			 'tax_query' => array(
				 'terms'    => 'featured'
			 	)
		 ];
		 
		$queries = wc_get_products( $args );
		 
		$featuredProductsCS = '<ul class="cs_featured_products">';
			 foreach ($queries as $query){
				 $id = $query->get_id();
					
				 $permalink = get_permalink($id);
				 $name = $query->name;
				 $image = get_the_post_thumbnail_url( $id );
				 
				$featuredProductsCS .= '<li class="cs_featured_products_li">';
				  	$featuredProductsCS .=  '<a href="'.$permalink.'">';
					  	$featuredProductsCS .= '<div class="cs_featured_products_image-container">';
					  		$featuredProductsCS .= '<img src="">';
						$featuredProductsCS .= '</div>';
						$featuredProductsCS .= '<div class="cs_featured_products_text">'.$name.'</div>';
					$featuredProductsCS .= '</a>';
				$featuredProductsCS .= '</li>'; 
			 }
		 $featuredProductsCS .= '</ul>';
		 
		 
		 return $featuredProductsCS;
	 }

	// Downloads
	public function MyDownloads(){
		/*
		print_r($downloads);
		$args = [
			'post_type' => 'product',
			'posts_per_page' => 8,
			'orderby'=> 'post_date', 
		   'order' => 'DESC',
		   'downloadable' => true
		];
		$queries = wc_get_products($args);
		$number = count($queries);

		if($number < 1){
			$downloads = '<div class="downloads-container">';
				$downloads .= '<div class="downloads-image">';
					$downloads .= '<img src="'.$this->plugin_url.'assets/images/Downloads.svg">';
				$downloads .= '</div>';
				$downloads .= '<div class="downloads-text">';
					$downloads .= 'No downloads available yet.';

					$downloads .= '<p class="return-to-shop">';
						$downloads .= '<a class="button wc-backward" href="'.home_url().'shop/">';
							$downloads .= 'Return to Shop';
						$downloads .= '</a>';
					$downloads .= '</p>';
				$downloads .= '</div>';
			$downloads .= '</div>';
		}else{
			$downloads = '<ul class="cs_standart_products cs_featured_products">';
				foreach ($queries as $query){

					$id = $query->get_id();
					$permalink = get_permalink($id);
					$name = $query->name;
					$image = get_the_post_thumbnail_url( $id );
					
					$downloads .= '<li class="cs_standart_products_li cs_featured_products_li">';
						$downloads .=  '<a href="'.$permalink.'">';
							$downloads .= '<div class="cs_featured_products_image-container cs_standart_products_image-container ">';
								$downloads .= '<span class="item-icon">';
									$downloads .= '<i class="fa fa-download"></i>';
								$downloads .= '</span>';
							$downloads .= '</div>';
							$downloads .= '<div class="cs_featured_products_text">'.$name.'</div>';
						$downloads .= '</a>';
					$downloads .= '</li>'; 

				}
			$downloads .= '</ul>';

			$downloads .= '<div class="button-container">';
				$downloads .= '<p class="return-to-shop">';
					$downloads .= '<a class="button wc-backward" href="'.home_url().'shop/">';
						$downloads .= 'Return to Shop';
					$downloads .= '</a>';
				$downloads .= '</p>';
			$downloads .= '</div>';
		}
		 
		 return $downloads;
		 */
		$downloads = WC()->customer->get_downloadable_products();

		if ( $downloads ) {
			do_action( 'woocommerce_before_available_downloads' );
			$downloads.='<ul class="woocommerce-Downloads digital-downloads">';
				foreach ( $downloads as $download ){
					$downloads.='<li>';
						do_action( 'woocommerce_available_download_start', $download );
						if ( is_numeric( $download['downloads_remaining'] ) ) {
							$downloads.= apply_filters( 'woocommerce_available_download_count', '<span class="woocommerce-Count count">' . sprintf( _n( '%s download remaining', '%s downloads remaining', $download['downloads_remaining'], 'woocommerce' ), $download['downloads_remaining'] ) . '</span> ', $download ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						$downloads.= apply_filters( 'woocommerce_available_download_link', '<a href="' . esc_url( $download['download_url'] ) . '">' . $download['download_name'] . '</a>', $download ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						do_action( 'woocommerce_available_download_end', $download );
					$downloads.='</li>';
				}
			$downloads.='</ul>';

			do_action( 'woocommerce_after_available_downloads' ); 
		}else{
			$downloads = '<div class="downloads-container">';
				$downloads .= '<div class="downloads-image">';
					$downloads .= '<img src="'.$this->plugin_url.'assets/images/Downloads.svg">';
				$downloads .= '</div>';
				$downloads .= '<div class="downloads-text">';
					$downloads .= 'No downloads available yet.';

					$downloads .= '<p class="return-to-shop">';
						$downloads .= '<a class="button wc-backward" href="'.home_url().'shop/">';
							$downloads .= 'Return to Shop';
						$downloads .= '</a>';
					$downloads .= '</p>';
				$downloads .= '</div>';
			$downloads .= '</div>';
		}
		return $downloads;
	}
	
	//Orders
	public function MyOrders(){

		$args = array(
			'status' => array('wc-processing', 'wc-on-hold'),
		);
		$queries = wc_get_orders( $args );
		$number = count($queries);
			$orders = '<ul class="cs_standart_products cs_featured_products">';
				foreach ($queries as $query){
					$userId = get_current_user_id();
					$usercustomer = $query->get_user_id();
					$id = $query->get_id();
					$permalink = $query->get_checkout_order_received_url($id);
					$name = $query->name;
					
					if($userId == $usercustomer ){
						$orders .= '<li class="cs_standart_products_li cs_featured_products_li">';
							$orders .=  '<a href="'.$permalink.'">';
								$orders .= '<div class="cs_featured_products_image-container cs_standart_products_image-container ">';
									$orders .= '<span class="item-icon">';
										$orders .= '<i class="fa fa-shopping-bag"></i>';
									$orders .= '</span>';
								$orders .= '</div>';
								$orders .= '<div class="cs_featured_products_text">Order #'.$id.'</div>';
							$orders .= '</a>';
						$orders .= '</li>'; 
					}else{
						$orders = '<li">';
							$orders .= '<div class="downloads-container">';
								$orders .= '<div class="downloads-image">';
									$orders .= '<img src="'.$this->plugin_url.'assets/images/order.svg">';
								$orders .= '</div>';
								$orders .= '<div class="downloads-text">';
									$orders .= 'No orders available yet.';
								$orders .= '</div>';
							$orders .= '</div>';
						$orders .= '</li>';
					}	
			$orders .= '</ul>';

			$orders .= '<div class="button-container">';
				$orders .= '<p class="return-to-shop">';
					$orders .= '<a class="button wc-backward" href="'.home_url().'shop/">';
						$orders .= 'Return to Shop';
					$orders .= '</a>';
				$orders .= '</p>';
			$orders .= '</div>';
		}
		 
		 return $orders;

	}

	 
 }