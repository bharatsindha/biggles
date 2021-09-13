<?php

namespace App\Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     ** path="/user-auth",
     *   tags={"Login"},
     *   summary="Login",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                          "success": {
     *    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI3IiwianRpIjoiZDk0MzcyZDY4ZGNjNjJmOGQ0MDJkYzNkZmRkZGNmMGQ5YWIwNDMwNGQ2NTE1NjFmNWU1YThhZjYxMWY2MDgwODY2NGMxOWYyNzc3ZmNmMTciLCJpYXQiOjE1OTM0OTk3NzksIm5iZiI6MTU5MzQ5OTc3OSwiZXhwIjoxNjI1MDM1Nzc4LCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.XKVCdog8NJRp682ksq7Lb3GEpvPP6MkOk_nWlTIA57rEQiUmCCnLaEHFZYXvhwaHzv9uflItXOCAT5dKR1w_Os-QJNC2BFi9QwTP0vKBVzm0MXzFyV2WubV5cHJU8HVvNuDAf-Ed57amB5J309EUmJnzJTyaJ87eGov0V0FnAf1zcwZLcd6Me--WljGRr4INGysrTU9ifeAJkhkqLFN0eKbb5Dz-cQFpa-ydNR9Qe1-ve5KiXpIas7fX6ETAcO7z5WRFWZ_fBWvL6cjtF5wdEBART8zVLCJZkjXdp2drIsVpzITSNmJEDgi1MjTT4weEOGNXhSMCim6hOf-tgHJ1gvb_be2zIRCiwAKirOfln3jHjE8P69d2sSZFNS6keVwtTbsGvmxk-i_Efqyavmv0UCDVLiMjzA35sBrzbuqzVpnLFmw2MnNnmcrIeD7D-K9UK-cBOUjeFErUFWTLhrpsNza2pO2Ge4UKIS7cXlx46tZxJUkCsXcv66oi8L3xcMBxwHXkKpMvmz-3cBndyfy33R1XjChgbdNIcDSQ77f6thvvqum_337eIHv7zV-1W8OV-U-49b5J2JOV_qilDS93HJZmvGoKvQZ53wyIRx8Fmnh_8GbUb6TA5MO0U6ev29H-OWvBztIXWSdPt71A4aIQB-40t2ZL4fCFpyE4eJemDG4",
     *    "user": {
     *      "id": 2,
     *      "name": "Xavier Tromp",
     *      "email": "company@example.com",
     *      "email_verified_at": "2020-06-27 09:31:27",
     *      "access_level": 1,
     *      "role_id": 1,
     *      "company_id": 1,
     *      "avatar": null,
     *      "viewed_scheduler": 0,
     *      "created_at": "2020-06-27 09:31:27",
     *      "updated_at": "2020-06-27 09:31:27",
     *      "deleted_at": null
     *          }
     *      }
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *							"message": "Unauthenticated."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *							"message": "Bad Request."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="not found",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *							"message": "not found."
     *                     }
     *                 )
     *             )
     *         }
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *							"message": "Forbidden."
     *                     }
     *                 )
     *             )
     *         }
     *      )
     *)
     **/

    /**
     * login api
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);


        if (!auth()->attempt($validator)) {
            return response()->json(['error' => 'Unauthorised'], 401);
        } else {
            $success['token'] = auth()->user()->createToken('authToken')->accessToken;
            $success['user'] = auth()->user();
            return response()->json(['success' => $success])->setStatusCode(Response::HTTP_ACCEPTED);
        }
    }
}
