CSV exporter
============
CSV extension from Active Query (Yii2)

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
            ...
         ]);

    $columns = [
        ['label' => 'Date&Time', 'value' => 'ins_ts'],
        ['label' => 'Type', 'value' => 'object'],
        ['label' => 'Event', 'value' => 'eventText'],
        // relation used example
        ['label' => 'Event', 'nested' => 'user', 'value' => 'username'],
        // closure example
        ['label' => 'Message', 'value' => function ($model){
                return $model->getBodyByModel();
            }
        ],
    ];

    (new \genrisol\export\CsvExport($query, $columns, 'histiory_'.time().'.csv'))->run();