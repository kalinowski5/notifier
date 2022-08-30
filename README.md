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
* Each sending attempt is logged in the database with the following data: customer, time, channel, status and possible failure reason (see `notification_log` table)
* All sensitive data (secrets, api keys, etc.) is stored in gitignored .env.local file and injected into services via container

## Tradeoffs and possible improvements
Because of time constraints, I had to postpone the following improvements that could be made in real-world solution:
* Use layered architecture namespace/directory structure (`Application`, `Domain`, `Infrastructure`, `UI`) instead of default Symfony's one (`Controller`, `Entity`, `Repository`, `Service`, ...)
* Make it asynchronous (ie. using Symfony Messenger and some transport like `RabbitMQ` or `Amazon SQS`)
* Extract `NotificationLogger` interface and add multiple implementations
* Use translations for messages
* Dockerize app

## System requirements
* PHP 8.1
* Composer

## Setup instructions

Just run the following command in your favorite CLI: `make init`

SQLite database will be automatically created and filled with sample data.

In order to send test notifications, use `make send-test-notification`.
