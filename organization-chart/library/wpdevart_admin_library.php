<?php

defined('ABSPATH') || exit;

class wpda_org_chart_library {

	public static function create_tab($tab_titles, $tab_contents, $options = []) {
		$options = array_merge([
			'theme' => 'wpda_blue',
			'title' => '',
		], $options);

		if (!is_array($tab_titles) || !is_array($tab_contents) || (count($tab_titles) !== count($tab_contents) && count($tab_contents) !== 0)) {
			return '<div class="wpda_error">Tab creator error!</div>';
		}

		$tab_html = '<div class="wpda_tab_container ' . esc_attr($options['theme']) . '">';
		if ($options['title'] !== '') {
			$tab_html .= '<h3>' . esc_html($options['title']) . '</h3>';
		}

		$tab_html .= '<div class="wpda_links_container"><ul>';
		foreach ($tab_titles as $tab_title) {
			$tab_html .= '<li>' . $tab_title . '</li>';
		}
		$tab_html .= '</ul><div></div></div><div class="wpda_contents_container">';
		foreach ($tab_contents as $tab_content) {
			$tab_html .= '<div>' . $tab_content . '</div>';//$tab_contents elements sanitized
		}
		return $tab_html . '</div></div>';
	}

	public static function create_setting($args) {
		if (empty($args['function_name'])) {
			return '';
		}

		$fn = $args['function_name'];
		if (is_callable(['wpda_org_chart_library', $fn])) {
			return self::$fn($args);
		}

		return '';
	}
	// return html for option description
	public static function gen_desc_panel($args) {
		$args = self::fill_empty_args($args);
		$html  = '<div class="wpda_option_description">';
		$pro = '';
		if ((isset($args["pro"]) && $args["pro"] === true)) {
			$pro = '<span class="wpda_pro_feature">(pro)</span>';
		}
		$html .= '<span class="wpdevart-title">' . esc_html($args['title']) . $pro . '</span>';
		if ($args['description'] !== '') {
			$html .= '<span class="wpdevart-info-container">?';
			$html .= '<span class="wpdevart-info">' . esc_html($args['description']) . '</span>';
			$html .= '</span>';
		}

		return $html . '</div>';
	}
	// return html for simple input
	public static function simple_input($args) {
		$html = '';
		$pro_class = '';
		$show_class = '';
		$show_attr = '';

		if (!empty($args['pro'])) {
			$pro_class = 'wpda_pro_option';
		}

		if (!empty($args['show_when'])) {
			$show_attr = ' data-condition=\'' . json_encode($args['show_when']) . '\'';
			$show_class = ' condition_element';
		}

		$html .= '<div' . $show_attr . ' class="wpda_option wpda_simple_input' . ($show_class ? ' ' . $show_class : '') . '">';
		$html .= self::gen_desc_panel($args);
		$html .= '<div class="' . $pro_class . '">';
		$html .= self::input($args);
		$html .= '</div></div>';

		return $html;
	}
	// return html for margin padding inputs
	public static function margin_padding_input($args) {
		$pro_class = !empty($args['pro']) ? 'wpda_pro_option' : '';

		$html  = '<div class="wpda_option wpda_margin_padding_input">';
		$html .= self::gen_desc_panel($args);
		$html .= '<div class="wpda_margin_padding_inputs_container ' . $pro_class . '">';
		$html .= self::margin_padding_inputs_helper($args);
		$html .= '</div></div>';

		return $html;
	}

	public static function input($args) {
		$preview_class = '';
		$preview_attr = '';
		$size = '';
		$metric = ['desktop' => '', 'tablet' => '', 'mobile' => ''];
		$disabled = '';
		$responsive_before = '';
		$responsive_after = '';
		$responsive_checkbox = '';
		$second_input = '';
		$third_input = '';
		$small_text = '';

		if (!empty($args['preview'])) {
			$preview_attr  = ' data-preview-id="' . esc_attr($args['preview']['id']) . '" data-preview-action="' . esc_attr($args['preview']['action']) . '"';
			$preview_class = 'with_preview';
		}

		if (!empty($args['size'])) {
			$size = ' size="' . esc_attr($args['size']) . '"';
		}

		if (!empty($args['metric'])) {
			if (count($args['metric']) === 1) {
				$disabled = 'disabled';
			}

			foreach (['desktop', 'tablet', 'mobile'] as $device) {
				$value = isset($args['value']["metric_$device"]) ? $args['value']["metric_$device"] : '';
				$metric[$device] .= '<select ' . esc_attr($disabled) . ' class="wpda_input_metric metric_' . esc_attr($device) . '" name="' . esc_attr($args['name']) . '[metric_' . esc_attr($device) . ']">';
				foreach ($args['metric'] as $unit) {
					$metric[$device] .= '<option value="' . esc_attr($unit) . '" ' . selected($unit, $value, false) . '>' . esc_html($unit) . '</option>';
				}
				$metric[$device] .= '</select>';
			}
		}

		if (!empty($args['small_text'])) {
			$small_text = '<small>' . esc_html($args['small_text']) . '</small>';
		}

		if (!empty($args['responsive'])) {
			$responsive_before = '<div class="responsive_elements_coneitner">';
			$responsive_after  = '</div>';
			$responsive_checkbox  = '<select class="dashicons wpda_responsive_select" name="' . esc_attr($args['name']) . '[responsive]">';
			$responsive_checkbox .= '<option value="desktop">&#xf472;</option>';
			$responsive_checkbox .= '<option value="tablet">&#xf471;</option>';
			$responsive_checkbox .= '<option value="mobile">&#xf470;</option>';
			$responsive_checkbox .= '</select>';

			$second_input = $responsive_before .
				'<input type="' . esc_attr(isset($args['type']) ? $args['type'] : 'text') . '" value="' . esc_attr($args['value']['tablet']) . '"' . $size . ' name="' . esc_attr($args['name']) . '[tablet]">' . $metric['tablet'] . $small_text . $responsive_after;

			$third_input = $responsive_before .
				'<input type="' . esc_attr(isset($args['type']) ? $args['type'] : 'text') . '" value="' . esc_attr($args['value']['mobile']) . '"' . $size . ' name="' . esc_attr($args['name']) . '[mobile]">' . $metric['mobile'] . $small_text . $responsive_after;
		}

		return $responsive_checkbox . $responsive_before . '<input class="' . esc_attr($preview_class) . '"' . $preview_attr . ' type="' . esc_attr(isset($args['type']) ? $args['type'] : 'text') . '" value="' . esc_attr($args['value']['desktop']) . '"' . $size . ' id="' . esc_attr($args['name']) . '" name="' . esc_attr($args['name']) . '[desktop]">' . $metric['desktop'] . $small_text . $responsive_after . $second_input . $third_input;
	}

