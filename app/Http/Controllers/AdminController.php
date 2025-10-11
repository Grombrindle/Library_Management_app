<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Actions\Admin\AdminCreateAction;
use App\Actions\Admin\AdminUpdateAction;
use App\Actions\Admin\AdminDeleteAction;
use App\Actions\Admin\AdminLogoutAction;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    protected $adminCreateAction;
    protected $adminUpdateAction;
    protected $adminDeleteAction;
    protected $adminLogoutAction;

    public function __construct(
        AdminCreateAction $adminCreateAction,
        AdminUpdateAction $adminUpdateAction,
        AdminDeleteAction $adminDeleteAction,
        AdminLogoutAction $adminLogoutAction
    ) {
        $this->adminCreateAction = $adminCreateAction;
        $this->adminUpdateAction = $adminUpdateAction;
        $this->adminDeleteAction = $adminDeleteAction;
        $this->adminLogoutAction = $adminLogoutAction;
    }

    public function add(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'admin_name' => 'required|string|max:255|unique:admins,name',
            'admin_user_name' => [
                Rule::unique('admins', 'userName'),
                Rule::unique('users', 'userName')
            ],
            'admin_number' => [
                Rule::unique('admins', 'number'),
                Rule::unique('users', 'number')
            ],
            'admin_privileges' => 'required',
            'object_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'admin_name' => __('messages.name_taken'),
            'admin_user_name' => __('messages.username_taken'),
            'admin_number' => __('messages.number_taken'),
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        // Call the AdminCreateAction to handle the logic
        $result = $this->adminCreateAction->execute($request->all());

        // Redirect or respond based on the result
        session(['add_info' => $result['sessionData']]);
        session(['link' => $result['redirectLink']]);
        return redirect()->route('add.confirmation');
    }

    public function edit(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'admin_name' => 'required|string|max:255|unique:admins,name,' . $id,
            'admin_user_name' => [
                Rule::unique('admins', 'userName')->ignore($id),
                Rule::unique('users', 'userName')
            ],
            'admin_number' => [
                Rule::unique('admins', 'number')->ignore($id),
                Rule::unique('users', 'number')
            ],
            'admin_privileges' => 'required',
            'object_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'admin_name' => __('messages.name_taken'),
            'admin_user_name' => __('messages.username_taken'),
            'admin_number' => __('messages.number_taken'),
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }


        $updatedAdmin = $this->adminUpdateAction->execute($admin, $request->all());

        session(['update_info' => ['element' => 'admin', 'id' => $updatedAdmin->id, 'name' => $updatedAdmin->name]]);
        session(['link' => '/admins']);
        return redirect()->route('update.confirmation');
    }

    public function delete($id)
    {
        $admin = Admin::findOrFail($id);

        $this->adminDeleteAction->execute($admin);

        session(['delete_info' => ['element' => 'admin', 'name' => $admin->name]]);
        session(['link' => '/admins']);
        return redirect()->route('delete.confirmation');
    }

    public function logout(Request $request)
    {
        $this->adminLogoutAction->execute();

        return redirect('/');
    }
}
