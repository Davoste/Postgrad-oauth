<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index()
    {
        return view('announcements.index');
    }

    /**
     * Display a specific announcement (optional - for future use)
     */
    public function show($id)
    {
        // You can expand this later
        return view('announcements.show', compact('id'));
    }
}