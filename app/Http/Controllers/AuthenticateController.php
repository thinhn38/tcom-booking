<?php

namespace App\Http\Controllers;

use App\Http\Actions\Authenticate\LoginAction;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    public function login(LoginRequest $request, LoginAction $action)
    {
        $user = $action->execute($request->email, $request->password);
    
        return (new LoginResource($user))
                ->response()
                ->setStatusCode(Response::HTTP_OK);
    }
}
