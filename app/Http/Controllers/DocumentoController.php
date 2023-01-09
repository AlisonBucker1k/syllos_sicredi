<?php

namespace App\Http\Controllers;

use App\Models\Documentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    protected string $cpfCnpj;
    protected string $tipoPessoa;

    private static $base_api = 'http://apim-canais.hom.sicredi.net:8280/';
    protected static $credential = 'Q2JQVFljNk45ZF92ZDAxdDJ3ejYySlpnU2tnYTowd3EzSE5XVEtMNTBWZUhtTXZlMWhMNXNlamNh';

    public function newDocument()
    {
        return view('newDocument');
    }
    protected function getDefaultBody(?string $title = null)
    {
        $titulo = $title ? $title : $this->cpfCnpj;

        return [
            "author" => "app_ged_syllosdoc",
            "roles" => [
                "sg_pessoa"
            ],
            "idProfile" => "PER_MIGRACAO",
            "comments" => [
                "Criado pela migração do SyllosDoc para o GED"
            ],
            "metadatas" => [
                [
                    "key" => "xsglsistemaorigem",
                    "value" => "syllosdoc"
                ],
                [
                    "key" => "xcpfcnpj",
                    "value" => $this->cpfCnpj
                ],
                [
                    "key" => "xnompessoa",
                    "value" => $this->cpfCnpj
                ],
                [
                    "key" => "xtpopessoa",
                    "value" => $this->tipoPessoa
                ],
                [
                    "key" => "xcodcooperativa",
                    "value" => "0167"
                ]
            ],
            "partition" => "SYLLOSDOC_GED",
            "publicDocument" => true,
            "systemOrigin" => "SYLLOSDOC",
            "tags" => [
                $this->cpfCnpj
            ],
            "title" => $titulo,
            "typeDocument" => "PASTA_VIRTUAL",
            "virtual" => true,
            "entityOwner" => [
                "cooperative" => "0167",
                "agency" => "01",
                "context" => "cas-p"
            ],
            "idAuthenticity" => "ORIGINAL"
        ];
    }

    protected function createFolder(string $title)
    {
        $body = $this->getDefaultBody($title);

        $request = Http::withHeaders([
            'userLogged' => 'sysadmin',
        ])->withToken($this->getToken())
            ->attach('in', json_encode($body))
            ->post(self::$base_api . 'ged-document/document');

        dd($request);
    }

    public function newDocumentAction(Request $request)
    {
        $fullPath = $request->caminho;
        $fullPath = str_replace("\\", "/", $fullPath);
        $paths = explode("/", $fullPath);

        $this->cpfCnpj = $paths[0];
        $this->tipoPessoa =  (strlen($this->cpfCnpj) > 11) ? "PJ" : "PF";

        foreach ($paths as $path) {
            $this->createFolder($path);
        }
    }

    public function getDocument($document_id)
    {
        $resp = Http::withHeaders([
            'userLogged' => 'sysadmin'
        ])
            ->withToken($this->getToken())
            ->get(self::$base_api . "ged-document/document/{$document_id}");

        if ($resp->successful()) {
            dd($resp);
        }
        dd($resp);
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

        $c = curl_init(self::$base_api . 'ged-document/document/search');
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($body));
        curl_setopt($c, CURLOPT_HTTPHEADER, [
            'Host: 15.228.95.130',
            'Authorization: ' . $this->getToken()
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
        $c = curl_init(self::$base_api . 'token?grant_type=client_credentials');
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($c, CURLOPT_HTTPHEADER, [
            'Host: 15.228.95.130',
            'Authorization: Bearer ' . self::$credential
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
