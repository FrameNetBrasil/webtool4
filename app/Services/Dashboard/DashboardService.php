<?php

namespace App\Services\Dashboard;

use App\Database\Criteria;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class DashboardService //extends AppService
{


    public static function frame2(): array
    {
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $fields = Criteria::table("dashboard_frame2")
            ->orderBy("idDashboardFrame2", "desc")
            ->first();
        return [
            'sentences' => $fields->text_sentence,
            'framesText' => $fields->text_frame,
            'fesText' => $fields->text_ef,
            'lusText' => $fields->text_lu,
            'asText' => $fields->text_as,
            'bbox' => $fields->video_bbox,
            'framesBBox' => $fields->video_frame,
            'fesBBox' => $fields->video_ef,
            'lusBBox' => $fields->video_obj,
            'avgDuration' => number_format($fields->avg_sentence, 3, $decimal, ''),
            'avgAS' => number_format($fields->avg_obj, 3, $decimal, ''),
        ];

    }


    public static function frame2PPM(): array
    {
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $fields = Criteria::table("dashboard_frame2ppm")
            ->orderBy("idDashboardFrame2PPM", "desc")
            ->first();
        return [
            'sentences' => $fields->text_sentence,
            'framesText' => $fields->text_frame,
            'fesText' => $fields->text_ef,
            'lusText' => $fields->text_lu,
            'asText' => $fields->text_as,
            'bbox' => $fields->video_bbox,
            'framesBBox' => $fields->video_frame,
            'fesBBox' => $fields->video_ef,
            'lusBBox' => $fields->video_obj,
            'avgDuration' => number_format($fields->avg_sentence, 3, $decimal, ''),
            'avgAS' => number_format($fields->avg_obj, 3, $decimal, ''),
        ];

    }

    public static function frame2NLG(): array
    {
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $fields = Criteria::table("dashboard_frame2nlg")
            ->orderBy("idDashboardFrame2NLG", "desc")
            ->first();
        return [
            'sentences' => $fields->text_sentence,
            'framesText' => $fields->text_frame,
            'fesText' => $fields->text_ef,
            'lusText' => $fields->text_lu,
            'asText' => $fields->text_as,
            'bbox' => $fields->video_bbox,
            'framesBBox' => $fields->video_frame,
            'fesBBox' => $fields->video_ef,
            'lusBBox' => $fields->video_obj,
            'avgDuration' => number_format($fields->avg_sentence, 3, $decimal, ''),
            'avgAS' => number_format($fields->avg_obj, 3, $decimal, ''),
        ];
    }

    public static function frame2Gesture(): array
    {
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $fields = Criteria::table("dashboard_frame2gesture")
            ->orderBy("idDashboardFrame2Gesture", "desc")
            ->first();
        return [
            'sentences' => $fields->text_sentence,
            'framesText' => $fields->text_frame,
            'fesText' => $fields->text_ef,
            'lusText' => $fields->text_lu,
            'asText' => $fields->text_as,
            'bbox' => $fields->video_bbox,
            'framesBBox' => $fields->video_frame,
            'fesBBox' => $fields->video_ef,
            'lusBBox' => $fields->video_obj,
            'avgDuration' => number_format($fields->avg_sentence, 3, $decimal, ''),
            'avgAS' => number_format($fields->avg_obj, 3, $decimal, ''),
        ];


    }

    public static function audition(): array
    {
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $fields = Criteria::table("dashboard_audition")
            ->orderBy("idDashboardAudition", "desc")
            ->first();
        return [
            'sentences' => $fields->text_sentence,
            'framesText' => $fields->text_frame,
            'fesText' => $fields->text_ef,
            'lusText' => $fields->text_lu,
            'asText' => $fields->text_as,
            'bbox' => $fields->video_bbox,
            'framesBBox' => $fields->video_frame,
            'fesBBox' => $fields->video_ef,
            'lusBBox' => $fields->video_obj,
            'avgDuration' => number_format($fields->avg_sentence, 3, $decimal, ''),
            'avgAS' => number_format($fields->avg_obj, 3, $decimal, ''),
            'origin' => unserialize($fields->origin),
        ];
    }

    public static function multi30k(): array
    {
        Criteria::$database = 'webtool';
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $fields = Criteria::table("dashboard_multi30k")
            ->orderBy("idDashboardMulti30k", "desc")
            ->first();
        return [
            'images' => $fields->multi30k_image_image,
            'bbox' => $fields->multi30k_image_bbox,
            'framesImage' => $fields->multi30k_image_frame,
            'fesImage' => $fields->multi30k_image_ef,
            'pttSentences' => $fields->multi30k_ptt_sentence,
            'pttFrames' => $fields->multi30k_ptt_lome,
            'ptoSentences' => $fields->multi30k_pto_sentence,
            'ptoFrames' => $fields->multi30k_pto_lome,
            'enoSentences' => $fields->multi30k_eno_sentence,
            'enoFrames' => $fields->multi30k_eno_lome,
        ];
    }

    public static function multi30kEntity(): array
    {
        Criteria::$database = 'webtool';
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $fields = Criteria::table("dashboard_multi30k")
            ->orderBy("idDashboardMulti30k", "desc")
            ->first();
        return [
            'images' => $fields->multi30kentity_image_image,
            'bbox' => $fields->multi30kentity_image_bbox,
            'framesImage' => $fields->multi30kentity_image_frame,
            'fesImage' => $fields->multi30kentity_image_ef,
        ];
    }

    public static function multi30kEvent(): array
    {
        Criteria::$database = 'webtool';
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $fields = Criteria::table("dashboard_multi30k")
            ->orderBy("idDashboardMulti30k", "desc")
            ->first();
        return [
            'images' => $fields->multi30kevent_image_image,
            'bbox' => $fields->multi30kevent_image_bbox,
            'framesImage' => $fields->multi30kevent_image_frame,
            'fesImage' => $fields->multi30kevent_image_ef,
        ];
    }

    public static function multi30kChart(): array
    {
        $dbFnbr = DB::connection('webtool37');
        $cmd = "SELECT year(tlDateTime) y, month(tlDateTime) m, count(*) n
         FROM fnbr_db.timeline t
where (tablename='objectsentencemm') or (tablename='staticannotationmm')
group by year(tlDateTime),month(tlDateTime)
order by 1,2;";
        $rows = $dbFnbr->select($cmd, []);
        $chart = [];
        $sum = 0;
        foreach ($rows as $row) {
            $sum += is_object($row) ? $row->n : $row['n'];
            $m = is_object($row) ? $row->m : $row['m'];
            $y = is_object($row) ? $row->y : $row['y'];
            $chart[] = [
                'm' => $m . '/' . $y,
                'value' => $sum
            ];
        }
        return $chart;
    }

    public static function updateTable($data)
    {
        $dbDaisy = DB::connection('daisy');
        $now = date('Y-m-d H:i:s');
        $frame2_avg_sentence = str_replace(',', '.', $data->frame2['avgDuration']);
        $frame2_avg_obj = str_replace(',', '.', $data->frame2['avgAS']);
        $frame2PPM_avg_sentence = str_replace(',', '.', $data->frame2PPM['avgDuration']);
        $frame2PPM_avg_obj = str_replace(',', '.', $data->frame2PPM['avgAS']);
        $frame2NLG_avg_sentence = str_replace(',', '.', $data->frame2NLG['avgDuration']);
        $frame2NLG_avg_obj = str_replace(',', '.', $data->frame2NLG['avgAS']);
        $frame2Gesture_avg_sentence = str_replace(',', '.', $data->frame2Gesture['avgDuration']);
        $frame2Gesture_avg_obj = str_replace(',', '.', $data->frame2Gesture['avgAS']);
        $audition_avg_sentence = str_replace(',', '.', $data->audition['avgDuration']);
        $audition_avg_obj = str_replace(',', '.', $data->audition['avgAS']);
        $cmd = "update dashboard set
 timeLastUpdate = '{$now}',
 frame2_text_sentence = {$data->frame2['sentences']},
 frame2_text_frame = {$data->frame2['framesText']},
 frame2_text_ef = {$data->frame2['fesText']},
 frame2_text_lu = {$data->frame2['lusText']},
 frame2_text_as = {$data->frame2['asText']},
 frame2_video_bbox = {$data->frame2['bbox']},
 frame2_video_frame = {$data->frame2['framesBBox']},
 frame2_video_ef = {$data->frame2['fesBBox']},
 frame2_video_obj = {$data->frame2['lusBBox']},
 frame2_avg_sentence = {$frame2_avg_sentence},
 frame2_avg_obj = {$frame2_avg_obj},
  frame2PPM_text_sentence = {$data->frame2PPM['sentences']},
 frame2PPM_text_frame = {$data->frame2PPM['framesText']},
 frame2PPM_text_ef = {$data->frame2PPM['fesText']},
 frame2PPM_text_lu = {$data->frame2PPM['lusText']},
 frame2PPM_text_as = {$data->frame2PPM['asText']},
 frame2PPM_video_bbox = {$data->frame2PPM['bbox']},
 frame2PPM_video_frame = {$data->frame2PPM['framesBBox']},
 frame2PPM_video_ef = {$data->frame2PPM['fesBBox']},
 frame2PPM_video_obj = {$data->frame2PPM['lusBBox']},
 frame2PPM_avg_sentence = {$frame2PPM_avg_sentence},
 frame2PPM_avg_obj = {$frame2PPM_avg_obj},
  frame2NLG_text_sentence = {$data->frame2NLG['sentences']},
 frame2NLG_text_frame = {$data->frame2NLG['framesText']},
 frame2NLG_text_ef = {$data->frame2NLG['fesText']},
 frame2NLG_text_lu = {$data->frame2NLG['lusText']},
 frame2NLG_text_as = {$data->frame2NLG['asText']},
 frame2NLG_video_bbox = {$data->frame2NLG['bbox']},
 frame2NLG_video_frame = {$data->frame2NLG['framesBBox']},
 frame2NLG_video_ef = {$data->frame2NLG['fesBBox']},
 frame2NLG_video_obj = {$data->frame2NLG['lusBBox']},
 frame2NLG_avg_sentence = {$frame2NLG_avg_sentence},
 frame2NLG_avg_obj = {$frame2NLG_avg_obj},
  frame2Gesture_text_sentence = {$data->frame2Gesture['sentences']},
 frame2Gesture_text_frame = {$data->frame2Gesture['framesText']},
 frame2Gesture_text_ef = {$data->frame2Gesture['fesText']},
 frame2Gesture_text_lu = {$data->frame2Gesture['lusText']},
 frame2Gesture_text_as = {$data->frame2Gesture['asText']},
 frame2Gesture_video_bbox = {$data->frame2Gesture['bbox']},
 frame2Gesture_video_frame = {$data->frame2Gesture['framesBBox']},
 frame2Gesture_video_ef = {$data->frame2Gesture['fesBBox']},
 frame2Gesture_video_obj = {$data->frame2Gesture['lusBBox']},
 frame2Gesture_avg_sentence = {$frame2Gesture_avg_sentence},
 frame2Gesture_avg_obj = {$frame2Gesture_avg_obj},
 audition_text_sentence = {$data->audition['sentences']},
 audition_text_frame = {$data->audition['framesText']},
 audition_text_ef = {$data->audition['fesText']},
 audition_text_lu = {$data->audition['lusText']},
 audition_text_as = {$data->audition['asText']},
 audition_video_bbox = {$data->audition['bbox']},
 audition_video_frame = {$data->audition['framesBBox']},
 audition_video_ef = {$data->audition['fesBBox']},
 audition_video_obj = {$data->audition['lusBBox']},
 audition_avg_sentence = {$audition_avg_sentence},
 audition_avg_obj = {$audition_avg_obj},
 multi30k_image_image = {$data->multi30k['images']},
 multi30k_image_bbox = {$data->multi30k['bbox']},
 multi30k_image_frame = {$data->multi30k['framesImage']},
 multi30k_image_ef = {$data->multi30k['fesImage']},
 multi30k_ptt_sentence = {$data->multi30k['pttSentences']},
 multi30k_ptt_lome = {$data->multi30k['pttFrames']},
 multi30k_pto_sentence = {$data->multi30k['ptoSentences']},
 multi30k_pto_lome = {$data->multi30k['ptoFrames']},
 multi30k_eno_sentence = {$data->multi30k['enoSentences']},
 multi30k_eno_lome = {$data->multi30k['enoFrames']},
 multi30kevent_image_image = {$data->multi30kEvent['images']},
 multi30kevent_image_bbox = {$data->multi30kEvent['bbox']},
 multi30kevent_image_frame = {$data->multi30kEvent['framesImage']},
 multi30kevent_image_ef = {$data->multi30kEvent['fesImage']},
 multi30kentity_image_image = {$data->multi30kEntity['images']},
 multi30kentity_image_bbox = {$data->multi30kEntity['bbox']},
 multi30kentity_image_frame = {$data->multi30kEntity['framesImage']},
 multi30kentity_image_ef = {$data->multi30kEntity['fesImage']}

 where idDashboard = 1
";
        $dbDaisy->update($cmd);
    }

    public static function mustCalculate(): bool
    {
        $now = date('Y-m-d H:i:s');
        $lastAnnotation = Criteria::table("timeline")
            ->orderByDesc("idTimeLine")
            ->first();
        $lastAnnotationTime = is_null($lastAnnotation) ? $now : $lastAnnotation->tlDateTime;
        $dashboard = Criteria::table("dashboard")
            ->first();
        if (is_null($dashboard)) {
            $lastUpdateTime = $now;
            $idDashboard = Criteria::create("dashboard", ["timeLastUpdate" => $now]);
        } else {
            $lastUpdateTime = $dashboard->timeLastUpdate;
            $idDashboard = $dashboard->idDashboard;
        }
        debug("=======================", $lastUpdateTime, $lastAnnotationTime);
        $mustCalculate = $lastAnnotationTime >= $lastUpdateTime;
        if ($mustCalculate) {
            Criteria::table("dashboard")
                ->where("idDashBoard", $idDashboard)
                ->update(["timeLastUpdate" => $now]);
        }
        return $mustCalculate;
    }

    public static function getFromTable($data)
    {
        $decimal = (App::currentLocale() == 'pt') ? ',' : '.';
        $dbDaisy = DB::connection('webtool');
        $cmd = "SELECT * FROM dashboard where (idDashBoard = 1)";
        $rows = $dbDaisy->select($cmd, []);
        $fields = $rows[0];
        $data->frame2['sentences'] = $fields->frame2_text_sentence;
        $data->frame2['framesText'] = $fields->frame2_text_frame;
        $data->frame2['fesText'] = $fields->frame2_text_ef;
        $data->frame2['lusText'] = $fields->frame2_text_lu;
        $data->frame2['asText'] = $fields->frame2_text_as;
        $data->frame2['bbox'] = $fields->frame2_video_bbox;
        $data->frame2['framesBBox'] = $fields->frame2_video_frame;
        $data->frame2['fesBBox'] = $fields->frame2_video_ef;
        $data->frame2['lusBBox'] = $fields->frame2_video_obj;
        $data->frame2['avgDuration'] = number_format($fields->frame2_avg_sentence, 3, $decimal, '');
        $data->frame2['avgAS'] = number_format($fields->frame2_avg_obj, 3, $decimal, '');
        $data->frame2PPM['sentences'] = $fields->frame2ppm_text_sentence;
        $data->frame2PPM['framesText'] = $fields->frame2ppm_text_frame;
        $data->frame2PPM['fesText'] = $fields->frame2ppm_text_ef;
        $data->frame2PPM['lusText'] = $fields->frame2ppm_text_lu;
        $data->frame2PPM['asText'] = $fields->frame2ppm_text_as;
        $data->frame2PPM['bbox'] = $fields->frame2ppm_video_bbox;
        $data->frame2PPM['framesBBox'] = $fields->frame2ppm_video_frame;
        $data->frame2PPM['fesBBox'] = $fields->frame2ppm_video_ef;
        $data->frame2PPM['lusBBox'] = $fields->frame2ppm_video_obj;
        $data->frame2PPM['avgDuration'] = number_format($fields->frame2ppm_avg_sentence, 3, $decimal, '');
        $data->frame2PPM['avgAS'] = number_format($fields->frame2ppm_avg_obj, 3, $decimal, '');
        $data->frame2NLG['sentences'] = $fields->frame2nlg_text_sentence;
        $data->frame2NLG['framesText'] = $fields->frame2nlg_text_frame;
        $data->frame2NLG['fesText'] = $fields->frame2nlg_text_ef;
        $data->frame2NLG['lusText'] = $fields->frame2nlg_text_lu;
        $data->frame2NLG['asText'] = $fields->frame2nlg_text_as;
        $data->frame2NLG['bbox'] = $fields->frame2nlg_video_bbox;
        $data->frame2NLG['framesBBox'] = $fields->frame2nlg_video_frame;
        $data->frame2NLG['fesBBox'] = $fields->frame2nlg_video_ef;
        $data->frame2NLG['lusBBox'] = $fields->frame2nlg_video_obj;
        $data->frame2NLG['avgDuration'] = number_format($fields->frame2nlg_avg_sentence, 3, $decimal, '');
        $data->frame2NLG['avgAS'] = number_format($fields->frame2nlg_avg_obj, 3, $decimal, '');
        $data->frame2Gesture['sentences'] = $fields->frame2gesture_text_sentence;
        $data->frame2Gesture['framesText'] = $fields->frame2gesture_text_frame;
        $data->frame2Gesture['fesText'] = $fields->frame2gesture_text_ef;
        $data->frame2Gesture['lusText'] = $fields->frame2gesture_text_lu;
        $data->frame2Gesture['asText'] = $fields->frame2gesture_text_as;
        $data->frame2Gesture['bbox'] = $fields->frame2gesture_video_bbox;
        $data->frame2Gesture['framesBBox'] = $fields->frame2gesture_video_frame;
        $data->frame2Gesture['fesBBox'] = $fields->frame2gesture_video_ef;
        $data->frame2Gesture['lusBBox'] = $fields->frame2gesture_video_obj;
        $data->frame2Gesture['avgDuration'] = number_format($fields->frame2gesture_avg_sentence, 3, $decimal, '');
        $data->frame2Gesture['avgAS'] = number_format($fields->frame2gesture_avg_obj, 3, $decimal, '');
        $data->audition['sentences'] = $fields->audition_text_sentence;
        $data->audition['framesText'] = $fields->audition_text_frame;
        $data->audition['fesText'] = $fields->audition_text_ef;
        $data->audition['lusText'] = $fields->audition_text_lu;
        $data->audition['asText'] = $fields->audition_text_as;
        $data->audition['bbox'] = $fields->audition_video_bbox;
        $data->audition['framesBBox'] = $fields->audition_video_frame;
        $data->audition['fesBBox'] = $fields->audition_video_ef;
        $data->audition['lusBBox'] = $fields->audition_video_obj;
        $data->audition['avgDuration'] = number_format($fields->audition_avg_sentence, 3, $decimal, '');
        $data->audition['avgAS'] = number_format($fields->audition_avg_obj, 3, $decimal, '');
        $data->multi30k['images'] = $fields->multi30k_image_image;
        $data->multi30k['bbox'] = $fields->multi30k_image_bbox;
        $data->multi30k['framesImage'] = $fields->multi30k_image_frame;
        $data->multi30k['fesImage'] = $fields->multi30k_image_ef;
        $data->multi30k['pttSentences'] = $fields->multi30k_ptt_sentence;
        $data->multi30k['pttFrames'] = $fields->multi30k_ptt_lome;
        $data->multi30k['ptoSentences'] = $fields->multi30k_pto_sentence;
        $data->multi30k['ptoFrames'] = $fields->multi30k_pto_lome;
        $data->multi30k['enoSentences'] = $fields->multi30k_eno_sentence;
        $data->multi30k['enoFrames'] = $fields->multi30k_eno_lome;
        $data->multi30kEntity['images'] = $fields->multi30kentity_image_image;
        $data->multi30kEntity['bbox'] = $fields->multi30kentity_image_bbox;
        $data->multi30kEntity['framesImage'] = $fields->multi30kentity_image_frame;
        $data->multi30kEntity['fesImage'] = $fields->multi30kentity_image_ef;
        $data->multi30kEvent['images'] = $fields->multi30kevent_image_image;
        $data->multi30kEvent['bbox'] = $fields->multi30kevent_image_bbox;
        $data->multi30kEvent['framesImage'] = $fields->multi30kevent_image_frame;
        $data->multi30kEvent['fesImage'] = $fields->multi30kevent_image_ef;
    }

}
