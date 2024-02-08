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
        if (Cache::has('transactions')) {
            // Если данные найдены в кеше, возвращаем их
            return response()->json(Cache::get('transactions'));
        }
        $this->authorize('view', Transaction::class);
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
        try {
            $transaction = Transaction::create($request->all());
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
        try {
            $transaction = Transaction::find($id);
            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }
            $transaction->update($request->all());
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
