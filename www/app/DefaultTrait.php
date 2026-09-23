<?php

namespace App;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

trait DefaultTrait
{
    public function verifyAndUpload(Request $request, $fieldname, $directory) {

        if( $request->hasFile( $fieldname ) ) {

            if (!$request->file($fieldname)->isValid()) {
                return back()->with('error', 'Invalid Image!');
            }
            $attachment = $request->file($fieldname);
            $randomName = str()->random(30);
            $name = $attachment->getClientOriginalName();
            $fileName = str($name)->append($randomName)->slug().'.'.$attachment->getClientOriginalExtension();
            $request->file($fieldname)->move(public_path($directory), $fileName);
            $filePath = $directory.$fileName;
            //$filePath = $request->file($fieldname)->store($directory, 'public'); 
            return $filePath;
        }

        return null;

    }

    public function ImageResizer(Request $request, $fieldname, $directory, $size_height=1280, $size_width=720) {
        if( $request->hasFile( $fieldname ) ) {

            if (!$request->file($fieldname)->isValid()) {
                return back()->with('error', 'Invalid Image!');
            }
            
            ini_set('memory_limit', '512M');
            $thumbnail = $request->file($fieldname);
            $fileName = time().'-'.$thumbnail->getClientOriginalName();
            $fileName = str($fileName)->slug().'.'.$thumbnail->getClientOriginalExtension();

            $thumbnail->move($directory,$fileName);

            $imgManager = new ImageManager(new Driver());

            $thumbImage = $imgManager->read(public_path($directory.$fileName));

            if(!empty($size_height) && !empty($size_width)) {
                $thumbImage->resize($size_height,$size_width);
            }

            $thumbImage->save(public_path($directory.$fileName));

            //Image::make($thumbnail)->resize($size_height,$size_width)->save(public_path($directory.$fileName));
            $filePath = $directory.$fileName;
            return $filePath;
        }
        return null;
    }
}
