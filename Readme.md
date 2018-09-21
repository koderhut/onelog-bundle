# One Log ...
to rule them all

# Onelog Bundle
This bundle will help with wrapping all monolog and other loggers in a Symfony app
into a single logger entry point simplifying the logging needed in an app.

## Usages
- $onelog->debug('test', []); <-- wil proxy the data to the default logger, in case of Monolog the app logger is used
- $onelog->my_logger->debug('test', []); <-- will forward the call to the logger my_logger, in case of Monolog to the my_logger channel
- \OneLog::debug('test', []); <-- shortcut to the default logger instance
- \OneLog::instance()->my_logger->debug('test', []); <-- shortcut for accessing a specific logger instance

## To be added:
- $onelog->logObject($object, []);

## LICENSE
Please review the LICENSE file