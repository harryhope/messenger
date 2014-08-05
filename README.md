messenger
=========

A straightforward php publish/subscribe library.

### Usage

Messenger lets you subscribe to functions and then trigger them when messages
are sent from other parts of your application.

- To subscribe:

```
Messenger::on('name change', function($name) {
    print 'Hello, ' . $name;
});
```

- You can have as many subscriptions to a given message as you like, and you can
pass in named functions instead of anonymous ones.

```
$greeting = function($name) {
    print 'How are you today, ' . $name;
};

Messenger::on('name change', $greeting);

```

- You can unsubscribe to a specific message + named callback combination, or to
all messages of a given message name by calling the off method without the
second parameter.

```
// Remove one message + callback pairing
Messenger::off('name change', $greeting);

// Remove everything with the message 'name change'
Messenger::off('name change');
```

- To send a message and have it trigger associated functions, use the send
method.

```
Messenger::send('name change', 'Dave');
```
