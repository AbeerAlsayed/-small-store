<?php

namespace App\Traits;

//use http\Env\Request;

use Illuminate\Http\Request;

trait UploadedFile
{
    public function uploadFile(Request $request, $disk = 'public'): bool|string{
        $file_name = time() . '.' . $request->file('url')->getClientOriginalName();
        $path = $request->file('url')->storeAs('images', $file_name, $disk);
        return $path;
    }
}
