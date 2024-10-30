<?php
/*
Plugin Name:  Countdown timer by Mateusz Styrna
Plugin URI:   https://mateusz-styrna.pl/
Description:  A simple plugin that adds a progress bar with countdown timer. 
Version:      1.0
Author:       Mateusz Styrna
Author URI:   https://mateusz-styrna.pl/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Tested up to: 5.0.3
Tags: countdown, timer, counter, time, events, clock, mateusz styrna
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//actions
add_action('admin_menu', 'cdt_menu');
add_action( 'wp_enqueue_scripts', 'cdt_display_counter' );

register_activation_hook(__FILE__, 'cdt_activate');

function cdt_menu() {
	add_menu_page('Countdown timer', 'Countdown Timer', 'administrator', 'countdown-timer', 'cdt_plugin_page', 'dashicons-clock', 61);
}

function cdt_plugin_page() {
	$cdt_eventName = sanitize_text_field($_POST['eventName']);
	$cdt_count_to = preg_replace("([^0-9-])", "", $_POST['count_to']);
	$cdt_count_from = preg_replace("([^0-9-])", "", $_POST['count_from']);
	$cdt_position = sanitize_text_field($_POST['position']);
	$cdt_enable = sanitize_key($_POST['enable']);

	if (strtotime($cdt_count_to) && strtotime($cdt_count_from) && $cdt_eventName && $cdt_position && current_user_can('administrator', 'edit_theme_options')) {
		update_option('countdown-timer_from', $cdt_count_from, 'yes');
		update_option('countdown-timer_to', $cdt_count_to, 'yes');
		update_option('countdown-timer_eventName', $cdt_eventName, 'yes');

		if ($cdt_position == "top") {
			update_option('countdown-timer_position', 'top', 'yes');
		}
		else {
			if ($cdt_position == "bottom")
			update_option('countdown-timer_position', 'bottom', 'yes');
			else {
				$cdt_success = false;
			}
		}

		if ($cdt_enable == "enable") {
			update_option('countdown-timer_enabled', 'true', 'yes');
		}
		else {
			update_option('countdown-timer_enabled', 'false', 'yes');
		}

		$cdt_success = true;
	}

?><div class="warp">
	<h1>Countdown Timer</h1>
	<form method="POST">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="count_from">Count from:</label>
					</th>
					<td>
						<input type="date" value="<?php if (get_option('countdown-timer_from') != 0) echo esc_attr(get_option('countdown-timer_from')); ?>" id="count_from" name="count_from" placeholder="Time of start counting"><br>
						<span class="description">Time of start counting. (0% of progress)</span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="count_to">Count to:</label>
					</th>
					<td>
						<input type="date" value="<?php if (get_option('countdown-timer_to') != 0) echo esc_attr(get_option('countdown-timer_to')); ?>" id="count_to" name="count_to" placeholder="Time of the event"><br>
						<span class="description">Time of the event you want to countdown. (100% of progress)</span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="eventName">Event name:</label>
					</th>
					<td>
						<input type="text" value="<?php if (get_option('countdown-timer_eventName')) echo esc_attr(get_option('countdown-timer_eventName')); ?>" id="eventName" name="eventName" placeholder="Name of your event"><br>
						<span class="description">That text will be displayed on the progress bar.</span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="position">Position of the progressbar:</label>
					</th>
					<td>
						<input type="radio" id="position" name="position" value="top"<?php if (get_option('countdown-timer_position') == 'top') echo esc_html('checked="checked"'); ?>> Top<br>
						<input type="radio" id="position" name="position" value="bottom"<?php if (get_option('countdown-timer_position') == 'bottom') echo esc_html('checked="checked"'); ?>> Bottom<br>
						<span class="description">Choose where the counter should be displayed.</span>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="enable">Enable counter and progressbar?</label>
					</th>
					<td>
						<input type="checkbox" id="enable" name="enable" value="enable"<?php if (get_option('countdown-timer_enabled') == 'true') echo esc_html('checked="checked"'); ?>><br>
						<span class="description">If no, just leave that checkbox blank.</span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" class="button-primary" value="Save">
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<?php if ($cdt_success) { ?>
		<div class="notice notice-success"><p>Done!</p></div>
	<?php } ?>
</div><?php
}

function cdt_activate() {
	//check if options exists, if no then create them
	if (!get_option('countdown-timer_enabled')) {
		add_option('countdown-timer_enabled', 'false', '', 'yes');
	}
	if (!get_option('countdown-timer_from')) {
		add_option('countdown-timer_from', '0', '', 'yes');
	}
	if (!get_option('countdown-timer_to')) {
		add_option('countdown-timer_to', '0', '', 'yes');
	}
	if (!get_option('countdown-timer_eventName')) {
		add_option('countdown-timer_eventName', 'event', '', 'yes');
	}
	if (!get_option('countdown-timer_position')) {
		add_option('countdown-timer_position', 'bottom', '', 'yes');
	}
}

function cdt_display_counter() {
	if (get_option('countdown-timer_enabled') == "true") {
		wp_enqueue_script( 'counter-js', plugins_url('counter.js', __FILE__), '', '1.0.0', true );
		wp_enqueue_style( 'counter-css', plugins_url('counter.css', __FILE__), '', '1.0.0', false );
		$vars = array(
			'from' => strtotime(get_option('countdown-timer_from')),
			'to' => strtotime(get_option('countdown-timer_to')),
			'eventName' => get_option('countdown-timer_eventName'),
			'position' => get_option('countdown-timer_position')
		);
		wp_localize_script( 'counter-js', 'options', $vars );
	}
}

?>