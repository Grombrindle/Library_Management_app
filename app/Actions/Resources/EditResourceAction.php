<?php

namespace App\Actions\Resources;

use App\Models\Resource;
use Illuminate\Http\Request;

class EditResourceAction
{
    public function ensureDirectoriesExist()
    {
        $dirs = [
            public_path('Images/Resources'),
            public_path('Files/Resources'),
            public_path('Files/Resources/Audio')
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
        }
    }

    public function execute(Request $request, int $id): array
    {
        $this->ensureDirectoriesExist();

        $resource = Resource::find($id);

        // Handle PDFs
        $pdfDir = public_path('Files/Resources');
        $pdfFiles = $resource->pdf_files ? json_decode($resource->pdf_files, true) : [];
        foreach (['ar', 'en', 'es', 'de', 'fr'] as $lang) {
            $input = 'pdf_' . $lang;
            if ($request->hasFile($input)) {
                $pdf = $request->file($input);
                $pdfName = time() . '_' . $lang . '_' . $pdf->getClientOriginalName();
                $pdf->move($pdfDir, $pdfName);
                $pdfFiles[$lang] = 'Files/Resources/' . $pdfName;
            }
        }

        // Handle image
        if ($request->hasFile('object_image')) {
            $file = $request->file('object_image');
            $imagePath = 'Images/Resources/' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('Images/Resources'), basename($imagePath));
            if ($resource->image != 'Images/Resources/default.png' && file_exists(public_path($resource->image))) {
                unlink(public_path($resource->image));
            }
            $resource->image = $imagePath;
        }

        // Handle audio
        if ($request->hasFile('resource_audio_file')) {
            $audio = $request->file('resource_audio_file');
            $audioName = time() . '_' . $audio->getClientOriginalName();
            $audio->move(public_path('Files/Resources/Audio'), $audioName);
            $resource->audio_file = 'Files/Resources/Audio/' . $audioName;
        }
        $resource->name = $request->input('resource_name');
        $resource->description = $request->input('resource_description');
        $resource->literaryOrScientific = $resource->subject->literaryOrScientific;
        $resource->{'publish date'} = $request->input('resource_publish_date');
        $resource->author = $request->input('resource_author');
        $resource->pdf_files = json_encode($pdfFiles);
        $resource->save();

        return $resource;
    }
}