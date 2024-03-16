<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\AdminDataTable;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    private $permission = 'admins.admin';
    /**
     * Display a listing of the resource.
     */
    public function index(AdminDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.administrators.index', [
            'title' => 'Administrators'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->confirmAuthorization('create');
        return view('pages.admin.administrators.create', [
            'title' => 'Create Administrator',
            'roles' => Role::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->confirmAuthorization('store');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|exists:roles,name'
        ]);

        DB::beginTransaction();
        try {
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            $admin->assignRole($request->role);
            DB::commit();
            return redirect()->route('admin.settings.administrators.index')->with('success', 'Administrator created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred. Please try again. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $administrator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $administrator)
    {
        $this->confirmAuthorization('edit');
        return view('pages.admin.administrators.edit', [
            'title' => 'Edit Administrator',
            'admin' => $administrator,
            'roles' => Role::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $administrator)
    {
        $this->confirmAuthorization('update');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $administrator->id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|exists:roles,name'
        ]);

        DB::beginTransaction();
        try {
            $administrator->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? bcrypt($request->password) : $administrator->password
            ]);
            $administrator->syncRoles([$request->role]);
            DB::commit();
            return redirect()->route('admin.settings.administrators.index')->with('success', 'Administrator updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred. Please try again. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $administrator)
    {
        $this->confirmAuthorization('destroy');
        if ($administrator->id === Auth::guard('admin')->id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself');
        }
        $administrator->delete();
        return redirect()->route('admin.settings.administrators.index')->with('success', 'Administrator deleted successfully');
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::guard('admin')->user()->can($this->permission . '.' . $operation)) {
            throw new AuthorizationException("You don't have permission to perform this action");
        }
    }
}
