<?php

namespace App\Services;

use Carbon\Carbon;

class WeatherFormatter
{
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
        ];
    }

    public static function formatDetails($details)
    {
        $formattedDetail = [];
        foreach ($details as $detail) {
            $formattedDetail[] = [
                'date'=> Carbon::make($detail['dt_txt'])->format('Y-m-d'),
            ];
        }
    }
}
