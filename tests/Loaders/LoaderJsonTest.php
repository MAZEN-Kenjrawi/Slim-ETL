<?php
namespace Tests\Loaders;

use Tests\TestCase;
use ETL\Models\Loader;

class LoaderJsonTest extends TestCase
{
    protected $data = [];
    protected $headingFields = [];

    protected $Loader;

    public function setUp() {
        # Removing all outputed files
        foreach(glob(__DIR__.'/../data/*.json') as $jsonFile) {
            if(basename($jsonFile) != 'json_output_test_json_fixed.json') {
                unlink($jsonFile);
            }
        }

        $this->data = [
            [1,'PHP Developer','Mazen Kenjrawi'],
            [4,'Dummy Classes','Foo Bar'],
        ];

        $this->headingFields = ['id', 'Title', 'Name'];

        $this->Loader = new Loader();
    }

    public function tearDown() {
        # Removing all outputed files
        foreach(glob(__DIR__.'/../data/*.json') as $jsonFile) {
            if(basename($jsonFile) != 'json_output_test_json_fixed.json') {
                unlink($jsonFile);
            }
        }
    }
    /** @test */
    public function test_generating_json_file()
    {
        $expectedJson = '[{"id":1,"title":"PHP Developer","name":"Mazen Kenjrawi"},{"id":4,"title":"Dummy Classes","name":"Foo Bar"}]';
        $this->assertEquals($expectedJson, $this->Loader->load('json', $this->data, $this->headingFields, 'output_test_json', __DIR__.'/../data/'));
    }

    /** @test */
    public function test_output_already_exist_json_file()
    {
        $Loader = new Loader();

        $expectedJson = '[{"id":1,"title":"PHP Developer","name":"Mazen Kenjrawi"},{"id":4,"title":"Dummy Classes","name":"Foo Bar"}]';
        $this->assertEquals($expectedJson, $Loader->load('json', $this->data, $this->headingFields, 'output_test_json_fixed', __DIR__.'/../data/'));
    }
}