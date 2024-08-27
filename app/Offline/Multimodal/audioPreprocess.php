<?php

namespace App\Offline\Multimodal;


use App\Database\Criteria;
use GoogleSpeechToText;
use GoogleStorage;
use GuzzleHttp\Client;

require_once 'GoogleSpeechToText.php';
require_once 'GoogleStorage.php';

class audioPreprocess
{
    public $videoFile;
    public $audioFile;
    public $transcriptFile;
    public $combinedFile;
    public $dataPath;
    public $videoSize;
    public $videoName;
    public $videoPath;
    public $videoFileOriginal;
    public $sha1Name;
    public $video;
    public $ffmpegConfig;
    private $ffmpeg;
    private $videoHeight;
    private $videoWidth;

    //public $testingPhase;

    private $idDocumentMM;
    private $idDocument;
    private $idUser;
    private $idLanguage;
    private $email;
    private $preprocess;

    public function __construct($videoPath)
    {
        Criteria::$database = 'webtool37';
        //debug($videoPath, $videoName, $documentMMTitle);
        $this->dataPath = "/home/ematos/temp/ReporterBrasilCorpus";
        $this->videoPath = $videoPath;
    }


    public function process()
    {
        $sufix = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24'];
        $directory = "{$this->dataPath}/{$this->videoPath}";
        $files = array_diff(scandir($directory), array('..', '.'));
        debug($files);
        $i = 0;
        foreach($files as $videoName) {
            $title = $this->videoPath . '_' . $sufix[$i++];
            debug($videoName, $title);
            $documentMM = Criteria::table("documentmm")
                ->where("title", $title)
                ->first();
            $this->idDocumentMM = $documentMM->idDocumentMM;
            $this->idLanguage = 1;
            $this->dataPath = "/home/ematos/temp/ReporterBrasilCorpus";
            $this->videoName = $videoName;
            $this->sha1Name = sha1($this->videoPath . $this->videoName);
            $this->videoFileOriginal = "{$this->dataPath}/{$this->videoPath}/{$this->videoName}";
            $this->videoSize = 'small';
            $this->audioProcess();
            //break;
        }

//        $this->videoProcess();
//        $this->getFrames();
//        $this->getAudio();
//        $this->speechToText();
//        $this->ccextractor();
//        $this->extractFrames();
//        $this->charon();
    }

    public function audioProcess()
    {
        $this->audioFile = "{$this->dataPath}/Audio/{$this->sha1Name}.flac";
        if (!file_exists($this->audioFile)) {
            $this->ffmpegConfig = $config = [
                'dataPath' => $this->dataPath,
                'ffmpeg.binaries' => '/usr/bin/ffmpeg', // '/var/www/html/core/support/charon/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/bin/ffprobe',//'/var/www/html/core/support/charon/bin/ffprobe',
            ];
            debug('=1');
            $logger = null;
            // video attributes
            // video compression
            $this->ffmpeg = \FFMpeg\FFMpeg::create([
                'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/bin/ffprobe',
                'timeout' => 3600, // The timeout for the underlying process
                'ffmpeg.threads' => 12, // The number of threads that FFMpeg should use
            ], @$logger);
            $this->video = $this->ffmpeg->open($this->videoFileOriginal);
            // Set the formats
            $outputFormat = new \FFMpeg\Format\Audio\Flac(); // Here you choose your output format
            $outputFormat->on('progress', function ($audio, $format, $percentage) {
                debug("$percentage % transcoded");
            });
            $outputFormat
                ->setAudioCodec("flac")
                ->setAudioChannels(1);
            //->setAudioKiloBitrate(256);
            debug("saving audio " . $this->audioFile);
            $this->video->save($outputFormat, $this->audioFile);

            // upload to bucket
            //$export = "export GOOGLE_APPLICATION_CREDENTIALS=/var/www/html/apps/webtool/offline/google-cloud/storage/charon-286713-0b09338da74c.json";
            //shell_exec($export);
            //$upload = "php /var/www/html/apps/webtool/offline/google-cloud/storage/storage.php objects --upload-from=" . $this->audioFile . " charon_bucket " . $this->sha1Name . ".flac";
            //debug($upload);
            //shell_exec($upload);
        }
        //$this->speechToText($this->audioFile );

    }

    public function speechToText($audioFile)
    {
//        $this->audioFile = "{$this->dataPath}/Audio/{$this->sha1Name}.flac";
        //$audioPath = $this->dataPath . "Audio_Store/audio/";
        //$this->audioFile = $audioPath . $this->sha1Name . ".flac";
        if (file_exists($audioFile)) {
            $storage = new GoogleStorage();
            $storage->upload_object('charon_bucket', $this->sha1Name . ".flac", $audioFile);
            debug('uploaded to bucket');

            $bucketObject = "gs://charon_bucket/" . $this->sha1Name . ".flac";
//            $transcriptPath = $this->dataPath . "Text_Store/transcripts/";
//            $outputFile = $transcriptPath . $this->sha1Name . '_' . $this->idLanguage . '.json';
            $outputFile = "{$this->dataPath}/Transcript/{$this->sha1Name}-{$this->idLanguage}.json";
            if (!file_exists($outputFile)) {
                $speechToText = new GoogleSpeechToText($bucketObject, $outputFile, $this->idLanguage);
                //$speechToText = new GoogleSpeechToText($bucketObject, $outputFile, 1);
                $speechToText->process();
                // update table WordMM
//                $wordMM = new fnbr\models\WordMM();
//                $deleteCriteria = $wordMM->getDeleteCriteria();
//                $deleteCriteria->where("idDocumentMM = {$this->idDocumentMM}");
//                $deleteCriteria->where("origin = 0");
//                $deleteCriteria->delete();
//                $transcript = json_decode(file_get_contents($outputFile));
//                foreach ($transcript as $sentence) {
//                    foreach ($sentence->words as $word) {
//                        $wordMM->setPersistent(false);
//                        $start = str_replace('s', '', $word->startTime);
//                        $end = str_replace('s', '', $word->endTime);
//                        $wordMM->setWord($word->word);
//                        $wordMM->setStartTime($start);
//                        $wordMM->setEndTime($end);
//                        $wordMM->setStartTimestamp($start);
//                        $wordMM->setEndTimestamp($end);
//                        $wordMM->setOrigin(0);
//                        $wordMM->setIdDocumentMM($this->idDocumentMM);
//                        $wordMM->save();
//                    }
//                }
            }
        } else {
            debug('Error! File ' . $audioFile . ' doesnt exist!');
        }
    }

}

