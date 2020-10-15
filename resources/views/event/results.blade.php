@extends('adminlte::page')

@section('title', 'Archive UI')

@section('content_header')
    <h1>Events</h1>
@stop

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-search"></i> Search results: {{ $events->total() }}</h3>
        <div class="card-tools">
          <div class="input-group input-group-sm" style="width: 150px;">
              <a href="{{ route('event.search') }}" class="btn btn-default"><i class="fas fa-search"></i>New search</a>
          </div>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
    @if ($events->isEmpty())
    <tr><td><center><strong>No data found</strong></center></td></tr>
    @else
    
          <thead>
            <tr>
              <th>Created</th>
              <th>Code</th>
              <th style="width: 10px">Level</th>
              <th>Source</th>
              <th>Description</th>
              <th style="width: 10px"></th>
            </tr>
          </thead>
          <tbody>
        @foreach($events as $event)
            <tr>
                <td>{{$event->created}}</td>
                <td>{{$event->code}}</td>
                <td>
                @switch($event->level)
                    @case(0)
                    <span class="badge bg-info">INFO</span>
                    @break
                    @case(1)
                    <span class="badge bg-yellow">LOW</span>
                    @break
                    @case(2)
                    <span class="badge bg-orange">MEDIUM</span>
                    @break
                    @case(3)
                    <span class="badge bg-red">HIGH</span>
                    @break
                    @case(4)
                    <span class="badge bg-danger">CRITICAL</span>
                    @break
                @endswitch
                </td>
                <td>{{$event->getAttribute('source.hostname')}}</td>
                <td>{{$event->description}}</td>
                <td><a href="{{ route('event.show', [ 'id' => $event->_id ]) }}" class="btn btn-block btn-info btn-sm">View</a></td>
             </tr>
        @endforeach              
          </tbody>
    @endif
        </table>
          {{ $events->appends($_GET)->links() }}
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>

@stop
