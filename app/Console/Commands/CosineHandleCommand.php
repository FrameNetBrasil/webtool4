<?php

namespace App\Console\Commands;

use App\Database\Criteria;
use App\Services\AppService;
use App\Services\CosineService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        ini_set("memory_limit", "10240M");
        AppService::setCurrentLanguage(1);

        /* Construções Natália - 04/07/2025 */
        //CosineService::createMoccaNetwork();
        /*
        idConstruction,cxIdLanguage,idEntity,name
        372,1,1561790,Futuro_do_Indicativo
        390,1,2740344,Futuro_do_pretérito_do_indicativo
        371,1,1561788,Presente_do_Indicativo
        376,1,1561815,Pretérito_Imperfeito_do_Indicativo
        408,1,2740610,Pretérito_mais_que_perfeito_composto_do_indicativo
        377,1,1561818,Pretérito_Mais_Que_Perfeito_do_Indicativo
        418,1,2740715,Pretérito_perfeito_composto_do_indicativo
        373,1,1561805,Pretérito_Perfeito_do_Indicativo
        401,3,2740562,Condicional_del_indicativo
        381,3,1561832,Futuro_de_indicativo
        380,3,1561829,Presente_de_indicativo
        384,3,1561839,Pretérito_imperfecto_de_indicativo
        396,3,2740381,Pretérito_perfecto_compuesto_de_indicativo
        382,3,1561835,Pretérito_perfecto_simple_de_indicativo
        397,3,2740394,Pretérito_pluscuamperfecto_de_indicativo
         */
//        $constructions = [
//            '372' => 'Futuro_do_Indicativo',
//            '390' => 'Futuro_do_pretérito_do_indicativo',
//            '371' => 'Presente_do_Indicativo',
//            '376' => 'Pretérito_Imperfeito_do_Indicativo',
//            '408' => 'Pretérito_mais_que_perfeito_composto_do_indicativo',
//            '377' => 'Pretérito_Mais_Que_Perfeito_do_Indicativo',
//            '418' => 'Pretérito_perfeito_composto_do_indicativo',
//            '373' => 'Pretérito_Perfeito_do_Indicativo',
//            '401' => 'Condicional_del_indicativo',
//            '381' => 'Futuro_de_indicativo',
//            '380' => 'Presente_de_indicativo',
//            '384' => 'Pretérito_imperfecto_de_indicativo',
//            '396' => 'Pretérito_perfecto_compuesto_de_indicativo',
//            '382' => 'Pretérito_perfecto_simple_de_indicativo',
//            '397' => 'Pretérito_pluscuamperfecto_de_indicativo',
//        ];
//
//        CosineService::createLinkCxnCeToConcept(372);
//        CosineService::createLinkCxnCeToConcept(390);
//        CosineService::createLinkCxnCeToConcept(371);
//        CosineService::createLinkCxnCeToConcept(376);
//        CosineService::createLinkCxnCeToConcept(408);
//        CosineService::createLinkCxnCeToConcept(377);
//        CosineService::createLinkCxnCeToConcept(418);
//        CosineService::createLinkCxnCeToConcept(373);
//        CosineService::createLinkCxnCeToConcept(401);
//        CosineService::createLinkCxnCeToConcept(381);
//        CosineService::createLinkCxnCeToConcept(380);
//        CosineService::createLinkCxnCeToConcept(384);
//        CosineService::createLinkCxnCeToConcept(396);
//        CosineService::createLinkCxnCeToConcept(382);
//        CosineService::createLinkCxnCeToConcept(397);
//
//        $array1 = [372, 390, 371, 376, 408, 377, 418, 373];
//        $array2 = [401, 381, 380, 384, 396, 382, 397];
//        $handle = fopen(__DIR__ . "/natalia_cxn_cosine.csv", "w");
//        foreach ($array1 as $idConstruction1) {
//            foreach ($array2 as $idConstruction2) {
//                $result = CosineService::compareConstructions($idConstruction1, $idConstruction2);
//                fputcsv($handle, [$constructions[$idConstruction1], $constructions[$idConstruction2], $result->cosine]);;
//            }
//        }
//        fclose($handle);

        /* Audion - PPM - 23/06/2025 */
        //CosineService::createFrameNetwork();
