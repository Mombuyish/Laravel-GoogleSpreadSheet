# Laravel Google SpreadSheet

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yish/laravel-googlespreadsheet.svg?style=flat-square)](https://packagist.org/packages/yish/laravel-googlespreadsheet)
[![Build Status](https://img.shields.io/travis/yish/laravel-googlespreadsheet/master.svg?style=flat-square)](https://travis-ci.org/yish/laravel-googlespreadsheet)
[![Total Downloads](https://img.shields.io/packagist/dt/yish/laravel-googlespreadsheet.svg?style=flat-square)](https://packagist.org/packages/yish/laravel-googlespreadsheet)

Google spreadsheet transforms to json and storing to file with laravel.

## Installation

You can install the package via composer:

```bash
composer require yish/laravel-googlespreadsheet
```

## Usage

``` php
// $sheet_id = your google spreadsheet id.
// $range = you need column range, like 'Class Data!A2:E', if you use chinese, using double quote. "'首頁'!A2:E".
// $title = you want to set which one be a title key.
// $unset = you want to unset which columns. 
// $scope = Google_Service_Sheets::SPREADSHEETS_READONLY.
GoogleSpreadSheet::json($sheet_id, $range, $title = 0, $unset = [], $scope = null)
GoogleSpreadSheet::json($sheet_id, 'index!A2:E', 0, [1, 2]) // get the sheet and set 0 column to be title key, unset column 1 and column 2.
// You can chain the storeAs.
GoogleSpreadSheet::json($sheet_id, 'index!A2:E', 0, [1, 2])->storeAs($path, $disk = 'public')
// Or you can use feed.
GoogleSpreadSheet::feed($sheet_id, $sheet = 1, $format = 'json')
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mombuartworks@gmail.com instead of using the issue tracker.

## Credits

- [Yish](https://github.com/mombuyish)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.