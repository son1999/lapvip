<?php
namespace App\Libs;

use Illuminate\Mail\Mailable;

class OlalaMail{

    protected static $sender = 'VietMartJP <no-reply@vietmartjp.bizfly.vn>';
    protected static $domain = 'ma.bizfly.vn';
    protected static $apiUrl = 'https://in-x.bizfly.vn/api/';
    protected static $apiKey = 'sFNuP5bndaW2BiW5P6Sb';

    public static function sendMailable(Mailable $mail){
        if(!empty($mail)) {
            $data = $mail->build();
            if (!empty($data->subject) && !empty($data->to)) {
                $to = [];
                foreach ($data->to as $t){
                    $to[] = $t['address'];
                }
                $postvar = [
//                    'from' => !empty($data->from) ? implode(',', $data->from) : self::$sender,
                    'from' => !empty($data->from) ? array_first($data->from)['address'] : self::$sender,
                    'to' => implode(',', $to),
                    'subject' => $data->subject,
                    'html' => $data->render(),
                ];

                //them ccs
                if(!empty($data->cc)){
                    $cc = [];
                    foreach ($data->cc as $t){
                        $cc[] = $t['address'];
                    }
                    $postvar['cc'] = implode(',', $cc);
                }

                //them bcc
                if(!empty($data->bcc)){
                    $bcc = [];
                    foreach ($data->bcc as $t){
                        $bcc[] = $t['address'];
                    }
                    $postvar['bcc'] = implode(',', $bcc);
                }
                return self::callApiSend($postvar);
            }
        }
        return false;
    }

    /*
     * Example:
     *
     * $result = \Olala::send('halymanh@olala.vn', 'lymanhha@gmail.com', 'From testing blade', ['FrontEnd::email.test']); //from template
     *
     * $result = \Olala::send('halymanh@olala.vn', 'lymanhha@gmail.com', 'From testing normal text', 'This is normal text'); //from normal text
     *
    */

    public static  function send($from = '', $to = '', $subject = '', $content, $cc = '', $bcc = '', $att = null){
        if(!empty($subject) && !empty($content) && \Lib::is_valid_email($to)){
            if(is_array($content)){
                $content = \View::make($content[0], isset($content[1]) ? $content[1] : [])->render();
            }
            $postvar = [
                'from' => !empty($from) ? $from : self::$sender,
                'to' => $to,
                'subject' => $subject,
                'html' => $content,
            ];
            if(!empty($cc)){
                $postvar['cc'] = $cc;
            }
            if(!empty($bcc)){
                $postvar['bcc'] = $bcc;
            }
//            if(!empty($att)){
//                $postvar['attachment'] = $this->attach($att);
//            }
            return self::callApiSend($postvar);
        }
        return false;
    }

    protected function attach($files){

    }

    protected static function callApiSend($post){
        if(!empty($post)) {
            $ch = curl_init();

            $postvars = '';
            foreach($post as $key=>$value) {
                $postvars .= $key . "=" .  curl_escape($ch,$value) . "&";
            }

            # Add security key to header
            curl_setopt($ch, CURLOPT_USERPWD, "api:" . self::$apiKey);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
            curl_setopt($ch, CURLOPT_URL, self::$apiUrl . self::$domain . '/messages');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_TIMEOUT, 50);
            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        }
        return false;
    }
}