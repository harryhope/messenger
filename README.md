Messenger
=========

A straightforward php publish/subscribe library.

## Usage

Messenger lets you subscribe to functions and then trigger them when messages
are sent from other parts of your application.

To subscribe:

```php
\Messenger::on('name change', function($name) {
    print 'Hello, ' . $name;
});
```

Named or anonymous functions can be used as subscriptions.
```php
$greeting = function($name) {
    print 'How are you today, ' . $name;
};

\Messenger::on('name change', $greeting);

```

Unsubscribe to a specific message + named callback combination, or to
all messages of a given message name by calling the off method without the
second parameter.

```php
// Remove one message + callback pairing
\Messenger::off('name change', $greeting);

// Remove everything with the message 'name change'
\Messenger::off('name change');
```

Use the send method to trigger associated subscriptions.

```php
\Messenger::send('name change', 'Dave');
```

Messenger also allows for method chaining.
```php
\Messenger::on('name change', $change_name)->on('day change', $change_day);

\Messenger::send('name change', 'Dave')
         ->send('day change', 'Tuesday');
```
