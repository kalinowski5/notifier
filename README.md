# Notifier app

## Architecture overview
* It uses adapter pattern for `NotificationChannel`, which allows to implement multiple types of notification providers: 
  * `AmazonSES`
  * `Twilio`
  * `Pushy`
* For test and demonstration purposes, additional implementations were added:
  * `DevNull`
  * `Spyable`
  * `AlwaysBroken`
  * `ChaosMonkey`
* System can operate using two delivery policies: 
  * `ALL_CHANNELS`, which sends notifications via all working providers each time
  * `FIRST_WORKING_CHANNEL`, which send notifications only via first working provider (fail-over)
* Delivery policy and list of available channels can be set in the configuration file: `config/services.yml`

## Tradeoffs and possible improvements
Because of time constraints, I had to postpone the following improvements that could be made in real-world solution:
* Use layered architecture (Application, Domain, Infrastructure, UI)
* Make it asynchronous (ie. using Symfony Messenger and some transport like `RabbitMQ` or `Amazon SQS`)
* Extract `NotificationLogger` interface and add multiple implementations
