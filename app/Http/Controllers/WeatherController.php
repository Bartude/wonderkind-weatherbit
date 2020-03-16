<?php

namespace App\Http\Controllers;

use Http;
use Cache;
use Illuminate\Http\Request;

class WeatherController extends Controller
{

    /**
     * Gets the average forecasts either from the Cache,
     * or if non-existent, then it fetches from the API and calculates it
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getAverageForecast(Request $request)
    {
        $this->validate(
            $request,
            [
                'city'        => 'required|string|max:191',
                'countryCode' => 'required|alpha|size:2',
            ]
        );

        $cacheKey = $request->city . '.' . $request->countryCode;

        $results = Cache::remember(
            $cacheKey,
            120,
            function () use ($request) {
                $forecasts = $this->getForecasts($request->city, $request->countryCode);

                return $this->calculateAverages($forecasts);
            }
        );

        return back()->with(
            [
                'results'     => $results,
                'city'        => $request->city,
                'countryCode' => $request->countryCode,
            ]
        );
    }

    /**
     * Fetches the forecasts from the Weatherbit API using a city and country code
     *
     * @param string $city
     * @param string $countryCode
     * @return array Containing all the information related to forecasts for the next 10 days
     * @throws \Throwable
     */
    private function getForecasts(string $city, string $countryCode): array
    {
        $params = http_build_query(
            [
                'city'    => $city,
                'country' => $countryCode,
                'days'    => 10,
                'key'     => config('weatherbit.api_key'),
            ]
        );

        $response = Http::get(config('weatherbit.base_url') . '?' . $params);

        throw_unless($response->ok(), \Exception::class, 'There was a problem with the weather request');

        return $response->json()['data'];
    }

    /**
     * Calculates the temperature averages based on the forecasts
     *
     * @param array $forecasts Contains forecasts for the next 10 days
     * @return array  Containing the current day average and the average
     * for the next tens days
     */
    private function calculateAverages(array $forecasts): array
    {
        $averages = collect($forecasts)->map(
            function ($day) {
                return collect(
                    [
                        'low'  => $day['low_temp'],
                        'high' => $day['high_temp'],
                    ]
                )->avg();
            }
        );

        $currentAverage = round($averages->first(), 1);
        $nextTenDays = round($averages->avg(), 1);

        return [
            'currentAverage' => $currentAverage,
            'nextTenDays'    => $nextTenDays,
        ];
    }
}
