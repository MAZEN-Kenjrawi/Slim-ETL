<?php
namespace Tests;

use Tests\TestCase;
use ETL\Models\ETL;

use ETL\Models\ExtractorCSV;
use ETL\Models\Transformer;
use ETL\Models\Loader;

class ETLTest extends TestCase
{
    protected $ETL;

    protected $Extractor;
    protected $Transformer;
    protected $Loader;

    public function setUp() {
        $this->Extractor = new ExtractorCSV;
        $this->Transformer = new Transformer;
        $this->Loader = new Loader;
        $this->ETL = new ETL($this->Extractor, $this->Transformer, $this->Loader);
    }

    public function tearDown() {
        
    }

    /** @test */
    public function test_empty_data()
    {
        $this->assertEquals($this->ETL->getData(), []);
    }

    /** @test */
    public function test_extract_config_hash()
    {
        $config = ['file_path' => __DIR__.'./data/csv_main_sample.csv'];

        $sortBy = [];
        $config = ['file_path' => __DIR__.'./data/csv_main_sample.csv'];
        $expectedHash = md5($this->Extractor->extract($config)->getArgumentsHash().md5(serialize($sortBy)));

        $this->assertEquals($this->ETL->extract($config)->transform($sortBy)->getArgumentsHash(), $expectedHash);
    }

    /** @test */
    public function test_transform_and_hash()
    {
        $sortBy = [1 => 'ASC'];
        $config = ['file_path' => __DIR__.'./data/csv_main_sample.csv'];
        $expectedHash = md5($this->Extractor->extract($config)->getArgumentsHash().md5(serialize($sortBy)));
        
        $this->assertEquals($this->ETL->extract($config)->transform($sortBy)->getArgumentsHash(), $expectedHash);
    }

    /** @test */
    public function test_load_and_hash()
    {
        require_once __DIR__.'/../bootstrap/functions.php';
        $sortBy = [1 => 'ASC'];
        $config = ['file_path' => __DIR__.'./data/csv_main_sample.csv'];
        $expectedHash = md5($this->Extractor->extract($config)->getArgumentsHash().md5(serialize($sortBy)));
        $ETL = $this->ETL->extract($config)->transform($sortBy)->load('json', __DIR__.'./data/');

        $this->assertEquals($ETL->getArgumentsHash(), $expectedHash);
        $this->assertEquals($ETL->getResponse(),  __DIR__.'./data/json_'.$ETL->getArgumentsHash().'.json');
    }
}