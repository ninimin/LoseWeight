<?php

namespace App\Http\Controllers;

use App\Models\pregnants as pregnants;
use App\Models\RecordOfPregnancy as RecordOfPregnancy;
use App\Models\sequents as sequents;
use App\Models\sequentsteps as sequentsteps;
use App\Models\users_register as users_register;
use App\Models\tracker as tracker;
use App\Models\question as question;
use App\Models\quizstep as quizstep;
use App\Models\reward as reward;

use App\Http\Controllers\checkmessageController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SqlController;
use App\Http\Controllers\CalController;

use Image; 
use Carbon\Carbon;
use DateTime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;

// define('LINE_MESSAGE_CHANNEL_SECRET','f571a88a60d19bb28d06383cdd7af631');
// define('LINE_MESSAGE_ACCESS_TOKEN','omL/jl2l8TFJaYFsOI2FaZipCYhBl6fnCf3da/PEvFG1e5ADvMJaILasgLY7jhcwrR2qOr2ClpTLmveDOrTBuHNPAIz2fzbNMGr7Wwrvkz08+ZQKyQ3lUfI5RK/NVozfMhLLAgcUPY7m4UtwVwqQKwdB04t89/1O/w1cDnyilFU=');

class ReplyMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function replymessage2($replyToken,$userMessage1,$userMessage2)
    {
          $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
          $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));


                      $textMessage1 = new TextMessageBuilder($userMessage1);
                      $textMessage2 = new TextMessageBuilder($userMessage2);

                      $multiMessage = new MultiMessageBuilder;
                      $multiMessage->add($textMessage1);
                      $multiMessage->add($textMessage2);
                      $textMessageBuilder = $multiMessage; 
     
          
             
                $response = $bot->replyMessage($replyToken,$textMessageBuilder); 


    }




 public function replymessage7($replyToken,$user)
    {

      // $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
      //     $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
   
                  $users_register = (new SqlController)->users_register_select($user);
                
                  $preg_week = $users_register->preg_week;

                  $user_Pre_weight = $users_register->user_Pre_weight;
                  $user_weight = $users_register->user_weight;
                  $user_height =  $users_register->user_height;
                  $status =  $users_register->status;

                  $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);
                  
                  $user_age =  $users_register->user_age;
                  $active_lifestyle =  $users_register->active_lifestyle;
                  $weight_criteria  = (new CalController)->weight_criteria($bmi);
                  $cal  = (new CalController)->cal_calculator($user_age,$active_lifestyle,$user_Pre_weight,$preg_week);
                  $meal_planing = (new SqlController)->meal_planing($cal);
                  $cal = json_encode($cal); 
                  $protein =  $user_Pre_weight+25;  
                  $protein = json_encode($protein); 

                  
                   $textMessageBuilder = [ 
  "type" => "flex",
  "altText" => "this is a flex message",
  "contents" =>array (
  'type' => 'carousel',
  'contents' => 
 array (
    0 => 
    array (
      'type' => 'bubble',
      'styles' => 
      array (
        'footer' => 
        array (
          'separator' => true,
        ),
      ),
      'body' => 
      array (
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => 
        array (
          0 => 
          array (
            'type' => 'text',
            'text' => 'พลังงานและปริมาณโปรตีนที่ต้องการ',
            'weight' => 'bold',
            'color' => '#1DB446',
            'size' => 'md',
            'wrap' => true,
          ),
          1 => 
          array (
            'type' => 'separator',
            'margin' => 'xxl',
          ),
          2 => 
          array (
            'type' => 'box',
            'layout' => 'vertical',
            'margin' => 'xxl',
            'spacing' => 'sm',
            'contents' => 
            array (
              0 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'พลังงานที่ต้องการในแต่ละวันคือ',
                    'size' => 'sm',
                    'color' => '#555555',
                    'weight' => 'bold',
                    'flex' => 0,
                  ),
                ),
              ),
              1 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => $cal,
                    'size' => 'md',
                    'color' => '#1DB446',
                    'flex' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'text',
                    'text' => 'กิโลแคลอรี่ต่อวัน',
                    'size' => 'sm',
                    'color' => '#555555',
                    'align' => 'end',
                  ),
                ),
              ),
              2 => 
              array (
                'type' => 'separator',
                'margin' => 'xxl',
              ),
              3 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'margin' => 'xl',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'ปริมาณโปรตีนที่ต้องการในแต่ละวันคือ',
                    'size' => 'sm',
                    'color' => '#555555',
                    'flex' => 0,
                    'weight' => 'bold',
                    'wrap' => true,
                  ),
                ),
              ),
              4 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' =>  $protein,
                    'size' => 'md',
                    'color' => '#1DB446',
                    'flex' => 0,
                  ),
                  1 => 
                  array (
                    'type' => 'text',
                    'text' => 'กรัมต่อวัน',
                    'size' => 'sm',
                    'color' => '#555555',
                    'align' => 'end',
                  ),
                ),
              ),
              5 => 
              array (
                'type' => 'separator',
                'margin' => 'xxl',
              ),
            ),
          ),
          // 3 => 
          // array (
          //   'type' => 'box',
          //   'layout' => 'vertical',
          //   'margin' => 'xxl',
          //   'contents' => 
          //   array (
          //     0 => 
          //     array (
          //       'type' => 'spacer',
          //     ),
          //     1 => 
          //     array (
          //       'type' => 'image',
          //       'url' => 'https://remi.softbot.ai/food/food.png',
          //       'aspectMode' => 'cover',
          //       'action' => 
          //       array (
          //         'type' => 'uri',
          //         'uri' => 'https://remi.softbot.ai/food/food.png',
          //       ),
          //       'size' => 'xxl',
          //     ),
          //     2 => 
          //     array (
          //       'type' => 'text',
          //       'text' => 'สามารถจัดเป็นจานอาหารสุขภาพง่ายๆ แบบรูปภาพนี้',
          //       'color' => '#aaaaaa',
          //       'wrap' => true,
          //       'margin' => 'xxl',
          //       'size' => 'xs',
          //       'align' => 'center',
          //     ),
          //   ),
          // ),
        ),
      ),
    ),
    1 => 
    array (
      'type' => 'bubble',
      'body' => 
      array (
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => 
        array (
          0 => 
          array (
            'type' => 'box',
            'layout' => 'vertical',
            'margin' => 'xxl',
            'spacing' => 'sm',
            'contents' => 
            array (
                   0 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'ข้อมูลโภชนาการ',
                    'weight' => 'bold',
                    'color' => '#1DB446',
                    'size' => 'md',
                  ),
                ),
              ),
              1 => 
              array (
                'type' => 'separator',
                'margin' => 'xxl',
              ),
              2 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'margin' => 'xl',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'มื้ออาหารหลัก 3 มื้อต่อวัน',
                    'size' => 'sm',
                    'color' => '#555555',
                    'align' => 'center',
                    'weight' => 'bold',
                    'flex' => 0,
                  ),
                ),
              ),
              3 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'ข้าว',
                    'size' => 'sm',
                    'color' => '#555555',
                  ),
                  1 => 
                  array (
                    'type' => 'text',
                    'text' => $meal_planing->starches,
                    'size' => 'sm',
                    'color' => '#1DB446',
                    'align' => 'center',
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'text' => 'ทัพพีต่อมื้อ',
                    'size' => 'sm',
                    'color' => '#555555',
                    'align' => 'end',
                  ),
                ),
              ),
              4 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'ผัก',
                    'size' => 'sm',
                    'color' => '#555555',
                  ),
                  1 => 
                  array (
                    'type' => 'text',
                    'text' =>  $meal_planing->vegetables,
                    'size' => 'sm',
                    'color' => '#1DB446',
                    'align' => 'center',
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'text' => 'ทัพพีต่อมื้อ',
                    'size' => 'sm',
                    'color' => '#555555',
                    'align' => 'end',
                  ),
                ),
              ),
              5 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'เนื้อสัตว์',
                    'size' => 'sm',
                    'color' => '#555555',
                  ),
                  1 => 
                  array (
                    'type' => 'text',
                    'text' =>  $meal_planing->meats,
                    'size' => 'sm',
                    'color' => '#1DB446',
                    'align' => 'center',
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'text' => 'ช้อนโต๊ะต่อมื้อ',
                    'size' => 'sm',
                    'color' => '#555555',
                    'align' => 'end',
                  ),
                ),
              ),
              6 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'ไขมัน',
                    'size' => 'sm',
                    'color' => '#555555',
                  ),
                  1 => 
                  array (
                    'type' => 'text',
                    'text' => $meal_planing->fats,
                    'size' => 'sm',
                    'color' => '#1DB446',
                    'align' => 'center',
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'text' => 'ช้อนโต๊ะต่อมื้อ',
                    'size' => 'sm',
                    'color' => '#555555',
                    'align' => 'end',
                  ),
                ),
              ),
              7 => 
              array (
                'type' => 'separator',
                'margin' => 'xxl',
              ),
              8 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'margin' => 'xxl',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'มื้อว่าง 2 มื้อต่อวัน',
                    'size' => 'sm',
                    'color' => '#555555',
                    'weight' => 'bold',
                    'flex' => 0,
                  ),
                ),
              ),
              9 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'ผลไม้',
                    'size' => 'sm',
                    'color' => '#555555',
                  ),
                  1 => 
                  array (
                    'type' => 'text',
                    'text' => $meal_planing->fruits,
                    'size' => 'sm',
                    'color' => '#1DB446',
                    'align' => 'center',
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'text' => 'ส่วนต่อวัน',
                    'size' => 'sm',
                    'color' => '#555555',
                    'align' => 'end',
                  ),
                ),
              ),
              10 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'นมไขมันต่ำ',
                    'size' => 'sm',
                    'color' => '#555555',
                  ),
                  1 => 
                  array (
                    'type' => 'text',
                    'text' => $meal_planing->lf_milk,
                    'size' => 'sm',
                    'color' => '#1DB446',
                    'align' => 'center',
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'text' => 'แก้วต่อวัน',
                    'size' => 'sm',
                    'color' => '#555555',
                    'align' => 'end',
                  ),
                ),
              ),
              11 => 
              array (
                'type' => 'box',
                'layout' => 'horizontal',
                'contents' => 
                array (
                  0 => 
                  array (
                    'type' => 'text',
                    'text' => 'อาหารว่าง',
                    'size' => 'sm',
                    'color' => '#555555',
                  ),
                  1 => 
                  array (
                    'type' => 'text',
                    'text' => $meal_planing->snack,
                    'size' => 'sm',
                    'color' => '#1DB446',
                    'align' => 'center',
                  ),
                  2 => 
                  array (
                    'type' => 'text',
                    'text' => 'มื้อต่อวัน',
                    'size' => 'sm',
                    'color' => '#555555',
                    'align' => 'end',
                  ),
                ),
              ),
            ),
          ),
          1 => 
          array (
            'type' => 'box',
            'layout' => 'vertical',
            'margin' => 'md',
            'contents' => 
            array (
              0 => 
              array (
                'type' => 'spacer',
              ),
            ),
          ),
        ),
      ),
    ),
    2 => 
    array (
      'type' => 'bubble',
      'styles' => 
      array (
        'footer' => 
        array (
          'separator' => true,
        ),
      ),
      'body' => 
      array (
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => 
        array (
          0 => 
          array (
            'type' => 'text',
            'text' => 'สามารถจัดเป็นจานอาหารสุขภาพง่ายๆ แบบรูปภาพนี้',
            'weight' => 'bold',
            'color' => '#1DB446',
            'size' => 'md',
            'wrap' => true,
          ),
          1 => 
          array (
            'type' => 'separator',
            'margin' => 'xxl',
          ),
          2 => 
          array (
            'type' => 'box',
            'layout' => 'vertical',
            'margin' => 'xxl',
            'contents' => 
            array (
              0 => 
              array (
                'type' => 'spacer',
              ),
              1 => 
              array (
                'type' => 'image',
                'url' => 'https://remi.softbot.ai/food/food.png',
                'aspectMode' => 'cover',
                'action' => 
                array (
                  'type' => 'uri',
                  'uri' => 'https://remi.softbot.ai/food/food.png',
                ),
                'size' => 'xxl',
              ),
              // 2 => 
              // array (
              //   'type' => 'text',
              //   'text' => 'สามารถจัดเป็นจานอาหารสุขภาพง่ายๆ แบบรูปภาพนี้',
              //   'color' => '#aaaaaa',
              //   'wrap' => true,
              //   'margin' => 'xxl',
              //   'size' => 'xs',
              //   'align' => 'center',
              // ),
            ),
          ),
        ),
      ),
    ),
  ),
)];


                                  $url = 'https://api.line.me/v2/bot/message/reply';
   $data = [
    'replyToken' => $replyToken,
    'messages' => [$textMessageBuilder],
   ];
   $access_token = 'qFLN6cTuyvSWdbB1FHgUBEsD9hM66QaW3+cKz/LsNkwzMrBNZrBkH9b1zuCGp9ks0IpGRLuT6W1wLOJSWQFAlnHT/KbDBpdpyDU4VTUdY6qs5o1RTuCDsL3jTxLZnW1qbgmLytIpgi1X1vqKKsYywAdB04t89/1O/w1cDnyilFU=';
   
   $post = json_encode($data);
   $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   $result = curl_exec($ch);
   curl_close($ch);
   echo $result . "\r\n";
}

    public function replymessage_result($replyToken,$preg_week,$bmi,$cal,$weight_criteria,$text,$user){

           $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
           $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
                   

                    if ($weight_criteria =='น้ำหนักน้อย') {
                      $result='1';
                    } elseif ($weight_criteria =='น้ำหนักปกติ') {
                      $result='2';
                    } elseif ($weight_criteria == 'น้ำหนักเกิน') {
                      $result='3';
                    } elseif ($weight_criteria =='อ้วน') {
                      $result='4';
                    }
                  


                   $actionBuilder1 = array(
                            // new MessageTemplateActionBuilder(
                            //     'กราฟน้ำหนัก', // ข้อความแสดงในปุ่ม
                            //     'กราฟน้ำหนัก'
                            // ),
                            new UriTemplateActionBuilder(
                                          'กราฟน้ำหนัก', // ข้อความแสดงในปุ่ม
                                          'https://remi.softbot.ai/graph/'.$user
                                          ),
                            new MessageTemplateActionBuilder(
                                'ทารกในครรภ์',// ข้อความแสดงในปุ่ม
                                'ทารกในครรภ์' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                        );
                   $actionBuilder2 = array(
                            new MessageTemplateActionBuilder(
                                'น้ำหนักตัวที่เหมาะสม',// ข้อความแสดงในปุ่ม
                                'น้ำหนักตัวที่เหมาะสม' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'ข้อมูลโภชนาการ',// ข้อความแสดงในปุ่ม
                                'ข้อมูลโภชนาการ' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                        );
                        $textMessageBuilder = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(
                                    new CarouselColumnTemplateBuilder(
                                        'ขณะนี้คุณมีอายุครรภ์'.$preg_week.'สัปดาห์',
                                        'สัปดาห์ที่ผ่านมา น้ำหนักตัวคุณแม่อยู่ในเกณฑ์'. $weight_criteria,
                                        'https://remi.softbot.ai/week/'.$preg_week.'.jpg',
                                        $actionBuilder1
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'จำนวนแคลอรี่ที่คุณต้องการต่อวันคือ '.$cal,
                                        'รายละเอียดการรับประทานอาหารสามารถกดปุ่มด้านล่างได้เลยค่ะ',
                                        'https://remi.softbot.ai/food/1_'.$result.'.jpg',
                                        $actionBuilder2
                                    ),                                        
                                )
                            )
                        );
              $response = $bot->replyMessage($replyToken,$textMessageBuilder);

    }
     public function replymessage($replyToken,$userMessage,$case)
    {
            $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
            $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
            
            switch($case) {
     
                 case 1 : 
                        $textMessageBuilder = new TextMessageBuilder($userMessage);
                    break;
                 case 2 : 
                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'ครั้งสุดท้ายที่มีประจำเดือน',
                                          'ครั้งสุดท้ายที่มีประจำเดือน' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'กำหนดการคลอด',
                                          'กำหนดการคลอด' 
                                          ) 
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('ขอทราบอายุครรภ์ของคุณแม่หน่อยค่ะ',
                        new ButtonTemplateBuilder(
                              $userMessage, 
                              'กรุณาเลือกตอบข้อใดข้อหนึ่งเพื่อให้ทางเราคำนวณอายุครรภ์ค่ะ', 
                               $imageUrl, 
                               $actionBuilder  
                           )
                        );              
                    break;
                 case 3 : 
                         $textMessageBuilder = new TemplateMessageBuilder('อายุครรภ์ของคุณแม่', new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ใช่',
                                        'อายุครรภ์ถูกต้อง'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่ใช่',
                                        'ไม่ถูกต้อง'
                                    )
                                )
                        )
                    ); 
                    break;

                 case 4 : 

                  $textReplyMessage = $userMessage;
                  $textMessage1 = new TextMessageBuilder($textReplyMessage);
                  $textReplyMessage =   "รายละเอียดของระดับ". "\n".
                                        "เบา -  วิถีชีวิตทั่วไป ไม่มีการออกกำลังกาย หรือมีการออกกำลังกายน้อย". "\n".
                                        "ปานกลาง - วิถีชีวิตกระฉับกระเฉง หรือ มีการออกกำลังกายสม่ำเสมอ". "\n".
                                        "หนัก - วิถีชีวิตมีการใช้แรงงานหนัก ออกกำลังกายหนักเป็นประจำ". "\n";
                  $textMessage2 = new TextMessageBuilder($textReplyMessage);
                  $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'เบา',// ข้อความแสดงในปุ่ม
                                          'เบา' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'ปานกลาง',// ข้อความแสดงในปุ่ม
                                          'ปานกลาง' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'หนัก',// ข้อความแสดงในปุ่ม
                                          'หนัก' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          ) 
                                         );

                     $imageUrl = NULL;
                    $textMessage3 = new TemplateMessageBuilder('ระดับของการออกกำลังกาย',
                     new ButtonTemplateBuilder(
                              'ระดับของการออกกำลังกาย', // กำหนดหัวเรื่อง
                              'เลือกระดับด้านล่างได้เลยค่ะ', // กำหนดรายละเอียด
                               $imageUrl, // กำหนด url รุปภาพ
                               $actionBuilder  // กำหนด action object
                         )
                      );                            

                  $multiMessage = new MultiMessageBuilder;
                  $multiMessage->add($textMessage1);
                  $multiMessage->add($textMessage2);
                  $multiMessage->add($textMessage3);
                  $textMessageBuilder = $multiMessage; 

                    break;
                 case 5 : 
                  $text1 = 'คุณแม่ต้องการแก้ไขข้อมูลไหม?';
                  $textMessage1 = new TextMessageBuilder($userMessage);
                  $textMessage2 = new TemplateMessageBuilder('คุณแม่ต้องการแก้ไขข้อมูลไหม?', new ConfirmTemplateBuilder( $text1 ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'แก้ไข',
                                        'แก้ไขข้อมูล'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ยืนยันข้อมูล',
                                        'ยืนยันข้อมูล'
                                    )
                                )
                        )
                    ); 
                  $multiMessage =     new MultiMessageBuilder;
                  $multiMessage->add($textMessage1);
                  $multiMessage->add($textMessage2);
                  // $multiMessage->add($textMessage3);
                  $textMessageBuilder = $multiMessage; 
                    break;
                  case 6 : 
                  $textMessage1 = new TextMessageBuilder('สวัสดีค่ะ ดิฉันเป็นหุ่นยนต์อัตโนมัติที่ถูกสร้างเพื่อว่าที่คุณแม่นะคะ ☺');
                  $textMessage2 = new TextMessageBuilder('ดิฉันสามารถให้ข้อมูลโภชนาการและติดตามไลฟ์สไตล์ของคุณได้ตลอดช่วงการตั้งครรภ์ค่ะ');
                  $textMessage3 = new TextMessageBuilder('เนื่องจากดิฉันยังเรียนรู้ภาษาอยู่ จึงอาจไม่เข้าใจภาษาดีพอนะคะ ต้องขออภัยล่วงหน้าด้วยค่ะ');
    

                  $textMessage4 = new TemplateMessageBuilder('คุณสนใจให้ดิฉันเป็นผู้ช่วยอัตโนมัติของคุณไหม', new ConfirmTemplateBuilder( 'คุณสนใจให้ดิฉันเป็นผู้ช่วยอัตโนมัติของคุณไหม' ,
                                array(
                                    // new MessageTemplateActionBuilder(
                                    //     'สนใจ',
                                    //     'สนใจ'
                                    // ),
                                    new UriTemplateActionBuilder(
                                        'สนใจ', // ข้อความแสดงในปุ่ม
                                        'line://app/1539139857-vwLn3OME'
                                  ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่สนใจ',
                                        'ไม่สนใจ'
                                    )
                                )
                        )
                    ); 
                  $multiMessage =     new MultiMessageBuilder;
                  $multiMessage->add($textMessage1);
                  $multiMessage->add($textMessage2);
                  $multiMessage->add($textMessage3);
                  $multiMessage->add($textMessage4);
 
                  // $multiMessage->add($textMessage3);
                  $textMessageBuilder = $multiMessage; 
                    break;

                 case 7:
                    
                    $users_register = (new SqlController)->users_register_select($userMessage);
                    $preg_week = $users_register->preg_week;

