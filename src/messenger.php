<?php
/**
 * messenger.php
 * A straightforward php publish/subscribe library.
 * (c) Harry Hope 2014
 */
class Messenger {

    // An array of callable functions.
    private static $subscriptions = array();

    /**
     * Add a subscription to the list.
     *
     * @param String $message
     *    The name of the subscription to add.
     * @param Callable $callback
     *    A callable function.
     * @return Messenger $this
     *     Returns the current instance for method chaining.
     */
    private function _on($message, $callback) {

        // Error Handling:
        // Make sure $message is a string and $callback is a callable function.
        if (!is_string($message))
            throw new InvalidArgumentException('First parameter of Messenger::on must be a string.');

        if (!is_callable($callback))
            throw new InvalidArgumentException('Second parameter of Messenger::on must be callable.');

        // Add (or replace) a subscription entry.
        self::$subscriptions[] = (object) array(
            'message' => $message,
            'callback' => $callback
        );

        return $this;
    }

    /**
     * Remove all subscriptions of the specified message from the list, or just
     * the one specified by the optional second parameter.
     *
     * @param String $message
     *    The name of the subscription to remove.
     * @param Callable $callback
     *    The (optional) specific function to remove.
     * @return Messenger $this
     *     Returns the current instance for method chaining.
     */
    private function _off($message, $callback = null) {

        // Error Handling:
        // Make sure $message is a string.
        if (!is_string($message))
            throw new InvalidArgumentException('First parameter of Messenger::off must be a string.');

        if (!is_null($callback) && !is_callable($callback))
            throw new InvalidArgumentException('Second parameter of Messenger::off must be callable.');

        // Delete entries where the message and (optionally) the callback matches.
        foreach(self::$subscriptions as $key => $subscription) {
            if ($subscription->message === $message) {

                // If we passed in a second parameter, only delete items with a
                // matching function.
                if (isset($callback) && $callback !== $subscription->callback) continue;

                unset(self::$subscriptions[$key]);
            }
        }

        return $this;
    }

    /**
     * Trigger a stored callback function with specified data.
     *
     * @param String $message
     *     The name of the subscription to trigger.
     * @param Mixed $data
     *     The data to pass to the callback.
     * @return Messenger $this
     *     Returns the current instance for method chaining.
     */
    private function _send($message, $data) {

        // Error Handling:
        // Make sure $message is a string.
        if (!is_string($message))
            throw new InvalidArgumentException('First parameter of Messenger::send must be a string.');

        // Get the stored callback from the subscriptions list.
        foreach(self::$subscriptions as $subscription) {

            if ($subscription->message === $message) {
                $callback = $subscription->callback;

                // Call the function, if possible, and set our return value to true.
                if (is_callable($callback)) {
                    $callback($data);
                }
            }
        }

        return $this;
    }

    /* ===================
           Magic Methods
         =================== */

    /**
     * Allows the use of Messenger's methods either through an instance
     * or statically.
     */
    public function __call($name, $arguments) {
        if (in_array($name, array('on', 'off', 'send')))
          return call_user_func_array(array($this, "_$name"), $arguments);
    }

    public static function __callStatic($name, $arguments) {
        $messenger = new Messenger;

        if (in_array($name, array('on', 'off', 'send')))
          return call_user_func_array(array($messenger, "_$name"), $arguments);
    }
}
