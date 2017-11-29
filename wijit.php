<?php

/*
Plugin Name: Wijit
Description: A custom text widget to show arbitrary text or HTML that can be individually styled. Why this plugin name? Wijit is just a phonetic representation of the word "widget", that's all.
Plugin URI: https://github.com/lutrov/wijit
Version: 1.3
Author: Ivan Lutrov
Author URI: http://lutrov.com/
*/

//
// Custom text widget class.
//
class wijit_text_widget extends wp_widget {
	function wijit_text_widget() {
		$options = array(
			'widget' => array(
				'classname' => 'custom-text',
				'description' => __('Arbitrary text or HTML that can be individually styled.', 'wijit')
			),
			'control' => array(
				'width' => 300,
				'height' => 300,
				'id_base' => 'custom-widget'
			)

		);
		$this->wp_widget('custom-widget', __('Custom Text', 'wijit'), $options['widget'], $options['control']);
	}
	function widget($args, $instance) {
		$show = isset($instance['show']) ? $instance['show'] : false;
		if ($show) {
			$title = apply_filters('widget_title', $instance['title']);
			$content = $instance['content'];
			echo $args['before_widget'];
			echo '<div class="' . sanitize_title($title) . '">';
			if (strlen($title) > 0) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			if (strlen($content) > 0) {
				if (function_exists('markdown')) {
					$content = markdown($content);
				}
				echo $content;
			}
			echo '</div>';
			echo $args['after_widget'];
		}
	}
	function update($new, $old) {
		$instance = $old;
		$instance['title'] = strip_tags($new['title']);
		$instance['content'] = $new['content'];
		$instance['show'] = $new['show'];
		return $instance;
	}
	function form($instance) {
		$defaults = array(
			'title' => __('Custom', 'wijit'),
			'content' => __('Custom widget text', 'wijit'),
			'class' => 'untitled',
			'show' => true
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		$instance['title'] = trim($instance['title']);
		$instance['content'] = trim($instance['content']);
		if (strlen($instance['title']) > 0) {
			$instance['class'] = sanitize_title($instance['title']);
		}
		if (strlen($instance['content']) > 0) {
			echo '<p><label for="' . $this->get_field_id('title') . '">' . __('Title:', 'wijit') . '</label><br /><input type="text" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" style="width:100%" value="' . $instance['title'] . '" /></p>';
			echo '<p><textarea id="' . $this->get_field_id('content') . '" name="' . $this->get_field_name('content') . '" rows="8" cols="20" style="width:100%">' . $instance['content'] . '</textarea></p>';
			echo '<p><input type="checkbox" ' . ($instance['show'] ? 'checked="checked"' : null) . ' id="' . $this->get_field_id('show') . '" name="' . $this->get_field_name('show') . '" class="checkbox" /><label for="' . $this->get_field_id('show') . '">' . __('Show widget', 'wijit') . '</label></p>';
			echo '<p class="description">' . sprintf(__("Individual styling can be applied by using a <code>.widget.custom-text .%s {}</code> rule in your theme's stylesheet file."),  $instance['class']) . '</p>';
		}
	}
}

//
// Load the custom widget.
//
if (function_exists('wijit_load_text_widget') == false) {
	function wijit_load_text_widget() {
		register_widget('wijit_text_widget');
	}
	add_action('widgets_init', 'wijit_load_text_widget');
}

?>
