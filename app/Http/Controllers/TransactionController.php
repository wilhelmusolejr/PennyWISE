<?php

namespace App\Http\Controllers;

use App\Models\Breakdown;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $userId = Auth::id();

        $totals = Transaction::selectRaw('type, SUM(amount) as total')
            ->where('user_id', $userId)
            ->groupBy('type')
            ->pluck('total', 'type');

        $data = Transaction::with('breakdowns')
            // Filter transactions by user ID
            ->where('user_id', $userId)
            ->latest()
            ->paginate(5);

        return view('dashboard', compact('data', 'totals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'transaction_type' => ['required'],
            'transaction_typetype' => ['required'],
            'transaction_name' => ['required', 'min:3'],
            'transaction_date' => ['required'],
            'transaction_desc' => ['required', 'min:3'],
            'transaction_amount' => ['required', 'numeric'],
            'bd_name.*'  => ['required'],
            'bd_amount.*'  => ['required', 'numeric']
        ]);

        if ($request->input('transaction_typetype') === 'set') {
            $transactionAmount = array_sum($request->input('bd_amount'));
        } else {
            $transactionAmount = $request->input('transaction_amount');
        }

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'type' => $request->input('transaction_type'),
            'typetype' => $request->input('transaction_typetype'),
            'name' =>  $request->input('transaction_name'),
            'date' => $request->input('transaction_date'),
            'description' =>  $request->input('transaction_desc'),
            'amount' => $transactionAmount
        ]);

        if ($request->input('transaction_typetype') === 'set') {
            foreach ($request->input('bd_name') as $index => $name) {
                Breakdown::create([
                    'transaction_id' => $transaction->id,
                    'name' => $name,
                    'amount' => $request->input('bd_amount')[$index]
                ]);
            }
        }

        return redirect('/dashboard')->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        Transaction::findOrFail($id)->delete();

        return redirect('/dashboard')->with('success', 'Transaction deleted successfully.');
    }

    public function destroyMultiple(Request $request) {
        $ids = $request->input('ids'); // Get the array of IDs from the request

        if (is_array($ids) && !empty($ids)) {
            Transaction::whereIn('id', $ids)->delete();
            return redirect('/dashboard')->with('success', 'Transactions deleted successfully.');
        }

        return redirect('/dashboard')->with('error', 'No transactions selected for deletion.');
    }
}