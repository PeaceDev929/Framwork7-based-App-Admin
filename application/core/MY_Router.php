<?php
/**
 * Created by PhpStorm.
 * User: Gambler
 * Date: 2018-06-18
 * Time: 오전 8:58
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Router extends CI_Router
{
    protected function _set_default_controller()
    {

        if (empty($this->default_controller)) {

            show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
        }

        // Is the method being specified?
        if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2) {
            $method = 'index';
        }

        // This is what I added, checks if the class is a directory
        if (is_dir(APPPATH . 'controllers/' . $class)) {

            // Set the class as the directory

            $this->set_directory($class);

            // $method is the class

            $class = $method;

            // Re check for slash if method has been set

            if (sscanf($method, '%[^/]/%s', $class, $method) !== 2) {
                $method = 'index';
            }
        }

        if (!file_exists(APPPATH . 'controllers/' . $this->directory . ucfirst($class) . '.php')) {

            // This will trigger 404 later

            return;
        }

        $this->set_class($class);
        $this->set_method($method);

        // Assign routed segments, index starting from 1
        $this->uri->rsegments = array(
            1 => $class,
            2 => $method
        );

        log_message('debug', 'No URI present. Default controller set.');
    }

    protected function _parse_routes()
    {
        // Turn the segment array into a URI string
        $uri = implode('/', $this->uri->segments);

        // Get HTTP verb
        $http_verb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

        // ::: START EDIT (Form Method Spoofing) :::
        $_request_method = strtolower(isset($_REQUEST['__method']) ? $_REQUEST['__method'] : NULL);
        if (!empty($_request_method) && in_array($_request_method, ['get', 'post', 'put', 'patch', 'delete'])) {
            $http_verb = $_request_method;
        }
        // ::: END EDIT (Form Method Spoofing) :::

        // Loop through the route array looking for wildcards
        foreach ($this->routes as $key => $val) {
            // Check if route format is using HTTP verbs
            if (is_array($val)) {
                $val = array_change_key_case($val, CASE_LOWER);
                if (isset($val[$http_verb])) {
                    $val = $val[$http_verb];
                } else {
                    continue;
                }
            }

            // Convert wildcards to RegEx
            $key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);

            // Does the RegEx match?
            if (preg_match('#^' . $key . '$#', $uri, $matches)) {
                // Are we using callbacks to process back-references?
                if (!is_string($val) && is_callable($val)) {
                    // Remove the original string from the matches array.
                    array_shift($matches);

                    // Execute the callback using the values in matches as its parameters.
                    $val = call_user_func_array($val, $matches);
                } // Are we using the default routing method for back-references?
                elseif (strpos($val, '$') !== FALSE && strpos($key, '(') !== FALSE) {
                    $val = preg_replace('#^' . $key . '$#', $val, $uri);
                }

                $this->_set_request(explode('/', $val));
                return;
            }
        }

        // If we got this far it means we didn't encounter a
        // matching route so we'll set the site default route
        $this->_set_request(array_values($this->uri->segments));
    }
}
