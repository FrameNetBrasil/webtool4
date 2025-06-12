<?php

namespace App\Console\Commands;

use App\Database\Criteria;
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
        //CosineService::createFrameNetwork();
//        CosineService::createLinkSentenceAnnotationTimeToFrame(614);
//        CosineService::createLinkSentenceAnnotationTimeToFrame(638);
//        CosineService::createLinkSentenceAnnotationTimeToFrame(617);
//        CosineService::createLinkSentenceAnnotationTimeToFrame(619);
//        CosineService::createLinkSentenceAnnotationTimeToFrame(631);
//        CosineService::createLinkSentenceAnnotationTimeToFrame(626);
//
        CosineService::createLinkObjectAnnotationTimeToFrame(614);
//        CosineService::createLinkObjectAnnotationTimeToFrame(638);
//        CosineService::createLinkObjectAnnotationTimeToFrame(617);
//        CosineService::createLinkObjectAnnotationTimeToFrame(619);
//        CosineService::createLinkObjectAnnotationTimeToFrame(631);
//        CosineService::createLinkObjectAnnotationTimeToFrame(626);
        $document = Criteria::byId("document","idDocument",614);
        CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_original_full_2.csv", CosineService::compareTimespan(614, 4, ''));
        CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_original_lu_2.csv", CosineService::compareTimespan(614, 4, 'lu'));
        CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_original_fe_2.csv", CosineService::compareTimespan(614, 4, 'fe'));
        CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_description_full_2.csv", CosineService::compareTimespan(614, 7, ''));
        CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_description_lu_2.csv", CosineService::compareTimespan(614, 7, 'lu'));
        CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_description_fe_2.csv", CosineService::compareTimespan(614, 7, 'fe'));

//        CosineService::createLinkSentenceAnnotationToFrame(1478);
//        CosineService::createLinkSentenceAnnotationToFrame(1479);
//        $pairs = [
//            [602476, 602485],
//            [602477, 602486],
//            [602478, 602487],
//            [602479, 602488],
//            [602480, 602489],
//            [602481, 602490],
//            [602482, 602491],
//            [602483, 602492],
//            [602484, 602493]
//        ];
//        foreach($pairs as $pair) {
//            CosineService::compareSentences($pair[0], $pair[1]);
//        }


    }
}
