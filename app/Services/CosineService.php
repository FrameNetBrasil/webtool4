<?php

namespace App\Services;

use App\Database\Criteria;

class CosineService
{
    static private $weigths;
    static private $frames;
    static private $processed;

    private static function init(): void
    {
        self::$weigths = [
            "rel_perspective_on" => 0.9,
            "rel_inheritance" => 0.9,
            "rel_using" => 0.8,
            "rel_see_also" => 0,
            "rel_subframe" => 0.85,
            "rel_causative_of" => 0.7,
            "rel_inchoative_of" => 0.7,
            "rel_metaphorical_projection" => 0,
            "rel_precedes" => 0.7
        ];
        self::$frames = [];
    }

    private static function createFrameLinks(int $idFrameSource): void
    {
        if (!isset(self::$frames[$idFrameSource])) {
            $idNodeSource = Criteria::byId("cosine_node", "idFrame", $idFrameSource)->idCosineNode;
            self::$frames[$idFrameSource] = $idNodeSource;
            $relations = Criteria::table("view_frame_relation")
                ->where("f2IdFrame", $idFrameSource)
                ->where("idLanguage", AppService::getCurrentIdLanguage())
                ->all();
            foreach ($relations as $relation) {
                if (self::$weigths[$relation->relationType] > 0) {
                    if (isset(self::$frames[$relation->f1IdFrame])) {
                        $idNodeTarget = self::$frames[$relation->f1IdFrame];
                    } else {
                        $idNodeTarget = Criteria::byId("cosine_node", "idFrame", $relation->f1IdFrame)->idCosineNode;
                    }
                    Criteria::create("cosine_link", [
                        "idCosineNodeSource" => $idNodeSource,
                        "idCosineNodeTarget" => $idNodeTarget,
                        "value" => self::$weigths[$relation->relationType],
                    ]);
                    self::createFrameLinks($relation->f1IdFrame);
                }
            }
        }
    }

    public static function createFrameNetwork(): void
    {
        self::init();
        // clear current network
        $frameNodes = Criteria::table("cosine_node")
            ->where("type", "FRM")
            ->get()->pluck("idFrame")->toArray();
//        debug($frameNodes);
        Criteria::table("cosine_link")
            ->whereIN("idCosineNodeSource", $frameNodes)
            ->delete();
        Criteria::table("cosine_link")
            ->whereIN("idCosineNodeTarget", $frameNodes)
            ->delete();
        Criteria::table("cosine_node")
            ->whereIN("idCosineNode", $frameNodes)
            ->delete();
        $frames = Criteria::table("frame")
            ->select("idFrame")
            ->all();
        // create all frame nodes
        foreach ($frames as $frame) {
            Criteria::create("cosine_node", [
                "name" => "frame_" . $frame->idFrame,
                "type" => "FRM",
                "idFrame" => $frame->idFrame,
            ]);
        }
        // now create links
        foreach ($frames as $frame) {
            self::createFrameLinks($frame->idFrame);
        }
    }

    public static function createLinkSentenceAnnotationToFrame(int $idDocument): void
    {
        // clear current network for the idDocument
        $sentences = Criteria::table("document_sentence")
            ->where("idDocument", $idDocument)
            ->all();
        foreach ($sentences as $sentence) {
            $sentenceNode = Criteria::byId("cosine_node", "idDocumentSentence", $sentence->idDocumentSentence);
            Criteria::table("cosine_link")
                ->where("idCosineNodeSource", $sentenceNode->idCosineNode)
                ->delete();
            Criteria::table("cosine_node")
                ->where("idCosineNode", $sentenceNode->idCosineNode)
                ->delete();
        }
        //
        $sentences = Criteria::table("document_sentence as ds")
            ->join("view_annotationset as a", "ds.idSentence", "a.idSentence")
            ->join("view_annotation_text_gl as t", "a.idAnnotationSet", "t.idAnnotationSet")
            ->join("lu", "lu.idLU", "a.idLU")
            ->where("ds.idDocument", $idDocument)
            ->where("t.idLanguage", AppService::getCurrentIdLanguage())
            ->where("t.name", "Target")
            ->select("ds.idSentence", "lu.idFrame", "ds.idDocumentSentence")
            ->distinct()
            ->all();
        $idDocumentSentence = $idCosineNodeSentence = 0;
        foreach ($sentences as $sentence) {
            if ($sentence->idDocumentSentence != $idDocumentSentence) {
                $idCosineNodeSentence = Criteria::create("cosine_node", [
                    "name" => "sen_" . $sentence->idSentence,
                    "type" => "SEN",
                    "idDocumentSentence" => $sentence->idDocumentSentence,
                ]);
                $idDocumentSentence = $sentence->idDocumentSentence;
            }
            $idCosineNodeFrame = Criteria::byId("cosine_node", "idFrame", $sentence->idFrame)->idCosineNode;
            Criteria::create("cosine_link", [
                "idCosineNodeSource" => $idCosineNodeSentence,
                "idCosineNodeTarget" => $idCosineNodeFrame,
                "value" => 1.0,
            ]);
        }
    }

    private static function getLinksFromTarget(int $idCosineNode, array &$links, float $weight = 1.0): array
    {
        if (!isset(self::$processed[$idCosineNode])) {
            self::$processed[$idCosineNode] = true;
            $linksFromTarget = Criteria::table("cosine_link")
                ->where("idCosineNodeSource", $idCosineNode)
                ->all();
            foreach ($linksFromTarget as $link) {
                $links[$link->idCosineNodeTarget] = $link->value * $weight;
                self::getLinksFromTarget($link->idCosineNodeTarget, $links, $weight * 0.9);
            }
        }
        return $links;
    }

