<?php
namespace genrisol\export;
/**
 * Created by PhpStorm.
 * User: V.Gordijenko genrisol@gmai.com
 * Date: 2020.09.30
 * Time: 11:06
 */

use yii\db\ActiveQuery;

class CsvExport extends Export implements IExporter
{
    use ExportTrait;

    /**
     * @return Generator
     */
    public function set(): \Generator
    {
        $this->openOutputStream();
        fputcsv($this->file, $this->getLabels(), $this->delimiter);
        foreach ($this->model->all() as $data) {
            $line = $this->getLine($data, $this->columns);
            yield fputcsv($this->file, $line, $this->delimiter);
        }
    }

    /**
     * Get data from Active Query (YII2)
     */
    public function run(string $filename): void
    {
        $this->setFilename($filename);

        $generator = $this->set();

        while ($generator->valid()) {
            $generator->next();
        }

        $this->closeOutputStream();

        die();
    }
}


