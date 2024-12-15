<?php

// enable all php exceptions to be caught by the debugger and passed to phpstorm to stop on the line that caused them
// https://www.jetbrains.com/help/phpstorm/debugging-with-php-exception-breakpoints.html#php-exception-breakpoints

echo 'Hi there 👋';

//show info.php - should include info about xdebug if it is set up correctly
phpinfo();


// write come code that will error out
echo $notdefinedvar; // this should throw a warning and pause on the line and not show the error in the browser frontend




