<?php

defined('ABSPATH') || exit;

class wpda_org_chart_tree_theme_page {

	public static $notification_html = '';

	private static $id = 0;

	private static $task = '';

	public static $options = array();

	private static $page_id = 'wpda_org_chart_theme_id';

	public static function initial_task() {
		self::$task = isset($_REQUEST['task']) ? sanitize_text_field($_REQUEST['task']) : '';
		self::$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	}

	public static function initial_options() {
		self::$options = array(
			"org_chart_theme_general" => array(
				"heading_name" => "General Settings",
				"params" => array(
					"mobile_frendly" => array(
						"title" => "Responsive",
						"description" => "How to display the chart, of the organization chart is bigger than the container. Set the  Mobile View & Horizontal option, if you need to display the mobile view only on mobile devices and the horizontal scrolling view on devices with a bigger resolution.",
						"values" => array("add_scrolll" => "Horizontal scroll ", "mobile" => "Mobile view", "mob_view_only_on_mob" => "Mobile View & Horizontal scroll"),
						"default_value" => "mobile",
						"function_name" => "radio",
					),
					"toggleable_element" => array(
						"title" => "Hide elements after",
						"description" => "Make toggleable elements that have children.",
						"values" => array("hide_all" => "All togable", "second" => "After second", "thirth" => "After thirth", "disable" => "Disable"),
						"default_value" => "disable",
						"function_name" => "radio",
						"pro"=>true,
					),
					"search_bar" => array(
						"title" => "Search elements",
						"description" => "when you enable this its add search bar in your chart",
						"values" => array("enable" => "Enable", "disable" => "Disable"),
						"default_value" => "disable",
						"function_name" => "radio",
						"pro"=>true,
					),
					"highligth_color" => array(
						"title" => "Search highlith background color",
						"description" => "Select search highlith background color",
						"default_value" => "#dbe908",
						"function_name" => "color_input",
						"show_when" => array('search_bar' => '!=disable'),
						"pro"=>true,
					),
					"search_placeholder" => array(
						"title" => "Search Placeholder",
						"description" => "type text, which will show on screen input when its empty.",
						"default_value" => array('desktop' => 'search by title or description'),
						"function_name" => "simple_input",
						"size" => 25,
						"show_when" => array('search_bar' => '!=disable'),
						"pro"=>true,
					),
					"scroll_position" => array(
						"title" => "Scroll position",
						"description" => "Type the horizontal scroll position",
						"default_value" => "0",
						"show_val" => true,
						"min_value" => '0',
						"max_value" => '100',
						"small_text" => '%',
						"function_name" => "range_input",
					),
					"background_color" => array(
						"title" => "Background Color",
						"description" => "Set the container background color.",
						"values" => array("color1" => "", "color2" => "", "gradient" => "0"),
						"default_value" => array("color1" => "rgba(255,255,255,0)", "color2" => "rgba(255,255,255,0)", "gradient" => "none"),
						"function_name" => "gradient_color_input",
						"transparent" => true,
					),					
					"button_theme" => array(
						"title" => "Buttons theme",
						"description" => "Select the button theme",
						"function_name" => "simple_select",
						"values" => array(
							"light" => "Light",
							"dark" => "Dark",
						),
						"default_value" => "light",
						"pro"=>true,
					),
					"general_border_popup" => array(
						"title" => "Item Border",
						"description" => "Select item border",
						"function_name" => "popup",
						"params" => array(
							"border_type" => array(
								"title" => "Item Border Type",
								"description" => "Select border type",
								"function_name" => "simple_select",
								"preview" => array('id' => 'general_border_demo', 'action' => 'border-style'),
								"values" => array("solid" => "Solid", "dotted" => "Dotted", "dashed" => "Dashed", "double" => "Double", "groove" => "Groove", "ridge" => "Ridge", "inset" => "Inset", "outset" => "Outset"),
								"default_value" => "solid",
							),
							"border_color" => array(
								"title" => "Item Border Color",
								"description" => "Select item border color",
								"default_value" => "#cccccc",
								"preview" => array('id' => 'general_border_demo', 'action' => 'border-color'),
								"function_name" => "color_input",
							),
							"border_width" => array(
								"title" => "Border Width",
								"description" => "Select border width",
								"default_value" => array('desktop' => '0', 'metric_desktop' => 'px'),
								"metric" => array("px"),
								"preview" => array('id' => 'general_border_demo', 'action' => 'border-width'),
								"function_name" => "simple_input",
							),
							"border_radius" => array(
								"title" => "Border Radius",
								"description" => "Select border radius",
								"default_value" => array('desktop' => '0', 'metric_desktop' => 'px'),
								"metric" => array("px", "%"),
								"preview" => array('id' => 'general_border_demo', 'action' => 'border-radius'),
								"function_name" => "simple_input",
							),
						)
					),
					"general_border_demo" => array(
						"title" => "Border preview",
						"function_name" => "demo_border",
					),
					
					"padding" => array(
						"title" => "Padding",
						"description" => "Type Padding <span style=\"color:red\">important when u enabled search elemment or zoom buttons set pading top for mobile and tablet</span>",
						"default_value" => array(
							'desktop_top' => '0',
							'desktop_right' => '0',
							'desktop_bottom' => '0',
							'desktop_left' => '0',
							'tablet_top' => '',
							'tablet_right' => '',
							'tablet_bottom' => '',
							'tablet_left' => '',
							'mobile_top' => '',
							'mobile_right' => '',
							'mobile_bottom' => '',
							'mobile_left' => '',
							'metric_desktop' => 'px',
							'metric_tablet' => 'px',
							'metric_mobile' => 'px',
						),
						"metric" => array("px"),
						"function_name" => "margin_padding_input",
						"responsive" => true,
					),

				)
			),
			"drag_and_zoom" => array(
				"heading_name" => "Drag and Zoom",
				"pro"=>true,
				"params" => array(
					"zoomable" => array(
						"title" => "Zoomable Chart",
						"description" => "Choose this option if you need to enable the zoom option for the chart.",
						"values" => array("enable_shift" => "Mousewheel + Shift", "enable" => "Mousewheel", "disable" => "Disable"),
						"default_value" => "disable",
						"function_name" => "radio",
						"pro"=>true,
					),
					"draggable" => array(
						"title" => "Draggable Chart",
						"description" => "Choose this option if you need to enable the drag option for the chart.",
						"values" => array("enable" => "Draggable", "horizontal" => "Only horizontal", "vertical" => "Only vertical", "disable" => "Disable"),
						"default_value" => "disable",
						"function_name" => "radio",
						"pro"=>true,
					),
					"zoomable_buttons" => array(
						"title" => "Control buttons",
						"description" => "Zoomable button",
						"values" => array("zoom_in" => "Zoom in", "zoom_out" => "Zoom out", "zoom_reset" => "Zoom reset", "fullscreen" => "Full Screen"),
						"default_value" => array(),
						"function_name" => "checkbox",
						"pro"=>true,
					),
					"zoom_in_text" => array(
						"title" => "Zoom in button text",
						"description" => "Type here the Zoom in button text.",
						"default_value" => array('desktop' => '+'),
						"function_name" => "simple_input",
						"size" => 15,
						"pro"=>true,
					),
					"zoom_out_text" => array(
						"title" => "Zoom out button text",
						"description" => "Type here the Zoom out button text.",
						"default_value" => array('desktop' => '-'),
						"function_name" => "simple_input",
						"size" => 15,
						"pro"=>true,
					),
					"zoom_reset_text" => array(
						"title" => "Zoom reset button text",
						"description" => "Type here the Zoom reset button text.",
						"default_value" => array('desktop' => 'Reset'),
						"function_name" => "simple_input",
						"size" => 15,
						"pro"=>true,
					),
					"zoom_fullscreen_text" => array(
						"title" => "Full screen button text",
						"description" => "Type here the full screen button text.",
						"default_value" => array('desktop' => 'Full screen'),
						"function_name" => "simple_input",
						"size" => 15,
						"pro"=>true,
					),
					"zoom_outfullscreen_text" => array(
						"title" => "Close button text",
						"description" => "Type here the close button text.",
						"default_value" => array('desktop' => 'Exit Full screen'),
						"function_name" => "simple_input",
						"size" => 15,
						"pro"=>true,
					),
					"zoom_default" => array(
						"title" => "Default zoom value",
						"description" => "Type here zoom value which will apply when you open the chart for the first time.",
						"default_value" => "100",
						"show_val" => true,
						"min_value" => '5',
						"max_value" => '200',
						"small_text" => '(%)',
						"function_name" => "range_input",
						"pro"=>true,
					),
					"max_zoomable" => array(
						"title" => "Maximum Zoom",
						"description" => "Choose the maximum zoom value.",
						"default_value" => "1",
						"show_val" => true,
						"min_value" => '1',
						"max_value" => '10',
						"small_text" => '(time)',
						"function_name" => "range_input",
						"pro"=>true,
					),
					"min_zoomable" => array(
						"title" => "Maximum Zoom-out",
						"description" => "Choose the maximum zoom-out value.",
						"default_value" => "10",
						"show_val" => true,
						"min_value" => '1',
						"max_value" => '20',
						"small_text" => '(time)',
						"function_name" => "range_input",
						"pro"=>true,
					),
					"zoom_speed" => array(
						"title" => "Zoom speed",
						"description" => "Choose the zoom speed.",
						"default_value" => "10",
						"show_val" => true,
						"min_value" => '1',
						"max_value" => '100',
						"small_text" => '(%)',
						"function_name" => "range_input",
						"pro"=>true,
					)
				),
			),
			"line_css" => array(
				"heading_name" => "Line style",
				"params" => array(
					"line_color" => array(
						"title" => "Set the line color.",
						"description" => "Select line color",
						"default_value" => "#cccccc",
						"function_name" => "color_input",
					),

					"line_height" => array(
						"title" => "Line Height",
						"description" => "Type the line height.",
						"default_value" => array('desktop' => '1', 'metric_desktop' => 'px'),
						"function_name" => "simple_input",
						"metric" => array("px"),
					),


				),
			),
			"items_css" => array(
				"heading_name" => "Item style",
				"pro"=>true,
				"params" => array(
					"item_bg_color" => array(
						"title" => "Background Color",
						"description" => "Select item background color",
						"values" => array("color1" => "", "color2" => "", "gradient" => "0"),
						"default_value" => array("color1" => "#ffffff", "color2" => "#ffffff", "gradient" => "none"),
						"function_name" => "gradient_color_input",
						"pro"=>true,
					),
					"item_min_width" => array(
						"title" => "Minimum Width",
						"description" => "Type minimum Width",
						"default_value" => array('desktop' => '120', 'tablet' => '', 'mobile' => '', 'metric_desktop' => 'px'),
						"metric" => array("px"),
						"responsive" => true,
						"function_name" => "simple_input",
						"pro"=>true,
					),
					"item_min_height" => array(
						"title" => "Minimum Height",
						"description" => "Type minimum Height",
						"default_value" => array('desktop' => '130', 'tablet' => '', 'mobile' => '', 'metric_desktop' => 'px'),
						"metric" => array("px", '%'),
						"responsive" => true,
						"function_name" => "simple_input",
						"pro"=>true,
					),
					"item_max_width" => array(
						"title" => "Maximum Width",
						"description" => "Type maximum Width",
						"default_value" => array('desktop' => '200', 'tablet' => '', 'mobile' => '', 'metric_desktop' => 'px'),
						"metric" => array("px"),
						"responsive" => true,
						"function_name" => "simple_input",
						"pro"=>true,
					),
					"item_img_max_width" => array(
						"title" => "Image width",
						"description" => "Type image width",
						"default_value" => array('desktop' => '120', 'metric_desktop' => 'px'),
						"metric" => array("px", '%'),
						"function_name" => "simple_input",
						"pro"=>true,
					),
					"item_img_max_height" => array(
						"title" => "Image height",
						"description" => "Type image height",
						"default_value" => array('desktop' => '130', 'metric_desktop' => 'px'),
						"metric" => array("px", '%'),
						"function_name" => "simple_input",
						"pro"=>true,
					),
					"item_img_border_radius" => array(
						"title" => "Image Border Radius",
						"description" => "Type image border radius",
						"default_value" => array('desktop' => '0', 'metric_desktop' => 'px'),
						"metric" => array("px", "%"),
						"preview" => array('id' => 'item_border_demo', 'action' => 'border-radius'),
						"function_name" => "simple_input",
						"pro"=>true,
					),
					"item_img_margin" => array(
						"title" => "Image Margin",
						"description" => "Type image margin",
						"default_value" => array(
							'desktop_top' => '0',
							'desktop_right' => '0',
							'desktop_bottom' => '0',
							'desktop_left' => '0',
							'tablet_top' => '',
							'tablet_right' => '',
							'tablet_bottom' => '',
							'tablet_left' => '',
							'mobile_top' => '',
							'mobile_right' => '',
							'mobile_bottom' => '',
							'mobile_left' => '',
							'metric_desktop' => 'px',
							'metric_tablet' => 'px',
							'metric_mobile' => 'px',
						),
						"metric" => array("px", '%'),
						"function_name" => "margin_padding_input",
						"responsive" => true,
						"pro"=>true,
					),
					"item_title_font_popup" => array(
						"title" => "Title font",
						"description" => "Configure the title font style.",
						"function_name" => "popup",
						"pro"=>true,
						"params" => array(
							"item_title_font_family" => array(
								"title" => "Font Family",
								"description" => "Select font family",
								"function_name" => "simple_select",
								"values" => wpda_org_chart_library::fonts_select(),
								"default_value" => "serif",
								"preview" => array('id' => 'item_title_demo_text', 'action' => 'font-family'),								
							),
							"item_title_color" => array(
								"title" => "Color",
								"description" => "Select color",
								"default_value" => "#000000",
								"function_name" => "color_input",
								"preview" => array('id' => 'item_title_demo_text', 'action' => 'color'),
							),
							"item_title_font_size" => array(
								"title" => "Font Size",
								"description" => "Font size:",
								"default_value" => array('desktop' => '14', 'metric_desktop' => 'px'),
								"function_name" => "simple_input",
								"responsive" => true,
								"metric" => array("px", "em", "rem", "vw"),
								"preview" => array('id' => 'item_title_demo_text', 'action' => 'font-size'),
							),
							"item_title_line_height" => array(
								"title" => "Line Height",
								"description" => "Line height",
								"default_value" => array('desktop' => 'normal', 'metric_desktop' => 'px'),
								"function_name" => "simple_input",
								"metric" => array("px", "em", "rem", "vw"),
								"preview" => array('id' => 'item_title_demo_text', 'action' => 'line-height'),
							),
							"item_title_letter_spacing" => array(
								"title" => "Letter Spacing",
								"description" => "Letter spacing",
								"default_value" => array('desktop' => 'normal', 'metric_desktop' => 'px'),
								"function_name" => "simple_input",
								"metric" => array("px", "em", "rem", "vw"),
								"preview" => array('id' => 'item_title_demo_text', 'action' => 'letter-spacing'),
							),
							"item_title_font_weight" => array(
								"title" => "Font Weight",
								"description" => "Select font weight",
								"function_name" => "simple_select",
								"values" => array('initial' => 'Initial', '100' => '100', '200' => '200', '300' => '300', '400' => '400', '500' => '500', '600' => '600', '700' => '700', '800' => '800', '900' => '900', 'normal' => 'Normal', 'bold' => 'Bold',),
								"default_value" => "initial",
								"preview" => array('id' => 'item_title_demo_text', 'action' => 'font-weight'),
							),
							"item_title_font_style" => array(
								"title" => "Font Style",
								"description" => "Select font style",
								"function_name" => "simple_select",
								"values" => array('initial' => 'Initial', 'normal' => 'Normal', 'italic' => 'Italic', 'oblique' => 'Oblique'),
								"default_value" => "initial",
								"preview" => array('id' => 'item_title_demo_text', 'action' => 'font-style'),
							),
						)
					),
					"item_title_demo_text" => array(
						"title" => "ABCDEFGHIK LMNOPQRSTVXYZ abcdefghik lmnopqrstvxyz",
						"function_name" => "demo_text",
					),
					"item_title_margin" => array(
						"title" => "Title Margin",
						"description" => "Type title margin",
						"pro"=>true,
						"default_value" => array(
							'desktop_top' => '0',
							'desktop_right' => '0',
							'desktop_bottom' => '0',
							'desktop_left' => '0',
							'tablet_top' => '',
							'tablet_right' => '',
							'tablet_bottom' => '',
							'tablet_left' => '',
							'mobile_top' => '',
							'mobile_right' => '',
							'mobile_bottom' => '',
							'mobile_left' => '',
							'metric_desktop' => 'px',
							'metric_tablet' => 'px',
							'metric_mobile' => 'px',
						),
						"metric" => array("px", '%'),
						"function_name" => "margin_padding_input",
						"responsive" => true,
					),
					"item_description_font_popup" => array(
						"title" => "Description font",
						"description" => "Configure the description font style",
						"function_name" => "popup",
						"pro"=>true,
						"params" => array(
							"item_description_font_family" => array(
								"title" => "Font Family",
								"description" => "Select font family",
								"function_name" => "simple_select",
								"values" => wpda_org_chart_library::fonts_select(),
								"default_value" => "serif",
								"preview" => array('id' => 'item_description_demo_text', 'action' => 'font-family'),
							),
							"item_description_color" => array(
								"title" => "Color",
								"description" => "Select clor",
								"default_value" => "#000000",
								"function_name" => "color_input",
								"preview" => array('id' => 'item_description_demo_text', 'action' => 'color'),
							),
							"item_description_font_size" => array(
								"title" => "Font Size",
								"description" => "Font size",
								"default_value" => array('desktop' => '14', 'metric_desktop' => 'px'),
								"function_name" => "simple_input",
								"responsive" => true,
								"metric" => array("px", "em", "rem", "vw"),
								"preview" => array('id' => 'item_description_demo_text', 'action' => 'font-size'),
							),
							"item_description_line_height" => array(
								"title" => "Line Height",
								"description" => "Line height",
								"default_value" => array('desktop' => 'normal', 'metric_desktop' => 'px'),
								"function_name" => "simple_input",
								"metric" => array("px", "em", "rem", "vw"),
								"preview" => array('id' => 'item_description_demo_text', 'action' => 'line-height'),
							),
							"item_description_letter_spacing" => array(
								"title" => "Letter Spacing",
								"description" => "Letter spacing",
								"default_value" => array('desktop' => 'normal', 'metric_desktop' => 'px'),
								"function_name" => "simple_input",
								"metric" => array("px", "em", "rem", "vw"),
								"preview" => array('id' => 'item_description_demo_text', 'action' => 'letter-spacing'),
							),
							"item_description_font_weight" => array(
								"title" => "Font Weight",
								"description" => "Select font weight",
								"function_name" => "simple_select",
								"values" => array('initial' => 'Initial', '100' => '100', '200' => '200', '300' => '300', '400' => '400', '500' => '500', '600' => '600', '700' => '700', '800' => '800', '900' => '900', 'normal' => 'Normal', 'bold' => 'Bold',),
								"default_value" => "initial",
								"preview" => array('id' => 'item_description_demo_text', 'action' => 'font-weight'),
							),
							"item_description_font_style" => array(
								"title" => "Font Style",
								"description" => "Select font style",
								"function_name" => "simple_select",
								"values" => array('initial' => 'Initial', 'normal' => 'Normal', 'italic' => 'Italic', 'oblique' => 'Oblique'),
								"default_value" => "initial",
								"preview" => array('id' => 'item_description_demo_text', 'action' => 'font-style'),
							),
						),
					),
					"item_description_demo_text" => array(
						"title" => "DABCDEFGHIK LMNOPQRSTVXYZ abcdefghik lmnopqrstvxyz",
						"function_name" => "demo_text",
					),
					"item_description_margin" => array(
						"title" => "Description margin",
						"description" => "Type description margin",
						"pro"=>true,
						"default_value" => array(
							'desktop_top' => '0',
							'desktop_right' => '0',
							'desktop_bottom' => '0',
							'desktop_left' => '0',
							'tablet_top' => '',
							'tablet_right' => '',
							'tablet_bottom' => '',
							'tablet_left' => '',
							'mobile_top' => '',
							'mobile_right' => '',
							'mobile_bottom' => '',
							'mobile_left' => '',
							'metric_desktop' => 'px',
							'metric_tablet' => 'px',
							'metric_mobile' => 'px',
						),
						"metric" => array("px", '%'),
						"function_name" => "margin_padding_input",
						"responsive" => true,
					),
					"item_border_popup" => array(
						"title" => "Item Border",
						"description" => "Select item border",
						"function_name" => "popup",
						"pro"=>true,
						"params" => array(
							"item_border_type" => array(
								"title" => "Item Border Type",
								"description" => "Select border type",
								"function_name" => "simple_select",
								"preview" => array('id' => 'item_border_demo', 'action' => 'border-style'),
								"values" => array("solid" => "Solid", "dotted" => "Dotted", "dashed" => "Dashed", "double" => "Double", "groove" => "Groove", "ridge" => "Ridge", "inset" => "Inset", "outset" => "Outset"),
								"default_value" => "solid",								
							),
							"item_border_color" => array(
								"title" => "Item Border Color",
								"description" => "Select item border color",
								"default_value" => "#cccccc",
								"preview" => array('id' => 'item_border_demo', 'action' => 'border-color'),
								"function_name" => "color_input",								
							),
							"item_border_width" => array(
								"title" => "Border Width",
								"description" => "Select border width",
								"default_value" => array('desktop' => '1', 'metric_desktop' => 'px'),
								"metric" => array("px"),
								"preview" => array('id' => 'item_border_demo', 'action' => 'border-width'),
								"function_name" => "simple_input",								
							),
							"item_border_radius" => array(
								"title" => "Border Radius",
								"description" => "Select border radius",
								"default_value" => array('desktop' => '0', 'metric_desktop' => 'px'),
								"metric" => array("px", "%"),
								"preview" => array('id' => 'item_border_demo', 'action' => 'border-radius'),
								"function_name" => "simple_input",								
							),
						)
					),
					"item_border_demo" => array(
						"title" => "Border preview",
						"function_name" => "demo_border",
					),
				),
			),
		);
	}