	public static function margin_padding_inputs_helper($args) {
		$html = '';
		$name = esc_attr($args['name']);
		$preview = ['class' => '', 'attr' => ['left' => '', 'right' => '', 'top' => '', 'bottom' => '']];

		if (
			!empty($args['preview']['action']['left']) && !empty($args['preview']['action']['right']) &&
			!empty($args['preview']['action']['top']) && !empty($args['preview']['action']['bottom'])
		) {
			foreach (['left', 'right', 'top', 'bottom'] as $pos) {
				$preview['attr'][$pos] = 'data-preview-id="' . esc_attr($args['preview']['id']) . '" data-preview-action="' . esc_attr($args['preview']['action'][$pos]) . '"';
			}
			$preview['class'] = 'with_preview';
		}

		$metric = ['desktop' => '', 'tablet' => '', 'mobile' => ''];
		$disabled = '';

		if (!empty($args['metric'])) {
			if (count($args['metric']) === 1) {
				$disabled = 'disabled';
			}

			foreach (['desktop', 'tablet', 'mobile'] as $device) {
				$metric_value = isset($args['value']["metric_$device"]) ? $args['value']["metric_$device"] : '';
				$metric[$device] .= '<select ' . esc_attr($disabled) . ' class="wpda_input_metric metric_' . esc_attr($device) . '" name="' . $name . '[metric_' . esc_attr($device) . ']">';
				foreach ($args['metric'] as $unit) {
					$metric[$device] .= '<option value="' . esc_attr($unit) . '" ' . selected($unit, $metric_value, false) . '>' . esc_html($unit) . '</option>';
				}
				$metric[$device] .= '</select>';
			}
		}

		$responsive_before = '';
		$responsive_after = '';
		$responsive_checkbox = '';
		$second_input = '';
		$third_input = '';

		if (!empty($args['responsive'])) {
			$responsive_before = '<div class="responsive_elements_coneitner">';
			$responsive_after = '</div>';
			$responsive_checkbox = '<select class="dashicons wpda_responsive_select" name="' . $name . '[responsive]">';
			$responsive_checkbox .= '<option value="desktop">&#xf472;</option>';
			$responsive_checkbox .= '<option value="tablet">&#xf471;</option>';
			$responsive_checkbox .= '<option value="mobile">&#xf470;</option>';
			$responsive_checkbox .= '</select>';

			foreach (['tablet', 'mobile'] as $device) {
				$inputs = $responsive_before;
				foreach (['top', 'right', 'bottom', 'left'] as $pos) {
					$value = isset($args['value']["{$device}_$pos"]) ? esc_attr($args['value']["{$device}_$pos"]) : '';
					$inputs .= '<span><input type="' . esc_attr(isset($args['type']) ? $args['type'] : 'text') . '" value="' . $value . '" id="' . $name . '" name="' . $name . '[' . esc_attr($device . '_' . $pos) . ']">' . '<span>' . ucfirst($pos) . '</span></span>';
				}
				$inputs .= $metric[$device] . $responsive_after;
				if ($device === 'tablet') {
					$second_input = $inputs;
				} else {
					$third_input = $inputs;
				}
			}
		}

		$html .= $responsive_checkbox . $responsive_before;

		foreach (['top', 'right', 'bottom', 'left'] as $pos) {
			$value = isset($args['value']["desktop_$pos"]) ? esc_attr($args['value']["desktop_$pos"]) : '';
			$html .= '<span><input class="' . esc_attr($preview['class']) . '" ' . $preview['attr'][$pos] . ' type="' . esc_attr(isset($args['type']) ? $args['type'] : 'text') . '" value="' . $value . '" id="' . $name . '" name="' . $name . '[desktop_' . esc_attr($pos) . ']"><span>' . ucfirst($pos) . '</span></span>';
		}

		$html .= $metric['desktop'] . $responsive_after . $second_input . $third_input;
		return $html;
	}
	// return html for radio
	public static function radio($args) {
		$show_when = ['class' => '', 'attr' => ''];
		if (!empty($args['show_when'])) {
			$show_when['attr']  = ' data-condition=\'' . esc_attr(json_encode($args['show_when'])) . '\'';
			$show_when['class'] = ' condition_element';
		}

		$name = esc_attr($args['name']);
		$current_value = $args['value'];
		$counter = 0;

		$html  = '<div class="wpda_option wpda_radio' . esc_attr($show_when['class']) . '"' . $show_when['attr'] . '>';
		$html .= self::gen_desc_panel($args);
		$html .= '<div><div class="switch-field">';

		foreach ($args['values'] as $key => $value) {
			$counter++;
			$key_esc = esc_attr($key);
			$html .= '<input type="radio" name="' . $name . '" id="' . $name . '_' . $counter . '" value="' . $key_esc . '" ' . checked($key, $current_value, false) . ' />';
			$html .= '<label for="' . $name . '_' . $counter . '">' . esc_html($value) . '</label>';
		}

		$html .= '</div></div></div>';
		return $html;
	}
	// return html for checkbox
	public static function checkbox($args) {
		$show_when = ['class' => '', 'attr' => ''];
		$draggable = ['class' => '', 'attr' => ''];

		if (!empty($args['show_when'])) {
			$encoded = esc_attr(json_encode($args['show_when']));
			$show_when['attr']  = ' data-condition=\'' . $encoded . '\'';
			$show_when['class'] = ' condition_element';
		}

		if (!empty($args['draggable'])) {
			$encoded = esc_attr(json_encode($args['show_when']));
			$draggable['attr']  = ' data-condition=\'' . $encoded . '\'';
			$draggable['class'] = ' condition_element';
		}

		$name = esc_attr($args['name']);
		$current_value = $args['value'];
		$counter = 0;

		$html  = '<div class="wpda_option wpda_checkbox' . esc_attr($show_when['class']) . '"' . $show_when['attr'] . '>';
		$html .= self::gen_desc_panel($args);
		$html .= '<div>';

		foreach ($args['values'] as $key => $value) {
			$counter++;
			$is_checked = !empty($current_value[$key]);

			$key_esc = esc_attr($key);
			$id = $name . '_' . $counter;

			$html .= '<div>';
			$html .= '<input type="checkbox" name="' . $name . '[' . $key_esc . ']" id="' . esc_attr($id) . '" value="' . $key_esc . '" ' . checked(true, $is_checked, false) . ' />';
			$html .= '<label for="' . esc_attr($id) . '">' . esc_html($value) . '</label>';
			$html .= '</div>';
		}

		$html .= '</div></div>';
		return $html;
	}
	// return color input with gradient
	public static function gradient_color_input($args) {
		$show_when = ['class' => '', 'attr' => ''];
		if (!empty($args['show_when'])) {
			$show_when['attr']  = ' data-condition=\'' . esc_attr(json_encode($args['show_when'])) . '\'';
			$show_when['class'] = ' condition_element';
		}

		$transparent = !empty($args['transparent']) ? 'data-alpha="true"' : '';
		$name = esc_attr($args['name']);

		$color1 = isset($args['value']['color1']) ? esc_attr($args['value']['color1']) : '';
		$color2 = isset($args['value']['color2']) ? esc_attr($args['value']['color2']) : '';

		$html  = '<div class="wpda_option wpda_color_gradient' . esc_attr($show_when['class']) . '"' . $show_when['attr'] . '>';
		$html .= self::gen_desc_panel($args);
		$html .= '<div>';

		$html .= '<input type="text" class="color" ' . $transparent . ' value="' . $color1 . '" data-default-color="' . $color1 . '" id="' . $name . '_color1" name="' . $name . '[color1]">';
		$html .= '<input type="text" class="color" ' . $transparent . ' value="' . $color2 . '" data-default-color="' . $color2 . '" id="' . $name . '_color2" name="' . $name . '[color2]">';

		$html .= '<select id="' . $name . '_select_grad" name="' . $name . '[gradient]">';
		foreach (self::gradient_directions() as $dir) {
			$selected = selected(isset($args['value']['gradient']) && $args['value']['gradient'] === $dir['key'], true, false);
			$html .= '<option ' . $selected . ' value="' . esc_attr($dir['key']) . '">' . esc_html($dir['val']) . '</option>';
		}
		$html .= '</select></div></div>';

		return $html;
	}

