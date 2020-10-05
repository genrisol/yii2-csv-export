CSV exporter
============
CSV extension for Yii2 from Query

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist genrisol/yii2-csv-exporter "*"
```

or add

```
"genrisol/yii2-csv-exporter": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply example to use it in your code by  :

    $query = \app\models\History::find()
        ->addSelect('history.*')
         ->with([
            'user',
            'customer',
         ]);

    $columns = [
        ['label' => 'Date&Time', 'value' => 'ins_ts'],
        ['label' => 'Type', 'value' => 'object'],
        ['label' => 'Event', 'value' => 'eventText'],
        // value is joined table field value
        ['label' => 'Event', 'nested' => 'user', 'value' => 'username'],
        ['label' => 'Message', 'value' => function ($model){
                return $model->getBodyByModel();
            }
        ],
    ];

    (new \app\helpers\export\CsvExport($query, $columns, 'histiory_'.time().'.csv'))->run();