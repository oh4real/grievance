<?php

abstract class Simple_Plugin_Settings {
	const PLUGIN_NAMESPACE = 'plugin';
	const PLUGIN_SETTINGS_TITLE = 'plugin Settings';
	const PLUGIN_SECTION = 'plugin_main';

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
		$options = $args['options'];
		$select = <<<HTML
			<select name="%s" id="%s">
				%s
			</select>
HTML;
		$optionTemplate = <<<HTML
			<option value="%s" %s>%s</option>
HTML;
		$selectOptions = sprintf($option, '', !get_option($option) ? 'selected' : '', 'Select One'); // make this default select one?
		foreach ($options as $value => $label) {
			$selectOptions .= sprintf($option, $value, get_option($option) == $value ? 'selected' : '', $label);
		}
		echo sprintf($input, $option, $option, $selectOptions);
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
			settings_fields( static::PLUGIN_NAMESPACE . '-settings-group' );
			do_settings_sections( static::PLUGIN_NAMESPACE );
			submit_button(); 
		$fields = ob_get_contents();
		ob_end_clean();

		echo sprintf($str, static::PLUGIN_SETTINGS_TITLE, $fields);
	}	

	public function plugin_ajaxurl() {
		echo sprintf("<script type='text/javascript'>if (typeof ajaxurl === 'undefined') var ajaxurl = '%s';</script>", admin_url('admin-ajax.php'));
	}

}