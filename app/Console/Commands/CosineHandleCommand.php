<?php

namespace App\Console\Commands;

use App\Services\AppService;
use App\Services\CosineService;
use Illuminate\Console\Command;

class CosineHandleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cosine:handle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cosine similarity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        AppService::setCurrentLanguage(1);
//        CosineService::createFrameNetwork();
//        CosineService::createLinkSentenceAnnotationToFrame(1478);
//        CosineService::createLinkSentenceAnnotationToFrame(1479);
        $pairs = [
            [602476, 602485],
            [602477, 602486],
            [602478, 602487],
            [602479, 602488],
            [602480, 602489],
            [602481, 602490],
            [602482, 602491],
            [602483, 602492],
            [602484, 602493]
        ];
        foreach($pairs as $pair) {
            CosineService::compareSentences($pair[0], $pair[1]);
        }


    }
}