	public static function render_theme() {
		global $wpdb;
		self::print_notifications();
		switch (self::$task) {
			case 'add_edit_theme':
			case 'update_theme':			
				self::add_edit_theme();
				break;
			default:
				self::display_table_list();
		}
	}

	public static function database_actions() {
		switch (self::$task) {
			case 'save_theme':
			case 'update_theme':
				if (self::$id) {
					self::update_theme();
				} else {
					self::save_theme();
				}
				break;
			case 'remove_theme':
				self::remove_theme();
				break;
			case 'duplicate_theme':
				self::duplicate_theme();
				break;
			case 'set_default_theme':
				self::set_default_theme();
				break;
		}
	}

	private static function display_table_list() {
		$params = array(
			'name' => 'Theme',
			'add_new_link' => 'admin.php?page=wpda_chart_tree_themes&task=add_edit_theme',
			'support_link' => wpda_org_chart_support_url,
		); // params used in admin-page-task-list-header.php' file
		include wpda_org_chart_plugin_path . 'library/base-templates/admin-page-task-list-header.php';
	}

	private static function add_edit_theme() {
		$name = self::generate_theme_parameters();
		$params = array(
			'current_page_link' => 'admin.php?page=wpda_chart_tree_themes',
			'support_link' => wpda_org_chart_support_url,
			'plugin_url' => wpda_org_chart_plugin_url,
			'options' => self::$options,
			'id' => self::$id,
		);
		include wpda_org_chart_plugin_path . 'library/base-templates/admin-page-task-add-edit-header.php';
		include wpda_org_chart_plugin_path . 'admin/theme-page/add-edit-theme-template.php';
	}

