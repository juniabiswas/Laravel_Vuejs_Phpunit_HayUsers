<?php



namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\User;
use App\UserAttributes;
class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user =$request->user();

        $profile_data = UserAttributes::where('user_id', $user->id)->first();
       
        $profile['id'] = $user->id;
        $profile['name'] = $user->name;
        $profile['email'] = $user->email;
        $profile['created'] = $user->created_at->format('m-d-Y');//date('m-d-Y',$user->created_at);
        $profile['updated'] = $user->updated_at->format('m-d-Y');
        if($profile_data){
            $profile['gender'] = $profile_data->gender;
            $profile['date_of_birth'] = $profile_data->date_of_birth;
        }else{
            $profile['gender'] = "";
            $profile['date_of_birth'] = "";
        }

        return response()->json($profile);
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $request->validate([
            
            'name' => 'required',
            'email' => ['required', 'email', 'max:255','unique:users'],
            'gender' => 'required',
            'date_of_birth' => 'required',
            
        ]);
        
        // Check for correct age
        $age = \Carbon\Carbon::parse($request->input('date_of_birth'))->age;
        
        if ($age<18) {
            return response()->json([
                'message' => ' Invalid Age limit',
                'errors' => ["date_of_birth"=> 'Age limit is 18 years'],
            ], 401);
        }

    
        // Check for correct user
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::findOrFail($request->user()->id);

        $user->id = $request->user()->id;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if($user->save()){
            $user_attributes=UserAttributes::where('user_id', $request->user()->id)->first();
            if(!$user_attributes){
                $user_attributes=new UserAttributes([
                    'user_id'=> $request->user()->id,
                    'gender'=> $request->input('gender'),
                    'date_of_birth' => Carbon::createFromFormat('Y-m-d', $request->input('date_of_birth'))->format('Y-m-d'),
          
                    ]);
                
            }else{
                    
                $user_attributes->gender = $request->input('gender');
                $user_attributes->date_of_birth = $request->input('date_of_birth');
            }

            $user_attributes->save();

        }
        return response()->json([
            'message' => 'Profile details saved successfully!'
        ], 200);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
