<?php
namespace App\Http\Controllers\Auth;

use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;
use Lcobucci\JWT\Parser;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Response;
use \Laravel\Passport\Http\Controllers\AccessTokenController as ATC;

class AccessTokenController extends ATC
{
    public function issueToken(ServerRequestInterface $request)
    {
        try {
            //get username (default is :email)
            $email = $request->getParsedBody()['username'];

            //get user
            //change to 'email' if you want
            $user = User::where('email', '=', $email)->select('id', 'code', 'name')->first();

            //generate token
            $tokenResponse = parent::issueToken($request);

            //convert response to json string
            $content = $tokenResponse->getContent();

            //convert json to array
            $data = json_decode($content, true);

            if(isset($data["error"]))
                throw new OAuthServerException('The user credentials were incorrect.', 6, 'invalid_credentials', 401);

            //add access token to user
            $user = collect($user);

            return Response::json([
                'user' => $user,
                'expires' => $data['expires_in'],
                'refresh' => $data['refresh_token'],
                'token' => $data['access_token'],
            ]);
        } catch (ModelNotFoundException $e) { // email notfound
            //return error message
            return response(["message" => "User not found"], 500);
        } catch (OAuthServerException $e) { //password not correct..token not granted
            //return error message
            return response(["message" => "The user credentials were incorrect.', 6, 'invalid_credentials"], 500);
        } catch (Exception $e) {
            ////return error message
            return response(["message" => "Internal server error"], 500);
        }
    }

    public function logout(Request $request)
    {
        // TODO!!
//        $jwt = $request->bearerToken();
//        $id = (new Parser())->parse($jwt)->getHeader('jti');
//
//        $token = (new Parser())->parse($jwt);
//        if ($token->verify(new Sha256(), $this->publicKey->getKeyPath()) === false) {
//            throw OAuthServerException::accessDenied('Access token could not be verified');
//        }
//
//        $token = DB::table('oauth_access_tokens')
//            ->where('id', $id);
//        DB::table('oauth_refresh_tokens')
//            ->where('access_token_id', $id)->delete();
//
//        $token->update([
//            'revoked' => true
//        ]);
//        $token->delete();
        return response()->json([
            "response" => true,
        ], 200);
    }
}



