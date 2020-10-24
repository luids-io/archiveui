<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Event;
use Carbon\Carbon;


class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of last event entries
     *
     * @return \Illuminate\Http\Response
     */
    public function last()
    {
        $events = Event::orderBy('created', 'desc')->paginate(20);
        return view('event.last', compact('events'));
    }

    /**
     * Display an event entry
     *
     * @param \Illuminate\Http\Response $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        $id = $request->get('id');
        $event = Event::find($id);
        return view('event.show', compact('event'));
    }
    
    /**
     * Search 
     *
     * @param \Illuminate\Http\Response $request
     * @param \Illuminate\Support\MessageBag $errors_bag
     * @return \Illuminate\Http\Response
     */    
    public function search(Request $request, MessageBag $errors_bag) {    
        $isSearch = count($request->input()) > 0;
        if (!$isSearch) {
            return view('event.search');
        }

        // parse input
        $sStart = false;
        $sEnd = false;
        $sTimeRange = trim($request->get('sTimeRange'));
        if ($sTimeRange != "") {
            $isSearch = true;
            $dates = explode(" - ", $sTimeRange);
            if (count($dates) != 2) {
                $errors_bag->add('sTimeRange', "Invalid range");
            } else {
                try {
                    $sStart = Carbon::createFromFormat("Y-m-d H:i:s", $dates[0], "UTC");
                    $sEnd = Carbon::createFromFormat("Y-m-d H:i:s", $dates[1], "UTC");                
                } catch (\Exception $ex) {
                    $errors_bag->add('sTimeRange', "Invalid date format");
                }                
            }
        }
        $sReverseOrder = trim($request->get('sReverseOrder'));
        $sID = trim($request->get('sID'));
        $iCode = 0;
        $sCode = trim($request->get('sCode'));
        if ($sCode != "") {
            if (!is_numeric($sCode)) {
                $errors_bag->add('sCode', "Invalid code");
            }
        }
        $sCodename = trim($request->get('sCodename'));
        $iLevel = 0;
        $sLevel= trim($request->get('sLevel'));
        if ($sLevel != "") {
            if (!is_numeric($sLevel)) {
                $errors_bag->add('sLevel', "Invalid level");
            } else {
                $iLevel = (int)$sLevel;
                if ($iLevel <0 || $iLevel >4) {
                    $errors_bag->add('sLevel', "Invalid level");
                }
            }
        }
        $sLevelGE = trim($request->get('sLevelGE'));
        $sSourceHostname = trim($request->get('sSourceHostname'));
        $sSourceProgram = trim($request->get('sSourceProgram'));
        $sDescription = trim($request->get('sDescription'));
        $sUnmatchDescription = trim($request->get('sUnmatchDescription'));        
        $sTag = trim($request->get('sTag'));
        $sDataField = trim($request->get('sDataField'));
        $sDataValue = trim($request->get('sDataValue'));
        if ($sDataField != "" || $sDataValue != "") {
            if ($sDataField == "" || $sDataValue == "") {
                $errors_bag->add('sDataField', 'Required field and value');
            }
        }
        
        // if has errors
        if ($errors_bag->count() > 0) {
            return view('event.search')->withErrors($errors_bag);
        }

        // do search query
        $query = Event::query();
        if ($sStart && $sEnd) {
            $query = $query->whereBetween("created", [$sStart, $sEnd]);
        }
        if ($sID != "") {
            $query = $query->where("_id", "=", $sID);
        }
        if ($iCode > 0) {
            $query = $query->where("code", "=", $iCode);
        }
        if ($sCodename != "") {
            $query = $query->where("codename", "like", $sCodename);
        }
        if ($sLevelGE == "") {
            $query = $query->where("level", "=", $iLevel);
        } else {
            $query = $query->where("level", ">=", $iLevel);
        }
        if ($sSourceHostname != "") {
            $query = $query->where("source.hostname", "=", $sSourceHostname);
        }
        if ($sSourceProgram != "") {
            $query = $query->where("source.program", "=", $sSourceProgram);
        }
        if ($sDescription != "") {
            if ($sUnmatchDescription != "") {
                $query = $query->where("description", "not like", '%'.$sDescription.'%');
            } else {
                $query = $query->where("description", "like", '%'.$sDescription.'%');
            }
        }
        if ($sTag != "") {
            $query = $query->where("tags", "=", $sTag);
        }
        if ($sDataField != "") {
            $query = $query->where("data.$sDataField", "=", $sDataValue);
        }
        if ($sReverseOrder != "") {
            $query = $query->orderBy('created', 'desc');
        }
        $events = $query->paginate(20);
        
        return view('event.results', compact('events'));
    }    
}
