<?php

namespace App\Services;

use Carbon\Carbon;

class WeatherFormatter
{
    /**
     * @param $weather
     * @return array
     */
    public static function format($weather): array
    {
        return [
            'temp' => $weather['main']['temp'],
            'temp_max' => $weather['main']['temp_max'],
            'temp_min' => $weather['main']['temp_min'],
            'weather' => ucfirst($weather['weather'][0]['description']),
            'icon' => $weather['weather'][0]['icon'],
            'feels_like' => $weather['main']['feels_like'],
            'humidity' => $weather['main']['humidity'],
            'wind' => $weather['wind']['speed'],
        ];
    }

    /**
     * @param $details
     * @return array
     */
    public static function formatDetails($details): array
    {
        $formattedDetail = [];
        foreach ($details as $detail) {
            $formattedDetail[] = [
                'date' => Carbon::make($detail['dt_txt'])->format('d/m/Y h:m a'),
                'temp' => $detail['main']['temp'],
                'weather' => $detail['weather'][0]['description'],
                'icon' => $detail['weather'][0]['icon'],
            ];
        }
        return $formattedDetail;
    }
}
