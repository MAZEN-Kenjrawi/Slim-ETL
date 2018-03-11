<?php

namespace ETL\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use ETL\Support\Contracts\ExtractorInterface;
use ETL\Support\Contracts\TransformerInterface;
use ETL\Support\Contracts\LoaderInterface;

use ETL\Models\ETL;

class ETLController
{
    protected $Extractor;
    protected $Transformer;
    protected $Loader;

    protected $format = '';
    protected $filters = [];
    protected $sort_by = [];

    protected $ETL;
    public function __construct(ExtractorInterface $Extractor, TransformerInterface $Transformer, LoaderInterface $Loader, $format = '', $filters = '', $sort = '') {
        $this->Extractor = $Extractor;
        $this->Transformer = $Transformer;
        $this->Loader = $Loader;

        $this->ETL = new ETL($Extractor, $Transformer, $Loader);
    }

    private function getArgumentsArray($passedString = '', $defaultControl = '') {
        if($passedString == '') {
            return [];
        }
        $array = [];
        $entries = explode('&', $passedString);
        foreach($entries as $entry) {
            $fieldReq = explode('-', $entry);
            if(count($fieldReq) > 0 && isset($fieldReq[1])) {
                $array[$fieldReq[0]] = $fieldReq[1];
            } elseif($defaultControl != '') {
                $array[$entry] = $defaultControl;
            }
        }

        return $array;
    }

    private function setSort($passedsort = '') {
        $this->sort_by = $this->getArgumentsArray($passedsort, 'ASC');
    }

    private function setFilters($passedfilters = '') {
        $this->filters = $this->getArgumentsArray($passedfilters);
    }

    public function default(Request $request, Response $response)
    {
        $defaultConfig = [
            'file_path' => './data_storage/hotels.csv',
            'filters' => [
                'name'      => 'trim|sanitize_ascii',
                'address'   => 'trim',
                'stars'     => 'trim|hotel_stars',
                'contact'   => 'trim',
                'phone'     => 'trim',
                'uri'       => 'clean_url'
            ]
        ];
        $responseETL['status'] = 'error';
        try {
            $responseETL['file'] = $this->ETL
                ->extract($defaultConfig)
                ->transform(['stars' => 'DESC'])
                ->load('json', './data_storage/')
                ->getResponse();
            $responseETL['status'] = 'success';
        } catch(\Exception $e) {
            $responseETL['error_messages'] = $e->getMessage();
        }

        header('Content-type', 'application/json');
        echo json_encode($responseETL);
        die;
    }

    protected function APIResponse($format = 'json') {
        $defaultConfig = [
            'file_path' => './data_storage/hotels.csv',
            'filters' => $this->filters
        ];
        $responseETL['status'] = 'error';
        try {
            $responseETL['file'] = $this->ETL
                ->extract($defaultConfig)
                ->transform($this->sort_by)
                ->load($format, './data_storage/')
                ->getResponse();
            $responseETL['status'] = 'success';
        } catch(\Exception $e) {
            $responseETL['error_messages'] = $e->getMessage();
        }

        header('Content-type', 'application/json');
        echo json_encode($responseETL);
        die;
    }

    public function index(Request $request, Response $response, $format = '', $filters = '', $sort = '') {
        $this->setFilters($filters);
        $this->setSort($sort);

        $this->APIResponse($format);
    }

    public function by_format(Request $request, Response $response, $format = '') {
        $this->APIResponse($format);
    }
}
