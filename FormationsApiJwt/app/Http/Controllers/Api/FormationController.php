<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormationRequest;
use App\Models\Formation;
use Illuminate\Http\Request;

class FormationController extends Controller
{/**
 * @OA\Get(
 *     path="/api/formations",
 *     summary="Liste de toutes les formations",
 *     tags={"Formations"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(
 *         response=200,
 *         description="La liste de toutes les formations",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=200),
 *                 @OA\Property(property="status_message", type="string", example="La liste de toutes les formations"),
 *                 @OA\Property(property="formation", ref="#/components/schemas/Formation"),
 *             )
 *         )
 *     )
 * )
 */

    public function index(){
        $formations = Formation::all();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "La liste de toutes les formations",
            'formation'=>$formations
        ],200);
    }
    /**
 * @OA\Post(
 *     path="/api/formations",
 *     summary="Enregistrer une nouvelle formation",
 *     tags={"Formations"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nom", "duree", "description"},
 *             @OA\Property(property="nom", type="string", example="Nom de la formation"),
 *             @OA\Property(property="duree", type="integer", example=120),
 *             @OA\Property(property="description", type="string", example="Description de la formation"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Formation enregistrée avec succès",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=200),
 *                 @OA\Property(property="status_message", type="string", example="Formation enregistrée avec succès"),
 *                 @OA\Property(property="formation", ref="#/components/schemas/Formation"),
 *             )
 *         )
 *     )
 * )
 */
    public function store(FormationRequest $request){
    // dd('ok');
        $formation = new Formation();
        $formation->nom = $request->nom;
        $formation->duree = $request->duree;
        $formation->description = $request->description;
        // dd($formation);
        $formation->save();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "Formation enregistré avec succès",
            'formation'=>$formation
        ],200);

   }
   /**
 * @OA\Put(
 *     path="/api/formations/{formation}",
 *     summary="Modifier une formation existante",
 *     tags={"Formations"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="formation",
 *         in="path",
 *         required=true,
 *         description="ID de la formation à modifier",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nom", "duree", "description"},
 *             @OA\Property(property="nom", type="string", example="Nouveau nom de la formation"),
 *             @OA\Property(property="duree", type="integer", example=150),
 *             @OA\Property(property="description", type="string", example="Nouvelle description de la formation"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Formation modifiée avec succès",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=200),
 *                 @OA\Property(property="status_message", type="string", example="Formation modifiée avec succès"),
 *                 @OA\Property(property="formation", ref="#/components/schemas/Formation"),
 *             )
 *         )
 *     )
 * )
 */
   public function update(Formation $formation, FormationRequest $request){
        $formation->nom = $request->nom;
        $formation->duree = $request->duree;
        $formation->description = $request->description;
        $formation->update();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "Formation modifié avec succès",
            'formation'=>$formation
        ],200);
   }
   /**
 * @OA\Delete(
 *     path="/api/formations/{formation}",
 *     summary="Supprimer une formation",
 *     tags={"Formations"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Parameter(
 *         name="formation",
 *         in="path",
 *         required=true,
 *         description="ID de la formation à supprimer",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Formation supprimée avec succès",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="status_code", type="integer", example=200),
 *                 @OA\Property(property="status_message", type="string", example="Formation supprimée avec succès"),
 *             )
 *         )
 *     )
 * )
 */
   public function destroy(Formation $formation){
        $formation->delete();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "Formation supprimé avec succès",
        ],200);
   }
}
