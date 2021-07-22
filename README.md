# AutoClaims Application Programming Interface

Below is a table information of the technologies used in development of this project.

Documentation: [PostMan](https://documenter.getpostman.com/view/3633314/Tzm5Fver) <br />
User Story: [Google Doc](https://docs.google.com/document/u/0/d/1JQ0tQeahNDf8cBveXeAWcvXREu4RQl-LwDEWxlxl8RY/mobilebasic) <br /> 
Design: [Figma](https://www.figma.com/file/IVAQPGLg8nXfJsQRNHXAaM/Curacel?node-id=984%3A1194) <br />


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
php artisan storage:link --force # Link storage folder to public folder
```

**The following are optional**

- View application process and request; [http://application-domain/telescope](http://application-domain/telescope) (this only works on local environment)

- Generate Entity Relation Diagram
```shell
# Make sure to install graphviz before running the genrate command
# Linux 
sudo apt install graphviz -y
# Mac
brew install graphviz
# Generate Command
php artisan generate:erd ./storage/diagram.svg --format=svg # Only on development/local
```
- Run a foreground cronjob for scheduled commands.
```shell
php artisan schedule:run
```

- Process Queues
```shell
php artisan queue:work
```

- PHPStan Code Check
```shell
composer error-check
```
