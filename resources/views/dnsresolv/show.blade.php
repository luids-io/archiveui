@extends('adminlte::page')

@section('title', 'Archive UI')

@section('content_header')
    <h1>DNS Resolvs</h1>
@stop

@section('content')
<div class="row">
  <div class="col-md-12">
@if (isset($resolv))
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit"></i> View DNS Resolv</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="card card-blue">
              <div class="card-header">
                <h3 class="card-title">Query</h3>
              </div>
              <div class="card-body">
                <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Timestamp</label>
                        <input type="text" class="form-control" value="{{$resolv->timestamp}}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Server IP</label>
                        <input type="text" class="form-control" value="{{$resolv->serverIP}}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Client IP</label>
                        <input type="text" class="form-control" value="{{$resolv->clientIP}}" readonly>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>QID</label>
                        <input type="text" class="form-control" value="{{$resolv->qid}}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" value="{{$resolv->name}}" readonly>
                    </div>
                      <div class="form-group">
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="isIPv6" readonly="readonly" onclick="javascript: return false;" @if ($resolv->isIPv6) checked @endif>
                          <label for="isIPv6" class="custom-control-label">IPv6</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="queryDo" readonly="readonly" onclick="javascript: return false;" @if ($resolv->getAttribute('queryFlags.do')) checked @endif>
                          <label for="queryDo" class="custom-control-label">DNSSEC OK</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="queryAD" readonly="readonly" onclick="javascript: return false;" @if ($resolv->getAttribute('queryFlags.authenticatedData')) checked @endif>
                          <label for="queryAD" class="custom-control-label">Authenticated Data</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="queryCD" readonly="readonly" onclick="javascript: return false;" @if ($resolv->getAttribute('queryFlags.checkingDisabled')) checked @endif>
                          <label for="queryCD" class="custom-control-label">Checking Disabled</label>
                        </div>
                      </div>
                </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card card-green">
              <div class="card-header">
                <h3 class="card-title">Response</h3>
              </div>
              <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Duration</label>
                            <input type="text" class="form-control" value="{{$resolv->duration}}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Return Code</label>
                            <input type="text" class="form-control" value="{{$resolv->returnCode}}" readonly>
                        </div>
                          <div class="form-group">
                            <div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" id="responseAD" readonly="readonly" onclick="javascript: return false;" @if ($resolv->getAttribute('responseFlags.authenticatedData')) checked @endif>
                              <label for="responseAD" class="custom-control-label">Authenticated Data</label>
                            </div>
                          </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Resolved IPs</label>
                            <textarea class="form-control" rows="4" readonly>@if (isset($resolv->resolvedIPs))
@foreach ($resolv->resolvedIPs as $resolvedIP)
{{$resolvedIP}}
@endforeach
@endif</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Resolved CNAMEs</label>
                            <textarea class="form-control" rows="2" readonly>@if (isset($resolv->resolvedCNAMEs))
@foreach ($resolv->resolvedCNAMEs as $resolvedCNAME)
{{$resolvedCNAME}}
@endforeach
@endif</textarea>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
        </div>        
    </div>
@else
Object doesn't exist
@endif
  </div>
</div>
@stop
