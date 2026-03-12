<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the user profile dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $profile = null;
        $profileType = null;

        if ($user->role === 'student') {
            $profile = $user->student;
            $profileType = 'student';
        } elseif ($user->role === 'teacher') {
            $profile = $user->teacher;
            $profileType = 'teacher';
        }

        return view('profile.dashboard', compact('user', 'profile', 'profileType'));
    }

    /**
     * Show the edit profile form for current user
     */
    public function editProfile()
    {
        $user = Auth::user();
        $profile = null;
        $profileType = null;

        if ($user->role === 'student') {
            $profile = $user->student;
            $profileType = 'student';
        } elseif ($user->role === 'teacher') {
            $profile = $user->teacher;
            $profileType = 'teacher';
        }

        return view('profile.edit', compact('user', 'profile', 'profileType'));
    }

    /**
     * Update the user's personal profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'cnic' => 'nullable|string|unique:users,cnic,' . $user->id,
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'full_address' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $validated['profile_completed_at'] = now();
        $user->update($validated);

        return redirect()->route('profile.dashboard')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show student's full profile
     */
    public function showStudent($studentId)
    {
        $student = Student::findOrFail($studentId);
        $this->authorize('view', $student);

        return view('profile.student-show', compact('student'));
    }

    /**
     * Edit student profile details
     */
    public function editStudent($studentId)
    {
        $student = Student::findOrFail($studentId);
        $this->authorize('update', $student);

        return view('profile.student-edit', compact('student'));
    }

    /**
     * Update student profile
     */
    public function updateStudent(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);
        $this->authorize('update', $student);

        $validated = $request->validate([
            'registration_number' => 'nullable|string|unique:students,registration_number,' . $student->id,
            'roll_number' => 'nullable|string',
            'blood_group' => 'nullable|in:O+,O-,A+,A-,B+,B-,AB+,AB-',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'guardian_cnic' => 'nullable|string|unique:students,guardian_cnic,' . $student->id,
            'guardian_occupation' => 'nullable|string|max:100',
            'guardian_phone_alt' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string|max:1000',
            'previous_school' => 'nullable|string|max:255',
            'admission_status' => 'in:active,inactive,graduated,transferred',
            'admission_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $student->update($validated);

        // Update user profile info
        $student->user->update([
            'phone' => $request->phone,
            'full_address' => $request->address,
        ]);

        return redirect()->route('students.show', $student)->with('success', 'Student profile updated successfully!');
    }

    /**
     * Show teacher's full profile
     */
    public function showTeacher($teacherId)
    {
        $teacher = Teacher::findOrFail($teacherId);
        $this->authorize('view', $teacher);

        return view('profile.teacher-show', compact('teacher'));
    }

    /**
     * Edit teacher profile
     */
    public function editTeacher($teacherId)
    {
        $teacher = Teacher::findOrFail($teacherId);
        $this->authorize('update', $teacher);

        return view('profile.teacher-edit', compact('teacher'));
    }

    /**
     * Update teacher profile
     */
    public function updateTeacher(Request $request, $teacherId)
    {
        $teacher = Teacher::findOrFail($teacherId);
        $this->authorize('update', $teacher);

        $validated = $request->validate([
            'cnic' => 'nullable|string|unique:teachers,cnic,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'qualification' => 'required|string|max:255',
            'qualifications' => 'nullable|string|max:1000',
            'specialization' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer|min:0',
            'previous_schools' => 'nullable|string|max:1000',
            'subject' => 'required|string|max:255',
            'employment_status' => 'in:permanent,contract,temporary',
            'salary_review_date' => 'nullable|date',
            'teaching_approach' => 'nullable|string|max:1000',
        ]);

        $teacher->update($validated);

        // Update user profile info
        $teacher->user->update([
            'phone' => $request->phone,
        ]);

        return redirect()->route('teachers.show', $teacher)->with('success', 'Teacher profile updated successfully!');
    }

    /**
     * Upload profile avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return response()->json(['success' => true, 'message' => 'Avatar uploaded successfully!', 'path' => asset('storage/' . $path)]);
    }

    /**
     * Delete profile avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return redirect()->route('profile.edit')->with('success', 'Avatar removed!');
    }
}
