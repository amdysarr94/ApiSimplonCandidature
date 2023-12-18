<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CandidatureRequest;
use App\Models\Candidature;
use App\Models\Formation;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
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
    public function acceptList(){
        $acceptList = Candidature::where('statut', 'accepté')->get();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "La liste des candidatures acceptées",
            'candidature'=>$acceptList
        ],200);
    }
    public function denyList(){
        $denyList = Candidature::where('statut', 'refusé')->get();
        return response()->json([
            'status_code'=>200,
            'status_message'=> "La liste des candidatures rejetées",
            'candidature'=>$denyList
        ],200);
    }
}
