<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show a list of all Admins.
     */
    public function list()
    {
        $data['get_record'] = User::getRecordAdmin(); // Refactor this if needed, User::getAdmin() should return only admins
        $data['header_title'] = "Admin List";
        return view('admin.userManagement.admin.list', $data);
    }

    /**
     * Show the form for creating a new Admin.
     */
    public function add()
    {
        $data['header_title'] = "Add Admin";
        return view('admin.userManagement.admin.add', $data);
    }

    /**
     * Store a new Admin.
     */
    public function postAdd(Request $request)
    {
        // Centralize validation in one step
        $this->validateRequest($request);

        // Create the new admin
        $user = new User;
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password); // Hash the password
        $user->role = 'admin';
        $user->save();

        return redirect()->route('admin.list')->with('success', 'New admin successfully created');
    }


    /**
     * Show the form for editing an existing Admin.
     */
    public function edit($id)
    {
        $data['user'] = User::findOrFail($id); // Use findOrFail to avoid manual null checking
        $data['header_title'] = "Edit Admin";
        return view('admin.userManagement.admin.edit', $data);
    }

    /**
     * Update the specified Admin in storage.
     */
    public function update($id, Request $request)
    {
        $user = User::findOrFail($id); // Find the user or throw 404

        // Validate the input with the user ID for uniqueness checks
        $this->validateRequest($request, $user->id);

        // Update the user's details
        $user->name = trim($request->name);
        $user->email = trim($request->email);

        // Update the password only if a new one is provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.list')->with('success', 'Admin details updated successfully.');
    }

    /**
     * Delete the specified Admin.
     */
    public function delete($id)
    {
        $user = User::findOrFail($id); // Use findOrFail to simplify error handling
        $user->delete(); // No need to call save() after delete()

        return redirect()->route('admin.list')->with('success', 'Admin deleted successfully.');
    }

    /**
     * Validate the incoming request for both creating and updating an admin.
     */
    private function validateRequest(Request $request, $userId = null)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $userId,  // Ignore current user ID on update
            'email' => 'required|email|max:255|unique:users,email,' . $userId, // Ignore current user ID on update
            'password' => $userId ? 'nullable|min:6' : 'required|min:6', // Password is required only on create
        ]);
    }
}
