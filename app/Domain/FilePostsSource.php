<?php

namespace App\Domain;

use Generator;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;
use OpenSpout\Reader\CSV\Reader as CsvReader;
use OpenSpout\Reader\ODS\Reader as OdsReader;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;

class FilePostsSource implements PostsSource
{
    public function __construct(
        private readonly UploadedFile $file,
    )
    {
    }

    public function posts(): Generator
    {
        $ext = $this->file->extension();
        $reader = match ($ext) {
            'xlsx' => new XlsxReader(),
            'csv' => new CsvReader(),
            'ods' => new OdsReader(),
            default => throw new InvalidArgumentException('Invalid file extension'),
        };
        $reader->open($this->file->getRealPath());
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $cells = $row->getCells();
                yield new Post($cells[0]->getValue(), $cells[1]->getValue());
            }
        }
    }
}
