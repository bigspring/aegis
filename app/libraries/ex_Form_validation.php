<?php
class EX_Form_validation extends CI_Form_validation {
    /**
     * Decimal number
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    function decimal_or_natural($str)
    {

        if(preg_match( '/^[0-9]+$/', $str))
            return true;
        
        if(preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $str))
            return true;
    }
}