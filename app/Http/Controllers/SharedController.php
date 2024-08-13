<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Country;
use App\Models\Service;

use App\Http\Requests\EstimateRequest;

use App\Services\CalculationService;

class SharedController extends Controller
{
    /**
     * Estimate actions
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  $customerId Customer ID
     * @return JsonResponse
     */
    public function estimate(EstimateRequest $request)
    {
        return CalculationService::estimate(
            $request->validated(),
            me()->currentTeam()
        );
    }

     /**
     * Delete comment actions
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  $id Industry ID
     * @return true or false deletion
     */
    public function deleteComment(Request $request, $id)
    {
        $comment = app()->make(CommentInterface::class);

        $comment->delete($id);

        return response()->json(['status' => 'success']);
    }
}
