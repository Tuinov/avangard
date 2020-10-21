<?php

namespace App\Http\Controllers\Widgets;

use App\Http\Controllers\Controller;

class WeatherController extends Controller
{

    public function index()
    {
        $temperature = self::getWeather();
        //dd($temperature);
        return view('widgets.weather',compact('temperature'));
    }

    public function getWeather()
    {
        $city = "Bryansk";
        $lang = "ru";
        $units = "metric";

        // формируем урл для запроса
        $url = "https://api.openweathermap.org/data/2.5/weather?q=$city,rus&units=$units&lang=$lang&appid=10a0b7202a43d231e5eab3cbe0437f36";

        // делаем запрос к апи
        $data = file_get_contents($url);

        if($data){
            // декодируем полученные данные
            $dataJson = json_decode($data);
            // выбираем из данных текущую температуру
            $result =$dataJson->main->temp;
            return $result;
        }else{
            return "Сервер не доступен!";
        }

    }

}
