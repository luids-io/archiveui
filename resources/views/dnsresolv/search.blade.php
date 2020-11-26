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
        <h3 class="card-title"><i class="fas fa-search"></i> Search</h3>
      </div>

        <form role="form" method="GET">
          <div class="card-body">

           @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Bad search</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
           @endif
                 
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Date and time range</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-clock"></i></span>
                </div>
                <input type="text" class="form-control float-right" id="sTimeRange" name="sTimeRange">
              </div>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="sReverseOrder" name="sReverseOrder">
                <label for="sReverseOrder" class="custom-control-label">Reverser order</label>
              </div>
            </div>
          </div>            
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Client IP</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-laptop"></i></span>
                </div>
                <input type="text" class="form-control" id="sClientIP" name="sClientIP">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Server IP</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-laptop"></i></span>
                </div>
                <input type="text" class="form-control" id="sServerIP" name="sServerIP">
              </div>
            </div>
          </div>
        </div>
                      
        <div class="row">
          <div class="col-md-6">   
            <div class="form-group">
              <label>Query name</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-cloud"></i></span>
                </div>
                <input type="text" class="form-control" id="sName" name="sName">
              </div>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="sUnmatchName" name="sUnmatchName">
                <label for="sUnmatchName" class="custom-control-label">Unmatch</label>
              </div>
            </div>
          </div>   
          <div class="col-md-6">
            <div class="form-group">
              <label>Resolved IP</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-server"></i></span>
                </div>
                <input type="text" class="form-control" id="sResolvedIP" name="sResolvedIP">
              </div>
            </div>
            <div class="form-group">
              <label>Resolved CNAME</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-server"></i></span>
                </div>
                <input type="text" class="form-control" id="sResolvedCNAME" name="sResolvedCNAME">
              </div>
            </div>
          </div>
        </div>
           
        <div class="row">
          <div class="col-md-6">   
            <div class="form-group">
              <label>Query ID</label>
              <input type="text" class="form-control" id="sQID" name="sQID">
            </div>
          </div>   
          <div class="col-md-6">
            <div class="form-group">
              <label>Return Code</label>
              <input type="text" class="form-control" id="sReturnCode" name="sReturnCode">
            </div>
          </div>
        </div>
           
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>        
        
    </div>
  </div>
</div>
@stop

@section('js')
<script>
  $(function () {
    //Date range picker with time picker
    $('#sTimeRange').daterangepicker({
      timePicker: true,
      timePicker24Hour: true,
      timePickerSeconds: true,
      timePickerIncrement: 1,
      maxDate: moment(),
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      },
      autoUpdateInput: false
    },
    function(start_date, end_date) {
        $('#sTimeRange').val(start_date.format('YYYY-MM-DD HH:mm:ss')+' - '+end_date.format('YYYY-MM-DD HH:mm:ss'));
      }
    );

    $('#sClientIP').inputmask({ alias: 'ip' });
    $('#sServerIP').inputmask({ alias: 'ip' });
    $('#sResolvedIP').inputmask({ alias: 'ip' });
  });
</script>
@stop