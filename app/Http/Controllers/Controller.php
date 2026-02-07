<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    

    // public function storeMedia(Request $request)
    // {

        
       
    //     $path = public_path('assets/uploads/');

    //     if (!file_exists($path)) {
    //         mkdir($path, 0777, true);
    //     }

    //     $file = $request->file('file');

    //     $name = uniqid() . '_' . trim($file->getClientOriginalName());

    //     $file->move($path, $name);

    //     $assetPath = asset('assets/uploads/'.$name);
       
      

    //     return response()->json([
    //         'name'          => $name,
    //         'original_name' => $file->getClientOriginalName(),
    //         'path'          => $assetPath,
    //     ]);

    // }
    // public function store(Request $request)
    // {
    //      dd($request->all());
    //     $jsonImageNames = json_encode($request->input('document'));
        

        
    //     $images = DB::table('images')->insert([
    //         'img' =>  $jsonImageNames
    //     ]);

    //     // foreach ($request->input('document', []) as $file) {
    //     //     $images->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document');
    //     // }

    //     // return redirect()->route('projects.index');
    //     return redirect()->back();
    // }

    //     public function update(Request $request, $id)
    // {

    //     $project->update($request->all());

    //     if (count($project->document) > 0) {
    //         foreach ($project->document as $media) {
    //             if (!in_array($media->file_name, $request->input('document', []))) {
    //                 $media->delete();
    //             }
    //         }
    //     }

    //     $media = $project->document->pluck('file_name')->toArray();

    //     foreach ($request->input('document', []) as $file) {
    //         if (count($media) === 0 || !in_array($file, $media)) {
    //             $project->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document');
    //         }
    //     }

    //     return redirect()->route('admin.projects.index');
    // }


    // public function index(){
    //     $images = DB::table('images')->get();
    //       // Pass the images to the view
    //     return view('projects.index', compact('images'));
    // }


    // public function getDocument(Request $request, $document)
    // {
    //     $filePath = public_path('assets/uploads/' . $document);

    //     if (file_exists($filePath)) {
    //         $file = file_get_contents($filePath);
    //         $mimeType = mime_content_type($filePath);
    //         $headers = [
    //             'Content-Type' => $mimeType,
    //             'Content-Disposition' => 'inline; filename="'. $document. '"',
    //         ];
    //         return response($file, 200, $headers);
    //     } else {
    //         abort(404, 'Document not found');
    //     }
    // }
}

