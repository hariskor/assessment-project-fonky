<?php

namespace App\Traits;

trait CSVReaderTrait
{
    /**
     *@param string $path
     *@param bool $withHeader
     *
     * @return array
     */
    public static function read($path, $withHeader = false): array
    {
        $rows = array_map('str_getcsv', file($path));

        if ($withHeader)
        {
            $header = array_shift($rows);
            $csv = [];
            foreach ($rows as $key=>$row)
            {
                $csv[] = array_combine($header, $row);
                if ($key == 5)
                {
                    break;
                }
            }
        }
        else
        {
            return $rows;
        }

        return $csv;
    }
}
