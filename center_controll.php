<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Athlete;
use App\Group; 
use App\week;
use App\Race; 
use App\Goal;

class center_controll extends Controller
{
    //
    public function show_group(){

        $group = Group::orderBy('groups_score', 'DESC')->get();

        return $group; 
    }

    public function show_goal(){

        $atglete_goal=Athlete::where('id','>=',1)->with('Goal_for_athlete')->get();
        for ($i = 0; $i<count(  $atglete_goal ); $i++){

            Athlete::where('id',$atglete_goal[ $i ]->id)

           ->update(['athlete_score' =>count($atglete_goal[ $i ]->Goal_for_athlete)]);
           

        }
        

       return $atglete_goal;

   }






    public function show_athlete_without_group(){
        

        $show_athlete_without_group=Athlete::where('group_id',0)->get();

        return response([
            'data' => $show_athlete_without_group,
            'status' => 'ok',
            'code' => 200
        ]); 
      

    }

    public function select_athlete(){

        $select_player= $this->validate(request() , [
            'athlete_id' => 'required',
                  'group_name' => 'required',
           
        ]);
      
    
    $request=request('athlete_id');
    
    
   

    $select_athlete = Athlete::findOrFail($request);

    $group_name=Group::where('group_name',request('group_name'))->get();

    if( count( $group_name)==0 ){

        return response([

            'status' => 'there isnt any group with this name',
            'code' => 422
            
        ]); 

    }


   
    Athlete::where('id',$request)
->update(['group_id' =>$group_name[0]->id]);

 
   return '200';
      

    }
    
    public function insert_goal(){

        $Goal= $this->validate(request() , [

            'host_group_name' => 'required',
            'guest_group_name' => 'required',
                  'athlete_id' => 'required',
            'goal_min' => 'required|',

            'group_id'=>'required'
           
        ]);

        $host_name=Group::where('group_name',request('host_group_name'))->get();

        $guest_team_name=Group::where('group_name',request('guest_group_name'))->get();

    
        $athlete_id=Athlete::where('athlete_name',request('athlete_id'))->get();


        if( count($host_name)==0 ){

            return response([
                'data' => 'there isnt any host with this name',
                'status' => 'no'
            ],422);

        }

        if( count($guest_team_name)==0 ){

            return response([
                'data' => 'there isnt any geust with this name',
                'status' => 'unsuccess'
            ],422);

        }
       
        $race_id=Race::where(
            [

            ["host_group_id",$host_name[0]->id],  

            ["guest_group_id",$guest_team_name[0]->id],

        ]
        )->get();

        
        
        if( count($athlete_id)==0 ){

            return response([
                'data' => 'athlete not exist',
                'status' => 'no'
            ],422);

        }

        if( count($race_id)==0 ){

            return response([
                'data' => 'athlete not exist',
                'status' => 'no'
            ],422);

        }

    

        

        $register_Goal= Goal::create([
            'race_id' =>$race_id[0]->id,
            'athlete_id' =>$athlete_id[0]->id,
            'goal_min' =>request('goal_min'),
            'group_id'=>request('group_id'),
            
        ]);

        return '200';

    }


    public function insert_athlet(){
  
        $athlet= $this->validate(request() , [
            'athlete_name' => 'required',
                  'athlete_role' => 'required',
            'athlete_birthday' => 'required',
            // 'score' => 'required',
            'team_id' => 'nullable',
        ]);

        //  return strlen(request('team'));

$team_id=(request('team_id'));
         if (strlen(request('team_id')==0 )){
            $team_id=0;

         }

        $insert_athlet= Athlete::create([
            'athlete_name' =>request('athlete_name'),
            'athlete_role' =>request('athlete_role'),
            'athlete_birthday' =>request('athlete_birthday'),
            'athlete_score' =>0,
            'team_id' =>$team_id,
          


        ]);

      return '200';

    }

    public function insert_group(){
  
        $group= $this->validate(request() , [
            'group_name' => 'required',
                  'first_group_cloth_color' => 'required',
            'second_group_cloth_color' => 'required',
           
        ]);



        $insert_group=Group::create([
            'group_name' =>request('group_name'),
            'first_group_cloth_color' =>request('first_group_cloth_color'),
            'second_group_cloth_color' =>request('second_group_cloth_color'),
            'group_rank_in_table' =>0,
            'groups_score' =>0,
        ]);

        return '200';

    }

    public function insert_race(){

        $race= $this->validate(request() , [
           'host_group_id' => 'required',

                'guest_group_id' => 'required',

           'race_date' => 'required',

         
          
       ]);

      

       $host_group=Group::where('group_name',request('host_group_id'))->get();

       $guest_group=Group::where('group_name',request('guest_group_id'))->get();

      

      
       

       if(count($host_group)==0){

           return response([
               'status' => 'there isnt host'
           ],422);
       }

       if(count($guest_group)==0){
           
           return response([
               'status' => 'there isnt guest'
           ],422);
       }

        $date = request('race_date');

        

        $week_id=week::where([

           ["start_date","<=",$date],

           ["end_date",">=",$date],

       ])->get();

       // return  $week_id[0]->id ;


       if( count($week_id)==0 ){

           return response([
               'status' => 'week is invalid'
           ],422);

       }
       

       $register_race= Race::create([
           'host_group_id' =>$host_group[0]->id,
           'guest_group_id' =>$guest_group[0]->id,
           'week_id' =>$week_id[0]->id,
    
       ]);
return '200';

   }



}
