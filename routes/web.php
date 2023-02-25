<?php

use App\Http\Controllers\TruckController;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::group(['prefix' => 'trucks'], function () {
    Route::get('/index', 'App\Http\Controllers\TruckController@index')->name('trucks.index');
    Route::get('/purchases', 'App\Http\Controllers\TruckController@purchases')->name('purchases.index');
    Route::put('/update/{id}', 'App\Http\Controllers\TruckController@update')->name('trucks.update');
    Route::post('/store', 'App\Http\Controllers\TruckController@store')->name('trucks.store');
    Route::get('/create/truck/{id?}', 'App\Http\Controllers\TruckController@createTruck')->name('trucks.create');
    Route::get('/create/{id}', 'App\Http\Controllers\TruckController@create')->name('trucks.purchase');
    Route::get('/buy/{id}', 'App\Http\Controllers\TruckController@buy')->name('trucks.buy');
    Route::get('/delete/{id}', 'App\Http\Controllers\TruckController@destroy')->name('trucks.delete');
});

Route::group(['prefix' => 'vacas'], function () {
    Route::get('/index', 'App\Http\Controllers\VacaController@index')->name('vacas.index');
    Route::put('/update', 'App\Http\Controllers\VacaController@update');
    Route::get('/create', 'App\Http\Controllers\VacaController@create')->name('vacas.create');
    // Route::delete('/delete', 'App\Http\Controllers\TruckController@destroy');
});

Route::group(['prefix' => 'exercise'], function () {
    Route::get('/index', function () {
        $fp = file(storage_path('app/public/miarchivo.txt'));
        $count = 0;

        foreach ($fp as $f) {

            if ($f[0] == '#') {
                $count++;
                echo $f . '<br>';
            } else {
                switch ($count) {
                    case 1:
                        if (intval($f) != 0) {
                            echo intval($f) . '<br>';
                        } else {
                            echo '<br>';
                        }
                        break;
                    case 2:
                        $cadenalimpia = preg_replace("[\n|\r|\n\r]", "", $f);
                        if (!empty($cadenalimpia)) {
                            echo $f . '<br>';
                            $tareas[] = preg_replace("[\n|\r|\n\r]", '', $f);
                            $time[] = intval($f[2]);
                        } else {
                            echo '<br>';
                        }
                        break;
                    case 3:
                        $cadenalimpia = preg_replace("[\n|\r|\n\r]", "", $f);
                        if (!empty($cadenalimpia)) {
                            echo $f . '<br>';
                            $workTime[] = explode(',', preg_replace("[\n|\r|\n\r]", "", $f));
                        } else {
                            $count++;
                        }
                        break;
                }
            }
        }
        if ($count == 4) {
            echo '<h1> Tareas y tiempo a tomar </h1>';
            $work = '';
            foreach ($workTime as $works) {
                $sumTime = 0;
                if ($works) {
                    $homeWorkFinal['homework'] = 'Tarea: ' . intval($works[0]);
                    
                    foreach ($works as $homework) {
                        
                        for ($i = 0; $i < count($tareas); $i++) {
                            if (intval($tareas[$i][0]) == intval($homework)) {
                                $posicion_coincidencia = strrpos($work, $homework);
                                if($posicion_coincidencia === false){
                                    $sumTime += $time[$i];
                                }
                            }
                        }
                        $work .= $homework . ' ';
                    }
                    $homeWorkFinal['time'] ='Tiempo: ' .  $sumTime . ' seg';
                    $finalData[] = $homeWorkFinal;
                }
            }
            foreach ($finalData as $data) {
                echo $data['homework'] . ', ' . $data['time'] . '<br>';
            }
        }
    })->name('exercise.index');
});
