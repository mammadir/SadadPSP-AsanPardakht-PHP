<?php

function curl_post($url, $params)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    $res = curl_exec($ch);
    curl_close($ch);

    return $res;
}

function curl_get($url, $timeout = 30)
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => $timeout,
    ]);
    $res = curl_exec($ch);
    curl_close($ch);

    return json_decode($res);
}

function persian_number_to_latin($string)
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    $num = range(0, 9);
    $convertedPersianNums = str_replace($persian, $num, $string);
    $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

    return str_replace(' ', '', $englishNumbersOnly);
}

function get_date_path()
{
    return date('Y') . '/' . date('m') . '/' . date('d');
}

function nl2br2($string)
{
    return nl2br(strip_tags(preg_replace('#<script(.*?)>(.*?)</script>#is', '', $string)));
}

function jalali_to_georgian($date)
{
    try {
        $dateArray = explode('-', $date);
        $dateTemp = Morilog\Jalali\jDateTime::toGregorian(persian_number_to_latin($dateArray[0]), persian_number_to_latin($dateArray[1]), persian_number_to_latin($dateArray[2]));
        $dateTemp[1] = $dateTemp[1] < 10 ? '0' . $dateTemp[1] : $dateTemp[1];
        $dateTemp[2] = $dateTemp[2] < 10 ? '0' . $dateTemp[2] : $dateTemp[2];
        $date = $dateTemp[0] . '-' . $dateTemp[1] . '-' . $dateTemp[2];

        return is_valid_date($date) ? $date : null;
    } catch (\Exception $e) {
        return null;
    }
}

function is_valid_date($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);

    return $d && $d->format($format) === $date;
}

function site_config($key)
{
    $config = App\Config::where('key', '=', $key)->first();
    if ($config) {
        return $config->value;
    }

    return '';
}


function handle_exception(\Exception $e)
{
    if (app('site_configs')['APP_ENV'] == 'local') {
        return redirect()->back()
            ->with('alert', 'danger')
            ->with('message', $e->getMessage());
    }

    return redirect()->back()
        ->with('alert', 'danger')
        ->with('message', 'Error');
}

function custom_money_format($money)
{
    return number_format($money, 0, '', ',');
}

function mask_card_number($cardNumber)
{
    $cardNumber = str_replace('-', '', $cardNumber);

    return substr($cardNumber, 0, 6) . '******' . substr($cardNumber, -4, 4);
}

function create_date_range($strDateFrom, $strDateTo)
{
    $aryRange = [];
    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }

    return $aryRange;
}


function theme_asset($url, $secure = null)
{
    return asset('themes/' . site_config('theme') . '/' . $url, $secure);
}

function lang($key)
{
    if (\Lang::has('fp::' . $key)) {
        return trans('fp::' . $key);
    }

    return trans($key);
}

function date_diff_in_minutes(\Carbon\Carbon $start, \Carbon\Carbon $finish)
{
    $totalDuration = $start->diffInMinutes($finish);

    return $totalDuration;
}

function date_diff_in_days(\Carbon\Carbon $start, \Carbon\Carbon $finish)
{
    $totalDuration = $start->diffInDays($finish);

    return $totalDuration;
}
