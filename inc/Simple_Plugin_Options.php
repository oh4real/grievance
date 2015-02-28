<?php

abstract class Simple_Plugin_Options {
	protected $pluginNamespace = 'TBD';
	protected $pluginSettingsTitle = 'TBD Settings';

	public function plugin_ajaxurl() {
		echo sprintf("<script type='text/javascript'>if (typeof ajaxurl === 'undefined') var ajaxurl = '%s';</script>", admin_url('admin-ajax.php'));
	}

	public function plugin_ajax_request() {
		throw new Exception(sprintf('Must define %::%',  __CLASS__, __METHOD__));
	}

	public function init_plugin_settings() {
		throw new Exception(sprintf('Must define %::%',  __CLASS__, __METHOD__));
	}

	public function plugin_section_text() {
		throw new Exception(sprintf('Must define %::%',  __CLASS__, __METHOD__));
	}

	public function add_plugin_menu() {
		throw new Exception(sprintf('Must define %::%',  __CLASS__, __METHOD__));
	}

	public function generate_input_field($args) {
		$option = $args['field'];
		$type = array_key_exists('type', $args) ? $args['type'] : 'text';
		$input = <<<HTML
			<input type="%s" name="%s" id="%s" value="%s" />
HTML;
		echo sprintf($input, $type, $option, $option, get_option($option));
	}

	public function generate_select_field($args) {
		$option = $args['field'];
		$type = array_key_exists('type', $args) ? $args['type'] : 'text';
		$input = <<<HTML
			<input type="%s" name="%s" id="%s" value="%s" />
HTML;
		echo sprintf($input, $type, $option, $option, get_option($option));
	}

	public function generate_radio_field($args) {
		$option = $args['field'];
		$type = array_key_exists('type', $args) ? $args['type'] : 'text';
		$input = <<<HTML
			<input type="%s" name="%s" id="%s" value="%s" />
HTML;
		echo sprintf($input, $type, $option, $option, get_option($option));
	}

	public function generate_checkbox_field($args) {
		$option = $args['field'];
		$type = array_key_exists('type', $args) ? $args['type'] : 'text';
		$input = <<<HTML
			<input type="%s" name="%s" id="%s" value="%s" />
HTML;
		echo sprintf($input, $type, $option, $option, get_option($option));
	}

	public function admin_settings_page() {
		$str = <<<HTML
		    <div>
	            <h2>%s</h2>
				<form method="post" action="options.php">%s</form>
	        </div>
HTML;
		ob_start();
			settings_fields( $this->pluginNamespace . '-settings-group' );
			do_settings_sections( $this->pluginNamespace );
			submit_button(); 
		$fields = ob_get_contents();
		ob_end_clean();

		echo sprintf($str, $this->pluginSettingsTitle, $fields);
	}	

}