<?php
class C3op_Util_ValidLongString extends Zend_Validate_Abstract {
    function isValid($value) {
        $nameValidator = new Zend_Validate_Regex("/^[0-9A-Za-zÀ-ú\'\[\]\(\)\-\.\,\:\;\!\?\/ ]{0,12000}$/");
        return $nameValidator->isValid($value);
    }
}

?>
