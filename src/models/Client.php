<?php

class Client

{   //no validation for now, so public properties
    // for validation, we can change properties to private/protected and specify property hooks
    // more info: https://www.zend.com/blog/php-8-4-property-hooks#a-developer-guide-to-php-8-4-property-hooks
    // also we don't have to specify properties manually, we can use __construct() method to do it
    // more info: https://www.php.net/manual/en/language.oop5.decon.php#language.oop5.decon.constructor.promotion
    // If a property is NOT NULL in the database, it is also declared without ? sign in the constructor.
    // Default values are necessary for PDO::FETCH_CLASS to work properly as it first creates an object
    // without passing any arguments to the constructor and then sets the properties of that object.
    // Without default values PDO fetches will cause an error because they would not pass the data
    // returned from the database to the constructor automatically.

    public function __construct(
        public int    $ID_Client = -1,
        public string $client_name = '',
        public string $client_surname = '',
        public string $nick = '',
        public string $password_hash = '',
        public string $mail = ''
    )
    {
    }
}