	// return range input
	public static function range_input($args) {
		$show_when = ['class' => '', 'attr' => ''];
		$preview   = ['class' => '', 'attr' => ''];

		if (!empty($args['show_when'])) {
			$show_when['attr']  = ' data-condition=\'' . esc_attr(json_encode($args['show_when'])) . '\'';
			$show_when['class'] = ' condition_element';
		}

		if (!empty($args['preview'])) {
			$preview['class'] = 'with_preview';
			$preview['attr']  = 'data-preview-id="' . esc_attr($args['preview']['id']) . '" data-preview-action="' . esc_attr($args['preview']['action']) . '"';
		}

		$name  = esc_attr($args['name']);
		$value = esc_attr($args['value']);

		$html  = '<div class="wpda_option wpda_range_input' . esc_attr($show_when['class']) . '"' . $show_when['attr'] . '>';
		$html .= self::gen_desc_panel($args);
		$html .= '<div>';

		$html .= '<input class="' . esc_attr($preview['class']) . '" ' . $preview['attr'] .
			' type="range" id="' . $name . '" name="' . $name . '" value="' . $value . '"';

		if (isset($args['step'])) {
			$html .= ' step="' . esc_attr($args['step']) . '"';
		}
		if (isset($args['min_value'])) {
			$html .= ' min="'  . esc_attr($args['min_value']) . '"';
		}
		if (isset($args['max_value'])) {
			$html .= ' max="'  . esc_attr($args['max_value']) . '"';
		}

		$html .= ' />';

		if (!empty($args['show_val'])) {
			$html .= '<output id="' . $name . '_connect">' . esc_html($args['value']) . '</output>';
		}

		if (!empty($args['small_text'])) {
			$html .= '<small>' . esc_html($args['small_text']) . '</small>';
		}

		$html .= '</div></div>';
		return $html;
	}
	// return color input
	public static function color_input($args) {
		$show_when = ['class' => '', 'attr' => ''];
		$preview   = ['class' => '', 'attr' => ''];
		$pro_class = '';
		$transparent = '';

		if (!empty($args['pro'])) {
			$pro_class = 'wpda_pro_option';
		}

		if (!empty($args['preview'])) {
			$preview['class'] = 'with_preview';
			$preview['attr']  = 'data-preview-id="' . esc_attr($args['preview']['id']) . '" data-preview-action="' . esc_attr($args['preview']['action']) . '"';
		}

		if (!empty($args['show_when'])) {
			$show_when['attr']  = ' data-condition=\'' . esc_attr(json_encode($args['show_when'])) . '\'';
			$show_when['class'] = ' condition_element';
		}

		if (!empty($args['transparent'])) {
			$transparent = 'data-alpha="true"';
		}

		$name          = esc_attr($args['name']);
		$value         = esc_attr($args['value']);
		$default_value = esc_attr($args['default_value']);

		$html  = '<div class="wpda_option wpda_color_input' . esc_attr($show_when['class']) . '"' . $show_when['attr'] . '>';
		$html .= self::gen_desc_panel($args);
		$html .= '<div class="' . esc_attr($pro_class) . '">';
		$html .= '<input ' . $transparent . ' type="text" class="color ' . esc_attr($preview['class']) . '" ' . $preview['attr'] .
			' value="' . $value . '" data-default-color="' . $default_value . '" id="' . $name . '" name="' . $name . '">';
		$html .= '</div></div>';

		return $html;
	}


