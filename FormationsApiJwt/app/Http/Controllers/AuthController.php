<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use OpenApi\Annotations as OA;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','logout']]);
    }
    /**
 * @OA\Post(
 *     path="/register",
 *     summary="Enregistrer un nouvel utilisateur",
 *     tags={"Utilisateurs"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password";},
 *             @OA\Property(property="name", type="string", example="Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="secret"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur enregistré",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=200),
 *                 @OA\Property(property="status_message", type="string", example="Utilisateur enregistré"),
 *                 @OA\Property(property="user", ref="#/components/schemas/User")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur d'enregistrement de l'utilisateur",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=422),
 *                 @OA\Property(property="status_message", type="string", example="Erreur d'enregistrement de l'utilisateur")
 *             )
 *         )
 *     ),
 *     security={}
 * )
 */
    public function register(Request $request){
        // dd('ok');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'role' => $request->role,
        ]);

        $token = Auth::guard('api')->login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Le compte est créé avec succès',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
/**
 * @OA\Post(
 *     path="/login",
 *     summary="Connecter un utilisateur",
 *     tags={"Utilisateurs"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="secret"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur connecté",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=200),
 *                 @OA\Property(property="status_message", type="string", example="Utilisateur connecté"),
 *                 @OA\Property(property="user", ref="#/components/schemas/User")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non autorisé",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=401),
 *                 @OA\Property(property="status_message", type="string", example="Non autorisé")
 *             )
 *         )
 *     ),
 *     security={}
 * )
 */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Non autorisé',
            ], 401);
        }

        $user = Auth::guard('api')->user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }
/**
 * @OA\Post(
 *     path="/logout",
 *     summary="Déconnecter l'utilisateur",
 *     tags={"Utilisateurs"},
 *     @OA\Response(
 *         response=200,
 *         description="Déconnexion réussie",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=200),
 *                 @OA\Property(property="status_message", type="string", example="Déconnexion réussie")
 *             )
 *         )
 *     ),
 *     security={}
 * )
 */
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Deconnexion réussie',
        ]);
    }


    // public function refresh()
    // {
    //     return response()->json([
    //         'status' => 'success',
    //         'user' => Auth::guard('api')->user(),
    //         'authorisation' => [
    //             'token' => Auth::guard('api')->refresh(),
    //             'type' => 'bearer',
    //         ]
    //     ]);
    // }


    
}