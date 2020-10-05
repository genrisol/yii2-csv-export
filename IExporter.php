<?php
namespace genrisol\export;
/**
 * Created by PhpStorm.
 * User: V.Gordijenko genrisol@gmai.com
 * Date: 2020.09.30
 * Time: 11:08
 */


interface IExporter
{
    public function set(): \Generator;
    public function run(): void;
}