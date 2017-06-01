<?php
function ext_analytics_plugin_menu() {

	add_options_page( 'Analytics', 'Analytics', 'administrator', 'analytics-plugin-settings',
		'evg_analytics_plugin_settings' );

}

// MAIN
function evg_analytics_plugin_settings() {
	require_once( plugin_dir_path( __FILE__ ) . 'settings_form.php' );
}


function evg_analytics_plugin_setup_settings() {

	register_setting( 'evg_analytics_plugin_settings_id',
		'evg_analytics_plugin_settings_group_name',
		'evg_analytics_plugin_options_sanitize' );

	add_settings_section( 'evg_analytics_plugin_section_id',
		'ANALYTICS SETTINGS',
		'',
		'analytics-plugin-settings' );

	add_settings_field( 'evg_analytics_code_id',
		'ANALYTICS CODE',
		'evg_analytics_code_field_cb',
		'analytics-plugin-settings',
		'evg_analytics_plugin_section_id',
		array( 'label_for' => 'evg_analytics_code_id' ) );

	add_settings_field( 'evg_analytics_ip_id',
		'IP',
		'evg_analytics_id_field_cb',
		'analytics-plugin-settings',
		'evg_analytics_plugin_section_id',
		array( 'label_for' => 'evg_analytics_ip_id' ) );

}


function evg_analytics_code_field_cb() {

	$options = get_option( 'evg_analytics_plugin_settings_group_name' );
	?><label for="evg_analytics_code_id"></label>
    <input type="text" name="evg_analytics_plugin_settings_group_name[evg_analytics_code]"
           id="evg_analytics_code_id" value="<?php
	echo esc_attr( $options['evg_analytics_code'] ); ?>" class="regular-text">
	<?php

}


function evg_analytics_id_field_cb() {

	$options = get_option( 'evg_analytics_plugin_settings_group_name' );
	?><label for="evg_analytics_ip_id"></label>
    <input type="text" name="evg_analytics_plugin_settings_group_name[evg_analytics_id]"
           id="evg_analytics_ip_id" value="<?php
	echo esc_attr( $options['evg_analytics_id'] ); ?>"
           class="regular-text"> Example:
    Your IP's from 111.111.111.1 to 111.111.111.10, so you place:
    111.111.111|1-10
	<?php
}


function evg_analytics_plugin_options_sanitize( $data ) {
	return $data;
}


function evgParseCompanyIpsAndReturnArray( $option_value ) {

	$arrayOfCompanyIps      = [];
	$arrayOfCompanyIpsFinal = [];
	$x                      = explode( '|', $option_value );

	if ( ( isset( $x[0] ) ) && ( isset( $x[1] ) ) ) {
		$beginIp = $x[0];
		$y       = explode( '-', $x[1] );
		if ( ( isset( $y[0] ) ) && ( isset( $y[1] ) ) ) {
			$rangeIpBegin = $y[0];
			$rangeIpEnd   = $y[1];

			for ( $i = $rangeIpBegin; $i < $rangeIpEnd + 1; $i ++ ) {

				$arrayOfCompanyIps[] = $i;
			}

			foreach ( $arrayOfCompanyIps as $ipNumber ) {
				$arrayOfCompanyIpsFinal[] = $beginIp . '.' . $ipNumber;
			}
		}
	}

	return $arrayOfCompanyIpsFinal;
}


function evgPlaceCodeFromGoogleAnalytics( $arrayOfOptions ) {

	extract( $arrayOfOptions );
	// getting user IP
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$user_ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty ( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$user_ip = $_SERVER['REMOTE_ADDR'];
	}

	$flag = false;

	if ( isset( $evg_analytics_id ) ) {

		$arrayIps = evgParseCompanyIpsAndReturnArray( $evg_analytics_id );

		foreach ( $arrayIps as $ip ) {
			if ( $user_ip == $ip ) {
				$flag = true;
			}
		}

	}


	if ( ( ! is_admin() ) && ( ! $flag ) ) {
		add_action( 'wp_head', 'evg_analytics_plugin_include_code_function' );
	}
}


function evg_analytics_plugin_include_code_function() {

	extract( get_option( 'evg_analytics_plugin_settings_group_name' ) );

	if ( isset( $evg_analytics_code ) ) {
		$result = "<script>$evg_analytics_code</script>";
		echo $result;
	}

}
