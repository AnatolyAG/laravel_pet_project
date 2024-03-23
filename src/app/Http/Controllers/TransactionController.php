<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Exception;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', Transaction::class);

        if (Cache::has('transactions')) {
            return response()->json(Cache::get('transactions'));
        }

        $all_transaction = Transaction::all();

        Cache::put('transactions', $all_transaction, now()->addMinutes(10));

        return response()->json($all_transaction);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Transaction::class);
        // add validate
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'ttype' => 'required|in:0,1',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $transaction = Transaction::create($validatedData);
            if (!$transaction) {
                return response()->json(['error' => 'Transaction not created'], 422);
            }
            Cache::forget('transactions');  // clear cache
            return response()->json($transaction, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Transaction::class);

        if (!is_numeric($id) || $id <= 0 || floor($id) != $id) {
            return response()->json(['error' => 'Invalid transaction ID'], 422);
        }

        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', Transaction::class);

        if (!is_numeric($id) || $id <= 0 || floor($id) != $id) {
            return response()->json(['error' => 'Invalid transaction ID'], 422);
        }

        try {
            $transaction = Transaction::find($id);

            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }
            // add validate
            $validatedData = $request->validate([
                'user_id' => 'sometimes|exists:users,id',
                'amount' => 'sometimes|numeric|min:0',
                'ttype' => 'sometimes|in:0,1',
                'description' => 'sometimes|nullable|string|max:255',
            ]);

            $transaction->update($validatedData);

            Cache::forget('transactions');

            return response()->json($transaction);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Transaction::class);

        if (!is_numeric($id) || $id <= 0 || floor($id) != $id) {
            return response()->json(['error' => 'Invalid transaction ID'], 422);
        }

        try {
            $transaction = Transaction::find($id);

            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            $transaction->delete();

            Cache::forget('transactions');

            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
