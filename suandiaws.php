<?php
/*
Plugin Name: Suandi AWS
Plugin URI: http://mtasuandi.wordpress.com
Description: Wordpress Plugin for grabbing content from Amazon using Amazon Product Advertising API. Non Commercial. Tidak diperkenankan untuk diperjualbelikan dengan alasan apapun.
Version: 1.0
Author: MTA Suandi
Author URI:  http://mtasuandi.wordpress.com
License: Copyright 2013, All rights reserved
*/

class SuandiAWS
{
	private $options;
	private $aws_access_key;
	private $aws_secret_key;
	private $aws_associate_key;
	
	public function __construct()
	{
		# Get AWS Key
		$this->options 				= get_option('suandiaws_aws_settings');
		$this->aws_access_key		= $this->options['suandiaws_aws_settings_access_key'];
		$this->aws_secret_key		= $this->options['suandiaws_aws_settings_secret_key'];
		$this->aws_associate_key	= $this->options['suandiaws_aws_settings_associate_key'];
				
		# Hook Wordpress API
		add_action('admin_menu', array($this, 'suandiaws_addmenu'));
		add_action('admin_init', array($this, 'suandiaws_admin_init'));
		add_action('admin_notices', array($this, 'suandiaws_admin_notice'));
	}
	
	public function suandiaws_admin_notice()
	{
		# Show Notice When App ID and App Secret Is Empty
		if(empty($this->aws_access_key) && empty($this->aws_secret_key) && empty($this->aws_associate_key))
		{
			echo '	<div class="error">
					<p>Please setup your Access Key, Secret Key and Associate Key!. Click <a href="?page=suandiaws&tab=suandiaws_aws_settings">here!</a></p>
					</div>
				 ';
		}
	}
	
	public function suandiaws_addmenu()
	{
		# Create Admin Menu | Need to add  Content Curator
		add_menu_page('Suandi AWS', 'Suandi AWS', 'manage_options', 'suandiaws', array($this, 'suandiawssettingspage'), plugins_url('images/tege.jpg',__FILE__));
	}
	
	public function suandiawssettingspage()
	{
		?>
		<div class="wrap">
			<?php
				screen_icon('options-general');
				$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : "suandiaws_aws_settings";
			?>
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab <?php if($tab == "suandiaws_aws_settings") echo "nav-tab-active"; ?>" href="?page=suandiaws&tab=suandiaws_aws_settings">Suandi AWS</a>
				<a class="nav-tab <?php if($tab == "suandiaws_aws_generate") echo "nav-tab-active"; ?>" href="?page=suandiaws&tab=suandiaws_aws_generate">Generate</a>
			</h2>

				<form action="options.php" enctype="multipart/form-data" method="post">
				<?php
				settings_fields($tab);
				do_settings_sections($tab);
				?>
				</form>
		</div>
		<?php
	}
	
	public function suandiaws_admin_init()
	{
		# Call Settings Field From Another File
		require_once dirname( __FILE__ ).'/code/suandiaws_aws_settings.php';
		require_once dirname( __FILE__ ).'/code/suandiaws_aws_generate.php';
		
		# Init AWS Settings
		register_setting('suandiaws_aws_settings', 'suandiaws_aws_settings', 'suandiaws_aws_settings_validate');
		add_settings_section('suandiaws_aws_settings', '', 'suandiaws_aws_settings_text', 'suandiaws_aws_settings');
		add_settings_field('suandiaws_aws_settings_access_key', 'Access Key', 'suandiaws_aws_settings_access_key', 'suandiaws_aws_settings', 'suandiaws_aws_settings');
		add_settings_field('suandiaws_aws_settings_secret_key', 'Secret Key', 'suandiaws_aws_settings_secret_key', 'suandiaws_aws_settings', 'suandiaws_aws_settings');
		add_settings_field('suandiaws_aws_settings_associate_key', 'Assoiciate Key', 'suandiaws_aws_settings_associate_key', 'suandiaws_aws_settings', 'suandiaws_aws_settings');
		
		# Init Sources
		register_setting('suandiaws_aws_generate', 'suandiaws_aws_generate', 'suandiaws_aws_generate_validate');
		add_settings_section('suandiaws_aws_generate', '', 'suandiaws_aws_generate_text', 'suandiaws_aws_generate');
		add_settings_field('suandiaws_aws_generate_keyword', 'Keyword', 'suandiaws_aws_generate_keyword', 'suandiaws_aws_generate', 'suandiaws_aws_generate');
		add_settings_field('suandiaws_aws_generate_category', 'Category', 'suandiaws_aws_generate_category', 'suandiaws_aws_generate', 'suandiaws_aws_generate');
		add_settings_field('suandiaws_aws_generate_poststatus', 'Post Status', 'suandiaws_aws_generate_poststatus', 'suandiaws_aws_generate', 'suandiaws_aws_generate');
		add_settings_field('suandiaws_aws_generate_totalpost', 'Post to Create', 'suandiaws_aws_generate_totalpost', 'suandiaws_aws_generate', 'suandiaws_aws_generate');
		add_settings_field('suandiaws_aws_generate_postcategory', 'Post Category', 'suandiaws_aws_generate_postcategory', 'suandiaws_aws_generate', 'suandiaws_aws_generate');
		
	}	
}
# Call SuandiAWS Class
new SuandiAWS;