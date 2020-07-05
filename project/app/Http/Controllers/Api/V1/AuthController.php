<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Login API
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = User::where([
            'email' => $request->email,
        ])->first();

        if (is_null($user) || !Hash::check($request->password, $user->password ?? '')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // update token
        $user->api_token = Hash::make(\Illuminate\Support\Carbon::now()->toRfc2822String() . "_" . $user->id);
        $user->save();

        return response()->json([
            'success' => [
                'token' => $user->api_token
            ]
        ]);
    }
}