	public static function simple_select($args) {
		$show_when = ['class' => '', 'attr' => ''];
		$preview   = ['class' => '', 'attr' => ''];
		$pro_class = '';

		if (!empty($args['pro'])) {
			$pro_class = 'wpda_pro_option';
		}

		if (!empty($args['preview'])) {
			$preview['class'] = 'with_preview';
			$preview['attr']  = 'data-preview-id="' . esc_attr($args['preview']['id']) . '" data-preview-action="' . esc_attr($args['preview']['action']) . '"';
		}

		if (!empty($args['show_when'])) {
			$show_when['attr']  = ' data-condition=\'' . esc_attr(json_encode($args['show_when'])) . '\'';
			$show_when['class'] = ' condition_element';
		}

		$name = esc_attr($args['name']);
		$html  = '<div class="wpda_option wpda_select_input' . esc_attr($show_when['class']) . '"' . $show_when['attr'] . '>';
		$html .= self::gen_desc_panel($args);
		$html .= '<div class="' . esc_attr($pro_class) . '">';
		$html .= '<select ' . $preview['attr'] . ' class="' . esc_attr($preview['class']) . '" id="' . $name . '" name="' . $name . '">';

		foreach ($args['values'] as $key => $value) {
			if (!is_array($value)) {
				$html .= '<option value="' . esc_attr($key) . '" ' . selected($key, $args['value'], false) . '>' . esc_html($value) . '</option>';
			} else {
				$html .= '<optgroup label="' . esc_attr(str_replace('_', ' ', $key)) . '">';
				foreach ($value as $sub_key => $sub_value) {
					$html .= '<option value="' . esc_attr($sub_key) . '" ' . selected($sub_key, $args['value'], false) . '>' . esc_html($sub_value) . '</option>';
				}
				$html .= '</optgroup>';
			}
		}

		$html .= '</select></div></div>';
		return $html;
	}


