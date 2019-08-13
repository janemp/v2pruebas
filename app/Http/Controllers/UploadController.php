<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    /**
     * Subir archivo
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function file(Request $request)
    {    
        $file = $request->file('file'); 
        if(is_array($file)){
            $stored = [];
            foreach($file as $f) {
                $nombre_unico = 'files/'.uniqid().'.'.$f->getClientOriginalExtension();
                Storage::disk('uploads')->put($nombre_unico,  File::get($f));
                $stored[] = 'uploads/'.$nombre_unico;
            }
            return implode(',', $stored);
        } else {            
            $nombre_unico = 'files/'.uniqid().'.'.$file->getClientOriginalExtension();
            Storage::disk('uploads')->put($nombre_unico,  File::get($file));
            return 'uploads/'.$nombre_unico;
        }
    }

    /**
     * Convierte bas64 a archivo png
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function base64_image(Request $request)
    {
        $image = $request->base64;
        $image = explode(',', $image);
        $image = str_replace(' ', '+', $image);
        $nombre_unico = 'images/'.uniqid().'.'.'png';
        Storage::disk('uploads')->put($nombre_unico,  base64_decode($image[1]));
        return 'uploads/'.$nombre_unico;
    }

    /**
     * elimina file
     *
     * @param  string $string
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $image = explode('/', $request->nombre);        
        Storage::disk('uploads')->delete($image[1].'/'.$image[2]);
        return $request->nombre;
    }
}
