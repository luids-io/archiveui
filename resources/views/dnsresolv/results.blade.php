@extends('adminlte::page')

@section('title', 'Archive UI')

@section('content_header')
    <h1>DNS Resolvs</h1>
@stop

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-search"></i> Search results: {{ $resolvs->total() }}</h3>
        <div class="card-tools">
          <div class="input-group input-group-sm" style="width: 150px;">
              <a href="{{ route('dnsresolv.search') }}" class="btn btn-default"><i class="fas fa-search"></i> New search</a>
          </div>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
    @if ($resolvs->isEmpty())
    <tr><td><center><strong>No data found</strong></center></td></tr>
    @else
          <thead>
            <tr>
              <th>Timestamp</th>
              <th>Client</th>
              <th>Name</th>
              <th style="width: 10px">AD</th>
              <th style="width: 10px">RCode</th>
              <th>Resolved</th>
              <th style="width: 10px"></th>
            </tr>
          </thead>
          <tbody>

        @foreach($resolvs as $resolv)
            <tr>
                <td>{{$resolv->timestamp}}</td>
                <td>{{$resolv->clientIP}}</td>
                <td>{{$resolv->name}}</td>
                <td>
                    @if ($resolv->getAttribute('responseFlags.authenticatedData'))
                    <span class="badge bg-success">True</span>
                    @else
                    <span class="badge bg-danger">False</span>
                    @endif
                </td>
                <td>{{$resolv->returnCode}}</td>
                <td>
                    @if(isset($resolv->resolvedIPs))
                    @foreach($resolv->resolvedIPs as $resolvedIP)
                        @if (!$loop->first)
                        <br>
                        @endif
                    {{ $resolvedIP }}
                    @endforeach
                    @endif
                </td>
                <td><a href="{{ route('dnsresolv.show', [ 'id' => $resolv->_id ]) }}" class="btn btn-block btn-info btn-sm">View</a></td>
             </tr>
        @endforeach
          </tbody>
    @endif
        </table>
          {{ $resolvs->appends($_GET)->links() }}
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>

@stop
