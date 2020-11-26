<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\DnsResolv;
use Carbon\Carbon;


class DnsResolvController extends Controller
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
     * Display a listing of last dns resolv entries
     *
     * @return \Illuminate\Http\Response
     */
    public function last()
    {
        $resolvs = DnsResolv::orderBy('timestamp', 'desc')->paginate(20);
        return view('dnsresolv.last', compact('resolvs'));
    }

    /**
     * Display a dns resolv entry
     *
     * @param \Illuminate\Http\Response $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        $id = $request->get('id');
        $resolv = DnsResolv::find($id);
        return view('dnsresolv.show', compact('resolv'));
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
            return view('dnsresolv.search');
        }
        
        // parse input
        $sStart = false;
        $sEnd = false;
        $sTimeRange = trim($request->get('sTimeRange'));
        if ($sTimeRange != "") {
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
        $sClientIP = trim($request->get('sClientIP'));
        if ($sClientIP != "") {
            if (!ip2long($sClientIP)) {
                $errors_bag->add('sClientIP', "Invalid ip client");
            }
        }
        $sServerIP = trim($request->get('sServerIP'));
        if ($sServerIP != "") {
            if (!ip2long($sServerIP)) {
                $errors_bag->add('sServerIP', "Invalid ip server");
            }
        }
        $sName = trim($request->get('sName'));
        $sUnmatchName = trim($request->get('sUnmatchName'));        
        $sResolvedIP = $request->get('sResolvedIP');
        if ($sResolvedIP != "") {
            if (!ip2long($sResolvedIP)) {
                $errors_bag->add('sResolvedIP', "Invalid ip resolved");
            }
        }
        $sResolvedCNAME = trim($request->get('sResolvedCNAME'));
        $iQID = 0;
        $sQID= trim($request->get('sQID'));
        if ($sQID != "") {
            if (!is_numeric($sQID)) {
                $errors_bag->add('sQID', "Invalid Query ID");
            } else {
                $iQID= (int)$sQID;
                if ($iQID <= 0) {
                    $errors_bag->add('sQID', "Invalid QueryID");
                }
            }
        }
        $iReturnCode = 0;
        $sReturnCode= trim($request->get('sReturnCode'));
        if ($sReturnCode != "") {
            if (!is_numeric($sReturnCode)) {
                $errors_bag->add('sReturnCode', "Invalid Return Code");
            } else {
                $iReturnCode = (int)$sReturnCode;
                if ($iReturnCode < 0) {
                    $errors_bag->add('sReturnCode', "Invalid Return Code");
                }
            }
        }
        
        // if has errors
        if ($errors_bag->count() > 0) {
            return view('dnsresolv.search')->withErrors($errors_bag);
        }
        
        // do search query
        $query = DnsResolv::query();
        if ($sStart && $sEnd) {
            $query = $query->whereBetween("timestamp", [$sStart, $sEnd]);
        }
        if ($sClientIP != "") {
            $query = $query->where("clientIP", "=", $sClientIP);
        }
        if ($sServerIP != "") {
            $query = $query->where("serverIP", "=", $sServerIP);
        }
        if ($sName != "") {
            if($sUnmatchName != "") {
                $query = $query->where("name", "not like", $sName);
            } else {
                $query = $query->where("name", "like", $sName);
            }
        }
        if ($sResolvedIP != "") {
            $query = $query->where("resolvedIPs", "=", $sResolvedIP);
        }
        if ($sResolvedCNAME != "") {
            $query = $query->where("resolvedCNAMEs", "=", $sResolvedCNAME);
        }
        if ($sQID != "") {
            $query = $query->where("qid", "=", $iQID);
        }
        if ($sReturnCode != "") {
            $query = $query->where("returnCode", "=", $iReturnCode);
        }
        if ($sReverseOrder != "") {
            $query = $query->orderBy('timestamp', 'desc');
        }
        $resolvs = $query->paginate(20);
        
        return view('dnsresolv.results', compact('resolvs'));
    }
}
