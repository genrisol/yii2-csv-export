<?php
namespace genrisol\export;
/**
 * Created by PhpStorm.
 * User: V.Gordijenko genrisol@gmai.com
 * Date: 2020.09.30
 * Time: 11:13
 */
class Export
{
    /**
     * @var array|object
     */
    protected $model;

    /**
     * @var array
     */
    protected $columns;

    /*
     * @var array
     */
    private $labels = [];

    /**
     * @var string
     */
    protected $filename;

    /*
     * Array of content types
     */
    private $contentTypes = [
        'csv' => 'application/csv',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    /*
     * Content type
     */
    private $contentType;

    /**
     * @var false|resource
     */
    protected $file;

    /**
     * @var string
     */
    protected $delimiter = ";";

    /**
     * example
     *  $query = \app\models\History::find()
     *    ->addSelect('history.*')
     *    ->with([
     *     'user',
     *     'customer',
     *     'sms',
     *     'task',
     *     'call',
     *     'fax',
     *    ]);
     *    $columns = [
     *      // simple columns
     *      ['label' => 'Date&Time', 'value' => 'ins_ts'],
     *      ['label' => 'Type', 'value' => 'object'],
     *      ['label' => 'Event', 'value' => 'eventText'],
     *      // realtion  use examlpe
     *      ['label' => 'Event', 'nested' => 'user', 'value' => 'username'],
     *      // closure (function) use  example
     *      ['label' => 'Message', 'value' => function ($model){
     *           return $model->getBodyByModel();
     *      }],
     *    ];
     *    (new \app\helpers\export\CsvExport($query, $columns, 'histiory_'.time().'.csv'))->run();

     *
     * @param array | object $model
     * @param array $columns
     * @param string $filename
     */
    protected function prepare($model, array $columns, string $filename)
    {
        $this->setModel($model);

        $this->labels = $this->columns = [];
        foreach ($columns as $item) {
            if (is_array($item)) {
                // label (field name as captions)
                $label = $item['label'] ?? '-';
                array_push($this->labels, $label);
                // field
                $fld = $item['value'] ?? null;
                // for realtions or nested array
                $nested = $item['nested'] ?? null;
                if ($nested)
                    $this->columns[$nested] = [$fld];
                else
                    array_push($this->columns, $fld);

            } else {
                array_push($this->columns, $item);
                array_push($this->labels, $item);
            }
        }

        $this->setFilename($filename);
        $this->setContentType();
    }

    /**
     * @param object | array $model
     */
    protected function setModel($model): void
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    protected  function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param string $filename
     */
    protected function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * Set content type
     */
    public function setContentType()
    {
        preg_match('/(?:.csv|.xlsx)/i', $this->filename, $parts);

        if (!$parts[0]) {
            $this->filename = $this->filename . '.csv';
            $this->contentType = $this->contentTypes['csv'];
        } else {
            $this->contentType = $this->contentTypes[trim(strtolower($parts[0]), '.')];
        }

        header("Content-Type: {$this->contentType}");
        header("Content-Disposition: attachment; filename={$this->filename};");
    }

    /**
     * Open the "output" stream
     */
    protected function openOutputStream()
    {
        $this->file = fopen('php://output', 'w');
    }

    /**
     * Close output stream
     */
    protected function closeOutputStream() {
        fclose($this->file);
    }
}