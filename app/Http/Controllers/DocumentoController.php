<?php

namespace App\Http\Controllers;

use App\Models\Documentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    private static $base_api = 'http://apim-canais.hom.sicredi.net:8280/';
    protected static $credential = 'Q2JQVFljNk45ZF92ZDAxdDJ3ejYySlpnU2tnYTowd3EzSE5XVEtMNTBWZUhtTXZlMWhMNXNlamNh';

    public function getFolderByCpf()
    {
        // $c = curl_init(self::$base_api.'token?grant_type=client_credentials');
        // curl_setopt($c, CURLOPT_POST, 1);
        // curl_setopt($c, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        // curl_setopt($c, CURLOPT_HTTPHEADER, [
        //     'Host: 15.228.95.130',
        //     'Authorization: Bearer '.self::$credential
        // ]);
        // curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        // $resp = curl_exec($c);
        // $ce = curl_errno($c);
        // if (!$resp) {
        //     $error = curl_error($c);
        //     dd($ce, $error);
        // }
        // dd($resp);
        // curl_close($c);

        $request = Http::withToken('Bearer '.self::$credential)
        ->withoutVerifying()
        ->post(self::$base_api.'token?grant_type='.self::$credential,[
            'grant_type' => 'client_credentials'
        ])
        ->json();
        dd($request);



        // $docs = Documentos::getAll();
        // $cpfList = [];

        // foreach ($docs as $doc) {
        //     $dir = explode('/', $doc);
        //     $cpf = $dir[2];

        //     if (!in_array($cpf, $cpfList)) {
        //         array_push($cpfList, $cpf);
        //     }
        // }

        // foreach ($cpfList as $cpf) {
        //     $folder = Http::post(self::$base_api.'ged-document/document/search', [
        //         "filters" => [
        //         [
        //             "attributeType" => "METADATA",
        //             "name" => "xnrodocumento",
        //             "value" => $cpf,
        //             "logicOperator" => "AND",
        //             "comparisonOperator" => "EQUAL"
        //         ],
        //         [
        //             "attributeType" => "DOCUMENT",
        //             "name" => "TYPE_DOCUMENT",
        //             "value" => "TD_PESSOA_VIRTUAL",
        //             "logicOperator" => "AND",
        //             "comparisonOperator" => "EQUAL"
        //         ]
        //         ],
        //         "page" => 0,
        //         "searchCriteria" => null,
        //         "size" => 1,
        //         "sortDirection" => "ASC",
        //         "sortField" => [
        //             "CODE"
        //         ]
        //     ])->json();

        //     dd($folder);
        // }
    }
}
