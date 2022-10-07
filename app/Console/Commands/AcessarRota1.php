<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        route('documento.cpf');
        return Command::SUCCESS;
    }
}
