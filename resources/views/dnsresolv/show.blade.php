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
        <h3 class="card-title">
          <i class="fas fa-question"></i>
          DNS Query
        </h3>
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
                      <input type="text" class="form-control" value="{{$resolv->serverip}}" readonly>
                  </div>
                  <div class="form-group">
                      <label>Client IP</label>
                      <input type="text" class="form-control" value="{{$resolv->clientip}}" readonly>
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
                        <input class="custom-control-input" type="checkbox" id="checkingDisabled" readonly="readonly" onclick="javascript: return false;" @if ($resolv->checkingdisabled) checked @endif>
                        <label for="checkingDisabled" class="custom-control-label">Checking disabled</label>
                      </div>
                    </div>
              </div>
          </div>

      </div>


    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-reply"></i>
          DNS Response
        </h3>
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
                      <input type="text" class="form-control" value="{{$resolv->returncode}}" readonly>
                  </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="authenticatedData" readonly="readonly" onclick="javascript: return false;" @if ($resolv->authenticateddata) checked @endif>
                        <label for="authenticatedData" class="custom-control-label">Authenticated Data</label>
                      </div>
                    </div>
              </div>
              <div class="col-sm-6">
                  <div class="form-group">
                      <label>Response</label>
                      <textarea class="form-control" rows="4" readonly>@if (isset($resolv->resolvedips))
@foreach ($resolv->resolvedips as $resolvedip)
{{$resolvedip}}
@endforeach
@endif</textarea>
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