	/*############  Save function  ################*/
	private static function save_theme() {
		if (count($_POST) == 0){
			return;
		}			
		global $wpdb;		
		$params_array = array();
		$name = "Theme";
		if (isset($_POST['name'])) {
			$name = sanitize_text_field($_POST['name']);
		}
		$params_array = array('name' => $name);
		foreach (self::$options as $param_heading_value) {
			foreach ($param_heading_value['params'] as $key => $value) {
				if ($value['function_name'] == 'demo_text' || $value['function_name'] == 'demo_border') {
					continue;
				}
				if (isset($value['params'])) {
					foreach ($value['params'] as $ins_key => $ins_value) {
						$params_array[$ins_key] = wpda_org_chart_library::get_value_by_name($ins_key, $ins_value);
					}
				} else {
					$params_array[$key] = wpda_org_chart_library::get_value_by_name($key, $value);
				}
			}
		}
		$save_or_no = $wpdb->insert(
			wpda_org_chart_database::$table_names['theme'],
			array(
				'name' => $name,
				'option_value' => json_encode($params_array),
			),
			array(
				'%s',
				'%s',
			)
		);
		self::$id = $wpdb->get_var("SELECT MAX(id) FROM " . wpda_org_chart_database::$table_names['theme']);
		wpda_org_chart_user_permissions_library::set_id_to_meta_key(self::$id, self::$page_id);
		if ($save_or_no) {
			self::$notification_html = '<div class="updated"><p><strong>Item Saved</strong></p></div>';
		} else {
			self::$notification_html = '<div id="message" class="error"><p>Error please reinstall plugin</p></div>';
		}
	}

