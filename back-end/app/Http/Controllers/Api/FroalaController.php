<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FroalaController extends Controller
{

    public function imageUpload(Request $request)
    {
        if ($request->hasFile('file')) {
            $filename = uniqid() . "_" . $request->file->getClientOriginalName();
            $request->file->storeAs('public/uploads/froala', $filename);
            return response()->json([
                "link" => url("storage/uploads/froala/$filename")
            ]);
        }
    }

    public function imageDelete(Request $request)
    {
        $object = parse_url($request->path);
        $filepath = str_replace('storage', 'public', $object['path']);

        if (Storage::exists($filepath)) {
            Storage::delete($filepath);
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'fail']);
    }

    public function imageManagerLoad()
    {
        $files = File::allFiles(public_path('/storage/uploads/froala'));
        $data = [];
        foreach ($files as $file) {
            $data[] = [
                "url" => url("/storage/uploads/froala/{$file->getRelativePathName()}"),
                "thumb" => url("/storage/uploads/froala/{$file->getRelativePathName()}"),
                "tag" => basename($file->getRelativePath()),
            ];
        }
        return response()->json($data);
    }

    public function imageManagerDelete(Request $request)
    {
        $object = parse_url($request->src);
        $filepath = str_replace('storage', 'public', $object['path']);

        if (Storage::exists($filepath)) {
            Storage::delete($filepath);
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'fail']);
    }
}
