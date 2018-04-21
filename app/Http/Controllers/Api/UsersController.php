<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Interactions\User\UpdateCalling;
use App\Models\Calling;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    public function index()
    {
        return UserResource::collection($this->currentWardUsers()->get());
    }

    public function withoutCalling()
    {
        return UserResource::collection($this->currentWardUsers()->whereDoesntHave('callings')->get());
    }

    public function checkStatus(Request $request)
    {
        $user = User::where('id', $request->user_id)->with('callings')->first();
        $calling = Calling::where('id', $request->calling_id)->with('organization')->first();
        $outcome = UpdateCalling::run([
            'user' => $user,
            'calling' => $calling,
        ]);

        if (!$outcome->valid) {
            return response()->json($outcome->errors->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return new UserResource($outcome->result);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
