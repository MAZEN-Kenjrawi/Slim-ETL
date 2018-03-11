<?php

namespace Tests\Loaders;

use ETL\Models\Transformer;
use Tests\TestCase;

class TransformerTest extends TestCase
{
    protected $data = [];
    protected $headingFields = [];

    protected $Transformer;

    public function setUp()
    {
        require_once __DIR__.'/../../bootstrap/functions.php';
        $this->data = [
            [2, '1.7 Cubic Foot Compact "Cube" Office Refrigerators', 'Barry French', 2.2],
            [1, 'Eldon Base for stackable storage shelf, platinum', 'Muhammed MacIntyre', 0.5],
            [4, '', 'Blay Rozendal', 11],
            [3, 'Cardinal Slant Ring Binder,\nHeavy Gauge Vinyl', 'Barry French', 4],
            [5, '', 'Carlos Soltero', 6],
        ];

        $this->headingFields = ['id', 'Title', 'Name', 'Count'];

        $this->Transformer = new Transformer();
    }

    /** @test */
    public function test_data_sorting_by_id()
    {
        $sortedDataByID = [
            [1, 'Eldon Base for stackable storage shelf, platinum', 'Muhammed MacIntyre', 0.5],
            [2, '1.7 Cubic Foot Compact "Cube" Office Refrigerators', 'Barry French', 2.2],
            [3, 'Cardinal Slant Ring Binder,\nHeavy Gauge Vinyl', 'Barry French', 4],
            [4, '', 'Blay Rozendal', 11],
            [5, '', 'Carlos Soltero', 6],
        ];

        $this->Transformer->transform($this->data, $this->headingFields)->sort_by('id', 1);
        $this->assertEquals($this->Transformer->getSortedData(), $sortedDataByID);

        $expectedResultByArray = ['0' => 'ASC'];
        $this->assertEquals($this->Transformer->getSortingBy(), $expectedResultByArray);
    }

    /** @test */
    public function test_data_sorting_by_id_single_item_in_array()
    {
        $sortedDataByID = [
            [1, 'Eldon Base for stackable storage shelf, platinum', 'Muhammed MacIntyre', 0.5],
            [2, '1.7 Cubic Foot Compact "Cube" Office Refrigerators', 'Barry French', 2.2],
            [3, 'Cardinal Slant Ring Binder,\nHeavy Gauge Vinyl', 'Barry French', 4],
            [4, '', 'Blay Rozendal', 11],
            [5, '', 'Carlos Soltero', 6],
        ];

        $this->Transformer->transform($this->data, $this->headingFields)->sort_by(['id'], 1);
        $this->assertEquals($this->Transformer->getSortedData(), $sortedDataByID);

        $expectedResultByArray = ['0' => 'ASC'];
        $this->assertEquals($this->Transformer->getSortingBy(), $expectedResultByArray);
    }

    /** @test */
    public function test_data_sorting_by_id_and_count()
    {
        $sortedDataByID = [
            [1, 'Eldon Base for stackable storage shelf, platinum', 'Muhammed MacIntyre', 0.5],
            [2, '1.7 Cubic Foot Compact "Cube" Office Refrigerators', 'Barry French', 2.2],
            [3, 'Cardinal Slant Ring Binder,\nHeavy Gauge Vinyl', 'Barry French', 4],
            [5, '', 'Carlos Soltero', 6],
            [4, '', 'Blay Rozendal', 11],
        ];

        $this->Transformer->transform($this->data, $this->headingFields)->sort_by(['id', 'count'], 1);
        $this->assertEquals($this->Transformer->getSortedData(), $sortedDataByID);

        $expectedResultByArray = ['0' => 'ASC', '3' => 'ASC'];
        $this->assertEquals($this->Transformer->getSortingBy(), $expectedResultByArray);

        // $expectedHash = md5(serialize($expectedResultByArray));
        // $this->assertEquals($this->Transformer->getArgumentsHash(), $expectedHash);
    }

    /** @test */
    public function test_data_sorting_by_count()
    {
        $sortedDataByCOUNT = [
            [1, 'Eldon Base for stackable storage shelf, platinum', 'Muhammed MacIntyre', 0.5],
            [2, '1.7 Cubic Foot Compact "Cube" Office Refrigerators', 'Barry French', 2.2],
            [3, 'Cardinal Slant Ring Binder,\nHeavy Gauge Vinyl', 'Barry French', 4],
            [5, '', 'Carlos Soltero', 6],
            [4, '', 'Blay Rozendal', 11],
        ];

        $this->Transformer->transform($this->data, $this->headingFields)->sort_by('cOunt', 1);
        $this->assertEquals($this->Transformer->getSortedData(), $sortedDataByCOUNT);

        $expectedResultByArray = [3 => 'ASC'];
        $this->assertEquals($this->Transformer->getSortingBy(), $expectedResultByArray);
    }

    /** @test */
    public function test_data_sorting_by_title()
    {
        $sortedDataByTITLE = [
            [1, 'Eldon Base for stackable storage shelf, platinum', 'Muhammed MacIntyre', 0.5],
            [3, 'Cardinal Slant Ring Binder,\nHeavy Gauge Vinyl', 'Barry French', 4],
            [2, '1.7 Cubic Foot Compact "Cube" Office Refrigerators', 'Barry French', 2.2],
            [4, '', 'Blay Rozendal', 11],
            [5, '', 'Carlos Soltero', 6],
        ];

        $this->Transformer->transform($this->data, $this->headingFields)->sort_by('Title', 0);

        $this->assertEquals($this->Transformer->getSortedData(), $sortedDataByTITLE);

        $expectedResultByArray = ['1' => 'DESC'];
        $this->assertEquals($this->Transformer->getSortingBy(), $expectedResultByArray);
    }
}
