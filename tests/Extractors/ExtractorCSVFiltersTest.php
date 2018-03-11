<?php

namespace Tests\Extractors;

use ETL\Models\ExtractorCSV;
use Tests\TestCase;

class ExtractorCSVFiltersTest extends TestCase
{
    protected $Extractor;

    public function setUp()
    {
        $this->Extractor = new ExtractorCSV();
    }

    /** @test */
    public function test_custom_filter_functions()
    {
        require_once __DIR__.'/../../bootstrap/functions.php';

        $expectedCSVData = [
            [1, 'Eldon Base for stackable storage shelf, platinum', 'Muhammed MacIntyre', 0.5],
            [2, '1.7 Cubic Foot Compact "Cube" Office Refrigerators', 'Barry French', 2.2],
            [3, 'Cardinal Slant Ring Binder,\nHeavy Gauge Vinyl', 'Barry French', 4],
            [4, '', 'Clay Rozendal', 11],
            [5, '', 'Carlos Soltero', 6],
        ];
        $config = [
            'file_path' => __DIR__.'/../data/csv_main_sample_1.csv',
            'filters'   => [0 => 'valid_int', 1 => 'trim|strip_tags|clean_string', 2 => 'trim|strip_tags|clean_string', 3 => 'valid_float'],
        ];
        $csvExtractor = $this->Extractor->extract($config);
        $this->assertEquals($expectedCSVData, $csvExtractor->getData());
    }

    /** @test */
    public function test_exeption_thrown_filter_does_not_exist()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Filter (foo) does not exist!');

        $expectedCSVData = [
            [1, 'Eldon Base for stackable storage shelf, platinum', 'Muhammed MacIntyre', 0.5],
            [2, '1.7 Cubic Foot Compact "Cube" Office Refrigerators', 'Barry French', 2.2],
            [3, 'Cardinal Slant Ring Binder,\nHeavy Gauge Vinyl', 'Barry French', 4],
            [4, '', 'Clay Rozendal', 11],
            [5, '', 'Carlos Soltero', 6],
        ];
        $config = [
            'file_path' => __DIR__.'/../data/csv_main_sample_1.csv',
            'filters'   => [0 => 'foo'],
        ];
        $csvExtractor = $this->Extractor->extract($config);
        $this->assertEquals($expectedCSVData, $csvExtractor->getData());
    }

    /** @test */
    public function test_exeption_thrown_key_does_not_exist()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Field (7) does not exist!');

        $expectedCSVData = [
            [1, 'Eldon Base for stackable storage shelf, platinum', 'Muhammed MacIntyre', 0.5],
            [2, '1.7 Cubic Foot Compact "Cube" Office Refrigerators', 'Barry French', 2.2],
            [3, 'Cardinal Slant Ring Binder,\nHeavy Gauge Vinyl', 'Barry French', 4],
            [4, '', 'Clay Rozendal', 11],
            [5, '', 'Carlos Soltero', 6],
        ];
        $config = [
            'file_path' => __DIR__.'/../data/csv_main_sample_1.csv',
            'filters'   => [7 => 'foo'],
        ];
        $csvExtractor = $this->Extractor->extract($config);
        $this->assertEquals($expectedCSVData, $csvExtractor->getData());
    }
}