	/*############  Update theme ID function  ################*/

	private static function update_theme() {
		if (count($_POST) == 0){
			return;
		}			
		global $wpdb;		
		$params_array = array();
		$name = "Theme";
		if (isset($_POST['name'])) {
			$name = sanitize_text_field($_POST['name']);
		}
		$params_array = array('name' => sanitize_text_field($name));
		foreach (self::$options as $param_heading_value) {
			foreach ($param_heading_value['params'] as $key => $value) {
				if ($value['function_name'] == 'demo_text' || $value['function_name'] == 'demo_border') {
					continue;
				}
				if (isset($value['params'])) {
					foreach ($value['params'] as $ins_key => $ins_value) {
						$params_array[$ins_key] = wpda_org_chart_library::get_value_by_name($ins_key, $ins_value);
					}
				} else {
					$params_array[$key] = wpda_org_chart_library::get_value_by_name($key, $value);
				}
			}
		}
		$wpdb->update(
			wpda_org_chart_database::$table_names['theme'],
			array(
				'name' => $name,
				'option_value' => json_encode($params_array),
			),
			array(
				'id' => self::$id
			),
			array(
				'%s',
				'%s'
			),
			array(
				'%d'
			)
		);
		self::$notification_html = '<div class="updated"><p><strong>Item Saved</strong></p></div>';
	}

