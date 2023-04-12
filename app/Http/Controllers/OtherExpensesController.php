<?php

namespace App\Http\Controllers;

use App\Models\OtherExpenses;
use App\Models\OtherExpenseSub;
use App\Models\User;
use App\Models\Token;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Str;


class OtherExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $other_expense = DB::table('other_expenses as oe')
        ->join('users as u','u.id','oe.executive_id')
        ->join('other_expense_subs as oes','oes.mainid','oe.id')
        ->select(
                'oe.id as oeid',
                'oe.entry_date','oe.expense_no',
                'u.id as uid','u.name',
                'oes.amount',
        )
        ->get();
            if($other_expense){
                return response()->json([
                    'status' => 200,
                    'otherexpense' => $other_expense
                    ]);
            } 
            else {
                return response()->json([
                'status' => 404,
                'message' => 'The provided credentials are incorrect.'
            ]);
            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //return "OTHER EXPENSE";

                // $get_id = OtherExpenses::orderBy('id', 'desc')->first('id');
                
                // $otherExp = OtherExpenses::find($get_id->id);
                // $otherExp->description = $request->description;
                // $otherExp->save();
            ///////////////////////////////    
    //     $randno = Str::upper(Str::random(16));
    //     $user = Token::where('tokenid', $request->tokenid)->first();   
    //     $userid = $user['userid'];
        

    //     if($userid){
    //         $otherExp = new OtherExpenses;
    //         $otherExp->randno = $randno;
    //         $otherExp->expense_no = $request->expense_no;
    //         $otherExp->entry_date = $request->entry_date;
    //         $otherExp->executive_id = $request->executive_id;
    //         $otherExp->description = $request->description;
    //         $otherExp->created_by = $user['userid'];
    //         $otherExp->save();
    //    }

    //    if ($otherExp) {
    //        return response()->json([
    //            'status' => 200,
    //            'message' => 'Other Expense created Succssfully!',
    //            'id' => $otherExp['id'],
    //        ]);
    //    }else{
    //        return response()->json([
    //            'status' => 400,
    //            'message' => 'Provided Credentials are Incorrect!'
    //        ]);
    //    }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OtherExpenses  $otherExpenses
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OtherExpenses  $otherExpenses
     * @return \Illuminate\Http\Response
     */
    public function edit(OtherExpenses $otherExpenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OtherExpenses  $otherExpenses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OtherExpenses  $otherExpenses
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $other_exp_del = OtherExpenses::destroy($id);
        if ($other_exp_del)
            return response()->json([
                'status' => 200,
                'message' => "Deleted Successfully!"
            ]);

        else {
            return response()->json([
                'status' => 400,
                'message' => 'The Provided Credentials are Incorrect.'
            ]);
        }
    }


  



}
