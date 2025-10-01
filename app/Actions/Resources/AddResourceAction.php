<?php

namespace App\Actions\Resources;

use App\Models\Resource;

class AddResourceAction
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

    public function execute($request)
    {
        $this->ensureDirectoriesExist();

        // Handle image
        $imagePath = 'Images/Resources/default.png';
        if ($request->hasFile('object_image')) {
            $file = $request->file('object_image');
            $imagePath = 'Images/Resources/' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('Images/Resources'), basename($imagePath));
        }

        // Handle PDFs
        $pdfDir = public_path('Files/Resources');
        $pdfFiles = [];
        foreach (['ar', 'en', 'es', 'de', 'fr'] as $lang) {
            $input = 'pdf_' . $lang;
            if ($request->hasFile($input)) {
                $pdf = $request->file($input);
                $pdfName = time() . '_' . $lang . '_' . $pdf->getClientOriginalName();
                $pdf->move($pdfDir, $pdfName);
                $pdfFiles[$lang] = 'Files/Resources/' . $pdfName;
            }
        }

        // Handle audio
        $audioPath = null;
        if ($request->hasFile('resource_audio_file')) {
            $audio = $request->file('resource_audio_file');
            $audioName = time() . '_' . $audio->getClientOriginalName();
            $audio->move(public_path('Files/Resources/Audio'), $audioName);
            $audioPath = 'Files/Resources/Audio/' . $audioName;
        }

        $resource = new Resource();
        $resource->name = $request->input('resource_name');
        $resource->description = $request->input('resource_description');
        $resource->subject_id = $request->input('resource_subject_id');
        $resource->{'publish date'} = $request->input('resource_publish_date');
        $resource->author = $request->input('resource_author');
        $resource->pdf_files = json_encode($pdfFiles);
        $resource->audio_file = $audioPath;
        $resource->image = $imagePath;
        $resource->literaryOrScientific = $resource->subject->literaryOrScientific;
        $resource->save();


        $data = ['element' => 'resource', 'name' => $resource->name];
        session(['add_info' => $data]);
        session(['link' => '/resources']);
        return redirect()->route('add.confirmation');
    }
}