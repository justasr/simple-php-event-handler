<?php
class Events {
    private static $registeredEvents = [];
    private static $registeredHandlers = [];
    
    public static function registerEvent($eventName) { 
        Events::$registeredEvents[] = $eventName;
    }
    
    public static function unregisterEvent($eventName, $unregisterHandlers = false) {
        if (in_array($eventName, Events::$registeredEvents)) {
            unset(Events::$registeredEvents[array_search($eventName, Events::$registeredEvents)]);
            Events::$registeredEvents = array_values(Events::$registeredEvents);
        }
        if (isset(Events::$registeredHandlers[$eventName]) && $unregisterHandlers) {
            unset(Events::$registeredHandlers[$eventName]);
        }
    }
    
    public static function registerHandler($eventName, $funcId, $func) {
        if (in_array($eventName, Events::$registeredEvents)) {
            if (!isset(Events::$registeredHandlers[$eventName]))
                Events::$registeredHandlers[$eventName] = [];
            Events::$registeredHandlers[$eventName][$funcId] = $func;
        }
        else
            trigger_error("Event '$eventName' does not exists!", E_USER_WARNING);
    }
    
    public static function unregisterHandler($eventName, $funcId) {
        if (isset(Events::$registeredHandlers[$eventName][$funcId])) {
            unset(Events::$registeredHandlers[$eventName][$funcId]);
        }
    }
    
    public static function triggerEvent($eventName, $params = []) {
        if (in_array($eventName, Events::$registeredEvents) && isset(Events::$registeredHandlers[$eventName])) {
            foreach (Events::$registeredHandlers[$eventName] as $id => $func)
                $params = call_user_func($func, $params);
        }
        return $params;
    }
}