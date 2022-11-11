<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collections\CollectionFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

class CollectionFiles extends Controller
{
    public function store(Request $request, CollectionFile $type)
    {
        $path = Storage::putFile('uploads', $file = $request->file('file'), [
            'visibility' => Filesystem::VISIBILITY_PUBLIC
        ]);

        $type = $type->create([
            'pre_evaluation_id' => $request['id'],
            'type_id' => $request['type_id'],
            'url' => $path,
        ]);

        return $type;
    }
}
