<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .inline-block {
                display: inline-block;
            }
            .block {
                display: block;
            }
            .text-left {
                text-align: left;
            }
            input {
                padding: 5px 10px;
                border-radius: 10px;
                font-size: 18px;
            }
            label {
                font-weight: 800;
            }
            .mb-10 {
                margin-bottom: 10px;
            }
            button {
                font-size: 18px;
                padding: 10px;
                border: none;
                border-radius: 10px;
                cursor: pointer;
            }
            .form-error {
                color: red;
                font-weight: bold;
            }
            .half-half {
                display: flex;
            }
            .half-half div {
                flex: 1 0 50%;
            }
            .bold {
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Wonderkind Test Case
                </div>
                <div class="half-half">
                    <div>
                        <form id="weatherForm" action="{{ route('average-forecast') }}" method="GET">
                            <div class="text-left mb-10">
                                <label class="block text-left" for="city">City Name</label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="For example, Amsterdam"/>
                                @error('city')
                                    <div>
                                        <span class="form-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="text-left mb-10">
                                <label class="block text-left" for="countryCode">Two letter Country Code</label>
                                <input type="text" name="countryCode" value="{{ old('countryCode') }}" placeholder="NL">
                                @error('countryCode')
                                    <div>
                                        <span class="form-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="text-left">
                                <button type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div>
                        @if(session()->has('results'))
                            <h2>Results for {{ session('city') . ', ' . session('countryCode')}} </h2>
                            <h3>Current Average Temperature</h3>
                            <span class="bold">{{ session('results')['currentAverage'] }} ºC</span>
                            <h3>Average Temperature Next 10 Days</h3>
                            <span class="bold">{{ session('results')['nextTenDays'] }} ºC</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
