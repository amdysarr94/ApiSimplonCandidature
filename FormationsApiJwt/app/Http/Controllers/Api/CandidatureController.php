<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CandidatureRequest;
use App\Models\Candidature;
use App\Models\Formation;
use Illuminate\Http\Request;
/**
 * @OA\Info(title="My First API", version="0.1")
 */


class CandidatureController extends Controller
{
    public function index(){
        $candidates = Candidature::all();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "Candidature envoyé avec succès",
            'candidature'=>$candidates
        ],200);
    }
    /**
 * @OA\Post(
 *     path="/api/candidatures",
 *     summary="Envoyer une candidature",
 *     tags={"Candidatures"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nom_formation"},
 *             @OA\Property(property="nom_formation", type="string", example="Nom de la formation"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Candidature envoyée avec succès",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=200),
 *                 @OA\Property(property="status_message", type="string", example="Candidature envoyée avec succès"),
 *                 @OA\Property(property="candidature", ref="#/components/schemas/Candidature"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Vous avez déjà candidaté à cette formation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=403),
 *                 @OA\Property(property="status_message", type="string", example="Vous avez déjà candidaté à cette formation"),
 *             )
 *         )
 *     )
 * )
 */
    public function store(CandidatureRequest $request){
        // dd('ok');
        $candidate = new Candidature();
        $candidate->user_id = auth()->user()->id;
        $nomFormation = $request->nom_formation;
        $candidate->formation_id = Formation::where('nom', $nomFormation)->get()->first()->id;
        //  dd($candidate);
        // dd(Candidature::where('user_id', auth()->user()->id)->get()->first());
        // $candidate= Candidature::where('formation_id', $candidate->formation_id)->get()->first();
        $candidature = Candidature::where('formation_id', $candidate->formation_id)
                        ->where('user_id', auth()->user()->id)
                        ->get()
                        ->first();

        // dd(Candidature::where('formation_id', $candidate->formation_id)->get());
        
        if(!$candidature){
            // dd('ok');
            $candidate->save();
            return response()->json([
                'status_code'=>200,
                'status_message'=> "Candidature envoyé avec succès",
                'candidature'=>$candidate
            ],200);
        }else{
            return response()->json([
                'status_code'=>403,
                'status_message'=> "Vous avez déjà candidaté à cette formation",
                
            ],403);
        }
       
    }
    /**
 * @OA\Put(
 *     path="/api/candidatures/{id}/accept",
 *     summary="Accepter une candidature",
 *     tags={"Candidatures"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la candidature",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Candidature acceptée",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=200),
 *                 @OA\Property(property="status_message", type="string", example="Candidature acceptée"),
 *                 @OA\Property(property="candidature", ref="#/components/schemas/Candidature"),
 *             )
 *         )
 *     )
 * )
 */
    public function accept($id){
        $candidate = Candidature::where('id', $id)->get()->first();
        // dd($candidate);
        $candidate->statut = "accepté";
        $candidate->save();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "Candidature accepté",
            'candidature'=>$candidate
        ],200);
    }
    /**
 * @OA\Put(
 *     path="/api/candidatures/{id}/deny",
 *     summary="Rejeter une candidature",
 *     tags={"Candidatures"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la candidature",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Candidature rejetée",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=200),
 *                 @OA\Property(property="status_message", type="string", example="Candidature rejetée"),
 *                 @OA\Property(property="candidature", ref="#/components/schemas/Candidature"),
 *             )
 *         )
 *     )
 * )
 */
    public function  deny($id){
        $candidate = Candidature::where('id', $id)->get()->first();
        // dd($candidate);
        $candidate->statut = "refusé";
        $candidate->save();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "Candidature rejeté",
            'candidature'=>$candidate
        ],200);
    }
    /**
 * @OA\Get(
 *      path="/api/accept-list",
 *      operationId="acceptList",
 *      tags={"Candidatures"},
 *      summary="Obtenir la liste des candidatures acceptées",
 *      description="Récupère la liste des candidatures acceptées",
 *      @OA\Response(
 *          response=200,
 *          description="Liste des candidatures acceptées",
 *          @OA\JsonContent(
 *              @OA\Property(property="status_code", type="integer", format="int32", example=200),
 *              @OA\Property(property="status_message", type="string", example="La liste des candidatures acceptées"),
 *              @OA\Property(property="candidature", type="array", @OA\Items(ref="#/components/schemas/Candidature")),
 *          ),
 *      ),
 * )
 */
    public function acceptList(){
        $acceptList = Candidature::where('statut', 'accepté')->get();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "La liste des candidatures acceptées",
            'candidature'=>$acceptList
        ],200);
    }
    /**
 * @OA\Get(
 *      path="/api/deny-list",
 *      operationId="denyList",
 *      tags={"Candidatures"},
 *      summary="Obtenir la liste des candidatures refusées",
 *      description="Récupère la liste des candidatures refusées",
 *      @OA\Response(
 *          response=200,
 *          description="Liste des candidatures refusées",
 *          @OA\JsonContent(
 *              @OA\Property(property="status_code", type="integer", format="int32", example=200),
 *              @OA\Property(property="status_message", type="string", example="La liste des candidatures rejetées"),
 *              @OA\Property(property="candidature", type="array", @OA\Items(ref="#/components/schemas/Candidature")),
 *          ),
 *      ),
 * )
 */
    public function denyList(){
        $denyList = Candidature::where('statut', 'refusé')->get();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "La liste des candidatures rejetées",
            'candidature'=>$denyList
        ],200);
    }
}
