<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\LoginUser;
use App\Customer;
use App\CreateRequest;

use Validator;
class UserController extends Controller
{
    // status for error and success
    public $successStatus = '200';
    public $failedStatus = '0';
/**
     * login api
     *
     * @return \Illuminate\Http\Response
     */

     //login api
    public function login(Request $request){
        //reuesting information from users
        $mobile = $request->mobile;
        $password=$request->password;
        //checking if it is emoty or not
       if(empty($mobile)){
            return response()->json([
                'responceMessage'         => 'mobile is required',
                'responceCode'            =>  $this->failedStatus,
               ]);
           }elseif(empty($password)){
            return response()->json([
                'responceMessage'         => 'password is required',
                'responceCode'            =>  $this->failedStatus,
               ]);
           }else{
               //genrating random otp
            $otp = rand('1111','9999');
            //counting that mobile occur only once neither more nor 0
            $countMobileNo = LoginUser::where('mobile',$mobile)->where('active','enable')->count();

            if($countMobileNo==1){
                //getting mobile number from db
                $userMobileData = LoginUser::where('mobile',$mobile)->first();
           //updating otp in database
           $updateOtp = LoginUser::where('mobile',$mobile)->update(['otp' => $otp]);
           if ($updateOtp) {
               //otp success message
             return response()->json([
              'responceMessage'         => 'otp send on your mobile no.',
              'responceCode'            =>  $this->successStatus,
              'mobile'                  =>  $userMobileData->mobile,
              'otp'                     =>  (string)$otp,
           ]);
           }
           else{
               //error when otp sending is fail
             return response()->json([
            'responceMessage'         => 'otp sending failed',
            'responceCode'            =>  $this->failedStatus,
           ]);
           }
        }else{
            //error when number is not register
                return response()->json([
                    'responceMessage'         => 'your number is not register',
                    'responceCode'            =>  $this->failedStatus,

                   ]);

            }

        }
    }
/**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    // otp for self registeration
    public function register(Request $request){
        // validating all information
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required|email',
            'gender_id' => 'required',
            'dob' => 'required',
            'anniversary' => 'required',
            'comment' => 'required',
        ]);
        //if validation fail throw error
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        //storing all data
        $input = $request->all();
        // creating records from data
        $user = User::create($input);
        // genrating token
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        $success['name'] =  $user->name;
        //reponse message after successfully saved
        if(empty($user)){
        return response()->json([
            'responceMessage'         => 'registered successfully',
            'responceCode'            =>  $this-> successStatus,
            'data'                    => $success,
           ]);
        }else{
            return response()->json([
                'responceMessage'         => 'register data not entered',
                'responceCode'            =>  $this-> failedStatus,
                'data'                    =>  [],
               ]);
            }
    }

    //add customer into customer list
    public function AddCustomer(Request $request){
        //validating one of twi field at a time
        $validator=validator::make($request->all(),[
            'mobile_no' => 'required_without:email',
             'email' => 'required_without:mobile_no',
        ]);
        //validation failed then show error message
        if ($validator->fails()) {
            return response()->json([
                    ['responceMessage'         => $validator->errors()],
                    'responceCode'            =>  $this-> failedStatus,
                   ]);

        }
        //storing data in database
        $contact = Customer::create($request->all());
        //getting success response when data stored in database
        if(empty($contact)){
           return response()->json([
            'responceMessage'         => 'data successfully stored',
            'responceCode'            =>  $this-> successStatus,
            'data'                    => $contact,
           ]);
        }else{
            return response()->json([
                'responceMessage'         => 'data not entered',
                'responceCode'            =>  $this-> failedStatus,
                'data'                    =>  [],
               ]);
            }
     }

    //view all data of customers
    public function ViewCustomer(){
        $customer = Customer::all();
        if(empty($customer)){
          return response()->json([
            'responceMessage'         => 'data successfully fetched',
            'responceCode'            =>  $this-> successStatus,
            'data'                    =>  $customer,
           ]);
        }else{
            return response()->json([
                'responceMessage'         => 'there is no data',
                'responceCode'            =>  $this-> failedStatus,
                'data'                    =>  [],
               ]);
            }
    }

    // create new requets of customer
    public function CreateRequest(Request $request){
        //validating the required fields
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|max:10',
            'expected_date' => 'required',
            'request_text' => 'required|min:10',
        ]);

         //if validation failed
        if ($validator->fails()) {
            return response()->json([
                ['responceMessage'         => $validator->errors()],
                'responceCode'            =>  $this-> successStatus,
               ]);
        }
            //taking data from customer and inserting in database column
            $createrequest= new CreateRequest;
            $createrequest->mobile_no=$request->mobile_no;
            $createrequest->product_inquired=$request->product_inquired;
            $createrequest->customer_price_expectation=$request->customer_price_expectation;
            $createrequest->expected_date=$request->expected_date;
            $createrequest->request_text=$request->request_text;
            $createrequest->image_file=$request->image_file;
            $storeddata=$createrequest->save();
             //response message after submission
             if(!empty($storeddata)){
                 return response()->json([
                'responceMessage'         => 'Request Created Successfully',
                'responceCode'            =>  $this-> successStatus,
                'data'                    =>  $createrequest,
               ]);
            }else{
                return response()->json([
                    'responceMessage'         => 'data not entered',
                    'responceCode'            =>  $this-> failedStatus,
                    'data'                    =>  [],
                   ]);

            }

    }
}