//        CosineService::createLinkSentenceAnnotationTimeToFrame(614);
//        CosineService::createLinkSentenceAnnotationTimeToFrame(638);
//        CosineService::createLinkSentenceAnnotationTimeToFrame(617);
//        CosineService::createLinkSentenceAnnotationTimeToFrame(619);
//        CosineService::createLinkSentenceAnnotationTimeToFrame(631);
//        CosineService::createLinkSentenceAnnotationTimeToFrame(626);
//
//        $array = [614,638,617,619,631,626,502,507,508,509,510,511,512,513,515,516];
        //$array = [619,631,626,502,507,508,509,510,511,512,513,515,516];
        //$array = [502,507,508,509,510,511,512,513,515,516];
        //$array = [614];
//        foreach($array as $idDocument){
//            CosineService::createLinkSentenceAnnotationTimeToFrame($idDocument);
//            CosineService::createLinkObjectAnnotationTimeToFrame($idDocument);
//            $document = Criteria::byId("document","idDocument",$idDocument);
//            CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_original_full_2.csv", CosineService::compareTimespan($idDocument, 4, ''));
//            CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_original_lu_2.csv", CosineService::compareTimespan($idDocument, 4, 'lu'));
//            CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_original_fe_2.csv", CosineService::compareTimespan($idDocument, 4, 'fe'));
//            if ($idDocument > 520) {
//                CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_description_full_2.csv", CosineService::compareTimespan($idDocument, 7, ''));
//                CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_description_lu_2.csv", CosineService::compareTimespan($idDocument, 7, 'lu'));
//                CosineService::writeToCSV(__DIR__ . "/{$document->entry}_audio_description_fe_2.csv", CosineService::compareTimespan($idDocument, 7, 'fe'));
//            }
//        }

//        CosineService::createLinkObjectAnnotationTimeToFrame(614);
//        CosineService::createLinkObjectAnnotationTimeToFrame(638);
//        CosineService::createLinkObjectAnnotationTimeToFrame(617);
//        CosineService::createLinkObjectAnnotationTimeToFrame(619);
//        CosineService::createLinkObjectAnnotationTimeToFrame(631);
//        CosineService::createLinkObjectAnnotationTimeToFrame(626);

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

        // DTake - comparando names com LOME
        $results = [];
        $cmd = "
