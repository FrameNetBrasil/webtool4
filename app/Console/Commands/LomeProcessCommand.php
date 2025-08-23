<?php

namespace App\Console\Commands;

use App\Database\Criteria;
use App\Services\LOME\LOMEService;
use App\Services\Trankit\TrankitService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LomeProcessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:lome-process-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process sentences from database to LOME tables';

    public function init()
    {
        ini_set("memory_limit", "10240M");
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->init();
            $frameNames = Criteria::table("view_frame as f")
                ->select("f.idFrame", "f.name")
                ->where("f.idLanguage", 1)
                ->chunkResult("idFrame", "name");
            $feNames = Criteria::table("view_frameelement as fe")
                ->select("fe.idFrameElement", "fe.name")
                ->where("fe.idLanguage", 1)
                ->chunkResult("idFrameElement", "name");
            $idSpan = 0;
            $lome = new LOMEService();
            $lome->init("https://lome.frame.net.br");
            $trankit = new TrankitService();
            $trankit->init("http://localhost:8405");
            // corpus copini
            $sentences = DB::connection('webtool')
                ->select("
                select s.idSentence, s.text,s.idOriginMM
from sentence s
join document_sentence ds on (s.idSentence = ds.idSentence)
join document d on (ds.idDocument = d.idDocument)
where d.idCorpus = 217
                and s.idSentence=1459948
                ");
            debug(count($sentences));
            $s = 0;
            foreach ($sentences as $sentence) {
                ++$s;
                try {
                    $text = trim($sentence->text);
//                    print_r("====================\n");
//                    print_r($sentence->idSentence . ": " . $text . "\n");
//                    print_r("====================\n");
                    print_r($s . "\n");
//                    if ($s < 702) continue;
                    //print_r($tokens);
                    Criteria::deleteById("lome_resultfe", "idSentence", $sentence->idSentence);
                    //$result = $lome->process($text);
                    $ud = $trankit->parseSentenceRawTokens($text, 1);
                    //print_r($ud);
                    $result = $lome->parse($text);
                    if (is_array($result)) {
                        $result = $result[0];
                        $tokens = $result->tokens;
                        print_r($tokens);
                        $ud = $trankit->processTrankitTokens($tokens, 1);
                        debug($ud);
                        $annotations = $result->annotations;
//                        print_r($annotations);
//                        print_r($tokens);
                        foreach ($annotations as $annotation) {
//                        print_r($annotation);
                            $x = explode('_', strtolower($annotation->label));
                            $idFrame = $x[1];
                            $startChar = $annotation->char_span[0];
                            $endChar = $annotation->char_span[1];
                            $word = '';
                            for ($t = $annotation->span[0]; $t <= $annotation->span[1]; $t++) {
                                $word .= $tokens[$t] . ' ';
                            }
                            Criteria::create("lome_resultfe", [
                                "start" => $startChar,
                                "end" => $endChar,
                                "word" => trim(strtolower($word)),
                                "type" => "lu",
                                "idSpan" => 0,
                                "idLU" => null,
                                "idFrame" => $idFrame,
                                "idFrameElement" => null,
                                "idSentence" => $sentence->idSentence,
                            ]);
                            foreach ($annotation->children as $fe) {
                                $x = explode('_', strtolower($fe->label));
                                $idFrameElement = $x[1];
                                $startChar = $fe->char_span[0];
                                $endChar = $fe->char_span[1];
                                $word = '';
                                for ($t = $fe->span[0]; $t <= $fe->span[1]; $t++) {
                                    $word .= $tokens[$t] . ' ';
                                }
                                Criteria::create("lome_resultfe", [
                                    "start" => $startChar,
                                    "end" => $endChar,
                                    "word" => trim(strtolower($word)),
                                    "type" => "fe",
                                    "idSpan" => 0,
                                    "idLU" => null,
                                    "idFrame" => $idFrame,
                                    "idFrameElement" => $idFrameElement,
                                    "idSentence" => $sentence->idSentence,
                                ]);
                            }
                        }
                    }
                    //if ($s > 5) die;
                } catch (\Exception $e) {
                    print_r($sentence->idSentence . ":" . $e->getMessage());
                    die;
                }
                break;
            }
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
}
