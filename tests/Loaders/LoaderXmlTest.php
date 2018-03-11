<?php
namespace Tests\Loaders;

use Tests\TestCase;
use ETL\Models\Loader;

class LoaderXmlTest extends TestCase
{
    protected $data = [];
    protected $headingFields = [];

    protected $expectedXml;

    protected $Loader;

    public function setUp() {
        # Removing all outputed files
        foreach(glob(__DIR__.'/../data/*.xml') as $xmlFile) {
            if(basename($xmlFile) != 'xml_output_test_xml_fixed.xml') {
                unlink($xmlFile);
            }
        }

        $this->data = [
            [1,'PHP Developer','Mazen Kenjrawi'],
            [4,'Dummy Classes','Foo Bar'],
        ];

        $this->headingFields = ['id', 'Title', 'Name'];

        $this->Loader = new Loader();

        $expectedXml = new \DOMDocument('1.0', 'UTF-8');
        $root = $expectedXml->createElement("data");
        foreach($this->data as $row) {
            $xml_row = $expectedXml->createElement("row");
            foreach($this->headingFields as $key => $fieldName) {
                $xml_field = $expectedXml->createElement(strtolower($fieldName), $row[$key]);
                $xml_row->appendChild($xml_field);
            }
            $root->appendChild($xml_row);
        }
        $expectedXml->appendChild($root);

        $this->expectedXml = $expectedXml->saveXML();
    }

    public function tearDown() {
        # Removing all outputed files
        foreach(glob(__DIR__.'/../data/*.xml') as $xmlFile) {
            if(basename($xmlFile) != 'xml_output_test_xml_fixed.xml') {
                unlink($xmlFile);
            }
        }
    }
    /** @test */
    public function test_generating_xml_file()
    {
        $this->assertEquals($this->expectedXml, $this->Loader->load('xml', $this->data, $this->headingFields, 'output_test_xml', __DIR__.'/../data/'));
    }

    /** @test */
    public function test_output_already_exist_xml_file()
    {
        $Loader = new Loader();
        
        $this->assertEquals($this->expectedXml, $Loader->load('xml', $this->data, $this->headingFields, 'output_test_xml_fixed', __DIR__.'/../data/'));
    }
}