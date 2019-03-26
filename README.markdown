# CSV to SQL

This project is a utility tool that allows to convert a CSV file to a SQL one.

## Why ?

It's quite common to have a CSV file that represents datas
because it has been exported from Excel.  

That's when we need to convert it as an SQL script that the problems begin.

## Installation

It's a Composer package so you only need to use:

`composer require anthonybocci\csv-to-sql`

## Usage

```csv
ID;FIRSTNAME;LASTNAME
1;John;Doe
2;Jane;Doe
```

```php
<?php
$converter = new AnthonyBocci\Convert\CsvToSql(
        'path/to/csv/file.csv',
        'myTableName',
        'path/to/sql/file.sql
        );
$converter->toInsert([0, 1, 2]);
```

The SQL file will use the columns 0 (ID), 1 (FIRSTNAME) and 2 (LASTNAME).

## Warning

This project is not under development, it's a utility tool that I use when I
need to, it's not perfect and doesn't aim to be used in production.
