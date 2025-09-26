<?php
namespace App\Actions\Admin;

use App\Models\Admin;

class AdminDeleteAction
{
    public function execute(Admin $admin)
    {
        // Delete the admin's image if it's not the default
        if ($admin->image !== "Images/Admins/adminDefault.png" && file_exists(public_path($admin->image))) {
            unlink(public_path($admin->image));
        }

        // Delete the admin
        $admin->delete();
    }
}