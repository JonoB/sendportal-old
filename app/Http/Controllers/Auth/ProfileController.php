<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the profile edit page.
     *
     * @return View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the active user's profile.
     *
     * @param ProfileUpdateRequest $request
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(ProfileUpdateRequest $request)
    {
        user()->update($request->validated());

        return redirect()->back()->with('success', 'Your profile was updated successfully!');
    }
}
