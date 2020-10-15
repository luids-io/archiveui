<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Event;
use Carbon\Carbon;


class EventController extends Controller
{
    /**
     * Display a listing of last dns resolv entries
     *
     * @return \Illuminate\Http\Response
     */
    public function last()
    {
        $events = Event::orderBy('created', 'desc')->paginate(20);
        return view('event.last', compact('events'));
    }

    /**
     * Display a dns resolv entry
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
//    public function search(Request $request, MessageBag $errors_bag) {
//        $isSearch = false;
//        $withErrs = false;
//
//        // parse input
//        $sTimeRange = trim($request->get('sTimeRange'));
//        if ($sTimeRange != "") {
//            $isSearch = true;
//            $dates = explode(" - ", $sTimeRange);
//            if (count($dates) != 2) {
//                $errors_bag->add('sTimeRange', "Invalid range");
//                $withErrs = true;
//            } else {
//                try {
//                    $sStart = Carbon::createFromFormat("Y-m-d H:i:s", $dates[0], "UTC");
//                    $sEnd = Carbon::createFromFormat("Y-m-d H:i:s", $dates[1], "UTC");                
//                } catch (\Exception $ex) {
//                    $errors_bag->add('sTimeRange', "Invalid date format");
//                    $withErrs = true;
//                }                
//            }
//        }
//        $sClientIP = trim($request->get('sClientIP'));
//        if ($sClientIP != "") {
//            $isSearch = true;
//            if (!ip2long($sClientIP)) {
//                $errors_bag->add('sClientIP', "Invalid ip client");
//                $withErrs = true;
//            }
//        }
//        $sName = trim($request->get('sName'));
//        if ($sName != "") {
//            $isSearch = true;
//        }
//        $sResolvedIP = $request->get('sResolvedIP');
//        if ($sResolvedIP != "") {
//            $isSearch = true;
//            if (!ip2long($sResolvedIP)) {
//                $errors_bag->add('sResolvedIP', "Invalid ip resolved");
//                $withErrs = true;
//            }
//        }
//
//        // if has errors
//        if ($withErrs) {
//            return view('dnsresolv.search')->withErrors($errors_bag);
//        }
//        
//        // do search query
//        if ($isSearch) {
//            $query = DnsResolv::query();
//            if ($sStart && $sEnd) {
//                $query = $query->whereBetween("timestamp", [$sStart, $sEnd]);
//            }
//            if ($sClientIP != "") {
//                $query = $query->where("clientip", "=", $sClientIP);
//            }
//            if ($sName != "") {
//                $query = $query->where("name", "like", $sName);
//            }
//            if ($sResolvedIP != "") {
//                $query = $query->where("resolvedips", "=", $sResolvedIP);
//            }            
//            $resolvs = $query->paginate(20);
//            return view('dnsresolv.results', compact('resolvs'));
//        }
//        
//        //return search form
//        return view('dnsresolv.search');
//    }
    
    /**
     * Search 
     *
     * @param \Illuminate\Http\Response $request
     * @param \Illuminate\Support\MessageBag $errors_bag
     * @return \Illuminate\Http\Response
     */    
    public function search(Request $request, MessageBag $errors_bag) {    
        $isSearch = false;
        $withErrs = false;

        // parse input
        $sStart = false;
        $sEnd = false;
        $sTimeRange = trim($request->get('sTimeRange'));
        if ($sTimeRange != "") {
            $isSearch = true;
            $dates = explode(" - ", $sTimeRange);
            if (count($dates) != 2) {
                $errors_bag->add('sTimeRange', "Invalid range");
                $withErrs = true;
            } else {
                try {
                    $sStart = Carbon::createFromFormat("Y-m-d H:i:s", $dates[0], "UTC");
                    $sEnd = Carbon::createFromFormat("Y-m-d H:i:s", $dates[1], "UTC");                
                } catch (\Exception $ex) {
                    $errors_bag->add('sTimeRange', "Invalid date format");
                    $withErrs = true;
                }                
            }
        }
        $sID = trim($request->get('sID'));
        if ($sID != "") {
            $isSearch = true;
        }
        $iCode = 0;
        $sCode = trim($request->get('sCode'));
        if ($sCode != "") {
            $isSearch = true;
            if (!is_numeric($sCode)) {
                $errors_bag->add('sCode', "Invalid code");
                $withErrs = true;
            }
        }
        $sCodename = trim($request->get('sCodename'));
        if ($sCodename != "") {
            $isSearch = true;
        }
        $iLevel = 0;
        $sLevel= trim($request->get('sLevel'));
        if ($sLevel != "") {
            $isSearch = true;
            if (!is_numeric($sLevel)) {
                $errors_bag->add('sLevel', "Invalid level");
                $withErrs = true;
            } else {
                $iLevel = (int)$sLevel;
                if ($iLevel <0 || $iLevel >4) {
                    $errors_bag->add('sLevel', "Invalid level");
                    $withErrs = true;
                }
            }
        }
        $sLevelGE = trim($request->get('sLevelGE'));
        $sSourceHostname = trim($request->get('sSourceHostname'));
        if ($sSourceHostname != "") {
            $isSearch = true;
        }
        $sDescription = trim($request->get('sDescription'));
        if ($sDescription != "") {
            $isSearch = true;
        }
        $sTag = trim($request->get('sTag'));
        if ($sTag != "") {
            $isSearch = true;
        }
        $sDataField = trim($request->get('sDataField'));
        $sDataValue = trim($request->get('sDataValue'));
        if ($sDataField != "" || $sDataValue != "") {
            $isSearch = true;
            if ($sDataField == "" || $sDataValue == "") {
                $errors_bag->add('sDataField', 'Required field and value');
                $withErrs = true;
            }
        }
        
        // if has errors
        if ($withErrs) {
            return view('event.search')->withErrors($errors_bag);
        }

        // do search query
        if ($isSearch) {
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
            if ($sDescription != "") {
                $query = $query->where("description", "like", '%'.$sDescription.'%');
            }
            if ($sTag != "") {
                $query = $query->where("tags", "=", $sTag);
            }
            if ($sDataField != "") {
                $query = $query->where("data.$sDataField", "=", $sDataValue);
            }
            
            $events = $query->paginate(20);
            return view('event.results', compact('events'));
        }

        // returns search form
        return view('event.search');
    }
    
    
}
