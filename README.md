# simple-php-event-handler

## Events.php

Simple class to handle custom events in php. Allows you to register/unregister events and handlers and makes triggering them easy.

## Instructions

##### Triggering an Event

When triggering an event you have the option to pass parameters as a single array to the handlers. It's recommended you include somewhere within your application's documentation what parameters are going to be passed in that array. Also if it's a sequential or associative array. So when somebody writes a handler they can know what they'll be recieving as the parameters and can use them accordingly. When all handlers have been called and executed the parameters are return and can be use by the script that triggered the event.

##### Handling an Event

When setting up an event handler refer to the specific handler's documentation on what parameters to expect from it. Note that the parameters will come as a single array. When the handler ends execution it's expected that the parameter's be returned to be passed on to the next handler. Also note that the handler can change the parameters if desired for example when using a custom output buffer that modifies the output. The buffer can trigger the event and the handlers can modify the outputs value and return it for the output buffer to return. 

## Usage

```
require_once 'Events.php';

Events::registerEvent('ob_final_edit');

// One way to register a handler {Params} event name : unique handler id : function closure or name
Events::registerHandler('ob_final_edit', 'output_modifier_1' function($params) {
    $params[0] = str_replace("{@WEBSITENAME}", "My Website's Name", $params[0]);
    return $params;
});

// Second way
function add_website_name($params) {
    $params[0] = str_replace("{@WEBSITENAME}", "My Website's Name", $params[0]);
    return $params;
}
Events::registerHandler('ob_final_edit', 'output_modifier_1', 'add_website_name');

// Triggering an event

// Custom output buffer handler, ob_start('my_output_buffer_handler'); included in other code
function my_output_buffer_handler($output) {
    // Code here
    $output = Events::triggerEvent('ob_final_edit', [$output])[0];
    return $output;
}
```