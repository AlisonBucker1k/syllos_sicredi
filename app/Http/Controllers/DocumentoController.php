<?php

namespace App\Http\Controllers;

use App\Models\Documentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    private static $base_api = 'https://apim-canais.hom.sicredi.net:8243/';

    public function getFolderByCpf()
    {
        echo 'DISGRAçA'; exit;
        $docs = Documentos::getAll();
        $cpfList = [];

        foreach ($docs as $doc) {
            $dir = explode('/', $doc);
            $cpf = $dir[2];

            if (!in_array($cpf, $cpfList)) {
                array_push($cpfList, $cpf);
            }
        }

        foreach ($cpfList as $cpf) {
            $folder = Http::post(self::$base_api.'ged-document/document/search', [
                "filters" => [
                [
                    "attributeType" => "METADATA",
                    "name" => "xnrodocumento",
                    "value" => $cpf,
                    "logicOperator" => "AND",
                    "comparisonOperator" => "EQUAL"
                ],
                [
                    "attributeType" => "DOCUMENT",
                    "name" => "TYPE_DOCUMENT",
                    "value" => "TD_PESSOA_VIRTUAL",
                    "logicOperator" => "AND",
                    "comparisonOperator" => "EQUAL"
                ]
                ],
                "page" => 0,
                "searchCriteria" => null,
                "size" => 1,
                "sortDirection" => "ASC",
                "sortField" => [
                    "CODE"
                ]
            ])->json();

            dd($folder);
        }
    }
}
