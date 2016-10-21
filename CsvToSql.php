<?php

namespace AnthonyBocci\Convert;

/**
 * @class CsvToSql
 * Convert a CSV file to SQL statements
 */
class CsvToSql
{
    /**
     * CSV file's name to read
     * @var string
     */
    private $csvFile;
    /**
     * SQL file's name to write
     * @var string
     */
    private $sqlFile;
    /**
     * Table to write
     * @var string
     */
    private $tableName;
    /**
     * CSV file content
     * @var string
     */
    private $csvContent;
    /**
     * String separator between cells
     * @var string
     */
    private $separator;
    /**
     * Insert statement
     * @var string
     */
    const INSERT = 'INSERT INTO %1$s VALUES %2$s';

    /**
     * Constructor
     * @param string $csvFile   CSV file to read
     * @param string $sqlFile   SQL file to write
     * @param string $tableName Table to write
     * @param string $separator Separator between cells
     * @param bool $read Should it read CSV file content ?
     */
    public function __construct($csvFile, $sqlFile, $tableName, $separator = ";", $read = true)
    {
        $this->csvFile = $csvFile;
        $this->sqlFile = !empty($sqlFile) ? $sqlFile : "out.sql";
        $this->tableName = $tableName;
        $this->separator = $separator;
        if ($read) {
            $this->read();
        }
    }
    
    /**
     * Read the CSV file content
     * @return CsvToSql this
     */
    public function read()
    {
        try {
            $this->csvContent = file_get_contents($this->csvFile);
            return $this;
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Convert the CSV file content into SQL insert statement
     * @param  array  $columnList An array that contains columns number to get.
     * Columns begin from 0.
     * @param  integer $firstLine  The first line to use. Lines begin from 0.
     * @return string              The sql statement
     */
    public function toInsert($columnList, $writeFile = true, $firstLine = 1)
    {
        //$columnList must be an array
        if (false === is_array($columnList)) {
            throw new Exception("$columnList should be an array");
        }
        //If there is no content, read it
        if (empty($this->csvContent)) {
            $this->read();
        }
        //Explode file to get lines
        $result = "";
        $lines = explode("\r\n", $this->csvContent);
        //Look over every line
        for ($i = $firstLine; $i < count($lines); $i ++) {
            //Explode to get every cell
            $cells = explode($this->separator, $lines[$i]);
            if (count($cells) <= 1) {
                continue;
            }
            //Look over every choosen column to add value into result
            $result .= "(";
            for ($j = 0; $j < count($columnList); $j++) {
                $result .= '"' . $cells[$columnList[$j]] . '"';
                if ($j+1 < count($columnList)) {
                    $result .= ", ";
                }
            }
            $result .= ")";
            //Add comma if there is an other line
            if ($i+1 < count($lines) -1) {
                $result .= ", ";
            }
        }
        $query = sprintf(self::INSERT , $this->tableName, $result);
        if ($writeFile) {
            file_put_contents($this->sqlFile, $query);
        } else {
            echo $query;
        }
    }
}
