<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

class AcessarRota1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pasta:cpf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rota de consulta de pastas pelo cpf';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $request = Request::create(route('documento.cpf'), 'GET');
        $response = app()->handle($request);
        $responseBody = json_decode($response->getContent(), true);
        dd($responseBody);
        return $responseBody;
        // return Command::SUCCESS;
    }
}