	private static function remove_theme() {
		global $wpdb;
		$default_theme = $wpdb->get_var($wpdb->prepare('SELECT `default` FROM ' . wpda_org_chart_database::$table_names['theme'] . ' WHERE id="%d"', self::$id));
		if (!$default_theme) {
			$wpdb->query($wpdb->prepare('DELETE FROM ' . wpda_org_chart_database::$table_names['theme'] . ' WHERE id="%d"', self::$id));
			wpda_org_chart_user_permissions_library::remove_id_from_meta_key(self::$id, self::$page_id);
			self::$notification_html = '<div class="updated"><p><strong>Theme removed</strong></p></div>';
		} else {
			self::$notification_html = '<div id="message" class="error"><p>You cannot remove default theme</p></div>';
		}
	}

	private static function duplicate_theme() {
		global $wpdb;
		$wpdb->query($wpdb->prepare('INSERT INTO ' . wpda_org_chart_database::$table_names['theme'] . ' ( `name`, `option_value`, `default` ) SELECT CONCAT(`name`,"(duplicate)"), `option_value`, 0 FROM ' . wpda_org_chart_database::$table_names['theme'] . ' WHERE id="%d"', self::$id));
		$local_id = $wpdb->get_var("SELECT MAX(id) FROM " . wpda_org_chart_database::$table_names['theme']);
		wpda_org_chart_user_permissions_library::set_id_to_meta_key($local_id, self::$page_id);
		self::$notification_html = '<div class="updated"><p><strong>Item Duplicated</strong></p></div>';
	}

