<?php 
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\User;
    use App\Traits\GeneralTrait;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Validator;
    use Auth;

    class UserController extends Controller
    {
        use GeneralTrait;

        public function login(Request $request)
        {

            try {
                $rules = [
                    "email" => "required|exists:users,email",
                    "password" => "required"

                ];
                 //validation
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $code = $this->returnCodeAccordingToInput($validator);
                    return $this->returnValidationError($code, $validator);
                }

                //login
                   //variable
                $credentials = $request->only(['email', 'password']); 

                $token = Auth::guard('user-api')->attempt($credentials);//jwt generate token
                

                if (!$token){
                    return $this->returnError('E001', 'بيانات الدخول غير صحيحة');
                }
                
                $user = Auth::guard('user-api')->user();
                $user->api_token = $token;
                 //return token
                 return $this->returnData('user', $user);//return json responce

            } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
     
        }



        public function logout(Request $request)
        {
             $token = $request -> header('auth-token');
            if($token){
                try {
                    
                    JWTAuth::setToken($token)->invalidate(); //logout
                }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                    return  $this -> returnError('','some thing went wrongs');
                }
                return $this->returnSuccessMessage('Logged out successfully');
            }else{
                $this -> returnError('','some thing went wrongs');
            }

        } 


        public function store(Request $request)
        {
           $user =new User;
               $user->name = $request->name;
               $user->email = $request->email;
               $user->password = bcrypt("$request->password");
               $user->phone = $request->phone;
               $user->location_id = $request->location_id;
               $user->save();

                $response['data'] = $user;
                $response['message'] = "store success";
                $response['status_code'] = 200;
                return response()->json($response,200) ;


               
        }


           public function index()
        {
            $users = User::all();

            $response['data'] = count($users);
            $response['message'] = "This is all users";
            $response['status_code'] = 200;
            return response()->json($response,200) ;
        }

        public function update_user(Request $request)
        {
            $user=User::where('id','=',$request->id)->first();
            if(isset($user)){
                if (isset($request->name)) {
                    $user->name = $request->name;
                }
                if (isset($request->email)) {
                    $user->email = $request->email;     
                }
                if (isset($request->password)) {
                    $user->password =bcrypt("$request->password");     
                }
                if (isset($request->image)) {
                  $image_name = rand() . "." . $request->image->
                    getClientOriginalExtension();
                  $user->image =  $image_name; 
                  $request->image->move('image', $image_name);     
                }
                if (isset($request->phone)) {
                    $user->phone = $request->phone;     
                }
                if (isset($request->location_id)) {
                   $user->location_id = $request->location_id; 
                }
               

                 $user->save();
                $response['data'] = $user;
                $response['message'] = "update success";
                $response['status_code'] = 200;
                return response()->json($response,200) ;
                
     
            }
                $response['data'] = '';
                $response['message'] = "error";
                $response['status_code'] = 404;
                return response()->json($response,404) ;

        }

         public function show_user_by_phone($phone)
        {
            $user = User::all()->where('phone','=', $phone );
            if (isset($user)) {
            $response['data'] = $user;
            $response['message'] = "success";
            $response['status_code'] = 200;
            return response()->json($response,200) ;
            }
            $response['data'] = $user;
            $response['message'] = "error";
            $response['status_code'] = 404;
            return response()->json($response,404) ;
        }
    }
