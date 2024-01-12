<?php

namespace App\Domain;

use InvalidArgumentException;
use OpenSpout\Common\Entity\Cell\StringCell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\CSV\Writer as CsvWriter;
use OpenSpout\Writer\ODS\Writer as OdsWriter;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;

class PostsExporter
{
    public function export(PostsSource $source, string $format): string
    {
        $writer = match($format) {
            'xlsx' => new XlsxWriter(),
            'csv' => new CsvWriter(),
            'ods' => new OdsWriter(),
            default => throw new InvalidArgumentException('Invalid export format'),
        };

        $titleCells = [];
        $contentCells = [];
        foreach ($source->posts() as $post) {
            $titleCells[] = new StringCell($post->getTitle(), null);
            // 32767 это лимит на количество символов в ячейке
            $contentCells[] = new StringCell(substr($post->getContent(), 0, 32767), null);
        }

        $tmpName = tempnam(sys_get_temp_dir(), 'posts_export_');

        $writer->openToFile($tmpName);
        $writer->addRow(new Row($titleCells));
        $writer->addRow(new Row($contentCells));
        $writer->close();

        return $tmpName;
    }
}
