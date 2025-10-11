<?php
namespace App\Actions\Admin;

use App\Models\Admin;

class AdminUpdateAction
{
    public function execute(Admin $admin, array $data)
    {
        // Handle image upload
        if (!empty($data['object_image'])) {
            $file = $data['object_image'];
            $directory = 'Images/Admins';
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            $file->move(public_path($directory), $filename);

            // Delete the old image if it's not the default
            if ($admin->image !== "Images/Admins/adminDefault.png" && file_exists(public_path($admin->image))) {
                unlink(public_path($admin->image));
            }

            $data['image'] = $directory . '/' . $filename;
        }

        if ($data['admin_privileges'] == "Semi-Administrator")
            $admin->privileges = 1;
        if ($data['admin_privileges'] == "Administrator")
            $admin->privileges = 2;

        $admin->name = $data['admin_name'];
        $admin->userName = $data['admin_user_name'];
        $admin->number = $data['admin_number'];
        $admin->image = $data['image'] ?? $admin->image;

        $admin->save();

        return $admin;
    }
}