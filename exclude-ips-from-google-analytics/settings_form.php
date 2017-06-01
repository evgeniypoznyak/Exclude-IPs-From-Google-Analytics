<div class="wrap">
	<h2></h2>
	<p></p>
	<form action="options.php" method="post">
		<?php
		settings_fields( 'evg_analytics_plugin_settings_id' );
		do_settings_sections( 'analytics-plugin-settings' );
		submit_button();
		?>
	</form>
</div>