	public static function popup($args) {
		$show_when = ['class' => '', 'attr' => ''];
		$pro_class = '';

		if (!empty($args['pro'])) {
			$pro_class = 'wpda_pro_option';
		}

		if (!empty($args['show_when'])) {
			$show_when['attr']  = ' data-condition=\'' . esc_attr(json_encode($args['show_when'])) . '\'';
			$show_when['class'] = ' condition_element';
		}

		$html = '<div class="wpda_option wpda_popup' . esc_attr($show_when['class']) . '"' . $show_when['attr'] . '>';
		$html .= self::gen_desc_panel($args);
		$html .= '<div class="' . esc_attr($pro_class) . '">';
		$html .= '<span class="wpda_popup_o_c_button dashicons dashicons-edit edit_font_button"></span>';
		$html .= '<div class="wpda_popup_window">';

		foreach ((array) $args['params'] as $ins_elem_name => $ins_elem_args) {
			$ins_elem_args = array_merge([
				'name'          => $ins_elem_name,
				'heading_name'  => isset($args['heading_name']) ? $args['heading_name'] : '',
				'heading_group' => isset($args['heading_group']) ? $args['heading_group'] : '',
			], $ins_elem_args);

			unset($ins_elem_args['description']);
			$html .= self::create_setting($ins_elem_args);
		}

		$html .= '</div></div></div>';
		return $html;
	}

	public static function demo_text($args) {
		$show_when = ['class' => '', 'attr' => ''];

		if (!empty($args['show_when'])) {
			$show_when['attr']  = ' data-condition=\'' . esc_attr(json_encode($args['show_when'])) . '\'';
			$show_when['class'] = ' condition_element';
		}

		$id    = esc_attr($args['name']);
		$title = esc_html($args['title']);

		$html  = '<div class="wpda_option demo_text' . esc_attr($show_when['class']) . '"' . $show_when['attr'] . '>';
		$html .= '<div id="' . $id . '">' . $title . '</div>';
		$html .= '</div>';

		return $html;
	}

	public static function demo_border($args) {
		$show_when = ['class' => '', 'attr' => ''];

		if (!empty($args['show_when'])) {
			$show_when['attr']  = ' data-condition=\'' . esc_attr(json_encode($args['show_when'])) . '\'';
			$show_when['class'] = ' condition_element';
		}

		$id    = esc_attr($args['name']);
		$title = esc_html($args['title']);

		$html  = '<div class="wpda_option demo_border' . esc_attr($show_when['class']) . '"' . $show_when['attr'] . '>';
		$html .= '<div id="' . $id . '">' . $title . '</div>';
		$html .= '</div>';

		return $html;
	}

