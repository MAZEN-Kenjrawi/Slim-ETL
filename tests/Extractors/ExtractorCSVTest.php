<?php
namespace Tests\Extractors;

use Tests\TestCase;
use ETL\Models\ExtractorCSV;

class ExtractorCSVTest extends TestCase
{
    protected $Extractor;

    public function setUp() {
        $this->Extractor = new ExtractorCSV();
    }
    
    /** @test */
    public function test_data()
    {
        $expectedCSVData = [
            ['1','Eldon Base for stackable storage shelf, platinum','Muhammed MacIntyre','0'],
            ['2','1.7 Cubic Foot Compact "Cube" Office Refrigerators','Barry French','2'],
            ['3','Cardinal Slant Ring Binder,\nHeavy Gauge Vinyl','Barry French','4'],
            ['4','','Clay Rozendal','11'],
            ['5','','Carlos Soltero','6']
        ];
        $csvExtractor = $this->Extractor->extract(['file_path' => __DIR__.'/../data/csv_main_sample.csv']);
        $this->assertEquals($expectedCSVData, $csvExtractor->getData());
    }

    /** @test */
    public function test_fetch_all_data_count_CSV_has_heading()
    {
        $expectedHeadingFields = ['id', 'Title', 'Name', 'Count'];
        $csvExtractor = $this->Extractor->extract(['file_path' => __DIR__.'/../data/csv_main_sample.csv']);
        $this->assertCount(5, $csvExtractor->getData());
        $this->assertEquals(5, $csvExtractor->getCountOfData());
        $this->assertEquals($expectedHeadingFields, $csvExtractor->getHeading());
    }

    /** @test */
    public function test_fetch_all_data_count_CSV_has_no_heading()
    {
        $expectedHeadingFields = ['row_0', 'row_1', 'row_2', 'row_3'];
        $config = [
            'file_path' => __DIR__.'/../data/csv_main_sample.csv',
            'has_heading' => false
        ];
        $csvExtractor = $this->Extractor->extract($config);
        $this->assertCount(6, $csvExtractor->getData());
        $this->assertEquals(6, $csvExtractor->getCountOfData());
        $this->assertEquals($expectedHeadingFields, $csvExtractor->getHeading());
    }
}