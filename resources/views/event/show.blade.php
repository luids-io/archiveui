@extends('adminlte::page')

@section('title', 'Archive UI')

@section('content_header')
    <h1>Events</h1>
@stop

@section('content')
<div class="row">
  <div class="col-md-12">
@if (isset($event))
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-edit"></i>  Event View</h3>
    </div>
    <div class="card-body">
        
      <div class="row">
        <div class="col-md-6">

          <div class="card card-blue">
            <div class="card-header">
              <h3 class="card-title">General</h3>
            </div>
            <div class="card-body">

             <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                      <label>ID</label>
                      <input type="text" class="form-control" value="{{$event->_id}}" readonly>
                  </div>
                </div>
             </div><!-- /row -->
                 
             <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Created</label>
                        <input type="text" class="form-control" value="{{$event->created}}" readonly>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Received</label>
                        <input type="text" class="form-control" value="{{$event->received}}" readonly>
                    </div>
                </div>
             </div><!-- .row -->      

             <div class="row">
                <div class="col-sm-6">                    
                    <div class="form-group">
                        <label>Type</label>
                        <input type="text" class="form-control" value="{{$event->type_name}}" readonly>
                    </div>
                </div>
                <div class="col-sm-6">                    
                    <div class="form-group">
                        <label>Level</label>
                        <input type="text" class="form-control" value="{{$event->level_name}}" readonly>
                    </div>
                </div>
             </div><!-- .row -->      

             <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" class="form-control" value="{{$event->code}}" readonly>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Codename</label>
                        <input type="text" class="form-control" value="{{$event->codename}}" readonly>
                    </div>
                </div>
             </div><!-- /row -->

             <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Duplicates</label>
                        <input type="text" class="form-control" value="{{$event->duplicates}}" readonly>
                    </div>
                </div>
             </div><!-- /row -->
             
           </div><!-- /card-body -->

          </div><!-- .card -->
                
        </div> <!-- /.col-md-6 -->
             
    <div class="col-md-6">
        
        <div class="card card-green">
           <div class="card-header">
             <h3 class="card-title">Source</h3>
           </div>

           <div class="card-body">
                       
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Hostname</label>
                            <input type="text" class="form-control" value="{{$event->getAttribute('source.hostname')}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Program</label>
                            <input type="text" class="form-control" value="{{$event->getAttribute('source.program')}}" readonly>
                        </div>
                    </div>
                </div><!-- .row -->
                                       
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Instance</label>
                            <input type="text" class="form-control" value="{{$event->getAttribute('source.instance')}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>PID</label>
                            <input type="text" class="form-control" value="{{$event->getAttribute('source.pid')}}" readonly>
                        </div>
                    </div>
                </div><!-- .row -->

           </div><!-- /source card-body -->

          </div><!-- /source card -->

        </div><!-- /col2 .primary-card-body -->
                
      </div><!-- /row1 .primary-card-body -->

      
      <div class="row">
        <div class="col-md-12">

          <div class="card card-cyan">
            <div class="card-header">
              <h3 class="card-title">Content</h3>
            </div>
            <div class="card-body">
                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" value="{{$event->description}}" readonly>
                        </div>
                    </div>
                </div><!-- .row -->

                <div class="row">
                    <div class="col-sm-6">
                        <table class="table table-hover text-nowrap">
                            @if (isset($event->data))
                            <thead>
                              <tr>
                                <th colspan="2">Data</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($event->data as $key => $value)
                                <tr>
                                    <td>{{$key}}</td><td>{{$value}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @else
                            <tr><td><center><strong>No data</strong></center></td></tr>                            
                            @endif
                        </table>    
                    </div>
                    <div class="col-sm-6">
                        <table class="table table-hover text-nowrap">
                            @if (isset($event->tags))
                            <thead>
                              <tr>
                                <th>Tags</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($event->tags as $tag)
                                <tr>
                                    <td>{{$tag}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            @else
                            <tr><td><center><strong>No tags</strong></center></td></tr>                            
                            @endif
                        </table>    
                    </div>
                    
                </div><!-- .row -->
                
                
            </div><!-- /content .card-body -->
          </div><!--/ contet .card -->
        </div><!-- /col1 -->
      </div><!-- row2 .primary-card-body -->
      
      
      
    </div><!-- /.primary-card-body -->

    <div class="card-footer">
      <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
    </div>

  </div><!-- /.primary-card -->
@else
Object doesn't exist
@endif
  </div><!-- /.col-md-12 -->
</div><!-- /.row -->
@stop
