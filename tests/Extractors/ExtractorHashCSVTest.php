<?php
namespace Tests\Extractors;

use Tests\TestCase;
use ETL\Models\ExtractorCSV;

class ExtractorHashCSVTest extends TestCase
{
    protected $Extractor;
    
    public function setUp() {
        $this->Extractor = new ExtractorCSV();
    }

    /** @test */
    public function test_data()
    {
        $config = [
            'file_path' => __DIR__.'/../data/csv_main_sample_1.csv',
            'filters' => [0 => 'valid_int', 1 => 'trim|strip_tags|clean_string', 2 => 'trim|strip_tags|clean_string', 3 => 'valid_float']
        ];
        $csvExtractor = $this->Extractor->extract($config);

        $expectedHash = md5(filemtime(__DIR__.'/../data/csv_main_sample_1.csv').serialize($config));
        
        $this->assertEquals($expectedHash, $csvExtractor->getArgumentsHash());
    }
}