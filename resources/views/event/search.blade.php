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
        <h3 class="card-title">
          <i class="fas fa-search"></i>
          Search
        </h3>
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
            
          <div class="col-md-6">
            <div class="form-group">
              <label>ID</label>
              <input type="text" class="form-control" id="sID" name="sID">
            </div>
          </div>
        </div>
           
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Code</label>
              <input type="text" class="form-control" id="sCode" name="sCode">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Codename</label>
              <input type="text" class="form-control" id="sCodename" name="sCodename">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Level</label>
              <div class="input-group">
                <select class="form-control" id="sLevel" name="sLevel">
                  <option value="0">INFO</option>
                  <option value="1">LOW</option>
                  <option value="2">MEDIUM</option>
                  <option value="3">HIGH</option>
                  <option value="4">CRITICAL</option>
                </select>
              </div>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="sLevelGE" name="sLevelGE" checked>
                <label for="sLevelGE" class="custom-control-label">Equal or greather than</label>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Source Hostname</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-desktop"></i></span>
                </div>
                <input type="text" class="form-control" id="sSourceHostname" name="sSourceHostname">
              </div>
              <label>Source Program</label>
              <input type="text" class="form-control" id="sSourceProgram" name="sSourceProgram">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">           
            <div class="form-group">
              <label>Description contains</label>
              <input type="text" class="form-control" id="sDescription" name="sDescription">
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="sUnmatchDescription" name="sUnmatchDescription">
                <label for="sUnmatchDescription" class="custom-control-label">Unmatch</label>
              </div>
            </div>
          </div>
        </div>
           
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Data field and value</label>
              <div class="input-group">
                <input type="text" class="form-control" id="sDataField" name="sDataField">
                <strong> = </strong>
                <input type="text" class="form-control" id="sDataValue" name="sDataValue">
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label>Tag</label>
              <input type="text" class="form-control" id="sTag" name="sTag">
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
    $('#sCode').inputmask({ alias: 'numeric' });
  });
</script>
@stop