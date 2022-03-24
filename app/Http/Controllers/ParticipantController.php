<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateParticipantRankingRequest;
use App\Models\Participant;

class ParticipantController extends Controller
{
    public function updateRanking(UpdateParticipantRankingRequest $request)
    {
        # This code block can be moved to a service
        collect($request->ranking)->each(function($participant){
            Participant::whereId($participant['participantId'])
                ->update(['rank' => $participant['updatedRank']]);
        });

        return response()->json([
            'status' => true,
            'message' => 'Ranking updated!'
        ]);
    }
}