	private static function generate_theme_parameters() {
		global $wpdb;
		$theme_params = NULL;
		$new_theme = 1;
		if (self::$id) {
			$theme_params = $wpdb->get_row('SELECT * FROM ' . wpda_org_chart_database::$table_names['theme'] . ' WHERE id=' . self::$id);
			$new_theme = 0;
		} else {
			$theme_params = $wpdb->get_row('SELECT * FROM ' . wpda_org_chart_database::$table_names['theme'] . ' WHERE `default`=1');
		}
		if ($theme_params == NULL) {
			foreach (self::$options as $param_heading_key => $param_heading_value) {
				foreach ($param_heading_value['params'] as $key => $value) {
					self::$options[$param_heading_key]['params'][$key]["value"] = isset(self::$options[$param_heading_key]['params'][$key]["default_value"]) ? self::$options[$param_heading_key]['params'][$key]["default_value"] : '';
				}
			}
		} else {
			$databases_parameters = json_decode($theme_params->option_value, true);
			foreach (self::$options as $param_heading_key => $param_heading_value) {
				foreach ($param_heading_value['params'] as $key => $value) {
					if (self::$options[$param_heading_key]['params'][$key]["function_name"] == 'demo_text' || self::$options[$param_heading_key]['params'][$key]["function_name"] == 'demo_border') {
						continue;
					}
					if (self::$options[$param_heading_key]['params'][$key]["function_name"] == 'popup') {
						foreach (self::$options[$param_heading_key]['params'][$key]["params"] as $ins_key => $ins_value) {
							if (isset($databases_parameters[$ins_key])) {
								self::$options[$param_heading_key]['params'][$key]['params'][$ins_key]["value"] = $databases_parameters[$ins_key];
							} else {
								self::$options[$param_heading_key]['params'][$key]['params'][$ins_key]["value"] = self::$options[$param_heading_key]['params'][$key]['params'][$ins_key]["default_value"];
							}
						}
					} else {
						if (isset($databases_parameters[$key])) {
							self::$options[$param_heading_key]['params'][$key]["value"] = $databases_parameters[$key];
						} else {
							self::$options[$param_heading_key]['params'][$key]["value"] = self::$options[$param_heading_key]['params'][$key]["default_value"];
						}
					}
				}
			}
			if ($new_theme) {
				return "New Theme";
			} else {
				return $theme_params->name;
			}
		}
	}	

