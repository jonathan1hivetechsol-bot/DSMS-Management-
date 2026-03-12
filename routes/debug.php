<?php

Route::get('/debug/user', function () {
    $user = auth()->user();
    
    if (!$user) {
        return 'Not logged in';
    }
    
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'can_manage_teachers' => auth()->user()->role === 'admin',
    ]);
})->name('debug.user');
