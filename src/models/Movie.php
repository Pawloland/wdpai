<?php

class Movie

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
    // There seems to be a bug with phpstorm debugger window or Xdebug itself, which causes any property that uses a get or set hook, to always show as null in the debugger window
    // this can be mitigated by adding a watch for the whole object like (array)$movie, which will show the correct values by first deserialize the object to an assoc array


    public string $duration_string {
        get => $this->duration->format('H:i:s');
    }

    public function __construct(
        public int      $ID_Movie = -1,
        public string   $title = '',
        public string   $original_title = '',
        public DateTime $duration = new DateTime('1979-01-01 00:00:00') {
            set (string|DateTime $value) {
                if (is_string($value)) {
                    $a = new DateTime('1970-01-01 ' . $value);
                    $this->duration = $a;
                } elseif ($value instanceof DateTime) {
                    $this->duration = $value;
                } else {
                    throw new InvalidArgumentException('Invalid type for duration');
                }

            }
        },
        public ?string  $description = null,
        public ?string  $poster = null,
        public int      $ID_Language = -1,
        public ?int     $ID_Dubbing = null,
        public ?int     $ID_Subtitles = null
    )
    {
    }
}