    private static function createVectorForDocumentSentence(int $idDocumentSentence): array
    {
        $vector = [];
        self::$processed = [];
        // links to start frames
        $sentenceNode = Criteria::byId("cosine_node", "idDocumentSentence", $idDocumentSentence);
        $linkToFrames = Criteria::table("cosine_link")
            ->where("idCosineNodeSource", $sentenceNode->idCosineNode)
            ->all();
        foreach ($linkToFrames as $linkToFrame) {
            $vector[$linkToFrame->idCosineNodeTarget] = $linkToFrame->value;
            $links = [];
            self::getLinksFromTarget($linkToFrame->idCosineNodeTarget, $links);
            foreach ($links as $idNode => $value) {
                $vector[$idNode] = $value;
            }
        }
        return $vector;
    }

    public static function compareSentences(int $idDocumentSentence1, int $idDocumentSentence2): object
    {
        $vector1 = self::createVectorForDocumentSentence($idDocumentSentence1);
        $vector2 = self::createVectorForDocumentSentence($idDocumentSentence2);
//        return [];
//        $vector1 = [];
//        $node1 = DB::connection("daisy")
//            ->table("cosine2_node")
//            ->where("name", $name1)
//            ->first();
//        if (!is_null($node1)) {
//            $links1 = DB::connection("daisy")
//                ->table("cosine2_link")
//                ->join("cosine2_node as target", "cosine2_link.idNodeTarget", "=", "target.idNode")
//                ->where("idNodeSource", $node1->idNode)
//                ->select("target.idNode", "target.idEntity")
//                ->get()->all();
//            foreach ($links1 as $link) {
//                $vector1[$link->idEntity] = 1.0;
//                $associateds = DB::connection("daisy")
//                    ->table("cosine2_associative")
//                    ->join("cosine2_node as associated", "cosine2_associative.idNodeB", "=", "associated.idNode")
//                    ->where("idNodeA", $link->idNode)
//                    ->select("associated.idEntity", "cosine2_associative.value")
//                    ->get()->all();
//                foreach ($associateds as $associated) {
//                    $a = $associated->value;
//                    $idEntity = $associated->idEntity;
//                    if (isset($vector1[$idEntity])) {
//                        if ($vector1[$idEntity] < $a) {
//                            $vector1[$idEntity] = $a;
//                        }
//                    } else {
//                        $vector1[$idEntity] = $a;
//                    }
//                }
//            }
//        }
//        /*
//         * Name2
//         */
//        $vector2 = [];
//        $node2 = DB::connection("daisy")
//            ->table("cosine2_node")
//            ->where("name", $name2)
//            ->first();
//        if (!is_null($node2)) {
//            $links2 = DB::connection("daisy")
//                ->table("cosine2_link")
//                ->join("cosine2_node as target", "cosine2_link.idNodeTarget", "=", "target.idNode")
//                ->where("idNodeSource", $node2->idNode)
//                ->select("target.idNode", "target.idEntity")
//                ->get()->all();
//            foreach ($links2 as $link) {
//                $vector2[$link->idEntity] = 1.0;
//                $associateds = DB::connection("daisy")
//                    ->table("cosine2_associative")
//                    ->join("cosine2_node as associated", "cosine2_associative.idNodeB", "=", "associated.idNode")
//                    ->where("idNodeA", $link->idNode)
//                    ->select("associated.idEntity", "cosine2_associative.value")
//                    ->get()->all();
//                foreach ($associateds as $associated) {
//                    $a = $associated->value;
//                    $idEntity = $associated->idEntity;
//                    if (isset($vector2[$idEntity])) {
//                        if ($vector2[$idEntity] < $a) {
//                            $vector2[$idEntity] = $a;
//                        }
//                    } else {
//                        $vector2[$idEntity] = $a;
//                    }
//                }
//            }
//        }


        // fill zeroes
        foreach ($vector2 as $idEntity => $a) {
            if (!isset($vector1[$idEntity])) {
                $vector1[$idEntity] = 0.0;
            }
        }
        foreach ($vector1 as $idEntity => $a) {
            if (!isset($vector2[$idEntity])) {
                $vector2[$idEntity] = 0.0;
            }
        }

        // cosine similarity
        // cos(theta) = sum(vector1, vector2) / (mod(vector1) * mod(vector2))

//        print_r("Calculing sum \n");
        $sum = 0;
        foreach ($vector1 as $idEntity => $a) {
            $sum += ($a * $vector2[$idEntity]);
        }
//        print_r('sum', $sum);
        $sumA = 0;
        foreach ($vector1 as $a) {
            $sumA += ($a * $a);
        }
//        print_r('sumA', $sumA);
        $modA = sqrt($sumA);
//        print_r('modA', $modA);
        $sumB = 0;
        foreach ($vector2 as $a) {
            $sumB += ($a * $a);
        }
//        print_r('sumB', $sumB);
        $modB = sqrt($sumB);
//        print_r('modB', $modB);
//        print_r('modA * modB', ($modA * $modB));
        $m = $modA * $modB;
        if ($m > 0) {
            $cosine = round($sum / ($modA * $modB), 6);
        } else {
            $cosine = -1;
        }
//        print_r("Sorting \n");
        asort($vector1);
        asort($vector2);

//        print_r($vector1);
//        print_r($vector2);

        $result = (object)[
            'array1' => $vector1,
            'array2' => $vector2,
            'cosine' => $cosine
        ];

        if (is_null($result)) {
            $result = (object)[
                'array1' => [],
                'array2' => [],
                'cosine' => 0];
        }
        debug($result);
        return $result;

    }


}
