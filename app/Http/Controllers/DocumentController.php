<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function show(User $user, $filename)
    {
        if(!\request()->user()->isAdmin()){
            abort(403);
        }

        $document = $user->documents()->whereFilename($filename)->firstOrFail();

        if($document->extention == 'pdf'){
            return response(Storage::disk('s3')->get('/documents/' . $user->id . '/' . $filename))
                ->header('Content-Type', 'application/pdf');
        }
    }
}
