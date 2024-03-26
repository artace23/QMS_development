<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Queueing;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function logout() {
        auth()->logout();
        return redirect('/');
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

    public function login(Request $request) {
        $incomingFields = $request->validate([
            'lemail' => 'required',
            'lpassword' => 'required'
        ]);

        if(auth()->attempt(['email' => $incomingFields['lemail'], 'password' => $incomingFields['lpassword']])) {
            $request->session()->regenerate();
            $this->index();
        }

        return redirect('/');
        
    }

    public function transaction_list() {
        $data = Transaction::all();

        return view('register', ['data' => $data]);
    }

    public function staff() {
        return Staff::all();
    }


    public function index()
    {
        $loggedInUserId = auth()->id();
        if($loggedInUserId == "") {
            return view('home');
        }
        else {
            $userData = User::where('id', $loggedInUserId)->value('transaction_id');
            $user = Transaction::where('transaction_id', $userData)->pluck('transaction_name');

            if($loggedInUserId == 1) {
                return view('admin');
            }

            else {
                $queuelogs = DB::select('CALL GetQueueLogs(?)', [$userData]);
                
                // Check if $queuelogs is not empty and is an array
                if (!empty($queuelogs) && is_array($queuelogs)) {
                    // Get the first item from the array
                    $firstQueueLog = reset($queuelogs);
                } else {
                    // If $queuelogs is empty or not an array, set $firstQueueLog to null
                    $firstQueueLog = null;
                }
                // Pass the data to the view
                return view('home', ['userData' => $user, 'queue' => $queuelogs, 'first' => $firstQueueLog]);
            }
        }
        
    }

    public function print_queue(Request $request) {
        date_default_timezone_set('Asia/Manila');
        $allFields = $request->input('transaction');

        // Call the stored procedure using raw SQL query
        $result = DB::select('CALL GetLatestQueueNo(?, @latest_queue_no, @is_last_range)', [$allFields]);

        // Retrieve the output parameter value
        $latestQueueNo = DB::select('SELECT @latest_queue_no AS latest_queue_no, @is_last_range AS is_last_range')[0]->latest_queue_no;

        $queueing = new Queueing();
        $queueing->queue_no = $latestQueueNo;
        $queueing->transaction_id = $allFields; // Assuming you have retrieved transaction_id
        $queueing->timestamp = Carbon::now('Asia/Manila'); // Or any specific timestamp you want to use
        $queueing->status = 1;
        $queueing->save();

        return 'Your Number is ' . $latestQueueNo;
    }

    public function user_transact() {
        $data = Transaction::all();

        return view('user', ['data' => $data]);
    }

    public function next(Request $request) {

    }
}
