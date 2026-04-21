<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Enums\RoleEnum;

class ExploreController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $users = User::with(['skills.category', 'needs.category'])
            ->role(RoleEnum::STUDENT->value)
            ->where('id', '!=', auth()->id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', '%'.$search.'%')
                        ->orWhere('last_name', 'like', '%'.$search.'%')
                        ->orWhere('city', 'like', '%'.$search.'%')
                        ->orWhereHas('skills', function ($query) use ($search) {
                            $query->where('title', 'like', '%'.$search.'%');
                        })
                        ->orWhereHas('needs', function ($query) use ($search) {
                            $query->where('title', 'like', '%'.$search.'%');
                        });
                });
            })
            ->latest()
            ->get();

        return view('explore.index', compact('users', 'search'));
    }
}
