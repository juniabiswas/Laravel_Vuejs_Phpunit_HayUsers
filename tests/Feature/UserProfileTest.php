<?php

namespace Tests\Feature;
use App\User;
use App\UserAttributes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    public function testGetAuthUserProfile()
    {
        
        $user = factory(User::class)->create();
        $userAttributes = factory(UserAttributes::class)->create([
            'user_id' => $user->id,
            'gender' => 'female',
            'date_of_birth' =>'1990-12-10',
         ]);
        $response = $this->actingAs($user, 'api')
        ->json('GET', 'api/auth/show', ['Accept' => 'application/json']);

        $response->assertStatus(200);
       
        $response->assertJson(["id" =>$user->id,
          'name'=> $user->name,
          'email'=> $user->email,
          'created' => $user->created_at->format('m-d-Y'),
          'updated' => $user->updated_at->format('m-d-Y'),
          'gender' => $userAttributes->gender,
          'date_of_birth' =>$userAttributes->date_of_birth
             ]
            
        );
    
        
    }
    
    public function testUpdateWrongAgeAuthUserProfile()
    {
        
        $user = factory(User::class)->create();
        $userAttributes = factory(UserAttributes::class)->create([
            'user_id' => $user->id,
            'gender' => 'female',
            'date_of_birth' =>'1990-10-22',
         ]);
         $edit_params = array(
            'name'=> $user->name,
            'email'=> $user->email,
            'gender' => 'male',//$userAttributes->gender,
            'date_of_birth' =>'2021-10-10'//$userAttributes->date_of_birth
         );
        $response = $this->actingAs($user, 'api')
        ->json('PUT', 'api/auth/update',$edit_params, ['Accept' => 'application/json']);

        $response->assertStatus(401);
       
        $response->assertJsonStructure([
              
            "message"
        ]);
    
        
    }

    public function testUpdateAuthUserProfile()
    {
        
        $user = factory(User::class)->create();
        $userAttributes = factory(UserAttributes::class)->create([
            'user_id' => $user->id,
            'gender' => 'female',
            'date_of_birth' =>'1990-10-10',
         ]);
         $edit_params = array(
            'name'=> $user->name,
            'email'=> $user->email,
            'gender' => 'male',//$userAttributes->gender,
            'date_of_birth' =>$userAttributes->date_of_birth
         );
        $response = $this->actingAs($user, 'api')
        ->json('PUT', 'api/auth/update',$edit_params, ['Accept' => 'application/json']);

        $response->assertStatus(200);
       
        $response->assertJsonStructure([
              
            "message"
        ]);
    
        
    }
    
}
