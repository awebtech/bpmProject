<?php

  /**
  * Render form label element
  *
  * @param void
  * @return null
  */
  function label_tag($text, $for = null, $is_required = false, $attributes = null, $after_label = ':') {
    if(trim($for)) {
      if(is_array($attributes)) {
        $attributes['for'] = trim($for);
      } else {
        $attributes = array('for' => trim($for));
      } // if
    } // if
    
    $render_text = trim($text) . $after_label;
    if($is_required) $render_text .= ' <span class="label_required">*</span>';
    
    return open_html_tag('label', $attributes) . $render_text . close_html_tag('label');
  } // form_label

  /**
  * Render input field
  *
  * @access public
  * @param string $name Field name
  * @param mixed $value Field value. Default is NULL
  * @param array $attributes Additional field attributes
  * @return null
  */
  function input_field($name, $value = null, $attributes = null) {
    $field_attributes = is_array($attributes) ? $attributes : array();
    
    $field_attributes['name'] = $name;
    $field_attributes['value'] = $value;
    
    return open_html_tag('input', $field_attributes, true);
  } // input_field
  
  /**
  * Render text field
  *
  * @access public
  * @param string $name
  * @param mixed $value
  * @param array $attributes Array of additional attributes
  * @return string
  */
  function text_field($name, $value = null, $attributes = null) {
    
    // If we don't have type attribute set it
    if(array_var($attributes, 'type', false) === false) {
      if(is_array($attributes)) {
        $attributes['type'] = 'text';
      } else {
        $attributes = array('type' => 'text');
      } // if
    } // if
    
    // And done!
    return input_field($name, $value, $attributes);
    
  } // text_field
  
  /**
  * Return password field
  *
  * @access public
  * @param string $name
  * @param mixed $value
  * @param array $attributes
  * @return string
  */
  function password_field($name, $value = null, $attributes = null) {
    
    // Set type to password
    if(is_array($attributes)) {
      $attributes['type'] = 'password';
    } else {
      $attributes = array('type' => 'password');
    } // if
    
    // Return text field
    return text_field($name, $value, $attributes);
    
  } // password_filed
  
  /**
  * Return file field
  *
  * @access public
  * @param string $name
  * @param mixed $value
  * @param array $attributes
  * @return string
  */
  function file_field($name, $value = null, $attributes = null) {
    
    // Set type to password
    if(is_array($attributes)) {
      $attributes['type'] = 'file';
    } else {
      $attributes = array('type' => 'file');
    } // if
    
    // Return text field
    return text_field($name, $value, $attributes);
    
  } // file_field
  
  /**
  * Render radio field
  *
  * @access public
  * @param string $name Field name
  * @param mixed $value
  * @param boolean $checked
  * @param array $attributes Additional attributes
  * @return string
  */
  function radio_field($name, $checked = false, $attributes = null) {
    
    // Prepare attributes array
    if(is_array($attributes)) {
      $attributes['type'] = 'radio';
      if(!isset($attributes['class'])) $attributes['class'] = 'checkbox';
    } else {
      $attributes = array('type' => 'radio', 'class' => 'checkbox');
    } // if
    
    // Value
    $value = array_var($attributes, 'value', false);
    if($value === false) $value = 'checked';
    
    // Checked
    if($checked) {
      $attributes['checked'] = 'checked';
    } else {
      if(isset($attributes['checked'])) unset($attributes['checked']);
    } // if
    
    // And done
    return input_field($name, $value, $attributes);
    
  } // radio_field
  
  /**
  * Render checkbox field
  *
  * @access public
  * @param string $name Field name
  * @param mixed $value
  * @param boolean $checked Checked?
  * @param array $attributes Additional attributes
  * @return string
  */
  function checkbox_field($name, $checked = false, $attributes = null) {
    
  	// Prepare attributes array
    if(is_array($attributes)) {
      $attributes['type'] = 'checkbox';
      if(!isset($attributes['class'])) $attributes['class'] = 'checkbox';
    } else {
      $attributes = array('type' => 'checkbox', 'class' => 'checkbox');
    } // if
    
    // Value
    $value = array_var($attributes, 'value', false);
    if($value === false) $value = 'checked';
    
    // Checked
    if($checked) {
      $attributes['checked'] = 'checked';
    } else {
      if(isset($attributes['checked'])) unset($attributes['checked']);
    } // if
    
    // And done
    return input_field($name, $value, $attributes);
    
  } // checkbox_field
  
  /**
  * This helper will render select list box. Options is array of already rendered option tags
  *
  * @access public
  * @param string $name
  * @param array $options Array of already rendered option tags
  * @param array $attributes Additional attributes
  * @return null
  */
  function select_box($name, $options, $attributes = null) {
    if(is_array($attributes)) {
      $attributes['name'] = $name;
    } else {
      $attributes = array('name' => $name);
    } // if
    
    $output = open_html_tag('select', $attributes) . "\n";
    if(is_array($options)) {
      foreach($options as $option) {
        $output .= $option . "\n";
      } // foreach
    } // if
    return $output . close_html_tag('select') . "\n";
  } // select_box
  
  /**
   * 
   * @param $name Control name
   * @param $options Array of array(value, text)
   * @param $selected Selected value string
   * @param $attributes
   * @return unknown_type
   */
  function simple_select_box($name, $options, $selected = null, $attributes = null) {
  	if(is_array($attributes)) {
      $attributes['name'] = $name;
    } else {
      $attributes = array('name' => $name);
    } // if
    
    $output = open_html_tag('select', $attributes) . "\n";
    if(is_array($options)) {
      foreach($options as $option) {
      	if ($selected == $option[0]) {
        	$output .= option_tag($option[1], $option[0], array('selected' => 'selected')) . "\n";
      	} else {
      		$output .= option_tag($option[1], $option[0]) . "\n";
      	}
      } // foreach
    } // if
    return $output . close_html_tag('select') . "\n";
  }
  /**
  * Render option tag
  *
  * @access public
  * @param string $text Option text
  * @param mixed $value Option value
  * @param array $attributes
  * @return string
  */
  function option_tag($text, $value = null, $attributes = null) {
    if(!($value === null)) {
      if(is_array($attributes)) {
        $attributes['value'] = $value;
      } else {
        $attributes = array('value' => $value);
      } // if
    } // if
    return open_html_tag('option', $attributes) . clean($text) . close_html_tag('option');
  } // option_tag
  
  /**
  * Render option group
  *
  * @param string $labe Group label
  * @param array $options
  * @param array $attributes
  * @return string
  */
  function option_group_tag($label, $options, $attributes = null) {
    if(is_array($attributes)) {
      $attributes['label'] = $label;
    } else {
      $attributes = array('label' => $label);
    } // if
    
    $output = open_html_tag('optgroup', $attributes) . "\n";
    if(is_array($options)) {
      foreach($options as $option) {
        $output .= $option . "\n";
      } // foreach
    } // if
    return $output . close_html_tag('optgroup') . "\n";
  } // option_group_tag

  /**
  * Render submit button
  *
  * @access public
  * @param string $this Button title
  * @param string $accesskey Accesskey. If NULL accesskey will be skipped
  * @param array $attributes Array of additinal attributes
  * @return string
  */
  function submit_button($title, $accesskey = 's', $attributes = null) {
    if(!is_array($attributes)) {
      $attributes = array();
    } // if
    $attributes['class'] = 'submit';
    $attributes['type'] = 'submit';
    $attributes['accesskey'] = $accesskey;
    
    if($accesskey) {
      if(strpos($title, $accesskey) !== false) {
        $title = str_replace_first($accesskey, "<u>$accesskey</u>", $title);
      } // if
    } // if
    
    return open_html_tag('button', $attributes) . $title . close_html_tag('button');
  } // submit_button
  
  /**
  * Render button
  *
  * @access public
  * @param string $this Button title
  * @param string $accesskey Accesskey. If NULL accesskey will be skipped
  * @param array $attributes Array of additinal attributes
  * @return string
  */
  function button($title, $accesskey = 's', $attributes = null) {
    if(!is_array($attributes)) {
      $attributes = array();
    } // if
    $attributes['class'] = 'submit';
    $attributes['type'] = 'button';
    $attributes['accesskey'] = $accesskey;
    
    if($accesskey) {
      if(strpos($title, $accesskey) !== false) {
        $title = str_replace_first($accesskey, "<u>$accesskey</u>", $title);
      } // if
    } // if
    
    return open_html_tag('button', $attributes) . $title . close_html_tag('button');
  } // submit_button
  
  /**
  * Return textarea tag
  *
  * @access public
  * @param string $name
  * @param string $value
  * @param array $attributes Array of additional attributes
  * @return string
  */
  function textarea_field($name, $value, $attributes = null) {
    if(!is_array($attributes)) {
      $attributes = array();
    } // if
    $attributes['name'] = $name;
    if(!isset($attributes['rows']) || trim($attributes['rows'] == '')) {
      $attributes['rows'] = '10'; // required attribute
    } // if
    if(!isset($attributes['cols']) || trim($attributes['cols'] == '')) {
      $attributes['cols'] = '40'; // required attribute
    } // if
    
    return open_html_tag('textarea', $attributes) . clean($value) . close_html_tag('textarea');
  } // textarea
  
  // ---------------------------------------------------
  //  Widgets
  // ---------------------------------------------------
  
  /**
  * Return date time picker widget
  *
  * @access public
  * @param string $name Field name
  * @param string $value Date time value
  * @return string
  */
  function pick_datetime_widget($name, $value = null) {
    return text_field($name, $value);
  } // pick_datetime_widget
    
  /**
  * Return pick date widget
  *
  * @access public
  * @param string $name Name prefix
  * @param DateTimeValue $value Can be DateTimeValue object, integer or string
  * @param integer $year_from Start counting from this year. If NULL this value will be set
  *   to current year - 10
  * @param integer $year_to Count to this year. If NULL this value will be set to current
  *   year + 10
  * @return null
  */
  function pick_date_widget($name, $value = null, $year_from = null, $year_to = null, $attributes = null, $id = null) {
  	require_javascript("og/DateField.js");
  	$oldValue = $value;
    if(!($value instanceof DateTimeValue)) $value = new DateTimeValue($value);
    
    $month_options = array();
    for($i = 1; $i <= 12; $i++) {
      $option_attributes = $i == $value->getMonth() ? array('selected' => 'selected') : null;
      $month_options[] = option_tag(lang("month $i"), $i, $option_attributes);
    } // for
    
    $day_options = array();
    for($i = 1; $i <= 31; $i++) {
      $option_attributes = $i == $value->getDay() ? array('selected' => 'selected') : null;
      $day_options[] = option_tag($i, $i, $option_attributes);
    } // for
    
    $year_from = (integer) $year_from < 1 ? $value->getYear() - 10 : (integer) $year_from;
    $year_to = (integer) $year_to < 1 || ((integer) $year_to < $year_from) ? $value->getYear() + 10 : (integer) $year_to;
    
    $year_options = array();
    
    if ($year_from <= 1902)
    {
    	$option_attributes = is_null($oldValue) ? array('selected' => 'selected') : null;
    	$year_options[] = option_tag(lang('select'), 0, $option_attributes);
    }
    
    for($i = $year_from; $i <= $year_to; $i++) {
      $option_attributes = ($i == $value->getYear() && !is_null($oldValue)) ? array('selected' => 'selected') : null;
      $year_options[] = option_tag($i, $i, $option_attributes);
    } // if
    $attM = $attributes;
    $attY = $attributes;
    $attD = $attributes;
    if ($attM['id']) {
    	$attM['id'] .= '_month';
    }
    if ($attY['id']) {
    	$attY['id'] .= '_year';
    }
    if ($attD['id']) {
    	$attD['id'] .= '_day';
    }
    if (strpos($name, "]")) {
    	$preName = substr_utf($name,0,strpos_utf($name,"]"));
    	return select_box($preName . '_month]', $month_options, $attM) . select_box($preName.'_day]', $day_options, $attD) . select_box($preName . '_year]', $year_options, $attY);
    } else
    	return select_box($name . '_month', $month_options, $attM) . select_box($name . '_day', $day_options, $attD) . select_box($name . '_year', $year_options, $attY );
  } // pick_date_widget
  
  function pick_date_widget2($name, $value = null, $genid = null, $tabindex = null, $display_date_info = true, $id = null) {
  	require_javascript('og/DateField.js');
  	
  	$date_format = user_config_option('date_format');
  	if ($genid == null) $genid = gen_id();
  	$dateValue = '';
  	if ($value instanceOf DateTimeValue){
  		$dateValue = $value->format($date_format);
  	}
  	if (!$id) $id = $genid . $name . "Cmp";
  	$daterow = '';
  	if ($display_date_info)
  		$daterow = "<td style='padding-top:4px;font-size:80%'><span class='desc'>&nbsp;(" . date_format_tip($date_format) . ")</span></td>";
  	$html = "<table><tr><td><span id='" . $genid . $name . "'></span></td>$daterow</tr></table>
	<script>
		var dtp" . gen_id() . " = new og.DateField({
			renderTo:'" . $genid . $name . "',
			name: '" . $name . "',
			id: '" . $id . "',".
			(isset($tabindex) ? "tabIndex: '$tabindex'," : "").
			"value: '" . $dateValue . "'});
	</script>
	";
	return $html;
  } // pick_date_widget
  
  /**
  * Return pick time widget
  *
  * @access public
  * @param string $name
  * @param string $value
  * @return string
  */
  function pick_time_widget($name, $value = null) {
    return text_field($name, $value);
  } // pick_time_widget
  
  function pick_time_widget2($name, $value = null, $genid = null, $tabindex = null, $format = null) {
  	if ($format == null) $format = (user_config_option('time_format_use_24') ? 'G:i' : 'g:i A');
  	if ($value instanceof DateTimeValue) {
  		$value = $value->format($format);
  	}
  	
  	$html = "<table><tr><td><div id='" . $genid . $name . "'></div></td></tr></table>
	<script>
		var tp" . gen_id() . " = new Ext.form.TimeField({
			renderTo:'" . $genid . $name . "',
			name: '" . $name . "',
			format: '" . $format . "',
			width: 80,".
			(isset($tabindex) ? "tabIndex: '$tabindex'," : "").
			"value: '" . $value . "'});
	</script>
	";
  	return $html;
  }
  /**
  * Return WYSIWYG editor widget
  *
  * @access public
  * @param string $name
  * @param string $value
  * @return string
  */
  function editor_widget($name, $value = null, $attributes = null) {
    $editor_attributes = is_array($attributes) ? $attributes : array();
    if(!isset($editor_attributes['class'])) $editor_attributes['class'] = 'editor';
    return textarea_field($name, $value, $editor_attributes);
  } // editor_widget
  
  /**
  * Render yes no widget
  *
  * @access public
  * @param string $name
  * @param $id_base
  * @param boolean $value If true YES will be selected, otherwise NO will be selected
  * @param string $yes_lang
  * @param string $no_lang
  * @return null
  */
  function yes_no_widget($name, $id_base, $value, $yes_lang, $no_lang, $tabindex = null, $attributes = null) {
  	$yes_attributes = array('id' => $id_base . 'Yes', 'class' => 'yes_no', 'value' => 1);
  	$no_attributes = array('id' => $id_base . 'No', 'class' => 'yes_no', 'value' => 0);
  	if ($tabindex != null) {
  		$yes_attributes['tabindex'] = $tabindex;
  		$no_attributes['tabindex'] = $tabindex;
  	}
  	if (is_array($attributes)) {
  		foreach ($attributes as $attr_name => $attr_value) {
  			$yes_attributes[$attr_name] = $attr_value;
  			$no_attributes[$attr_name] = $attr_value;
  		}
  	}
  	
    $yes_input = radio_field($name, $value, $yes_attributes);
    $no_input = radio_field($name, !$value, $no_attributes);
    $yes_label = label_tag($yes_lang, $id_base . 'Yes', false, array('class' => 'yes_no'), '');
    $no_label = label_tag($no_lang, $id_base . 'No', false, array('class' => 'yes_no'), '');
    
    return $yes_input . ' ' . $yes_label . ' ' . $no_input . ' ' . $no_label;
  } // yes_no_widget
  
  /**
  * Show select country box
  *
  * @access public
  * @param string $name Control name
  * @param string $value Country code of selected country
  * @param array $attributes Array of additional select box attributes
  * @return string
  */
  function select_country_widget($name, $value, $attributes = null) {
    $country_codes = array_keys(CountryCodes::getAll());
    $countries = array();
    foreach($country_codes as $code) {
      if (Localization::instance()->lang_exists("country $code")) {
        $countries[$code] = lang("country $code");
      } else {
        $countries[$code] = CountryCodes::getCountryNameByCode($code);
      }
    } // foreach
    
    asort($countries);
    
    $country_options = array(option_tag(lang('none'), ''));
    foreach($countries as $country_code => $country_name) {
      $option_attributes = $country_code == $value ? array('selected' => true) : null;
      $country_options[] = option_tag($country_name, $country_code, $option_attributes);
    } // foreach
    
    return select_box($name, $country_options, $attributes);
  } // select_country_widget
  
  /**
  * Render select timezone widget
  *
  * @param string $name Name of the select box
  * @param float $value Timezone value. If NULL GMT will be selected
  * @param array $attributes Array of additional attributes
  * @return string
  */
  function select_timezone_widget($name, $value = null, $attributes = null) {
    $selected_value = (float) $value;
    $all_timezones = Timezones::getTimezones();
    
    $options = array();
    foreach($all_timezones as $timezone) {
      $option_attributes = $selected_value == $timezone ? array('selected' => true) : null;
      $option_text = $timezone > 0 ? lang("timezone gmt +$timezone") : lang("timezone gmt $timezone");
      $options[] = option_tag($option_text, $timezone, $option_attributes);
    } // if
    
    return select_box($name, $options, $attributes);
  } // select_timezone_widget
  
  function number_field($name, $value = null, $attributes = null) {
  	//if (!is_numeric($value)) $value = 0;
  	return text_field($name, $value, array("maxlength" => 9, "style"=> "width:100px", "onkeyup" => "event.target.value = event.target.value.replace(/[^0-9]/g, '')"));
  }
  
  /**
   * Takes an html color and returns it a $percentage % darker
   *
   * @param string $htmlColor
   * @param integer $percentage
   * @return string $darkerColor
   */
  function darkerHtmlColor($htmlColor, $percentage = 20) {
  	if ($percentage > 100 || $percentage < 0) $percentage = 0;
    if (substr($htmlColor, 0, 1) == '#') {
        $htmlColor = substr($htmlColor, 1);
    }
    if (strlen($htmlColor)!=6) {
        return;
    }
    $darkerColor = '';
    $pieces = explode(' ', rtrim(chunk_split($htmlColor, 2, ' ')));
    foreach ($pieces as $piece) {
        # convert from base16 to base10, reduce the value then come back to base16
        $tmp = (int) (base_convert($piece, 16, 10));
        $amount = (int) ($tmp * $percentage / 100); 
        $darkpiece = $tmp - $amount;
        $darkerColor .= sprintf("%02x", $darkpiece);
    }
    return '#'. $darkerColor;
  } // darkerHtmlColor

  function doubleListSelect($name, $values, $attributes = null) {
  	if (is_array($attributes)) {
		if (!array_var($attributes, "size")) $attributes['size'] = "15";
	} else {
		$attributes = array("size" => 15);
	}
	if (!array_var($attributes, "class")) $attributes['class'] = "og-double-list-sel";
	
	$id = array_var($attributes, 'id');
	if (!$id) $id = "list_values";
	 
	$options1 = array();
	$options2 = array();
	$hfields = "";
	$order = 1;
	foreach ($values as $val) {
		$sel = array_var($val, 'selected');
		if (!$sel)
			$options1[] = option_tag(array_var($val, 'text'), array_var($val, 'id'));
		else
			$options2[] = option_tag(array_var($val, 'text'), array_var($val, 'id'));
		
		$hfields .= '<input id="'.$id.'['.array_var($val, 'id').']" name="'.$name.'['.array_var($val, 'id').']" type="hidden" value="'.($sel ? $order++ : '0').'" />'; 
	}
	
	// 1st box
	$attributes['id'] = $id . "_box1";
	$html = "<table><tr><td>" . select_box($name."_box1", $options1, $attributes) . "</td>";
	
	// buttons
	$btn_style = 'border:1px solid #bbb; width:35px; margin:2px;';
	$html .= "<td align='center' class='og-double-list-sel-btns'>";
	$html .= "<div style='margin: 5px 10px;' title='".lang('move all to right')."'><a href='#' class='ico-2arrowright' style='padding: 0 0 3px 12px;' onclick=\"og.doubleListSelCtrl.selectAll('$id')\">&nbsp;</a></div>";
	$html .= "<div style='margin: 5px 10px 15px;' title='".lang('move to right')."'><a href='#' class='ico-arrowright' style='padding: 0 0 3px 12px;' onclick=\"og.doubleListSelCtrl.selectOne('$id')\">&nbsp;</a></div>";
	$html .= "<div style='margin: 15px 10px 5px;' title='".lang('move to left')."'><a href='#' class='ico-arrowleft' style='padding: 0 0 3px 12px;' onclick=\"og.doubleListSelCtrl.deselectOne('$id')\">&nbsp;</a></div>";
	$html .= "<div style='margin: 5px 10px;' title='".lang('move all to left')."'><a href='#' class='ico-2arrowleft' style='padding: 0 0 3px 12px;' onclick=\"og.doubleListSelCtrl.deselectAll('$id')\">&nbsp;</a></div>";
	$html .= "</td>";
	
	// 2nd box
	$attributes['id'] = $id . "_box2";
	$html .= "<td>" . select_box($name."_box2", $options2, $attributes) . "</td>";
	
	$html .= "<td>";
	$html .= "<div style='margin: 2px;' title='".lang('move up')."'><a href='#' class='ico-upload' style='padding: 0 0 3px 12px;' onclick=\"og.doubleListSelCtrl.moveUp('$id', '_box2')\">&nbsp;</a></div>";
	$html .= "<div style='margin: 2px;' title='".lang('move down')."'><a href='#' class='ico-download' style='padding: 0 0 3px 12px;' onclick=\"og.doubleListSelCtrl.moveDown('$id', '_box2')\">&nbsp;</a></div>";
	$html .= "</td></tr></table>";
	
	// hidden fields containing the selection
	$html .= $hfields;
	return $html;
  }

?>