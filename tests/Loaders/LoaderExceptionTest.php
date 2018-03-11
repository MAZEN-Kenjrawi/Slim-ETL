<?php
namespace Tests\Loaders;

use Tests\TestCase;
use ETL\Models\Loader;

class LoaderExceptionTest extends TestCase
{
    protected $data = [];
    protected $headingFields = [];

    protected $Loader;

    public function setUp() {

        $this->data = [
            [1,'PHP Developer','Mazen Kenjrawi'],
            [4,'Dummy Classes','Foo Bar'],
        ];

        $this->headingFields = ['id', 'Title', 'Name'];

        $this->Loader = new Loader();
    }

    /** @test */
    public function test_unsupported_output_file()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unsupported format!');
        
        $expectedJson = '[{"id":1,"title":"PHP Developer","name":"Mazen Kenjrawi"},{"id":4,"title":"Dummy Classes","name":"Foo Bar"}]';
        $this->assertEquals($expectedJson, $this->Loader->load('exe', $this->data, $this->headingFields, 'output_test_json', __DIR__.'/../data/'));
    }
}