	private static function set_default_theme() {
		global $wpdb;
		$wpdb->update(wpda_org_chart_database::$table_names['theme'], array('default' => 0), array('default' => 1));
		$wpdb->update(wpda_org_chart_database::$table_names['theme'], array('default' => 1), array('id' => self::$id));
	}

	private static function print_notifications() {
		if (self::$notification_html != '') {
			echo self::$notification_html;
			self::$notification_html = '';
		}
	}

	private static function get_table_info() {
		return array(
			'keys' => array(
				'id' => array('name' => 'ID', 'sortable' => true),
				'name' => array('name' => 'Name', 'link' => '&task=add_edit_theme', 'sortable' => true),
				'default' => array('name' => 'Default', 'link' => '&task=set_default_theme', 'replace_value' => array('0' => '<img src = "' . wpda_org_chart_plugin_url . 'admin/assets/images/default0.png">', '1' => '<img src = "' . wpda_org_chart_plugin_url . 'admin/assets/images/default1.png">')),
				'edit' => array('name' => 'Edit', 'link' => '&task=add_edit_theme'),
				'duplicate' => array('name' => 'Duplicate', 'link' => '&task=duplicate_theme'),
				'delete' => array('name' => 'Delete', 'link' => '&task=remove_theme')
			),
			'link_page' => 'wpda_chart_tree_themes',
		);
	}

