<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 File: MY_form_helper

Creates HTML5 extensions for the standard CodeIgniter form helper.

These functions also wrap the form elements as necessary to create
the styling that the Bootstrap-inspired admin theme requires to
make it as simple as possible for a developer to maintain styling
with the core. Also makes changing the core a snap.

All methods (including overriden versions of the originals) now
support passing a final 'label' attribute that will create the
label along with the field.
*/

/*
 Function: _form_common()

Used by many of the new functions to wrap the input in the correct
tags so that the styling is automatic.

Parameters:
$type	- A string with the name of the element type.
$data	- Either a string with the element name, or an array of
key/value pairs of all attributes.
$value	- Either a string with the value, or blank if an array is
is passed to the $data param.
$label	- A string with the label of the element.
$extra	- A string with any additional items to include, like Javascript.
$validation - A string for inline help or a tooltip icon

Returns:
A string with the formatted input element, label tag and wrapping divs.
*/
if (!function_exists('_form_common'))
{
	function _form_common($type='text', $data='', $value='', $label='', $extra='', $validation = '', $tooltip = '', $default_option = null)
	{

		$valid_class = '';
		
		// add the bootstrap markup to inline help		
		if($tooltip != '')
		{
			$tooltip = '<span class="help-inline">' . $tooltip . '</span>';
		}
		
		// prepare a validation class if necessary
		if($validation != '')
		{
			$valid_class = ' error';
		}
			
		$defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => (( ! is_array($value)) ? $value : ''));
		$selected_options = array();
		
		if($type != 'text')
			$defaults['type'] = $type;

		// If name is empty at this point, try to grab it from the $data array
		if (empty($defaults['name']) && is_array($data) && isset($data['name']))
		{
			$defaults['name'] = $data['name'];
			unset($data['name']);
		}
		
		// if we have been passed an options array, remove it from the data var and set to its own var
		if(is_array($data) && array_key_exists('options', $data)) 
		{
			$options = $data['options'];
			unset($data['options']);
		}
		
		// if we've been passed an array of options, must be a multiselect
		if(is_array($value))
		{
			$selected_options = $value;
			$value = '';
		}
			
		$output = _parse_form_attributes($data, $defaults);
						
		if($type == 'textarea') 
		{
			$output = '
				<div class="form-group'.$valid_class.'">
					<label class="control-label" for="' . $defaults['name'] . '"> ' . $label . '</label>
						 <textarea ' . $output . ' ' . $extra . '>' . $value . '</textarea>
						' . $validation . ' ' . $tooltip . '
				</div>';
		}
		elseif($type == 'dropdown') 
		{			
			$output = '
			<div class="form-group'.$valid_class.'">
			<label class="control-label" for="' . $defaults['name'] . '">' . $label . '</label>
			<select name="' . $defaults['name'] . '" ' . $extra . '> ';
            
            if($default_option) $output .= '<option value="">Please select...</option>';

			foreach($options AS $k => $v){
				
				$selected = '';
				
				if($selected_options)
				{
					if(in_array($k, $selected_options))
						$selected = ' selected="selected"';
				}
				else
				{
					if($value == $k)
						$selected = ' selected="selected"';
				}
				

				$output .= '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
			}
			
			$output .= '</select> ' . $validation . ' ' . $tooltip . '
			</div>';
		}
		elseif($type == 'radio')
		{
			$output = '
				<div class="form-group'.$valid_class.'">
					<label class="control-label" for="' . $defaults['name'] . '">' . $label . '</label>';

			foreach($options AS $k => $v)
			{
				$checked = '';
				
				if($selected_options)
				{
					if(in_array($k, $selected_options))
						$checked = ' checked="checked"';
				}
				else
				{
					if($value == $k)
						$checked = ' checked="checked"';
				}
				$output .= '<label class="radio" for="'.$defaults['name'].'">';
				$output .= '<input type="radio" name="'.$defaults['name'].'" value="'.$k.'"'.$checked.' '.$extra.'>'.$v;
				$output .= '</label>';
			}
					
			$output .= $validation . ' ' . $tooltip . '
				</div>';
		}
        elseif($type == 'password')
        {
                $output = '
                <div class="form-group'.$valid_class.'">
                    <label class="control-label" for="' . $defaults['name'] . '">' . $label . '</label>
                         <input ' . $output . ' ' . $extra . ' />
                        ' . $validation . ' ' . $tooltip;
                $output .= ($default_option === null) ? '<span class="password-meter help-inline"><span class="password-meter-message"> </span></span>' : '';
                $output .=
                '</div>';
        }
		else 
		{
			$output = '
				<div class="form-group'.$valid_class.'">
					<label class="control-label" for="' . $defaults['name'] . '">' . $label . '</label>
						 <input ' . $output . ' ' . $extra . ' />
						' . $validation . ' ' . $tooltip . '
				</div>';
		}

		return $output;
	}
}

