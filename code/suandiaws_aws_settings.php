<?php
// Settings AWS Developer Key
function suandiaws_aws_settings_text(){

}

function suandiaws_aws_settings_access_key()
{
	$options = get_option('suandiaws_aws_settings');
	echo "<input id='suandiaws_aws_settings_access_key' name='suandiaws_aws_settings[suandiaws_aws_settings_access_key]' value='{$options['suandiaws_aws_settings_access_key']}' size='50'/>";
}

function suandiaws_aws_settings_secret_key()
{
	$options = get_option('suandiaws_aws_settings');
	echo "<input id='suandiaws_aws_settings_secret_key' name='suandiaws_aws_settings[suandiaws_aws_settings_secret_key]' value='{$options['suandiaws_aws_settings_secret_key']}' size='50'/>";
}

function suandiaws_aws_settings_associate_key()
{
	$options = get_option('suandiaws_aws_settings');

	echo "<input id='suandiaws_aws_settings_associate_key' name='suandiaws_aws_settings[suandiaws_aws_settings_associate_key]' value='{$options['suandiaws_aws_settings_associate_key']}' size='25'/>";
	
	submit_button('Save Settings');
}

function suandiaws_aws_settings_validate($input)
{
	$options = get_option('suandiaws_aws_settings');
	
	$options['suandiaws_aws_settings_access_key']		= trim($input['suandiaws_aws_settings_access_key']);
	$options['suandiaws_aws_settings_secret_key']		= trim($input['suandiaws_aws_settings_secret_key']);
	$options['suandiaws_aws_settings_associate_key'] 	= trim($input['suandiaws_aws_settings_associate_key']);
	
	return $options;
}

?>