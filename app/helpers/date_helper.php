<?php
/**
 * Created by PhpStorm.
 * User: jonmartin
 * Date: 15/02/2014
 * Time: 22:43
 */
/**
 * Accepts a date and returns a UK format date, optional time parmaeter
 * @param string $date the date to be displayed
 * @param string $time if not false, a time will be displayed too
 * @return string $datestr
 */
function format_date($date, $time = false)
{
    $datestr = '';

    if($time != false)
        $datestring = 'd/m/Y H:i';
    else
        $datestring = 'd/m/Y';

    if($date != null && $date != '0000-00-00 00:00:00' && $date != '0000-00-00')
        $datestr = date_format(date_create($date), $datestring);

    return $datestr;
}