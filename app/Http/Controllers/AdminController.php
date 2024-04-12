<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function registerStaff() {
        $data = Transaction::all();

        return view('register', ['data' => $data]);
    }

    public function transactionTable() {
        $data = DB::table('transaction')
        ->select('transaction.*', 'queue.*')
        ->join('queue', 'queue.transaction_id', '=', 'transaction.transaction_id')
        ->get();

        return view('transaction', ['data' => $data]);
    }

    public function windowTable() {
        $data = DB::select('SELECT * FROM window w INNER JOIN transaction t ON t.transaction_id = w.transaction_id');

        $data1 = Transaction::all();

        return view('window', ['data' => $data, 'transaction' => $data1]);
    }

    public function addWindow(Request $request) {
        $incomingFields = $request->validate([
            'wname' => 'required',
            'transaction_id' => 'required'
        ]);

        DB::select('CALL InsertIntoWindow(?,?)', [$incomingFields['wname'], $incomingFields['transaction_id']]);
        $message = 'Transaction Successfully Added';

        return view('admin', ['prompt' => $message]);
    } 

    public function register(Request $request) {

        $incomingFields = $request->validate([
            'name' => ['required', Rule::unique('users','name')],
            'email' => ['required', Rule::unique('users', 'email')],
            'password' => 'required',
            'transaction_id' => 'required'
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/');
    }

    public function staffTable() {
        $data = Transaction::all();

        $transaction = DB::select('SELECT * FROM users u INNER JOIN transaction t ON u.transaction_id = t.transaction_id');

        return view('staff', ['data' => $transaction, 'transaction'=>$data]);
    }

    public function historyTable() {
        $data = DB::select('SELECT * FROM manange_queue mq INNER JOIN transaction t ON mq.transaction_id = t.transaction_id');

        return view('history', ['data' => $data]);
    }


    public function editData(Request $request) {
        $incomingFields = $request->validate([
            'sname' => 'required',
            'email' => 'required',
            'tname' => 'required'
        ]);
        
        $id = $request->filled('id') ? $request->input('id') : null;
        $sname = $incomingFields['sname']; 
        $email = $incomingFields['email'];
        $tname = $incomingFields['tname'];

        DB::select('CALL UpdateUser(?,?,?,?)', [$id, $sname, $email, $tname]);
        return redirect('/admin');

    }


    public function addTransaction(Request $request) {
        $incomingFields = $request->validate([
            'tname' => ['required', Rule::unique('transaction','transaction_name')],
            'tsrange' => ['required', Rule::unique('transaction','queue_srange')],
            'tlrange' => ['required', Rule::unique('transaction','queue_lrange')]
        ]);
        $tranName = $incomingFields['tname'];
        $tsrange = $incomingFields['tsrange'];
        $tlrange = $incomingFields['tlrange'];
        DB::select('CALL InsertTransaction(?, ?, ?)', [$tranName, $tsrange, $tlrange]);

        return redirect('/admin');
    }

    public function editTransaction(Request $request) {
        $incomingFields = $request->validate([
            'id' => 'required',
            'tname' => 'required',
            'tsrange' => 'required',
            'tlrange' => 'required'
        ]);

        DB::select('CALL UpdateTransaction(?, ?, ?, ?)',[$incomingFields['id'], $incomingFields['tname'], $incomingFields['tsrange'], $incomingFields['tlrange']]);

        return redirect('/admin');
    }

    public function editWindow(Request $request) {
        $incomingFields = $request->validate([
            'id' => 'required',
            'wname' => 'required',
            'tname' => 'required'
        ]);

        DB::select('CALL UpdateWindow(?, ?, ?)',[$incomingFields['id'], $incomingFields['wname'], $incomingFields['tname']]);

        return redirect('/admin');
    }

    public function deleteData(Request $request) {
        $incomingFields = $request->validate([
            'id' => 'required'
        ]);

        DB::select('DELETE FROM user WHERE id = ?', [$incomingFields['id']]);

        return redirect('/admin');
    }

    public function deleteTransaction(Request $request) {
        $incomingFields = $request->validate([
            'id' => 'required'
        ]);

        DB::select('DELETE FROM transaction WHERE transaction_id = ?', [$incomingFields['id']]);

        return redirect('/admin');
    }

    public function deleteWindow(Request $request) {
        $incomingFields = $request->validate([
            'id' => 'required'
        ]);

        DB::select('DELETE FROM window WHERE window_id = ?', [$incomingFields['id']]);

        return redirect('/admin');
    }

    public function index() {
        $staffCount = DB::table('users')->count('id');
        $userCount = DB::table('users')->count('id');
        $queueCount = DB::table('manange_queue')->count();
        $windowCount = DB::table('window')->count();
        return view('admin', ['staffCount' => $staffCount, 'userCount' => $userCount, 'queueCount' => $queueCount, 'windowCount' => $windowCount]);
    }
}