<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Participant;

class GroupController extends Controller
{
    public function index()
    {
        return view('groups.index', [
            'groups' => Group::all()
        ]);
    }

    public function show(Group $group)
    {
        $group->load([
            'Participants' => function($query) {
                $query->orderBy('rank');
            }
        ]);

        return view('groups.show', compact('group'));
    }
}
