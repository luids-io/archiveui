<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\DnsResolv;
use Carbon\Carbon;


class DnsResolvController extends Controller
{
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
        $sClientIP = trim($request->get('sClientIP'));
        if ($sClientIP != "") {
            if (!ip2long($sClientIP)) {
                $errors_bag->add('sClientIP', "Invalid ip client");
            }
        }
        $sName = trim($request->get('sName'));
        $sResolvedIP = $request->get('sResolvedIP');
        if ($sResolvedIP != "") {
            if (!ip2long($sResolvedIP)) {
                $errors_bag->add('sResolvedIP', "Invalid ip resolved");
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
            $query = $query->where("clientip", "=", $sClientIP);
        }
        if ($sName != "") {
            $query = $query->where("name", "like", $sName);
        }
        if ($sResolvedIP != "") {
            $query = $query->where("resolvedips", "=", $sResolvedIP);
        }            
        $resolvs = $query->paginate(20);
        
        return view('dnsresolv.results', compact('resolvs'));
    }
}
