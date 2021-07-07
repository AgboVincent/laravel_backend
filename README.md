# AutoClaims Application Programming Interface

Below is a table information of the technologies used in development of this project.

Documentation: [PostMan](https://documenter.getpostman.com/view/3633314/Tzm5Fver)

| Technology | Version |
------------- | --------------
| PHP Version | ^8.0 |
| Laravel | ^8.* |
| Telescope (dev) | 4.5 |

## Project Installation
### Development

In other to install, download composer at [https://getcomposer.org/download/](https://getcomposer.org/download/)

```shell
composer install # Install Project Dependencies
cp .env.example .env # Copy environment config file and make sure to configure before next step
php artisan migrate --seed --step # Run database migration and seed files
```

**The following are optional**

- View application process and request; [http://application-domain/telescope](http://application-domain/telescope) (this only works on local environment)

- Run a foreground cronjob for scheduled commands.
```shell
php artisan schedule:run
```

- Process Queues
```shell
php artisan queue:work
```
