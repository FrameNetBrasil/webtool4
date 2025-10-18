<?php

namespace App\Console\Commands\Reporter_Brasil;

use App\Database\Criteria;
use App\Services\AppService;
use App\Services\CosineService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CosineReporterBrasilCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cosine:reporterbrasil';

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
        ini_set('memory_limit', '10240M');
        AppService::setCurrentLanguage(1);

        // Reporter Brasil - comparando anotação manual e anotação via LOME
        // A diferença é que não posso comparar sentenças, pois os idSentences são os mesmos
        // tenho de comparar com base no idDocumentSentence

        $docs = [
            'Reporter_Brasil_03_11',
            'Reporter_Brasil_03_12',
            'Reporter_Brasil_02_13',
            'Reporter_Brasil_02_14',
            'Reporter_Brasil_04_01',
            'Reporter_Brasil_04_06',
            'Reporter_Brasil_05_01',
            'Reporter_Brasil_05_02',
            'Reporter_Brasil_05_03',
            'Reporter_Brasil_07_02',
            'Reporter_Brasil_07_03',
            'Reporter_Brasil_07_07',
        ];

        $annoDocs = Criteria::table('view_document')
            ->where('idLanguage', 1)
            ->whereNotIn('idCorpus', [227, 228, 229])
            ->whereIN('name', $docs)
            ->select('idDocument')
            ->all();
        foreach ($annoDocs as $annoDoc) {
            $idDocument = $annoDoc->idDocument;
            CosineService::createLinkDocumentSentenceToFrame($idDocument);
        }
        $lomeDocs = Criteria::table('view_document')
            ->where('idLanguage', 1)
            ->whereIn('idCorpus', [227])
            ->whereIN('name', $docs)
            ->select('idDocument')
            ->all();
        foreach ($lomeDocs as $lomeDoc) {
            $idDocument = $lomeDoc->idDocument;
            CosineService::createLinkDocumentSentenceToFrame($idDocument);
        }

        // Anno - LOME
        $documents = Criteria::table('document_sentence as ds1')
            ->join('document_sentence as ds2', 'ds1.idSentence', '=', 'ds2.idSentence')
            ->join('document as d1', 'ds1.idDocument', '=', 'd1.idDocument')
            ->join('view_document as d2', 'ds2.idDocument', '=', 'd2.idDocument')
            ->join('view_sentence as s', 'ds2.idSentence', '=', 's.idSentence')
            ->where('d2.idCorpus', 227)
            ->whereNotIn('d1.idCorpus', [227, 228, 229])
            ->where('d2.idLanguage', 1)
            ->select('d2.name', 'ds1.idDocumentSentence as idDs1', 'ds2.idDocumentSentence as idDs2', 's.text')
            ->orderby('d2.name')
            ->get()->groupBy('name')->toArray();
        //        print_r($documents);
        foreach ($documents as $name => $docSentencePairs) {
            print_r($name."\n");
            $result = [];
            foreach ($docSentencePairs as $pair) {
                //                print_r($pair->idDs1 . '     ' . $pair->idDs2 . "\n");
                $r = CosineService::compareDocumentSentences($pair->idDs1, $pair->idDs2);
                //                print_r($r);
                //                die;
                $result[] = [
                    'idDocumentSentence1' => $pair->idDs1,
                    'idDocumentSentence2' => $pair->idDs2,
                    'cosine' => $r->cosine,
                    'sentence' => $pair->text,
                ];
            }
            //            print_r($result);
            //            die;
            CosineService::writeToCSV(__DIR__."/Anno_LOME_{$name}.csv", $result);
        }

        // Anno - LOMEedt
        $documents = Criteria::table('document_sentence as ds1')
            ->join('document_sentence as ds2', 'ds1.idSentence', '=', 'ds2.idSentence')
            ->join('document as d1', 'ds1.idDocument', '=', 'd1.idDocument')
            ->join('view_document as d2', 'ds2.idDocument', '=', 'd2.idDocument')
            ->join('view_sentence as s', 'ds2.idSentence', '=', 's.idSentence')
            ->where('d2.idCorpus', 228)
            ->whereNotIn('d1.idCorpus', [227, 228, 229])
            ->where('d2.idLanguage', 1)
            ->select('d2.name', 'ds1.idDocumentSentence as idDs1', 'ds2.idDocumentSentence as idDs2', 's.text')
            ->orderby('d2.name')
            ->get()->groupBy('name')->toArray();
        //        print_r($documents);
        foreach ($documents as $name => $docSentencePairs) {
            print_r($name."\n");
            $result = [];
            foreach ($docSentencePairs as $pair) {
                //                print_r($pair->idDs1 . '     ' . $pair->idDs2 . "\n");
                $r = CosineService::compareDocumentSentences($pair->idDs1, $pair->idDs2);
                //                print_r($r);
                //                die;
                $result[] = [
                    'idDocumentSentence1' => $pair->idDs1,
                    'idDocumentSentence2' => $pair->idDs2,
                    'cosine' => $r->cosine,
                    'sentence' => $pair->text,
                ];
            }
            //            print_r($result);
            //            die;
            CosineService::writeToCSV(__DIR__."/Anno_LOMEEdt_{$name}.csv", $result);
        }

        // LOME - LOMEedt
        $documents = Criteria::table('document_sentence as ds1')
            ->join('document_sentence as ds2', 'ds1.idSentence', '=', 'ds2.idSentence')
            ->join('document as d1', 'ds1.idDocument', '=', 'd1.idDocument')
            ->join('view_document as d2', 'ds2.idDocument', '=', 'd2.idDocument')
            ->join('view_sentence as s', 'ds2.idSentence', '=', 's.idSentence')
            ->where('d2.idCorpus', 227)
            ->where('d1.idCorpus', 228)
            ->where('d2.idLanguage', 1)
            ->select('d2.name', 'ds1.idDocumentSentence as idDs1', 'ds2.idDocumentSentence as idDs2', 's.text')
            ->orderby('d2.name')
            ->get()->groupBy('name')->toArray();
        //        print_r($documents);
        foreach ($documents as $name => $docSentencePairs) {
            print_r($name."\n");
            $result = [];
            foreach ($docSentencePairs as $pair) {
                //                print_r($pair->idDs1 . '     ' . $pair->idDs2 . "\n");
                $r = CosineService::compareDocumentSentences($pair->idDs1, $pair->idDs2);
                //                print_r($r);
                //                die;
                $result[] = [
                    'idDocumentSentence1' => $pair->idDs1,
                    'idDocumentSentence2' => $pair->idDs2,
                    'cosine' => $r->cosine,
                    'sentence' => $pair->text,
                ];
            }
            //            print_r($result);
            //            die;
            CosineService::writeToCSV(__DIR__."/LOME_LOMEEdt_{$name}.csv", $result);
        }

    }
}