// new UriTemplateActionBuilder('กราฟ','https://remi.softbot.ai/graph/'.$userMessage),
                    $actionBuilder = array(
                                          // new UriTemplateActionBuilder(
                                          // 'กราฟน้ำหนัก', // ข้อความแสดงในปุ่ม
                                          // 'https://remi.softbot.aihttps://remi.softbot.ai/graph/'.$userMessage
                                          // ),
                                           new MessageTemplateActionBuilder(
                                         'ทารกในครรภ์',// ข้อความแสดงในปุ่ม
                                         'ทารกในครรภ์' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          )
                                         );

                    $imageUrl = 'https://remi.softbot.ai/week/'.$preg_week.'.jpg';
                    $textMessageBuilder = new TemplateMessageBuilder('สรุปข้อมูล',
                     new ButtonTemplateBuilder(
                               'ขณะนี้คุณแม่มีอายุครรภ์'.$preg_week.'สัปดาห์', // กำหนดหัวเรื่อง
                               'กราฟน้ำหนักระหว่างการตั้งครรภ์', // กำหนดรายละเอียด
                               $imageUrl, // กำหนด url รุปภาพ
                               $actionBuilder  // กำหนด action object
                         )
                      );  

                    break;

                 case 8 : 
                         $textMessageBuilder = new TemplateMessageBuilder('สัปดาห์นี้คุณแม่มีน้ำหนัก', new ConfirmTemplateBuilder( 'สัปดาห์นี้คุณแม่มีน้ำหนัก'.$userMessage.'กิโลกรัมใช่ไหมคะ?' ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ถูกต้อง',
                                        'น้ำหนักถูกต้อง'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่ถูกต้อง',
                                        'ไม่ถูกต้อง'
                                    )
                                )
                        )
                    ); 
                    break;
                     case 9 : 
                  $textMessageBuilder = new TemplateMessageBuilder('คุณแม่มีประวัติการแพ้ยาไหมคะ', new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'แพ้',
                                        'แพ้ยา'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่แพ้',
                                        'ไม่แพ้ยา'
                                    )
                                )
                        )
                    ); 

                    break;
                     case 10 : 
                  $textMessageBuilder = new TemplateMessageBuilder('คุณแม่มีประวัติการแพ้อาหารไหมคะ', new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'แพ้',
                                        'แพ้อาหาร'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่แพ้',
                                        'ไม่แพ้อาหาร'
                                    )
                                )
                        )
                    ); 

                    break;
                    case 11 : 
                         $textMessageBuilder = new TemplateMessageBuilder('วันนี้คุณแม่ทานอะไรไปบ้างคะ?', new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ทานแล้ว',
                                        'ทานแล้ว'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ยังไม่ได้ทาน',
                                        'ยังไม่ได้ทาน'
                                    )
                                )
                        )
                    ); 
                    break;
                      case 12 : 
                         $textMessageBuilder = new TemplateMessageBuilder($userMessage, new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ออกแล้ว',
                                        'ออกแล้ว'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ยังไม่ได้ออก',
                                        'ยัง'
                                    )
                                )
                        )
                    ); 
                    break;
         
                      case 13 : 
                         $textMessageBuilder = new TemplateMessageBuilder($userMessage, new ConfirmTemplateBuilder( $userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ต้องการ',
                                        'ต้องการเชื่อมข้อมูล'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่ต้องการ',
                                        'ไม่ต้องการเชื่อมข้อมูล'
                                    )
                                )
                        )
                    ); 
                    break;

                    case 14 :  
                 $textMessageBuilder = new TemplateMessageBuilder('คุณแม่เคยลงทะเบียนกับ ulife.info ไหม?', new ConfirmTemplateBuilder('คุณเคยลงทะเบียนกับ ulife.info ไหม?' ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'เคย',
                                        'เคยลงทะเบียน'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่เคย',
                                        'ไม่เคยลงทะเบียน'
                                    )
                                )
                        )
                    ); 


                   break;
                   case 15:
                      $textMessageBuilder = new TemplateMessageBuilder('แนะนำอาหาร',
                       new ImageCarouselTemplateBuilder(
                         array(
                              new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/food/f_1.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/food/f_1.jpg'
                               )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/food/f_2.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/food/f_2.jpg'
                                )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/food/f_3.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/food/f_3.jpg'
                                )
                              ),
                                 new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/food/f_4.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/food/f_4.jpg'
                               )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/food/f_5.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/food/f_5.jpg'
                                )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/food/f_6.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/food/f_6.jpg'
                                )
                              ),    
                               new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/food/n_1.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/food/n_1.jpg'
                               )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/food/n_2.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/food/n_2.jpg'
                                )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/food/n_3.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/food/n_3.jpg'
                                )
                              ),                                       
                        )
                      )
                    );
                   break;  
                     case 16:
                      $textMessageBuilder = new TemplateMessageBuilder('แนะนำการออกกำลังกาย',
                       new ImageCarouselTemplateBuilder(
                         array(
                              new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/manual/exercise.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'http://www.raipoong.com/content/detail.php?section=12&category=26&id=467'
                               )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/manual/exercise2.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'http://www.raipoong.com/content/detail.php?section=12&category=26&id=467'
                                )
                              ),
                              new ImageCarouselColumnTemplateBuilder(
                                'https://remi.softbot.ai/manual/exercise3.jpg',
                              new UriTemplateActionBuilder(
                                'Uri Template', // ข้อความแสดงในปุ่ม
                                'http://www.raipoong.com/content/detail.php?section=12&category=26&id=467'
                                )
                              )                                       
                        )
                      )
                    );
                   break;  

                      case 17 : 
                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'โรงพยาบาลธรรมศาสตร์',
                                          'โรงพยาบาลธรรมศาสตร์' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'โรงพยาบาลศิริราช',
                                          'โรงพยาบาลศิริราช' 
                                          ) 
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('โรงพยาบาลที่ฝากครรภ์',
                        new ButtonTemplateBuilder(
                              $userMessage, 
                              'กดเลือกด้านล่างเลยนะคะ', 
                               $imageUrl, 
                               $actionBuilder  
                           )
                        );  
                         break;
                      case 18 : 


                    $picFullSize = $userMessage;
                    $picThumbnail = $userMessage;
                    $textMessageBuilder = new ImageMessageBuilder($picFullSize,$picThumbnail);
                   

                    break;

                   case 19 : 


                  $text1 = 'อยากรู้อะไรกดเลยค่ะ';
                  $textMessage1 = new TextMessageBuilder($text1);
               
                    // $imageMapUrl = 'https://remi.softbot.ai/food/new_nutri2.jpg?_ignored=';
                     $imageMapUrl = 'https://remi.softbot.ai/image/menu10.jpg?_ignored=';
                    $textMessage2 = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'แนะนำอาหาร',
                        new BaseSizeBuilder(1040,1040),
                        array(
                            new ImagemapMessageActionBuilder(
                                'ไม่กิน [อาหารบางชนิด] กินอะไรแทนดี?',
                                new AreaBuilder(0,40,346,333)
                                ),
                            new ImagemapMessageActionBuilder(
                                'สัดส่วนอาหาร',
                                new AreaBuilder(346,40,346,333)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ซื้ออาหารกินข้างนอก จะกะปริมาณอย่างไร?',
                                new AreaBuilder(692,40,346,333)
                                ),


                            new ImagemapMessageActionBuilder(
                                'กินไม่ถึง หรือกินเกิน ทำอย่างไร?',
                                new AreaBuilder(0,373,346,333)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ท้องผูก ท้องอืด ทำอย่างไร?',
                                new AreaBuilder(346,373,346,333)
                                ),
                            new ImagemapMessageActionBuilder(
                                'แพ้ท้อง กินอย่างไร?',
                                new AreaBuilder(692,373,346,333)
                                ),


                            new ImagemapMessageActionBuilder(
                                'ไม่อิ่ม ทำอย่างไร?',
                                new AreaBuilder(0,706,346,333)
                                ),
                            new ImagemapMessageActionBuilder(
                                'อาหารอะไรที่ควรหลีกเลี่ยง?',
                                new AreaBuilder(346,706,346,333)
                                ),
                             new ImagemapMessageActionBuilder(
                                'อื่น ๆ (ฝากคำถามไว้ได้)',
                                new AreaBuilder(692,706,346,333)
                                ),

                        )); 

                  $multiMessage =     new MultiMessageBuilder;
                  $multiMessage->add($textMessage1);
                  $multiMessage->add($textMessage2);
                  // $multiMessage->add($textMessage3);
                  $textMessageBuilder = $multiMessage; 
                    break;     
                      case 20 : 
                    $text1 = 'อยากรู้อะไรกดเลยค่ะ';
                    $textMessage1 = new TextMessageBuilder($text1);
                    $imageMapUrl = 'https://remi.softbot.ai/food/exer1.jpg?_ignored=';
                    $textMessage2 = new ImagemapMessageBuilder(
                        $imageMapUrl,
                        'แนะนำการออกกำลังกาย',
                        new BaseSizeBuilder(1040,1040),
                        array(

                            new ImagemapMessageActionBuilder(
                                'กระดกข้อเท้า',
                                new AreaBuilder(0,173,346,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยกก้น',
                                new AreaBuilder(346,173,346,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'นอนเตะขา',
                                new AreaBuilder(692,173,346,173)
                                ),


                            new ImagemapMessageActionBuilder(
                                'นอนตะแคงยกขา',
                                new AreaBuilder(0,346,346,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'คลานสี่ขา',
                                new AreaBuilder(346,346,346,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'แมวขู่',
                                new AreaBuilder(692,346,346,173)
                                ),


                            new ImagemapMessageActionBuilder(
                                'นั่งโยกตัว',
                                new AreaBuilder(0,519,346,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'นั่งเตะขา',
                                new AreaBuilder(346,519,346,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยืนงอเข่า',
                                new AreaBuilder(692,519,346,173)
                                ),


                            new ImagemapMessageActionBuilder(
                                'ยืนเตะขาไปข้างหลัง',
                                new AreaBuilder(0,692,346,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยืนเตะขาไปด้านข้าง',
                                new AreaBuilder(346,692,346,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยืนเขย่งเท้า',
                                new AreaBuilder(692,692,346,173)
                                ),


                            new ImagemapMessageActionBuilder(
                                'ยืนกางแขน',
                                new AreaBuilder(0,865,346,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยืนแกว่งแขนสลับขึ้นลง',
                                new AreaBuilder(346,865,346,173)
                                ),
                            new ImagemapMessageActionBuilder(
                                'ยืนย่ำอยู่กับที่',
                                new AreaBuilder(692,865,346,173)
                                ),

                        )); 
                        $multiMessage =     new MultiMessageBuilder;
                        $multiMessage->add($textMessage1);
                        $multiMessage->add($textMessage2);
                       // $multiMessage->add($textMessage3);
                        $textMessageBuilder = $multiMessage; 
                    break;   

                    case 21 :  
                    $picFullSize = 'https://remi.softbot.ai/food/ex'.$userMessage.'.jpg';
                    $picThumbnail = 'https://remi.softbot.ai/food/ex'.$userMessage.'.jpg';
                    $textMessage1 = new ImageMessageBuilder($picFullSize,$picThumbnail);
                    // $picThumbnail = 'https://www.youtube.com/watch?v=eUvG5U8g6SY&list=PLWa93dkeDtZ_CidjnWp-EECxCA5IDjOa7&index=1'.$userMessage.'.mp4';
                    // $videoUrl = 'https://remi.softbot.ai/video/'.$userMessage.'.mp4';             
                    // $textMessage2 = new VideoMessageBuilder($videoUrl,$picThumbnail);

                  if($userMessage=='1'){
                    $url ='https://www.youtube.com/watch?v=eUvG5U8g6SY' ;
                  }elseif ($userMessage=='2') {
                    $url ='https://youtu.be/TdPpYXmZcr4' ;

                  }elseif ($userMessage=='3') {
                    $url ='https://youtu.be/pO8U_fZb76g' ;
                  }elseif ($userMessage=='4') {
                    $url ='https://youtu.be/Dc6C60dPXAs' ;
                  }elseif ($userMessage=='5') {
                    $url ='https://youtu.be/FHj0JX2Ofzg' ;
                  }elseif ($userMessage=='6') {
                    $url ='https://youtu.be/UuChDVqnBW4' ;
                  }elseif ($userMessage=='7') {
                    $url ='https://youtu.be/SThBwQ9ep-g' ;
                  }elseif ($userMessage=='8') {
                    $url ='https://youtu.be/NGohxaUL17g' ;
                  }elseif ($userMessage=='9') {
                    $url ='https://youtu.be/-4HhyY05FfU' ;
                  }elseif ($userMessage=='10') {
                    $url ='https://youtu.be/IS8OvBCyf-E' ;
                  }elseif ($userMessage=='11') {
                    $url ='https://youtu.be/jZsJoU2Qbdk' ;
                  }elseif ($userMessage=='12') {
                    $url ='https://youtu.be/B01N_H6FxsE' ;
                  }elseif ($userMessage=='13') {
                    $url ='https://youtu.be/0-eETKKXZ3U' ;
                  }elseif ($userMessage=='14') {
                    $url ='https://youtu.be/yrham5v5ubM' ;
                  }elseif ($userMessage=='15') {
                    $url ='https://youtu.be/0lRZGU0QLNI' ;
                  }
         
                  $textMessage2 = new TextMessageBuilder($url);
                  $multiMessage =     new MultiMessageBuilder;
                  $multiMessage->add($textMessage1);
                  $multiMessage->add($textMessage2);
                  $textMessageBuilder = $multiMessage;  
                  break;
                  case 22: 
                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'บันทึกอาหาร',
                                          'บันทึกอาหาร' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'บันทึกวิตามิน',
                                          'บันทึกวิตามิน' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'บันทึกการออกกำลังกาย',
                                          'บันทึกการออกกำลังกาย' 
                                          )  
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('บันทึกย้อนหลัง',
                        new ButtonTemplateBuilder(
                              $userMessage, 
                              'กดเลือกด้านล่างเลยนะคะ', 
                               $imageUrl, 
                               $actionBuilder  
                           )
                        );  
                         break;
                  case 23: 

                      $text1 = $userMessage->id;
                      $text2 = $userMessage->content;

                      $textMessage1 = new TextMessageBuilder($text1);
                      $textMessage2 = new TextMessageBuilder($text2);

                      $multiMessage = new MultiMessageBuilder;
                      $multiMessage->add($textMessage1);
                      $multiMessage->add($textMessage2);
                      $textMessageBuilder = $multiMessage; 
                  break;
                  case 24:
                  $user = $userMessage;
                  $users_register = (new SqlController)->users_register_select($user);
                
                  $preg_week = $users_register->preg_week;

                  $user_Pre_weight = $users_register->user_Pre_weight;
                  $user_weight = $users_register->user_weight;
                  $user_height =  $users_register->user_height;
                  $status =  $users_register->status;

                  $bmi  = (new CalController)->bmi_calculator($user_Pre_weight,$user_height);
                  
                  $user_age =  $users_register->user_age;
                  $active_lifestyle =  $users_register->active_lifestyle;
                  $weight_criteria  = (new CalController)->weight_criteria($bmi);
                  $cal  = (new CalController)->cal_calculator($user_age,$active_lifestyle,$user_Pre_weight,$preg_week);
            

                   // $sq =  (new SqlController)->select_quizstep_user($user);
                   // $code_quiz1 = $sq->code_quiz;
                   // $reward_se =  (new SqlController)->reward_select($user,$code_quiz1);
                   $reward_se =  (new SqlController)->reward_select1($user);
                   $point = $reward_se->point;
                   if($point==null)
                    {
                      $point = 0;
                    }
                          $actionBuilder1 = array(
                            new UriTemplateActionBuilder(
                                'ดูบันทึกอาหาร', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/food_diary/'.$userMessage
                            ),
                            new UriTemplateActionBuilder(
                                'ดูบันทึกวิตามิน', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/vitamin_diary/'.$userMessage
                            ),
                            new UriTemplateActionBuilder(
                                'ดูบันทึกออกกำลังกาย', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/exercise_diary/'.$userMessage
                            ),
                            // new MessageTemplateActionBuilder(
                            //     'ข้อมูลส่วนตัว',// ข้อความแสดงในปุ่ม
                            //     'ข้อมูลส่วนตัว' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            // ),
                           );
                           $actionBuilder2 = array(
                            new MessageTemplateActionBuilder(
                                'บันทึกอาหาร',// ข้อความแสดงในปุ่ม
                                'บันทึกอาหารย้อนหลัง' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'บันทึกวิตามิน',// ข้อความแสดงในปุ่ม
                                'บันทึกวิตามินย้อนหลัง' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'บันทึกออกกำลังกาย',// ข้อความแสดงในปุ่ม
                                'บันทึกออกกำลังกายย้อนหลัง' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                           );
                            $actionBuilder3 = array(
                            new MessageTemplateActionBuilder(
                                'ข้อมูลส่วนตัว',// ข้อความแสดงในปุ่ม
                                'ดูข้อมูล' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'ข้อมูลลูกน้อย', // ข้อความแสดงในปุ่ม
                                'ข้อมูลลูกน้อย'
                            ),
                            new MessageTemplateActionBuilder(
                                'บันทึกน้ำหนักย้อนหลัง',// ข้อความแสดงในปุ่ม
                                'บันทึกน้ำหนักย้อนหลัง' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                           );
                            $actionBuilder4 = array(
                            new UriTemplateActionBuilder(
                                'กราฟน้ำหนัก', // ข้อความแสดงในปุ่ม
                                'https://remi.softbot.ai/graph/'.$userMessage
                            ),
                            new MessageTemplateActionBuilder(
                                'น้ำหนักตัวที่เหมาะสม',// ข้อความแสดงในปุ่ม
                                'น้ำหนักตัวที่เหมาะสม' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'ข้อมูลโภชนาการ',// ข้อความแสดงในปุ่ม
                                'ข้อมูลโภชนาการ' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                           );
                           /////////reward//////////////
                              $actionBuilder5 = array(
                            new MessageTemplateActionBuilder(
                                'เงื่อนไขการรับสิทธิ์', // ข้อความแสดงในปุ่ม
                                'เงื่อนไขการรับสิทธิ์'
                            ),
                            new MessageTemplateActionBuilder(
                                'แลกของรางวัล',// ข้อความแสดงในปุ่ม
                                'แลกของรางวัล' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'ดูของรางวัล',// ข้อความแสดงในปุ่ม
                                'ดูของรางวัล' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                           );
          
                        $textMessageBuilder = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(

                                    new CarouselColumnTemplateBuilder(
                                        'ข้อมูลคุณแม่',
                                        'ข้อมูลส่วนตัวของคุณแม่'."\n".'ขณะนี้คุณแม่มีอายุครรภ์'.$preg_week.'สัปดาห์',
                                        'https://remi.softbot.ai/image/profile_card1.png',
                                        $actionBuilder3
                                    ),    
                                    new CarouselColumnTemplateBuilder(
                                        'ข้อมูลโภชนาการของคุณแม่',
                                        'ค่าBMI คือ'.$bmi.' จำนวนแคลอรี่ที่คุณต้องการต่อวันคือ '.$cal,
                                        'https://remi.softbot.ai/image/food_c2.png',
                                        $actionBuilder4
                                    ),                  
                                    new CarouselColumnTemplateBuilder(
                                        'ดูบันทึกย้อนหลัง',
                                        'ดูบันทึกอาหาร,การทานวิตามินและการออกกำลังกาย',
                                        'https://remi.softbot.ai/image/note1.png',
                                        $actionBuilder1
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'บันทึกข้อมูลย้อนหลัง',
                                        'การบันทึกอาหาร,การทานวิตามินและการออกกำลังกายย้อนหลัง',
                                        'https://remi.softbot.ai/image/note2.png',
                                        $actionBuilder2
                                    ), 
                                    /////////reward//////////////
                                    new CarouselColumnTemplateBuilder(
                                        'แต้มสะสม',
                                        'ตอนนี้คุณแม่มีแต้มสะสม '.$point.' แต้มค่ะ',
                                        'https://remi.softbot.ai/image/reward.png',
                                        $actionBuilder5
                                    ), 
                                                               
                                )
                            )
                        );
                    break;
                     case 25: 
                
                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'บันทึกอาหารเช้า',
                                          'บันทึกอาหารเช้าย้อนหลัง' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'บันทึกอาหารกลางวัน',
                                          'บันทึกอาหารกลางวันย้อนหลัง' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'บันทึกอาหารเย็น',
                                          'บันทึกอาหารเย็นย้อนหลัง' 
                                          )  
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('ช่วงเวลาที่คุณต้องการบันทึก',
                        new ButtonTemplateBuilder(
                              'ช่วงเวลาที่คุณแม่ต้องการบันทึกย้อนหลังค่ะ', 
                              'กดเลือกด้านล่างเลยนะคะ', 
                               $imageUrl, 
                               $actionBuilder  
                           )
                        );  
                         break;
                    case 26: 
                
                        $actionBuilder1 = array(
                                          new MessageTemplateActionBuilder(
                                          'อาหารเช้า',
                                          'อาหารเช้า' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'อาหารจานเดียว',
                                          'อาหารจานเดียว' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'กับข้าว',
                                          'กับข้าว' 
                                          )
                                         );
                        $actionBuilder2 = array(
                                          new MessageTemplateActionBuilder(
                                          'เครื่องดื่ม',
                                          'เครื่องดื่ม' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'ผลไม้',
                                          'ผลไม้' 
                                          ),
                                           new MessageTemplateActionBuilder(
                                          'อาหารว่าง',
                                          'อาหารว่าง' 
                                          )
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('แนะนำเมนูอาหาร',

                           new CarouselTemplateBuilder(
                                     array(
                                        new CarouselColumnTemplateBuilder(
                                             'แนะนำเมนูอาหาร', 
                                              'กดเลือกด้านล่างได้เลยค่ะ', 
                                               NULL, 
                                               $actionBuilder1  
                                        ),
                                        new CarouselColumnTemplateBuilder(
                                            'แนะนำเมนูอาหาร', 
                                            'กดเลือกด้านล่างได้เลยค่ะ', 
                                             NULL, 
                                             $actionBuilder2 
                                        ),                                         
                                    )
                             
                           )
                        );  
                         break;
                     case 27: 
                
                        $actionBuilder1 = array(
                                          new MessageTemplateActionBuilder(
                                          'ข้อมูลการใช้งาน',
                                          'ข้อมูลการใช้งาน' 
                                          ),
                                          new MessageTemplateActionBuilder(
                                          'วิดีโอการใช้งาน',
                                          'วิดีโอการใช้งาน' 
                                          ),
                                        
                                         );
                        $actionBuilder2 = array(
                                          new MessageTemplateActionBuilder(
                                          'เชื่อม Ulife.info',
                                          'เชื่อม Ulife.info' 
                                          ),
                                          new MessageTemplateActionBuilder(
                                          'คุณหมอประจำตัว',
                                          'คุณหมอประจำตัว' 
                                          ),
                                        
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('แนะนำการใช้งาน',
                        new CarouselTemplateBuilder(
                                     array(
                                        new CarouselColumnTemplateBuilder(
                                             'แนะนำการใช้งาน', 
                                              'กดเลือกข้างล่างได้เลยค่ะ', 
                                               NULL, 
                                               $actionBuilder1  
                                        ),
                                        new CarouselColumnTemplateBuilder(
                                            'การเชื่อมข้อมูล', 
                                            'กดเลือกข้างล่างได้เลยค่ะ', 
                                             NULL, 
                                             $actionBuilder2 
                                        ),                                         
                                    )
                             
                           )
                        );  
                         break;
                      case 28: 
                
                          // กำหนด action 4 ปุ่ม 4 ประเภท
                          $actionBuilder = array(
                              new DatetimePickerTemplateActionBuilder(
                                  'Datetime Picker', // ข้อความแสดงในปุ่ม
                                  http_build_query(array(
                                      'action'=>'reservation',
                                      'person'=>5
                                  )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                  'datetime', // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
                                  substr_replace(date("Y-m-d H:i"),'T',10,1), // วันที่ เวลา ค่าเริ่มต้นที่ถูกเลือก
                                  substr_replace(date("Y-m-d H:i",strtotime("+5 day")),'T',10,1), //วันที่ เวลา มากสุดที่เลือกได้
                                  substr_replace(date("Y-m-d H:i"),'T',10,1) //วันที่ เวลา น้อยสุดที่เลือกได้
                              ),      
      
                          );
                          $imageUrl = 'https://www.mywebsite.com/imgsrc/photos/w/simpleflower';
                          $textMessageBuilder = new TemplateMessageBuilder('Button Template',
                              new ButtonTemplateBuilder(
                                      'button template builder', // กำหนดหัวเรื่อง
                                      'Please select', // กำหนดรายละเอียด
                                      $imageUrl, // กำหนด url รุปภาพ
                                      $actionBuilder  // กำหนด action object
                              )
                          );              
                      break;
                         case 29: 
                            $stickerID = 22;
                            $packageID = 2;
                            $textMessageBuilder = new StickerMessageBuilder($packageID,$stickerID);
                        
                      break;

                       case 30 :  
                 $textMessageBuilder = new TemplateMessageBuilder('ยืนยัน', new ConfirmTemplateBuilder('คุณแม่ยืนยันจะแลกของรางวัล '.$userMessage.' ใช่ไหมคะ?' ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ยืนยัน',
                                        'ยืนยัน'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่ยืนยัน',
                                        'ไม่ยืนยัน'
                                    )
                                )
                        )
                    ); 


                   break;
                     case 31: 
                
                        $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'แลกของรางวัล',
                                          'แลกของรางวัล' 
                                          ),
                                          // new MessageTemplateActionBuilder(
                                          // 'รับของรางวัล',
                                          // 'รับของรางวัล' 
                                          // ),
                                          new MessageTemplateActionBuilder(
                                          'exit',
                                          'Q' 
                                          ),
                                         );

                        $imageUrl = NULL;
                        $textMessage2 = new TemplateMessageBuilder('menu',
                        new ButtonTemplateBuilder(
                              'Menu', 
                              'เลือกด้านล่างได้เลยค่ะ', 
                              NULL, 
                              $actionBuilder  
                           )
                        );  
                        $textReplyMessage = $userMessage;
                        $textMessage1 = new TextMessageBuilder($textReplyMessage);
                        $multiMessage =     new MultiMessageBuilder;
                        $multiMessage->add($textMessage1);
                        $multiMessage->add($textMessage2);
                       // $multiMessage->add($textMessage3);
                        $textMessageBuilder = $multiMessage; 
                         break;
                    case 32 :  
                             $textMessageBuilder = new TemplateMessageBuilder('ยืนยัน', new ConfirmTemplateBuilder($userMessage,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ยืนยัน',
                                        'ยืนยัน'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'แก้ไขอีเมล',
                                        'แก้ไขข้อมูล'
                                    )
                                  )
                                )
                             ); 


                   break;

                      case 33 :  
                               $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          'กรอกรหัส',
                                          'กรอกรหัสคุณหมอ' 
                                          ),
                                          new UriTemplateActionBuilder(
                                          'scan QRcode',
                                          'line://nv/addFriends' 
                                          ),            
                                         );

                        $imageUrl = NULL;
                        $textMessageBuilder = new TemplateMessageBuilder('คุณหมอประจำตัว',
                        new ButtonTemplateBuilder(
                              'เลือกการใส่รหัสคุณหมอประจำตัว', 
                              'กดเลือกข้างล่างได้เลยค่ะ', 
                               NULL, 
                               $actionBuilder  
                           )
                        );  

                   break;
                      case 34 :  
                                $textMessageBuilder = new TemplateMessageBuilder('ยืนยัน', new ConfirmTemplateBuilder($userMessage ,
                                array(
                                    new MessageTemplateActionBuilder(
                                        'ใช่',
                                        'ใช่'
                                    ),
                                    new MessageTemplateActionBuilder(
                                        'ไม่ใช่',
                                        'ไม่ใช่'
                                    )
                                )
                        )
                    ); 

                   break;
                     case 35 :  
                            $actionBuilder = array(
                                new MessageTemplateActionBuilder(
                                    'Message Template',// ข้อความแสดงในปุ่ม
                                    'This is Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                ),
                                new UriTemplateActionBuilder(
                                    'Uri Template', // ข้อความแสดงในปุ่ม
                                    'https://www.ninenik.com'
                                ),
                                new DatetimePickerTemplateActionBuilder(
                                    'Datetime Picker', // ข้อความแสดงในปุ่ม
                                    http_build_query(array(
                                        'action'=>'reservation',
                                        'person'=>5
                                    )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                    'datetime', // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
                                    substr_replace(date("Y-m-d H:i"),'T',10,1), // วันที่ เวลา ค่าเริ่มต้นที่ถูกเลือก
                                    substr_replace(date("Y-m-d H:i",strtotime("+5 day")),'T',10,1), //วันที่ เวลา มากสุดที่เลือกได้
                                    substr_replace(date("Y-m-d H:i"),'T',10,1) //วันที่ เวลา น้อยสุดที่เลือกได้
                                ),      
                                new PostbackTemplateActionBuilder(
                                    'Postback', // ข้อความแสดงในปุ่ม
                                    http_build_query(array(
                                        'action'=>'buy',
                                        'item'=>100
                                    )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                    'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                ),      
                            );
                            $imageUrl = 'https://www.mywebsite.com/imgsrc/photos/w/simpleflower';
                            $textMessageBuilder = new TemplateMessageBuilder('Button Template',
                                new ButtonTemplateBuilder(
                                        'button template builder', // กำหนดหัวเรื่อง
                                        'Please select', // กำหนดรายละเอียด
                                        $imageUrl, // กำหนด url รุปภาพ
                                        $actionBuilder  // กำหนด action object
                                )
                            );              

                   break;
                       case 36: 
                                     $reward_gift = (new SqlController)->reward_gift();
                                     $columnTemplateBuilders = array();
                              
                                  foreach ($reward_gift as $reward) {
                                  $columnTemplateBuilder = 
                                        new ImageCarouselColumnTemplateBuilder(
                                           'https://remi.softbot.ai/reward_gift/'.$reward['code_gift'].'.jpg',
                                            new UriTemplateActionBuilder(
                                                'link', // ข้อความแสดงในปุ่ม
                                                'https://remi.softbot.ai/reward_gift/'.$reward['code_gift'].'.jpg'
                                            )
                                        );

                                  array_push($columnTemplateBuilders, $columnTemplateBuilder);
                                }

                              $textMessageBuilder = new TemplateMessageBuilder('Image Carousel',
                              new ImageCarouselTemplateBuilder(
                                 $columnTemplateBuilders  
                              )
                           );
                      break;
                       case 37: 
                
             
                $foodmenus = (new SqlController)->foodmenu($userMessage);

                $columnTemplateBuilders = array();
                foreach ($foodmenus as $foodmenu) {

                    $columnTemplateBuilder = new CarouselColumnTemplateBuilder(
                                  $foodmenu['name_food'], 
                                  $foodmenu['cal'],
                                  'https://remi.softbot.ai/menu/'.$foodmenu['img'].'.jpg',
                                  [
                                            new PostbackTemplateActionBuilder(
                                            'คำแนะนำ', // ข้อความแสดงในปุ่ม
                                            http_build_query(array(
                                                'action'=>'foodmenu',
                                                'item'=> 'MENUfood '.$foodmenu['id']
                                            )) //,// ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                           //'คำแนะนำ'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                        ),      
                                  ]
                    );
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                }

                $carouselTemplateBuilder = new CarouselTemplateBuilder($columnTemplateBuilders);
                $textMessageBuilder = new TemplateMessageBuilder('รายการอาหาร', $carouselTemplateBuilder);



                      break;
          
             }
                $response = $bot->replyMessage($replyToken,$textMessageBuilder); 
         
    }
      public function replymessage3($replyToken,$question,$choice1,$choice2,$choice3)
    {
            $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
            $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));


                          // $textReplyMessage = $userMessage;
                          // $textMessage1 = new TextMessageBuilder($textReplyMessage);
                          // $textReplyMessage =   "คำถาม";
                          // $textMessage2 = new TextMessageBuilder($textReplyMessage);
                          $actionBuilder = array(
                                          new MessageTemplateActionBuilder(
                                          $choice1,// ข้อความแสดงในปุ่ม
                                          $choice1 // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          ),
                                           new MessageTemplateActionBuilder(
                                          $choice2,// ข้อความแสดงในปุ่ม
                                          $choice2 // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          ),
                                          //  new MessageTemplateActionBuilder(
                                          // $choice3,// ข้อความแสดงในปุ่ม
                                          // $choice3 // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                          // ) 
                                         );

                     $imageUrl = NULL;
                     $textMessage3 = new TemplateMessageBuilder('คำถาม',
                     new ButtonTemplateBuilder(
                              NULL, // กำหนดหัวเรื่อง
                              $question, // กำหนดรายละเอียด
                               $imageUrl, // กำหนด url รุปภาพ
                               $actionBuilder  // กำหนด action object
                         )
                      );                            

                  $multiMessage = new MultiMessageBuilder;
                  // $multiMessage->add($textMessage1);
                  // $multiMessage->add($textMessage2);
                  $multiMessage->add($textMessage3);
                  $textMessageBuilder = $multiMessage; 

     
          
             
                $response = $bot->replyMessage($replyToken,$textMessageBuilder); 


    }
     public function replymessage4($replyToken)
    {
            $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
            $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
  
            $user_update = (new SqlController)->reward_gift(); 

              foreach($user_update as $value){  

                $a = array(
                                    new CarouselColumnTemplateBuilder(
                                        $value->name_gift,
                                        'จำนวนแต้มสะสม '.$value->point .' แต้ม',
                                        NULL,
                                        array(
                                            new MessageTemplateActionBuilder(
                                                 'แลก',// ข้อความแสดงในปุ่ม
                                                 $value->code_gift // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                            ),
                                           )
                                    ),                                  
                        );


              $textMessageBuilder = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                $a
                            )
                        );

             }
          
             
                $response = $bot->replyMessage($replyToken,$textMessageBuilder); 


    }

public function replymessage5($replyToken,$user)
    {
          $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
          $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));


                $reward_gift = (new SqlController)->reward_gift();

                if( $reward_gift ==NULL){
                  $message = 'ไม่มีของรางวัล';
                  $textMessageBuilder = new TextMessageBuilder($message);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

                }else{
                $columnTemplateBuilders = array();
                foreach ($reward_gift as $reward) {

                    $columnTemplateBuilder = new CarouselColumnTemplateBuilder(
                                  $reward['name_gift'], 
                                  'ใช้ '.$reward['point'].' แต้มในการแลก',
                                  'https://remi.softbot.ai/reward_gift/'.$reward['code_gift'].'.jpg',
                                  [
                                            new PostbackTemplateActionBuilder(
                                            'แลกของรางวัล', // ข้อความแสดงในปุ่ม
                                            http_build_query(array(
                                                'action'=>'reward',
                                                'item'=> $reward['code_gift']
                                            )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                           // 'แลก'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                        ),      
                                  ]
                    );
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                }

                $carouselTemplateBuilder = new CarouselTemplateBuilder($columnTemplateBuilders);
                $textMessageBuilder = new TemplateMessageBuilder('รายการ Sponser', $carouselTemplateBuilder);

                }

              

                $response = $bot->replyMessage($replyToken,$textMessageBuilder);

    }

    public function replymessage6($replyToken,$user)
    {
          $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
          $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));

              //   $count = (new SqlController)->presenting_gift_count($user);

                 $rewards = (new SqlController)->presenting_gift_group($user);

                if( $rewards == NULL){
                  $message = 'คุณแม่ไม่มีของรางวัลที่ต้องรับค่ะ';
                  $textMessageBuilder = new TextMessageBuilder($message);
                  $seqcode = '0000';
                  $nextseqcode = '0000';
                  $sequentsteps_insert =  (new SqlController)->sequentsteps_update($user,$seqcode,$nextseqcode);

                }else{
                $columnTemplateBuilders = array();

                foreach ($rewards as $reward) {
                        
                    $columnTemplateBuilder = new CarouselColumnTemplateBuilder(
                        $reward['name_gift'], 
                        'จำนวน: X '.$reward['total'],
                        'https://remi.softbot.ai/card/badge.png',
                        [
                            new MessageTemplateActionBuilder('รับของรางวัล', $reward['code_gift'])
                        ,]
                    ); 
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                }
     
                $carouselTemplateBuilder = new CarouselTemplateBuilder($columnTemplateBuilders);
                $textMessageBuilder = new TemplateMessageBuilder('รายการ Sponser', $carouselTemplateBuilder);

                }
                $response = $bot->replyMessage($replyToken,$textMessageBuilder);
          

            


    }
public function replymessage_food($replyToken,$user)
    {
          $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
          $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));

          
                    $rewards   = (new SqlController)->foodmenu_img();
                    $columnTemplateBuilders = array();
                 foreach ($rewards as $reward) {
                        
                    $columnTemplateBuilder = new CarouselColumnTemplateBuilder(
                       NULL, 
                        'อาหาร',
                        'https://remi.softbot.ai/sug_food/'.$reward['name_img'],
                        [
                            new UriTemplateActionBuilder('link','https://remi.softbot.ai/sug_food/'.$reward['name_img'])
                        ,]
                    ); 
                    array_push($columnTemplateBuilders, $columnTemplateBuilder);
                }
     
                $carouselTemplateBuilder = new CarouselTemplateBuilder($columnTemplateBuilders);
                $textMessageBuilder = new TemplateMessageBuilder('รายการอาหาร', $carouselTemplateBuilder);

     
                $response = $bot->replyMessage($replyToken,$textMessageBuilder);

            


    }


public function replymessage_food1($replyToken,$user)
    {
          $httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
          $bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
          


          $rewards   = (new SqlController)->foodmenu_img();
          $columnTemplateBuilders = array();

          foreach ($rewards as $reward) {
          $columnTemplateBuilder = 
                new ImageCarouselColumnTemplateBuilder(
                     'https://remi.softbot.ai/sug_food/'.$reward['name_img'],
                    new UriTemplateActionBuilder(
                        'link', // ข้อความแสดงในปุ่ม
                        'https://remi.softbot.ai/sug_food/'.$reward['name_img']
                    )
                );

          array_push($columnTemplateBuilders, $columnTemplateBuilder);
        }



        $textMessageBuilder = new TemplateMessageBuilder('Image Carousel',
        new ImageCarouselTemplateBuilder(
           $columnTemplateBuilders  
        )
    );
                  

     
                $response = $bot->replyMessage($replyToken,$textMessageBuilder);

     
                
    }
}
