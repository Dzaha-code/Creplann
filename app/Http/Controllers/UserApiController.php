<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => [$request->user()->only(['id', 'name', 'email', 'avatar', 'google_id'])],
        ]);
    }

    public function show(Request $request, User $user): JsonResponse
    {
        abort_unless($request->user()?->is($user), 403, 'You do not have permission to view this user.');

        return response()->json(['data' => $user->only(['id', 'name', 'email', 'avatar', 'google_id'])]);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create($validatedData);

        return response()->json(['data' => $user->only(['id', 'name', 'email'])], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        abort_unless($request->user()?->is($user), 403, 'You do not have permission to update this user.');

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        $user->update($validatedData);

        return response()->json(['data' => $user->fresh()->only(['id', 'name', 'email', 'avatar', 'google_id'])]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        abort_unless($request->user()?->is($user), 403, 'You do not have permission to delete this user.');

        $user->delete();

        return response()->json(null, 204);
    }
}