	/*for front end*/
	public static function hex2rgba($color, $opacity = false) {
		$default = 'rgb(0,0,0)';

		if (empty($color)) {
			return $default;
		}

		if ($color[0] === '#') {
			$color = substr($color, 1);
		}

		if (strlen($color) === 6) {
			$hex = [$color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]];
		} elseif (strlen($color) === 3) {
			$hex = [$color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]];
		} else {
			return $default;
		}

		$rgb = array_map('hexdec', $hex);
		$opacity = ($opacity !== false) ? min(1, floatval($opacity)) : 1;

		return 'rgba(' . implode(',', $rgb) . ',' . $opacity . ')';
	}

	private static function description_panel($title, $desc, $pro) {
		$pro_html = $pro === true ? '<span class="pro_feature">(pro)</span>' : '';
		$html  = '<div class="wpda_option_description">';
		$html .= '<span class="wpdevart-title">' . esc_html($title) . '</span>';
		$html .= $pro_html;

		if (!empty($desc)) {
			$html .= '<span class="wpdevart-info-container">?<span class="wpdevart-info">' . esc_html($desc) . '</span></span>';
		}

		$html .= '</div>';
		return $html;
	}

	private static function fill_empty_args($args) {
		$defaults = [
			'title'       => '',
			'description' => '',
			'pro'         => false,
		];

		foreach ($defaults as $key => $default) {
			if (!isset($args[$key])) {
				$args[$key] = $default;
			}
		}

		return $args;
	}

	private static function popup_edit_elements($options_html, $results_inner_html = '') {
		$class = 'dashicons dashicons-edit edit_font_button';

		return
			'<span class="' . esc_attr($class) . '">' . esc_html($results_inner_html) . '</span>' .
			'<div class="options_popup">' . $options_html . '</div>';
	}

	public static function fonts_select() {
		$font_choices['Arial,Helvetica Neue,Helvetica,sans-serif'] = 'Arial *';
		$font_choices['Arial Black,Arial Bold,Arial,sans-serif'] = 'Arial Black *';
		$font_choices['Arial Narrow,Arial,Helvetica Neue,Helvetica,sans-serif'] = 'Arial Narrow *';
		$font_choices['Courier,Verdana,sans-serif'] = 'Courier *';
		$font_choices['Georgia,Times New Roman,Times,serif'] = 'Georgia *';
		$font_choices['Times New Roman,Times,Georgia,serif'] = 'Times New Roman *';
		$font_choices['Trebuchet MS,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Arial,sans-serif'] = 'Trebuchet MS *';
		$font_choices['Verdana,sans-serif'] = 'Verdana *';
		$font_choices['American Typewriter,Georgia,serif'] = 'American Typewriter';
		$font_choices['Andale Mono,Consolas,Monaco,Courier,Courier New,Verdana,sans-serif'] = 'Andale Mono';
		$font_choices['Baskerville,Times New Roman,Times,serif'] = 'Baskerville';
		$font_choices['Bookman Old Style,Georgia,Times New Roman,Times,serif'] = 'Bookman Old Style';
		$font_choices['Calibri,Helvetica Neue,Helvetica,Arial,Verdana,sans-serif'] = 'Calibri';
		$font_choices['Cambria,Georgia,Times New Roman,Times,serif'] = 'Cambria';
		$font_choices['Candara,Verdana,sans-serif'] = 'Candara';
		$font_choices['Century Gothic,Apple Gothic,Verdana,sans-serif'] = 'Century Gothic';
		$font_choices['Century Schoolbook,Georgia,Times New Roman,Times,serif'] = 'Century Schoolbook';
		$font_choices['Consolas,Andale Mono,Monaco,Courier,Courier New,Verdana,sans-serif'] = 'Consolas';
		$font_choices['Constantia,Georgia,Times New Roman,Times,serif'] = 'Constantia';
		$font_choices['Corbel,Lucida Grande,Lucida Sans Unicode,Arial,sans-serif'] = 'Corbel';
		$font_choices['Franklin Gothic Medium,Arial,sans-serif'] = 'Franklin Gothic Medium';
		$font_choices['Garamond,Hoefler Text,Times New Roman,Times,serif'] = 'Garamond';
		$font_choices['Gill Sans MT,Gill Sans,Calibri,Trebuchet MS,sans-serif'] = 'Gill Sans MT';
		$font_choices['Helvetica Neue,Helvetica,Arial,sans-serif'] = 'Helvetica Neue';
		$font_choices['Hoefler Text,Garamond,Times New Roman,Times,sans-serif'] = 'Hoefler Text';
		$font_choices['Lucida Bright,Cambria,Georgia,Times New Roman,Times,serif'] = 'Lucida Bright';
		$font_choices['Lucida Grande,Lucida Sans,Lucida Sans Unicode,sans-serif'] = 'Lucida Grande';
		$font_choices['Palatino Linotype,Palatino,Georgia,Times New Roman,Times,serif'] = 'Palatino Linotype';
		$font_choices['Tahoma,Geneva,Verdana,sans-serif'] = 'Tahoma';
		$font_choices['Rockwell, Arial Black, Arial Bold, Arial, sans-serif'] = 'Rockwell';
		$font_choices['Segoe UI'] = 'Segoe UI';
		return $font_choices;
	}

	public static function must_showed_select($selected_values = array()) {
		if (!is_array($selected_values)) {
			$selected_values = array();
		}

		$output_html = '';
		$options_group = array(
			'pages'            => 'Pages',
			'posts'            => 'Posts',
			'categories'       => 'Categories',
			'custom_post_type' => 'custom post type',
			'taxonomy'         => 'taxonomy',
			'other'            => 'other',
			'device'           => 'device',
			'user'             => 'user',
		);

		$options = array();
		add_filter('posts_fields', 'wpda_library::alter_fields_wpse_10888');
		add_filter('pages_fields', 'wpda_library::alter_fields_wpse_10888');

		$options['pages']            = self::get_pages();
		$options['posts']            = self::get_posts();
		$options['categories']       = self::get_categories();
		$options['custom_post_type'] = self::get_custom_post_type();
		$options['taxonomy']         = self::get_taxonomy();
		$options['other']            = self::get_other();
		$options['device']           = self::get_device();
		$options['user']             = self::get_user();

		foreach ($options_group as $group_key => $group_label) {
			$output_html .= '<optgroup label="' . esc_attr($group_label) . '">';

			foreach ($options[$group_key] as $option_value => $option_label) {
				$selected = in_array($option_value, $selected_values, true) ? ' selected="selected"' : '';
				$output_html .= '<option label="' . esc_attr($group_label) . '" value="' . esc_attr($option_value) . '"' . $selected . '>' . esc_html($option_label) . '</option>';
			}

			$output_html .= '</optgroup>';
		}

		return $output_html;
	}


	function alter_fields_wpse_10888($fields) {
		return 'ID,post_title'; // etc
	}

	private static function get_pages() {
		$pages = array();
		$pages_loc = get_pages(
			array(
				'sort_order'	 => 'ASC',
				'sort_column'	 => 'post_title',
				'number'		 => '',
				'post_type'		 => 'page',
				'post_status'	 => 'publish'
			)
		);
		$count = count($pages_loc);
		for ($i = 0; $i < $count; $i++) {
			$pages["page_" . $pages_loc[$i]->ID] = $pages_loc[$i]->post_title;
		}
		return $pages;
	}

	private static function get_posts() {
		$posts = array();
		$posts_loc =  get_posts(
			array(
				'sort_order'	 => 'ASC',
				'sort_column'	 => 'post_title',
				'number'		 => '',
				'post_type'		 => 'post',
				'post_status'	 => 'publish'
			)
		);
		$count = count($posts_loc);
		for ($i = 0; $i < $count; $i++) {
			$posts["post_" . $posts_loc[$i]->ID] = $posts_loc[$i]->post_title;
		}
		return $posts;
	}

	private static function get_categories() {
		$categories = array();
		$categories_loc = get_categories(
			array(
				'hide_empty' => false
			)
		);
		$count = count($categories_loc);
		for ($i = 0; $i < $count; $i++) {
			$categories["category_" . $categories_loc[$i]->cat_ID] = $categories_loc[$i]->cat_name;
		}
		return $categories;
	}

	private static function get_custom_post_type() {
		$custom_post_types = array();
		$custom_post_types_loc = get_post_types(
			array(
				'public' => true
			),
			'objects',
			'and'
		);
		foreach ($custom_post_types_loc as $key => $value) {
			$custom_post_types["custom_post_type_" . $value->name] = $value->label;
		}
		return $custom_post_types;
	}

	private static function get_taxonomy() {
		$taxonomies = array();
		$taxonomies_loc = get_taxonomies(
			array(
				'public' => true
			),
			'objects',
			'and'
		);
		foreach ($taxonomies_loc as $key => $value) {
			$taxonomies["taxonomy_" . $value->name] = $value->label;
		}
		return $taxonomies;
	}

	private static function get_other() {
		return array(
			'front_page'	 => 'Front Page',
			'blog_page'		 => 'Blog Page',
			'single_post'	 => 'Single Posts',
			'sticky_post'	 => 'Sticky Posts',
			'date_archive'	 => 'Date Archive',
			'author_archive' => 'Author Archive',
			'search_page'	 => 'Search Page',
			'404_page'		 => '404 Page'
		);
	}

	private static function get_device() {
		return array(
			'mobile'	 => 'Mobile',
			'desktop'	 => 'Desktop'
		);
	}

	private static function get_user() {
		return array(
			'logged_in'	 => 'Logged in users',
			'logged_out' => 'Logged out users'
		);
	}

	public static function darkest_color($color, $percent) {
		$color = ltrim($color, '#');

		if (strlen($color) !== 6) {
			return '#' . $color;
		}

		$r = hexdec(substr($color, 0, 2));
		$g = hexdec(substr($color, 2, 2));
		$b = hexdec(substr($color, 4, 2));

		$r = max(0, intval($r - ($r * $percent / 100)));
		$g = max(0, intval($g - ($g * $percent / 100)));
		$b = max(0, intval($b - ($b * $percent / 100)));

		return sprintf("#%02x%02x%02x", $r, $g, $b);
	}

	public static function is_plugin_active($plugin) {
		return in_array($plugin, (array) get_option('active_plugins', array())) || self::is_plugin_active_for_network($plugin);
	}

	public static function  is_plugin_active_for_network($plugin) {
		if (!is_multisite()) {
			return false;
		}
		$plugins = get_site_option('active_sitewide_plugins');
		if (isset($plugins[$plugin])) {
			return true;
		}
		return false;
	}

	public static function get_random_animation() {
		$anim_list = self::get_css_animations_list();
		return $anim_list[array_rand($anim_list)];
	}

	public static function get_css_animations_list() {
		return array(
			'bounce',
			'flash',
			'pulse',
			'rubberBand',
			'shake',
			'swing',
			'tada',
			'wobble',
			'bounceIn',
			'bounceInDown',
			'bounceInLeft',
			'bounceInRight',
			'bounceInUp',
			'fadeIn',
			'fadeInDown',
			'fadeInDownBig',
			'fadeInLeft',
			'fadeInLeftBig',
			'fadeInRight',
			'fadeInRightBig',
			'fadeInUp',
			'fadeInUpBig',
			'flip',
			'flipInX',
			'flipInY',
			'lightSpeedIn',
			'rotateIn',
			'rotateInDownLeft',
			'rotateInDownRight',
			'rotateInUpLeft',
			'rotateInUpRight',
			'rollIn',
			'zoomIn',
			'zoomInDown',
			'zoomInLeft',
			'zoomInRight',
			'zoomInUp'
		);
	}

	private static function gradient_directions() {
		return array(
			array('key' => 'none',            'val' => 'Without gradient'),
			array('key' => 'to right',        'val' => 'Right'),
			array('key' => 'to left',         'val' => 'Left'),
			array('key' => 'to bottom',       'val' => 'Bottom'),
			array('key' => 'to top',          'val' => 'Top'),
			array('key' => 'to bottom right', 'val' => 'Bottom Right'),
			array('key' => 'to bottom left',  'val' => 'Bottom Left'),
			array('key' => 'to top right',    'val' => 'Top Right'),
			array('key' => 'to top left',     'val' => 'Top Left'),
		);
	}

	private static function sanitize_color($color) {
		preg_match(
			'/(?:rgb|rgba)[(][ ]{0,100}[0-9]{1,3}[ ]{0,100},[ ]{0,100}[0-9]{1,3}[ ]{0,100},[ ]{0,100}[0-9]{1,3}[ ]{0,100}[,]{0,1}[ ]{0,100}[0-1]{0,1}[.]{0,1}[0-9]{0,9}[)]/',
			$color,
			$matches
		);

		if ($matches) {
			return $color;
		}

		return sanitize_hex_color($color);
	}

	private static function get_simple_input_value($key = '', $args = array()) {
		$parameter = array();
		if (isset($args['metric'])) {
			$parameter['metric_desktop'] = sanitize_text_field($_POST[$key]['metric_desktop']);
			if (isset($args['responsive'])) {
				$parameter['metric_tablet'] = sanitize_text_field($_POST[$key]['metric_tablet']);
				$parameter['metric_mobile'] = sanitize_text_field($_POST[$key]['metric_mobile']);
			}
		}
		$parameter['desktop'] = sanitize_text_field($_POST[$key]['desktop']);
		if (isset($args['responsive'])) {
			$parameter['tablet'] = sanitize_text_field($_POST[$key]['tablet']);
			$parameter['mobile'] = sanitize_text_field($_POST[$key]['mobile']);
		}
		return $parameter;
	}

	private static function get_margin_padding_input_value($key = '', $args  = array()) {
		$parameter = array();
		if (isset($args['metric'])) {
			$parameter['metric_desktop'] = sanitize_text_field($_POST[$key]['metric_desktop']);
			if (isset($args['responsive'])) {
				$parameter['metric_tablet'] = sanitize_text_field($_POST[$key]['metric_tablet']);
				$parameter['metric_mobile'] = sanitize_text_field($_POST[$key]['metric_mobile']);
			}
		}
		$parameter['desktop_top'] = intval($_POST[$key]['desktop_top']);
		$parameter['desktop_right'] = intval($_POST[$key]['desktop_right']);
		$parameter['desktop_bottom'] = intval($_POST[$key]['desktop_bottom']);
		$parameter['desktop_left'] = intval($_POST[$key]['desktop_left']);
		if (isset($args['responsive'])) {
			$parameter['tablet_top'] = intval($_POST[$key]['tablet_top']);
			$parameter['tablet_right'] = intval($_POST[$key]['tablet_right']);
			$parameter['tablet_bottom'] = intval($_POST[$key]['tablet_bottom']);
			$parameter['tablet_left'] = intval($_POST[$key]['tablet_left']);
			$parameter['mobile_top'] = intval($_POST[$key]['mobile_top']);
			$parameter['mobile_right'] = intval($_POST[$key]['mobile_right']);
			$parameter['mobile_bottom'] = intval($_POST[$key]['mobile_bottom']);
			$parameter['mobile_left'] = intval($_POST[$key]['mobile_left']);
		}
		return $parameter;
	}

	private static function get_gradient_color_input_value($key = '') {
		$parameter = array();
		$parameter['gradient'] = sanitize_text_field($_POST[$key]['gradient']);
		$parameter['color1'] = self::sanitize_color($_POST[$key]['color1']);
		$parameter['color2'] = self::sanitize_color($_POST[$key]['color2']);
		return $parameter;
	}

	private static function get_range_input_value($key = '') {
		return sanitize_text_field($_POST[$key]);
	}

	private static function get_radio_value($key = '') {
		return sanitize_text_field($_POST[$key]);
	}

	private static function get_checkbox_value($key = '') {
		if (!isset($_POST[$key]) && is_array($_POST[$key])) {
			return array();
		}
		$parameter = array();
		foreach ($_POST[$key] as $key_checkbox => $value) {
			$parameter[$key_checkbox] = sanitize_text_field($value);
		}
		return $parameter;
	}

	private static function get_color_input_value($key = '') {
		return self::sanitize_color($_POST[$key]);
	}

	private static function get_simple_select_value($key = '') {
		return sanitize_text_field($_POST[$key]);
	}

	public static function get_value_by_name($key = '', $args = array()) {
		if (!isset($args['function_name']) || $key == '' || !isset($_POST[$key])) {
			return NULL;
		}
		if ($args['function_name'] == 'demo_text' || $args['function_name'] == 'demo_border') {
			return NULL;
		}
		if (isset($args['pro']) && $args['pro'] == true) {
			return NULL;
		}
		switch ($args['function_name']) {
			case 'simple_input':
				return self::get_simple_input_value($key, $args);
				break;
			case 'margin_padding_input':
				return self::get_margin_padding_input_value($key, $args);
				break;
			case 'gradient_color_input':
				return self::get_gradient_color_input_value($key);
				break;
			case 'range_input':
				return self::get_range_input_value($key);
				break;
			case 'radio':
				return self::get_radio_value($key);
				break;
			case 'checkbox':
				return self::get_checkbox_value($key);
				break;
			case 'color_input':
				return self::get_color_input_value($key);
				break;
			case 'simple_select':
				return self::get_simple_select_value($key);
				break;
			default:
				if (!isset($_POST[$key])) {
					return '';
				}
				if (is_array($_POST[$key])) {
					$param = array();
					foreach ($_POST[$key] as $post_key => $value) {
						$param[$post_key] = sanitize_text_field($value);
					}
					return $param;
				}
				return sanitize_text_field($_POST[$key]);
		}
	}
}
