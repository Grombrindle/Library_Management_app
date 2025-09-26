<?php
namespace App\Actions\Admin;

use App\Models\Admin;

class AdminCreateAction
{
    public function execute(array $data)
    {
        // Handle image upload
        $imagePath = "Images/Admins/adminDefault.png";
        if (!empty($data['object_image'])) {
            $file = $data['object_image'];
            $directory = 'Images/Admins';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            $file->move(public_path($directory), $filename);
            $imagePath = $directory . '/' . $filename;
        }

        // Create the admin
        $admin = Admin::create([
            'name' => $data['admin_name'],
            'userName' => $data['admin_user_name'],
            'number' => $data['admin_number'],
            'password' => bcrypt($data['admin_password']),
            'privileges' => $data['admin_privileges'] === 'Admin' ? 2 : 1,
            'countryCode' => "+963",
            'image' => $imagePath,
        ]);

        return [
            'sessionData' => ['element' => 'admin', 'id' => $admin->id, 'name' => $admin->name],
            'redirectLink' => '/admins',
        ];
    }
}