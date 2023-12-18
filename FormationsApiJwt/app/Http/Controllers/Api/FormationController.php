<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormationRequest;
use App\Models\Formation;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    public function index(){
        $formations = Formation::all();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "La liste de toutes les formations",
            'formation'=>$formations
        ],200);
    }
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
   public function update(Formation $formation, FormationRequest $request){
        $formation->nom = $request->nom;
        $formation->duree = $request->duree;
        $formation->description = $request->description;
        $formation->save();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "Formation modifié avec succès",
            'formation'=>$formation
        ],200);
   }
   public function destroy(Formation $formation){
        $formation->delete();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "Formation supprimé avec succès",
        ],200);
   }
}
