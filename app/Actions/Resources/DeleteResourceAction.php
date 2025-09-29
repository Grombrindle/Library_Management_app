<?php

namespace App\Actions\Resources;

use App\Models\Resource;

class DeleteResourceAction
{
    public function execute(int $id): array
    {
        $resource = Resource::findOrFail($id);
        $name = $resource->name;

        // Delete old image if it's not the default
        if ($resource->image != "Images/Resources/default.png" && file_exists(public_path($resource->image))) {
            unlink(public_path($resource->image));
        }

        // Delete all PDF files according to the pdf_files attribute
        if ($resource->pdf_files) {
            $pdfFiles = json_decode(json_encode($resource->pdf_files), true);
            if (is_array($pdfFiles)) {
                foreach ($pdfFiles as $lang => $filePath) {
                    if ($filePath && $filePath !== 'Files/Resources/default.pdf' && file_exists(public_path($filePath))) {
                        unlink(public_path($filePath));
                    }
                }
            }
        }

        // Delete audio file if it's not the default
        if (file_exists(public_path($resource->audio_file)) && $resource->audio_file != null && $resource->audio_file != 'Files/Resources/Audio/default.mp3') {
            unlink(public_path($resource->audio_file));
        }

        $resource->delete();

        $data = ['element' => 'resource', 'name' => $name];
        session(['delete_info' => $data]);
        session(['link' => '/resources']);
        return redirect()->route('delete.confirmation');
    }
}