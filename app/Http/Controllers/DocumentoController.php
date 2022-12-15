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



    public function newDocument()
    {
        $resp = Http::withHeaders([
            'userLogged' => 'sysadmin'
        ])
            ->withToken($this->getToken())
            ->get(self::$base_api.'ged-document/document/200606918')->json();

        // $c = curl_init(self::$base_api.'ged-document/document/200606918');
        // // curl_setopt($c, CURLOPT_POST, );
        // curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($c, CURLOPT_POSTFIELDS, '');
        // curl_setopt($c, CURLOPT_HTTPHEADER, [
        //     // 'Host: 15.228.95.130',
        //     // 'Authorization: '.$this->getToken(),
        //     // 'Content-Type: application/json',
        //     'userLoged: sysadmin'
        // ]);
        // curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        // $resp = curl_exec($c);
        // curl_close($c);

        // $response = json_decode($resp);

        dd($resp);

        // return view('newDocument');
    }

    public function newDocumentAction(Request $request)
    {
        $body = [
            'files' => [$request->file],
            'in' => [
                "typeDocument" => "RELATORIO_CORPORATIVO",
                "title" => "Relatório xxxx",
                "author" => "gustavo_medeiros",
                "roles" => [
                    "relatorio_corporativo"
                ],
                "metadatas" => [
                    [
                        "key" => "xdata",
                        "value" => "10/04/2021"
                    ],
                    [
                        "key" => "xusuario",
                        "value" => "gustavo_medeiros"
                    ]
                ],
                "tags" => [
                    "nao_obrigatorio"
                ],
                "comments" => [
                    "Comentário não obrigatório"
                ],
                "partition" => "RELATORIO",
                "systemOrigin" => "RelatorioCorporativoService",
                "virtual" => false,
                "publicDocument" => true,
                "entityOwner" => [
                    "context" => "cas-p"
                ],
                "flgRestricted" => true,
                "restricted" => ["gustavo_medeiros"],
                "flgExclusive" => false,
                "idAuthenticity" => "ORIGINAL",
                "checkDiscard" =>true,
                "limitStorageDate" =>"2021-06-20",
                "files" => [
                    [
                        "name" => $request->file,
                        "rendition" => "FULL"
                    ]
                ]
            ]
        ];

        $c = curl_init(self::$base_api.'ged-document/document');
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($c, CURLOPT_HTTPHEADER, [
            // 'Host: 15.228.95.130',
            // 'Authorization: '.$this->getToken(),
            'Content-Type: application/json',
            'userLoged: sysadmin'
        ]);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        $resp = curl_exec($c);
        curl_close($c);

        $response = json_decode($resp);

        dd($response);
    }

    public function getFolderByCpf()
    {
        $body = [
            "filters" => [
                [
                    "attributeType" => "METADATA",
                    "name" => "xnrodocumento",
                    "value" => "00475854985",
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
            [
                "page" => 0,
                "searchCriteria" => null,
                "size" => 1,
                "sortDirection" => "ASC",
                "sortField" => [
                    "CODE"
                ]
            ]
        ];

        $c = curl_init(self::$base_api.'ged-document/document/search');
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($body));
        curl_setopt($c, CURLOPT_HTTPHEADER, [
            'Host: 15.228.95.130',
            'Authorization: '.$this->getToken()
        ]);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        $resp = curl_exec($c);
        curl_close($c);

        $response = json_decode($resp);

        dd($response);

    }

    private function getToken()
    {
        $c = curl_init(self::$base_api.'token?grant_type=client_credentials');
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($c, CURLOPT_HTTPHEADER, [
            'Host: 15.228.95.130',
            'Authorization: Bearer '.self::$credential
        ]);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        $resp = curl_exec($c);
        curl_close($c);

        $response = json_decode($resp);

        return $response->access_token;
    }
}



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
