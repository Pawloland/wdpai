<?php

class Language
{
    //no validation for now, so public properties
    // for validation, we can change properties to private/protected and specify property hooks
    // more info: https://www.zend.com/blog/php-8-4-property-hooks#a-developer-guide-to-php-8-4-property-hooks
    // also we don't have to specify properties manually, we can use __construct() method to do it
    // more info: https://www.php.net/manual/en/language.oop5.decon.php#language.oop5.decon.constructor.promotion
    public function __construct(
        public string $language_name,
        public string $code,
    )
    {
    }
}
