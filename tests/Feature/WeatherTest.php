<?php

namespace Tests\Feature;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeatherTest extends TestCase
{

    const TEST_CITY = 'Amsterdam';
    const TEST_COUNTRY_CODE = 'NL';

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testCanAccessWelcomePage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
        $response->assertSee('Wonderkind Test Case');
    }

    public function testFormValidationWorks()
    {
        $response = $this->get(route('average-forecast'));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('city');
        $response->assertSessionHasErrors('countryCode');
    }

    public function testCheckIfCacheRememberWasCalled()
    {
        $cacheKey = self::TEST_CITY . '.' . self::TEST_COUNTRY_CODE;
        Cache::shouldReceive('remember')
            ->once()
            ->with($cacheKey, 120, Closure::class);

        $response = $this->get(
            route(
                'average-forecast',
                [
                    'city'        => self::TEST_CITY,
                    'countryCode' => self::TEST_COUNTRY_CODE,
                ]
            )
        );
    }

    public function testCheckIfAfterRequestAllDataIsThere()
    {
        $response = $this->get(
            route(
                'average-forecast',
                [
                    'city'        => self::TEST_CITY,
                    'countryCode' => self::TEST_COUNTRY_CODE,
                ]
            )
        );

        $response->assertSessionHas('results');
        $response->assertSessionHas('city');
        $response->assertSessionHas('countryCode');
    }

    public function testCheckConfigOptionsAreSet() {
        $this->assertTrue(Config::get('weatherbit.base_url', null) !== null);
        $this->assertTrue(Config::get('weatherbit.api_key', null) !== null);
    }
}
