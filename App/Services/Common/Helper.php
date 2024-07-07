<?php
namespace App\Services\Common;

class Helper
{
    public static function HashSha128($data)
    {
        return sha1($data);
    }
    //bcrypt
    public static function HashBcrypt($data)
    {
        return password_hash($data, PASSWORD_DEFAULT);
    }
    public static function VerifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }
    // number to string 1 => 0001
    public static function NumberToString($number, $length = 4)
    {
        return str_pad($number, $length, '0', STR_PAD_LEFT);
    }
    public static function NewGuiId($data = null)
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    public static function generateRandomString($length = 32, $prefix= '')
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = $prefix;
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function convertDateToTimestamp($date){
        return strtotime($date);
    }

    public static function convertTimestampToDate($timestamp){
        return date('Y-m-d H:i:s', $timestamp);
    }

    public static function Slugify($string, $slug = '-', $extra = null)
    {
        if (strpos($string = htmlentities($string, ENT_QUOTES, 'UTF-8'), '&') !== false) {
            $string = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|caron|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $string), ENT_QUOTES, 'UTF-8');
        }

        if (preg_match('~[^[:ascii:]]~', $string) > 0) {
            $latin = array(
                'a' => '~[àáảãạăằắẳẵặâầấẩẫậÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬą]~iu',
                'ae' => '~[ǽǣ]~iu',
                'b' => '~[ɓ]~iu',
                'c' => '~[ćċĉč]~iu',
                'd' => '~[ďḍđɗð]~iu',
                'e' => '~[èéẻẽẹêềếểễệÈÉẺẼẸÊỀẾỂỄỆęǝəɛ]~iu',
                'g' => '~[ġĝǧğģɣ]~iu',
                'h' => '~[ĥḥħ]~iu',
                'i' => '~[ìíỉĩịÌÍỈĨỊıǐĭīįİ]~iu',
                'ij' => '~[ĳ]~iu',
                'j' => '~[ĵ]~iu',
                'k' => '~[ķƙĸ]~iu',
                'l' => '~[ĺļłľŀ]~iu',
                'n' => '~[ŉń̈ňņŋ]~iu',
                'o' => '~[òóỏõọôồốổỗộơờớởỡợÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢǒŏōőǫǿ]~iu',
                'r' => '~[ŕřŗ]~iu',
                's' => '~[ſśŝşșṣ]~iu',
                't' => '~[ťţṭŧ]~iu',
                'u' => '~[ùúủũụưừứửữựÙÚỦŨỤƯỪỨỬỮỰǔŭūűůų]~iu',
                'w' => '~[ẃẁŵẅƿ]~iu',
                'y' => '~[ỳýỷỹỵYỲÝỶỸỴŷȳƴ]~iu',
                'z' => '~[źżžẓ]~iu',
            );

            $string = preg_replace($latin, array_keys($latin), $string);
        }

        return strtolower(trim(preg_replace('~[^0-9a-z' . preg_quote($extra, '~') . ']++~i', $slug, $string), $slug));
    }
    // Convert to uppercase and remove Vietnamese accents
    public static function remove_vietnamese_diacritics($str) {
        if (empty($str)) {
            return $str;
        }
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
    
        foreach($unicode as $nonUnicode=>$uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }

    // isUsername
    public static function isUsername($username)
    {
        return preg_match('/^[a-zA-Z0-9_]{6,20}$/', $username);
    }

    // hidden char by 70% length string
    // ex: senms -> se***
    public static function hiddenChar($string)
    {
        $length = strlen($string);
        $hiddenLength = (int) ($length * 0.7);
        $hiddenString = substr($string, 0, $hiddenLength);
        for ($i = 0; $i < $length - $hiddenLength; $i++) {
            $hiddenString .= '*';
        }
        return $hiddenString;
    }

    // Number to Currency
    public static function formatCurrencyVND($number)
    {
        // Set locale to Vietnamese
        setlocale(LC_MONETARY, 'vi_VN');
        // Format the number as currency in VND
        $formattedNumber = number_format($number, 0, ',', '.') . ' VNĐ';
        return $formattedNumber;
    }

    //1000 => 1.000
    public static function formatCurrency($number)
    {
        //check type is number and not empty
        if (!is_numeric($number) || empty($number)) {
            return 0;
        }
        return number_format($number, 0, ',', '.');
    }

    // Ramdom String
    public static function randomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    // Random Number 0-9
    public static function randomNumber($length = 10)
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }

    // Shoppe Price
    public static function shoppePrice($price, $isFormat = true)
    {
        $price = $price / 100000;
        if ($isFormat) {
            return number_format($price, 0, ',', '.') . ' VNĐ';
        }
        return $price;
    }

    // Shopee commission
    //₫19.690 => 19690
    public static function shoppeReturn($commission)
    {
        // Remove currency symbol and dot
        $commission = str_replace(['₫', '.'], '', $commission);
        // Remove all characters except digits
        return intval(filter_var($commission, FILTER_SANITIZE_NUMBER_INT), 10);
    }

    
    public static function currencyToNumber($currency)
    {
        // Remove currency symbol and dot
        $currency = str_replace(['₫', '.'], '', $currency);
        
        // Remove all characters except digits
        return intval(filter_var($currency, FILTER_SANITIZE_NUMBER_INT), 10);
    }

    
    // Shoppe commission: 5.4% is string => to 0.054
    public static function shoppeCommission($commission)
    {
       // percen to number 
        // '5.4%' => 0.054
        $commission = str_replace('%', '', $commission);
        $commission = (float) $commission / 100;
        return $commission;
    }

    // Anti XSS
    public static function antiXSS($data)
    {
        $data = trim($data);
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    // Google ReCaptcha
    public static function ReCAPTCHA($token,$secretKey)
    {   
        $IPAddress = self::GetClientIP();
        $verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$token.'&remoteip='.$IPAddress);
        $response = json_decode($verify);
        if($response->success){
            return true;
        }
        return  false;
    }

    public static function GetClientIP(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = '
            UNKNOWN';
        return $ipaddress;
    }
}