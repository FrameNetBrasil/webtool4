<?php

use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;

class GoogleSpeechToText
{

    private $bucketObject;
    private $outputFile;
    private $idLanguage;

    public function __construct($bucketObject, $outputFile, $idLanguage)
    {
        $this->bucketObject = $bucketObject;
        $this->outputFile = $outputFile;
        $this->idLanguage = $idLanguage;
        //$file = trim("/var/www/html/apps/webtool/offline/google-cloud/storage/charon-286713-0b09338da74c.json");
        $file = trim("/home/framenetbr/devel/fnbr/charon_docker_maestro/apps/webtool/offline/google-cloud/storage/charon-286713-0b09338da74c.json");
        putenv("GOOGLE_APPLICATION_CREDENTIALS=$file");
    }

    public function process() {

        // change these variables if necessary
        $encoding = AudioEncoding::FLAC;
        $sampleRateHertz = 44100;
        $this->idLanguage = 1;
        print_r('GoogleSpeechToText idLanguage = ' . $this->idLanguage . PHP_EOL);
        if ($this->idLanguage == 1) {
            $languageCode = 'pt-BR';
        } else if ($this->idLanguage == 2) {
            $languageCode = 'en-US';
        }
        print_r('GoogleSpeechToText languageCode = ' . $languageCode . PHP_EOL);

        if (!extension_loaded('grpc')) {
            throw new \Exception('Install the grpc extension (pecl install grpc)');
        }

// When true, time offsets for every word will be included in the response.
        $enableWordTimeOffsets = true;

// set string as audio content
        $audio = (new RecognitionAudio())
            ->setUri($this->bucketObject);

// set config
        $config = (new RecognitionConfig())
            ->setEncoding($encoding)
//            ->setSampleRateHertz($sampleRateHertz)
            ->setLanguageCode($languageCode)
            ->setEnableWordTimeOffsets($enableWordTimeOffsets);

// create the speech client
        $client = new SpeechClient();

// create the asyncronous recognize operation
        $operation = $client->longRunningRecognize($config, $audio);
        print_r('running ' . PHP_EOL);
        $operation->pollUntilComplete();
        print_r('complete ' . PHP_EOL);

        $output = [];

        if ($operation->operationSucceeded()) {
            $response = $operation->getResult();

            // each result is for a consecutive portion of the audio. iterate
            // through them to get the transcripts for the entire audio file.
            foreach ($response->getResults() as $result) {
                $alternatives = $result->getAlternatives();
                $mostLikely = $alternatives[0];
                $transcript = $mostLikely->getTranscript();
                $confidence = $mostLikely->getConfidence();
                //printf('Transcript: %s' . PHP_EOL, $transcript);
                //printf('Confidence: %s' . PHP_EOL, $confidence);
                $words = [];
                foreach ($mostLikely->getWords() as $wordInfo) {
                    $startTime = $wordInfo->getStartTime();
                    $endTime = $wordInfo->getEndTime();
                    mdump(sprintf('  Word: %s (start: %s, end: %s)' . PHP_EOL,
                        $wordInfo->getWord(),
                        $startTime->serializeToJsonString(),
                        $endTime->serializeToJsonString()));
                    $words[] = [
                        'word' => $wordInfo->getWord(),
                        'startTime' => str_replace('"', '', $startTime->serializeToJsonString()),
                        'endTime' => str_replace('"', '', $endTime->serializeToJsonString())
                    ];
                }
                $output[] = [
                    'text' => $transcript,
                    'words' => $words
                ];
            }
        } else {
            mdump('===============');
            mdump($operation->getError());
        }

        file_put_contents($this->outputFile, json_encode($output));

        $client->close();

    }
}