select distinct a.idDocument
from (
select d.idDocument, count(distinct sob.idStaticObject) n
from view_staticobject sob
join document d on (sob.idDocument = d.idDocument)
where d.entry like 'doc_dtake%'
and sob.idLanguage = 2
group by d.idDocument) a join (
select d.idDocument, count(distinct sob.idStaticObject) n
from view_staticobject sob
join view_lu lu on (sob.name = lu.lemmaName)
join document d on (sob.idDocument = d.idDocument)
where d.entry like 'doc_dtake%'
and lu.idlanguage = 2 and sob.idLanguage = 2
group by d.idDocument) b on (a.idDocument = b.idDocument)
where ((b.n / a.n) > 0.5)
                    ";
        $documents = DB::connection('webtool')->select($cmd);
        print_r(count($documents));
        $i = 0;
        foreach ($documents as $row) {
            $this->dtakeCreateLinkDocumentNamesToFrame($row->idDocument);
            $this->dtakeCreateLinkDocumentLOMEToFrame($row->idDocument);
            $doc1Node = Criteria::byId("cosine_node", "idReference", $row->idDocument + 100000);
            $vector1 = CosineService::createVectorFromNode($doc1Node->idCosineNode);
            $doc2Node = Criteria::byId("cosine_node", "idReference", $row->idDocument + 200000);
            $vector2 = CosineService::createVectorFromNode($doc2Node->idCosineNode);
            $result = CosineService::compareVectors($vector1, $vector2);
            $results[$row->idDocument] = $result->cosine;
//            if (++$i == 5) break;
        };
        print_r($results);
        arsort($results);
        print_r($results);
        $handle = fopen(__DIR__ . "/dtake_documents", "w");
        foreach($results as $idDocument=>$cosine) {
            fputcsv($handle, [$idDocument, $cosine]);
        }
        fclose($handle);
    }

    public function dtakeCreateLinkDocumentNamesToFrame(int $idDocument): void
    {
        // clear current network for the idDocument + 100000
        $fakeIdDocument = $idDocument + 100000;
        $documentNode = Criteria::byId("cosine_node", "idReference", $fakeIdDocument);
        if ($documentNode) {
            Criteria::table("cosine_link")
                ->where("idCosineNodeSource", $documentNode->idCosineNode)
                ->delete();
            Criteria::table("cosine_node")
                ->where("idCosineNode", $documentNode->idCosineNode)
                ->delete();
        }
        //

//        $cmd = "
//select lu.idFrame
//from view_staticobject sob
//join view_lu lu on (sob.name = lu.lemmaName)
//join document d on (sob.idDocument = d.idDocument)
//where lu.idlanguage = 2 and sob.idLanguage = 2
//and d.iddocument = {$idDocument}
//";

        $cmd = "
select ds.idDocument, lu.idFrame
from document_sentence ds
join sentence s on (ds.idSentence = s.idSentence)
join document d on (ds.idDocument = d.idDocument)
join view_lexicon_lemma lm on (lower(s.text) = lm.name)
join lu on (lu.idLexicon = lm.idLexicon)
where d.entry like 'doc_dtake%'
and (s.idOriginmm in (9))
and d.iddocument = {$idDocument}
";

        $idCosineNodeDocument = Criteria::create("cosine_node", [
            "name" => "doc_" . $fakeIdDocument,
            "type" => "DOC",
            "idReference" => $fakeIdDocument,
        ]);
        $data = DB::connection('webtool')->select($cmd);

        foreach ($data as $row) {
            $idCosineNodeFrame = Criteria::byId("cosine_node", "idFrame", $row->idFrame)->idCosineNode;
            if ($idCosineNodeFrame) {
                Criteria::create("cosine_link", [
                    "idCosineNodeSource" => $idCosineNodeDocument,
                    "idCosineNodeTarget" => $idCosineNodeFrame,
                    "value" => 1.0,
                    "type" => "lu"
                ]);
            }
        }
    }

    public function dtakeCreateLinkDocumentLOMEToFrame(int $idDocument): void
    {
        // clear current network for the idDocument + 200000
        $fakeIdDocument = $idDocument + 200000;
        $documentNode = Criteria::byId("cosine_node", "idReference", $fakeIdDocument);
        if ($documentNode) {
            Criteria::table("cosine_link")
                ->where("idCosineNodeSource", $documentNode->idCosineNode)
                ->delete();
            Criteria::table("cosine_node")
                ->where("idCosineNode", $documentNode->idCosineNode)
                ->delete();
        }

        $cmd = "
select ds.idDocument, lome.idFrame
from document_sentence ds
    join sentence s on (ds.idSentence = s.idSentence)
join lome_resultfe lome on (ds.idSentence = lome.idSentence)
join document d on (ds.idDocument = d.idDocument)
where d.entry like 'doc_dtake%'
and (lome.type = 'lu')
and (s.idOriginmm in (15,16))  and d.idDocument={$idDocument}
";

        $idCosineNodeDocument = Criteria::create("cosine_node", [
            "name" => "doc_" . $fakeIdDocument,
            "type" => "DOC",
            "idReference" => $fakeIdDocument,
        ]);
        $data = DB::connection('webtool')->select($cmd);

        foreach ($data as $row) {
            $idCosineNodeFrame = Criteria::byId("cosine_node", "idFrame", $row->idFrame)->idCosineNode;
            if ($idCosineNodeFrame) {
                Criteria::create("cosine_link", [
                    "idCosineNodeSource" => $idCosineNodeDocument,
                    "idCosineNodeTarget" => $idCosineNodeFrame,
                    "value" => 1.0,
                    "type" => "lu"
                ]);
            }
        }
    }

}
