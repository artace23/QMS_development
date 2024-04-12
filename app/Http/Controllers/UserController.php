<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Queueing;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
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

    public function next(Request $request) {
        $incomingFields = $request->validate([
            'ongoing_no' => 'required'
        ]);

        
        $loggedInUserId = auth()->id();
    
        $ongoing_no = $incomingFields['ongoing_no'];
        
        $pending_no = $request->filled('pending_no') ? $request->input('pending_no') : null;
    
        // Call the stored procedure to update the queue status
        DB::select('CALL UpdateQueueStatus(?, ?)', [$pending_no, $loggedInUserId]);

        // Call the stored procedure to update the queue status
        DB::select('CALL UpdateQueueStatusDone(?)', [$ongoing_no]);

        // return view('home');
        return redirect('/');
    }

    public function call(Request $request) {
        $incomingFields = $request->validate([
            'pending_no' => 'required'
        ]);
        
        $loggedInUserId = auth()->id();
        
        DB::select('CALL UpdateQueueStatus(?, ?)', [$incomingFields['pending_no'], $loggedInUserId]);

        return redirect('/');
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
                return redirect('/admin');
            }

            else {
                $queuelogs = DB::select('CALL GetQueueLogs(?)', [$userData]);
                $firstQueue = null;
                $firstQueueLog = null;
                
                // Check if $queuelogs is not empty and is an array
                if (!empty($queuelogs) && is_array($queuelogs)) {
                    // Get the first item from the array
                    $firstQueue = reset($queuelogs);

                } 

                $pendinglogs = DB::select('CALL GetPendingLogs(?, ?)', [$userData, $loggedInUserId]);

                if (!empty($pendinglogs) && is_array($pendinglogs)) {
                    // Get the first item from the array
                    $firstQueueLog = reset($pendinglogs);
                } 
                // Pass the data to the view
                return view('home', ['userId' => $loggedInUserId,'userData' => $user, 'queue' => $queuelogs, 'pending_first' => $firstQueue , 'first' => $firstQueueLog]);
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

        $timestamp = Carbon::now('Asia/Manila')->format('YmdHis');
        $fileName = 'queue_numbers_' . $timestamp . '.txt';

        // Save the file to the storage directory
        $filePath = storage_path('app/' . $fileName);
        file_put_contents($filePath, $latestQueueNo);

        // Provide download link to the user
        return Response::download($filePath, $fileName);
    }

    public function user_transact() {
        $data = Transaction::all();

        return view('user', ['data' => $data]);
    }

    public function display_index() {
        $dataQueue = DB::select('CALL DisplayQueueLogs()');
        $dataPending = DB::select('CALL DisplayPendingLogs()');

        return view('display', ['Ongoing' => $dataQueue, 'Pending' => $dataPending]);
    }

    public function currentServing() {
        $dataPending = DB::select('SELECT * FROM manange_queue mq INNER JOIN users u ON u.id = mq.user_id INNER JOIN window w ON u.window_id = w.window_id WHERE status = 2 AND DATE(timestamp) = DATE(NOW())');
        return response()->json($dataPending);
    }

    public function fetchOngoingQueues() {
        $dataQueue = DB::select('CALL DisplayQueueLogs()');
        return response()->json($dataQueue);
    }

    public function fetchFirstQueue() {
        $loggedInUserId = auth()->id();

        $userData = User::where('id', $loggedInUserId)->value('transaction_id');
        $queuelogs = DB::select('CALL GetQueueLogs(?)', [$userData]);
            $firstQueue = null;
            
            // Check if $queuelogs is not empty and is an array
            if (!empty($queuelogs) && is_array($queuelogs)) {
                // Get the first item from the array
                $firstQueue = reset($queuelogs);

            } 

            return response()->json($firstQueue);
    }


    public function fetchStaffPendingQueue() {
        $loggedInUserId = auth()->id();

        $userData = User::where('id', $loggedInUserId)->value('transaction_id');
        $queuelogs = DB::select('CALL GetQueueLogs(?)', [$userData]);
            
        return response()->json($queuelogs);
    }
}