function _form_inline($output, $attributes='', $value='', $label='', $extra='', $help = '')
{

}

function _form_search($output, $attributes='', $value='', $label='', $extra='', $help = '')
{

}

function _form_horizontal($output, $attributes='', $value='', $label='', $extra='', $help = '')
{
	$output = '
	<div class="form-group">
	<label class="control-label" for="">' . $label . '</label>
	<input ' . $output . ' ' . $extra . ' />' .
	$help .'
	</div>';

	return $output;
}

//--------------------------------------------------------------------

if (!function_exists('form_input'))
{
	function form_input($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '' )
	{
		return _form_common('text', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

if (!function_exists('form_upload'))
{
	function form_upload($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '' )
	{
		return _form_common('file', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_textarea'))
{
	function form_textarea($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '')
	{
		return _form_common('textarea', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_email'))
{
	function form_email($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '' )
	{
		return _form_common('email', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_password'))
{
	function form_password($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '', $strength_indicator = null )
	{
		return _form_common('password', $data, $value, $label, $extra, $validation, $tooltip, $strength_indicator);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_url'))
{
	function form_url($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '' )
	{
		return _form_common('url', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_telephone'))
{
	function form_telephone($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '' )
	{
		return _form_common('tel', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_number'))
{
	function form_number($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '' )
	{
		return _form_common('number', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_checkbox'))
{
	function form_checkbox($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '' )
	{
		return _form_common('checkbox', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_radio'))
{
	function form_radio($data='', $options = array(), $value='', $label='', $extra='', $validation = '', $tooltip = '' )
	{
		if(is_array($data)){
			$data['options'] = $options;
		}
		else
		{
			$data = array(
					'name' => $data,
					'options' => $options
			);
		}
		
		return _form_common('radio', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_dropdown'))
{
	function form_dropdown($data='', $options = array(), $value='', $label='', $extra='', $validation = '', $tooltip = '', $default_option = null )
	{
		if(is_array($data)){
			$data['options'] = $options;
		}
		else
		{
			$data = array(
					'name' => $data,
					'options' => $options
			);
		}

		return _form_common('dropdown', $data, $value, $label, $extra, $validation, $tooltip, $default_option);
	}
}

if (!function_exists('form_multiselect'))
{
	function form_multiselect($data = '', $options = array(), $value = array(), $label='', $extra = '', $validation = '', $tooltip = '', $default_option = null)
	{
		if ( ! strpos($extra, 'multiple'))
		{
			$extra .= ' multiple="multiple"';
		}

		return form_dropdown($data, $options, $value, $label, $extra, $validation, $tooltip, $default_option);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_color'))
{
	function form_color($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '' )
	{
		return _form_common('color', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_search'))
{
	function form_search($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '' )
	{
		return _form_common('search', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_date'))
{
	function form_date($data='', $value='', $label='', $extra='', $validation = '', $tooltip = '' )
	{
		return _form_common('date', $data, $value, $label, $extra, $validation, $tooltip);
	}
}

/**
* Returns an array of hours for a select box for use for CI's form_dropdown helper method
* @return array $hours
*/
function getHoursSelect()
{
    $hours = array(
                    '00' => '00',
                    '01' => '01',
                    '02' => '02',
                    '03' => '03',
                    '04' => '04',
                    '05' => '05',
                    '06' => '06',
                    '07' => '07',
                    '08' => '08',
                    '09' => '09',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                    '13' => '13',
                    '14' => '14',
                    '15' => '15',
                    '16' => '16',
                    '17' => '17',
                    '18' => '18',
                    '19' => '19',
                    '20' => '20',
                    '21' => '21',
                    '22' => '22',
                    '23' => '23'
    );
    
    return $hours;
}

/**
* Returns an array of minutes for a select box for use for CI's form_dropdown helper method
* @return array $minutes
*/    
function getMinutesSelect()
{
    $minutes = array(
                    '00' => '00',
                    '15' => '15',
                    '30' => '30',
                    '45' => '45'
                );
    
    return $minutes;
}

/**
* Returns an array of countries for a select box for use for CI's form_dropdown helper method
* @return array $hours
*/
function getCountryList()
{
    $countrylist = $countrylist = Array ("" => "Please select...",
                                        "France" => "France",
                                        "Germany" => "Germany",
                                        "Italy" => "Italy",
                                        "Russia" => "Russia", 
                                        "Spain" => "Spain",
                                        "United Kingdom" => "United Kingdom",
                                        "United States" => "United States",
                                        "Afghanistan" => "Afghanistan" ,
                                        "Albania" => "Albania" ,
                                        "Algeria" => "Algeria" ,
                                        "Andorra" => "Andorra" ,
                                        "Angola" => "Angola" ,
                                        "Antigua and Barbuda" => "Antigua and Barbuda" ,
                                        "Argentina" => "Argentina" ,"Armenia" => "Armenia" ,"Australia" => "Australia" ,"Austria" => "Austria" ,"Azerbaijan" => "Azerbaijan" ,"Bahamas" => "Bahamas" ,"Bahrain" => "Bahrain" ,"Bangladesh" => "Bangladesh" ,"Barbados" => "Barbados" ,"Belarus" => "Belarus" ,"Belgium" => "Belgium" ,"Belize" => "Belize" ,"Benin" => "Benin" ,"Bhutan" => "Bhutan" ,"Bolivia" => "Bolivia" ,"Bosnia and Herzegovina" => "Bosnia and Herzegovina" ,"Botswana" => "Botswana" ,"Brazil" => "Brazil" ,"Brunei" => "Brunei" ,"Bulgaria" => "Bulgaria" ,"Burkina Faso" => "Burkina Faso" ,"Burundi" => "Burundi" ,"Cambodia" => "Cambodia" ,"Cameroon" => "Cameroon" ,"Canada" => "Canada","Cape Verde" => "Cape Verde" ,"Central African Republic" => "Central African Republic" ,"Chad" => "Chad" ,"Chile" => "Chile" ,"China" => "China" ,"Colombi" => "Colombi" ,"Comoros" => "Comoros" ,"Congo (Brazzaville)" => "Congo (Brazzaville)" ,"Congo" => "Congo" ,"Costa Rica" => "Costa Rica" ,"Cote d'Ivoire" => "Cote d'Ivoire" ,"Croatia" => "Croatia" ,"Cuba" => "Cuba" ,"Cyprus" => "Cyprus" ,"Czech Republic" => "Czech Republic" ,"Denmark" => "Denmark" ,"Djibouti" => "Djibouti" ,"Dominica" => "Dominica" ,"Dominican Republic" => "Dominican Republic" ,"East Timor (Timor Timur)" => "East Timor (Timor Timur)" ,"Ecuador" => "Ecuador" ,"Egypt" => "Egypt" ,"El Salvador" => "El Salvador" ,"Equatorial Guinea" => "Equatorial Guinea" ,"Eritrea" => "Eritrea" ,"Estonia" => "Estonia" ,"Ethiopia" => "Ethiopia" ,"Fiji" => "Fiji" ,"Finland" => "Finland" ,"Gabon" => "Gabon" ,"Gambia, The" => "Gambia, The" ,"Georgia" => "Georgia" ,"Ghana" => "Ghana" ,"Greece" => "Greece" ,"Grenada" => "Grenada" ,"Guatemala" => "Guatemala" ,"Guinea" => "Guinea" ,"Guinea-Bissau" => "Guinea-Bissau" ,"Guyana" => "Guyana" ,"Haiti" => "Haiti" ,"Honduras" => "Honduras" ,"Hungary" => "Hungary" ,"Iceland" => "Iceland" ,"India" => "India" ,"Indonesia" => "Indonesia" ,"Iran" => "Iran" ,"Iraq" => "Iraq" ,"Ireland" => "Ireland" ,"Israel" => "Israel" ,"Jamaica" => "Jamaica" ,"Japan" => "Japan" ,"Jordan" => "Jordan" ,"Kazakhstan" => "Kazakhstan" ,"Kenya" => "Kenya" ,"Kiribati" => "Kiribati" ,"Korea, North" => "Korea, North" ,"Korea, South" => "Korea, South" ,"Kuwait" => "Kuwait" ,"Kyrgyzstan" => "Kyrgyzstan" ,"Laos" => "Laos" ,"Latvia" => "Latvia" ,"Lebanon" => "Lebanon" ,"Lesotho" => "Lesotho" ,"Liberia" => "Liberia" ,"Libya" => "Libya" ,"Liechtenstein" => "Liechtenstein" ,"Lithuania" => "Lithuania" ,"Luxembourg" => "Luxembourg" ,"Macedonia" => "Macedonia" ,"Madagascar" => "Madagascar" ,"Malawi" => "Malawi" ,"Malaysia" => "Malaysia" ,"Maldives" => "Maldives" ,"Mali" => "Mali" ,"Malta" => "Malta" ,"Marshall Islands" => "Marshall Islands" ,"Mauritania" => "Mauritania" ,"Mauritius" => "Mauritius" ,"Mexico" => "Mexico" ,"Micronesia" => "Micronesia" ,"Moldova" => "Moldova" ,"Monaco" => "Monaco" ,"Mongolia" => "Mongolia" ,"Morocco" => "Morocco" ,"Mozambique" => "Mozambique" ,"Myanmar" => "Myanmar" ,"Namibia" => "Namibia" ,"Nauru" => "Nauru" ,"Nepa" => "Nepa" ,"Netherlands" => "Netherlands" ,"New Zealand" => "New Zealand" ,"Nicaragua" => "Nicaragua" ,"Niger" => "Niger" ,"Nigeria" => "Nigeria" ,"Norway" => "Norway" ,"Oman" => "Oman" ,"Pakistan" => "Pakistan" ,"Palau" => "Palau" ,"Panama" => "Panama" ,"Papua New Guinea" => "Papua New Guinea" ,"Paraguay" => "Paraguay" ,"Peru" => "Peru" ,"Philippines" => "Philippines" ,"Poland" => "Poland" ,"Portugal" => "Portugal" ,"Qatar" => "Qatar" ,"Romania" => "Romania" ,"Rwanda" => "Rwanda" ,"Saint Kitts and Nevis" => "Saint Kitts and Nevis" ,"Saint Lucia" => "Saint Lucia" ,"Saint Vincent" => "Saint Vincent" ,"Samoa" => "Samoa" ,"San Marino" => "San Marino" ,"Sao Tome and Principe" => "Sao Tome and Principe" ,"Saudi Arabia" => "Saudi Arabia" ,"Senegal" => "Senegal" ,"Serbia and Montenegro" => "Serbia and Montenegro" ,"Seychelles" => "Seychelles" ,"Sierra Leone" => "Sierra Leone" ,"Singapore" => "Singapore" ,"Slovakia" => "Slovakia" ,"Slovenia" => "Slovenia" ,"Solomon Islands" => "Solomon Islands" ,"Somalia" => "Somalia" ,"South Africa" => "South Africa" ,"Sri Lanka" => "Sri Lanka" ,"Sudan" => "Sudan" ,"Suriname" => "Suriname" ,"Swaziland" => "Swaziland" ,"Sweden" => "Sweden" ,"Switzerland" => "Switzerland" ,"Syria" => "Syria" ,"Taiwan" => "Taiwan" ,"Tajikistan" => "Tajikistan" ,"Tanzania" => "Tanzania" ,"Thailand" => "Thailand" ,"Togo" => "Togo" ,"Tonga" => "Tonga" ,"Trinidad and Tobago" => "Trinidad and Tobago" ,"Tunisia" => "Tunisia" ,"Turkey" => "Turkey" ,"Turkmenistan" => "Turkmenistan" ,"Tuvalu" => "Tuvalu" ,"Uganda" => "Uganda" ,"Ukraine" => "Ukraine" ,"United Arab Emirates" => "United Arab Emirates" ,"Uruguay" => "Uruguay" ,"Uzbekistan" => "Uzbekistan" ,"Vanuatu" => "Vanuatu" ,"Vatican City" => "Vatican City" ,"Venezuela" => "Venezuela" ,"Vietnam" => "Vietnam" ,"Yemen" => "Yemen" ,"Zambia" => "Zambia" ,"Zimbabwe" => "Zimbabwe" );
        
    return $countrylist;
}

/**
 * @param null $entity Name of the entity being described
 * @param null $text Override that returns specific text
 * @return null|string Text to display
 */
function get_submit_text($entity = null, $text = null) {
    $CI =& get_instance();

    if($text)
           return $text;

    $method = $CI->router->fetch_method();

    if($method == 'add') {

        $verb = (isset($verb)) ? $verb : 'Create';

        $text = ($entity) ? $verb . ' ' . $entity : $verb;
        return $text;

    } elseif ($method == 'edit') {
        return 'Save';
    }
}