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
    public function on($message, callable $callback) {

        // Error Handling:
        // Make sure $message is a string and $callback is a callable function.
        if (!is_string($message))
            throw new InvalidArgumentException('First parameter of Messenger::on must be a string.');

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
    public function off($message,callable $callback = null) {

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
    public function send($message, $data) {

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

    /**
     * A static version of on()
     *
     * @param String $message
     *    The name of the subscription to add.
     * @param Closure Object $callback
     *    A callable function.
     * @return Messenger
     *     Returns a new instance of Messenger.
     */
    public static on($message, $callback) {
        $messenger = new Messenger;
        return $messenger->on($message, $callback);
    }
    
   /**
    * A static version of off()
    *
    * @param String $message
    *    The name of the subscription to remove.
    * @param Closure Object $callback
    *    The (optional) specific function to remove.
    * @return Messenger
    *     Returns a new instance of Messenger.
    */
   public static off($message, $callback = null) {
       $messenger = new Messenger;
       return $messenger->off($message, $callback);
   }

   /**
    * A static version of send()
    *
    * @param String $message
    *     The name of the subscription to trigger.
    * @param $data
    *     The data to pass to the callback.
    * @return Messenger
    *     Returns a new instance of Messenger.
    */
   public static send($message, $data) {
       $messenger = new Messenger;
       return $messenger->send($message, $data);
   }
}
