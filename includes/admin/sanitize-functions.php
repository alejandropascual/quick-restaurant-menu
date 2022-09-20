<?php


function qrm_sanitize_array($input)
{
	$new_input = array();

	foreach ( $input as $key => $val ) {
		$new_input[ $key ] = sanitize_text_field($val);
	}

	return $new_input;
}

function qrm_sanitize_text_or_array_field($array_or_string)
{
	if(is_string($array_or_string) ) {
		$array_or_string = sanitize_text_field($array_or_string);
	}
	elseif(is_array($array_or_string) ) {
		foreach ( $array_or_string as $key => &$value ) {
			if (is_array($value) ) {
				$value = qrm_sanitize_text_or_array_field($value);
			}
			else {
				$value = sanitize_text_field($value);
			}
		}
	}

	return $array_or_string;
}
