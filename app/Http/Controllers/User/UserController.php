<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @method validate(Request $request, string[] $rules)
 */
class UserController extends Controller
{
    use ValidatesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::all();

        return response()->json(['data' => $users], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['email_verified_at'] = now(); // Set email verified at to now
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);

        return response()->json(['data' => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $user = User::findorfail($id);

        return response()->json(['data' => $user], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::findorfail($id);

        $rules = [
            'email' => 'email|max:255|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];

        try {
            $this->validate($request, $rules);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation error', 'details' => $e->errors(), 'code' => 422], 422);
        }

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('email') && $user->email != $request->input('email')) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->input('email');
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        if ($request->has('admin')) {
            if(!$user->isVerified()) {
                return response()->json(['error' => 'Only verified users can modify admin field', 'code' => 409], 409);
            }

            $user->admin = $request->input('admin');
        }

        if(!$user->isDirty()) {
            return response()->json(['error' => 'You need to specify a different value to update', 'code' => 409], 409);
        }

        $user->save();

        return response()->json(['data' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
