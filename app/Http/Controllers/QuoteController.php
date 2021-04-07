<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Quote;
use App\Models\QuotePerson;
use App\Models\AgeLoad;

use Validator;

class QuoteController extends Controller
{
    /**
     * Create a new QuoteController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Submit a Quote
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitQuote(Request $request){
        $message_created = "";
    	$validator = Validator::make($request->all(), [
            'age' => 'required|string',
            'currency_id' => ['required', Rule::in(['EUR', 'GBP', 'USD'])],
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $quote = Quote::create([
          'email_address'  => $request->email_address,
          'currency_id' => $request->currency_id,
          'total' => 0,
          'start_date' => $request->start_date,
          'end_date' => $request->end_date
        ]);

        //Get Number of Days
        $days = date_diff(date_create($request->end_date),date_create($request->start_date));

        //Get AgeLoad Table
        $ageLoads = AgeLoad::all();

        //Set initial Total Value
        $total_value = 0;

        $ages = explode(',', $request->age);
        foreach ($ages as $current_age) {
            if($current_age > 17 && $current_age < 71) {
                $quote_age = QuotePerson::create([
                    'quote_id' => $quote->id,
                    'age' => $current_age
                ]);

                $total_value += ($days->days * 3 * $this->getLoad($ageLoads, $current_age));
            } else {
                $message_created .= (strlen($message_created) > 0 ? ', ' : '') . $current_age . " is not a valid age.";
            }
        }

        $quote->total = $total_value;
        $quote->save();

        $quotePerson = QuotePerson::where('quote_id', $quote->id)->get();
        return response()->json([
            'message' => 'Quote Created',
            'quote' => $quote,
            'ages' => $quotePerson,
            'total' => $total_value,
        ], 201);
    }

     /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function quoteRequest(Request $request, $id) {
        //Get Quote
        $quote = Quote::find($id);
        $message = 'Quote Found';
        $quotePerson = QuotePerson::where('quote_id', $id)->get();

        return response()->json([
            'message' => 'Quote',
            'quote' => $quote,
            'ages' => $quotePerson
        ], 200);
    }

    private function getLoad($age_load_table, $value) {
        foreach ($age_load_table as $alt) {
            if($value >= $alt->start_age && $value <= $alt->end_age) {
                return $alt->load;
            }
        }
        //Return 0 for no value found.
        return 0;
    }
}
