<?php

use Illuminate\Http\Request;
Route::prefix('v1')->namespace('Api\v1')->group(function() {
  

    Route::post('insert_athlet' , 'center_controll@insert_athlet'); 

    Route::post('insert_group' , 'center_controll@insert_group'); 

    Route::post('insert_race' , 'center_controll@insert_race'); 
    
    Route::post('insert_goal' , 'center_controll@insert_goal'); 

    Route::post('select_athlete' , 'center_controll@select_athlete');

    Route::get('show_athlete_without_group' , 'center_controll@show_athlete_without_group');

    
    Route::get('show_group' , 'center_controll@show_group');

    Route::get('show_goal' , 'center_controll@show_goal');


});
