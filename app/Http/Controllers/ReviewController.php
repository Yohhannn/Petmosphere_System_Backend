<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReviewController extends Controller
{
    public function getAllReview(){
        $reviews = Review::with('user')->get();
        return response()->json(["message" => "Sucessfully get data of Reviews","data" => $reviews],200);
    }
    public function getReviewById($req_id){
        $reviews = Review::with('user')->find($req_id);
        if(!$reviews){
            return response()->json(["message" => "Review not found"],404);
        }
        return response()->json(["message" => "Sucessfully get data of Reviews","data" => $reviews],200);
    }
    public function createReview(Request $request){
        $validated = $request->validate([
            'rev_rating' => 'required|integer|between:1,5',
            'rev_description' => 'nullable|string|max:255',
            'rev_rated_by' => 'required|string|max:50',
            'user_id' => 'required|integer|exists:user,user_id',
        ]);
        $review = Review::create($validated);
        return response()->json(["message" => "Sucessfully created reviews","data" => $review],201);
    }
    public function updateReview(Request $request,$id){
        $review = Review::find($id);
        if(!$review){
            return response()->json(["message" => "Review not found"],404);
        }
        $validated = $request->validate([
            'rev_date' => 'required|date',
            'rev_rating' => 'required|integer|between:1,5',
            'rev_description' => 'nullable|string|max:255',
            'rev_rated_by' => 'required|string|max:50',
            'user_id' => 'required|integer|exists:user,user_id',
        ]);
        $review->update($validated);
        return response()->json(["message" => "Sucessfully updated reviews","data" => $review],201);
    }
    public function deleteReview($req_id){
        $review = Review::find($req_id);
        if(!$review){
            return response()->json(["message" => "Review not found"],404);
        }
        $review->delete();
        return response()->json(["message" => "Sucessfully deleted reviews","data" => $review],201);
    }
}