	// helper functions
	public static function get_row_list() {
		global $wpdb;
		$query = "SELECT `id`,`name`,`default` FROM " . wpda_org_chart_database::$table_names['theme'];
		$row_list = $wpdb->get_results($query);
		return self::filter_by_user($row_list);
	}

	//filter rows by user
	private static function filter_by_user($rows) {
		if (current_user_can('manage_option')) {
			return $rows;
		}
		$allowed_by_other_user = wpda_org_chart_user_permissions::get_option_value('chart_theme_page_allow_other_users');
		if ($allowed_by_other_user == 'yes') {
			return $rows;
		}
		$filtered_rows = array();
		foreach ($rows as $row) {
			if (wpda_org_chart_user_permissions_library::can_current_user_edit_element(self::$page_id, $row->id)) {
				$filtered_rows[] = $row;
			}
		}
		return $filtered_rows;
	}

	public static function enqueue_scripts_styles() {
		wp_enqueue_style('wpda_chart_theme_page_css', wpda_org_chart_plugin_url . 'admin/assets/css/theme_page.css');
		switch (self::$task) {
			case 'add_edit_theme':
			case 'update_theme':
				wp_enqueue_style("wpda_admin_page_task_add_edit_header", wpda_org_chart_plugin_url . 'library/css/admin-page-task-add-edit-header.css');
				wp_enqueue_script('wp-color-picker');
				wp_enqueue_script("wpda_chart_theme_page_js", wpda_org_chart_plugin_url . 'admin/assets/js/theme_page.js');
				wp_enqueue_script('wpda_library_admin_js', wpda_org_chart_plugin_url . 'library/js/admin.js', array('jquery-ui-draggable'));
				wp_enqueue_style('wpda_library_admin_css', wpda_org_chart_plugin_url . 'library/css/admin.css');
				wp_localize_script('alpha-color-picker', 'wpColorPickerL10n', array(
					'clear' => 'Clear',
					'clearAriaLabel' => 'Clear color',
					'defaultString' => 'Default',
					'defaultAriaLabel' => 'Select default color',
					'pick' => 'Select Color',
					'defaultLabel' => 'Color value',
				));
				wp_enqueue_script('alpha-color-picker');
				wp_enqueue_style('wp-color-picker');
				if (function_exists('wp_enqueue_media')) {
					wp_enqueue_media();
				}
				if (function_exists('wp_enqueue_editor')) {
					wp_enqueue_editor();
				}
				break;
			default:
				wp_enqueue_style("wpda_admin_page_task_list_header", wpda_org_chart_plugin_url . 'library/css/admin-page-task-list-header.css');
				wp_enqueue_script("wpda_chart_theme_page_list_js", wpda_org_chart_plugin_url . 'admin/assets/js/wpda_table_maker.js');
				wp_localize_script("wpda_chart_theme_page_list_js", 'wpdaPageRowsList', self::get_row_list());
				wp_localize_script("wpda_chart_theme_page_list_js", 'wpdaPageRowsInfo', self::get_table_info());
				wp_enqueue_style("wpda_chart_theme_page_list_css", wpda_org_chart_plugin_url . 'admin/assets/css/wpda_table_maker.css');
		}
	}
}
