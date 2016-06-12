# JDBRBAC

RBAC

## Install

Via Composer

``` bash
$ composer require niceforbear/jdbrbac
```

## Usage

Firstly, please update the config in `commands\Common.php` and change some consts in `helpers\RbacConsts.php`.
  
You need to modify these values:

1. `helpers\RbacConsts.php` : SYSTEM_ID, DB_TABLE_PREFIX, DB_CONNECT_DB
2. `commands\Common.php` : $sourceData

How to use it?

1. Use `Initial::route()` to initial the routes which you have updated in `Common::$dataSource`.
2. Assign permissions and roles. Assign some role.id to user.id.
3. Use the only check method.

``` php
use niceforbear\jdbrbac\modules\CheckModule;

$result = CheckModule::isAllowed($userId);
```

If the user is allowed, the `$result` is true, else the value is false.

## Testing

Tests unavailable.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.