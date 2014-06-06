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
     * @param Closure Object $callback
     *    A callable function.
     */
    public static function on($message, $callback) {

        // Error Handling:
        // Make sure $message is a string and $callback is a callable function.
        if (!is_string($message))
            throw new InvalidArgumentException('First parameter of Messenger::on must be a string.');

        if (!is_callable($callback))
            throw new InvalidArgumentException('Second parameter of Messenger::on must be a callable function.');

        // Add (or replace) a subscription entry.
        self::$subscriptions[] = (object) array(
            'message' => $message,
            'callback' => $callback
        );
    }

    /**
     * Remove all subscriptions of the specified message from the list, or just
     * the one specified by the optional second parameter.
     *
     * @param String $message
     *    The name of the subscription to remove.
     * @param Closure Object $callback
     *    The (optional) specific function to remove.
     */
    public static function off($message, $callback = null) {

        // Error Handling:
        // Make sure $message is a string.
        if (!is_string($message))
            throw new InvalidArgumentException('Parameter of Messenger::off must be a string.');

        // Delete entries where the message and (optionally) the callback matches.
        foreach(self::$subscriptions as $key => $subscription) {
            if ($subscription->message === $message) {

                // If we passed in a second parameter, only delete items with a
                // matching function.
                if (isset($callback) && $callback !== $subscription->callback) continue;

                unset(self::$subscriptions[$key]);
            }

        }
    }

    /**
     * Trigger a stored callback function with specified data.
     *
     * @param String $message
     *     The name of the subscription to trigger.
     * @param $data
     *     The data to pass to the callback.
     * @return Boolean $called
     *     Returns true or false depending on if at least one callback was triggered.
     */
    public static function send($message, $data) {

        $called = false;

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
                    $called = true;
                }
            }
        }

        return $called;
    }
}
