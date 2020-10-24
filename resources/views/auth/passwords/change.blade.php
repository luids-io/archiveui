@extends('adminlte::page')

@section('title', 'Archive UI')

@section('content_header')
    <h1>User Profile</h1>
@stop

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-lock"></i> Change password</h3>
      </div>

      <form action="{{ route('password.change') }}" method="post">
          {{ csrf_field() }}
          <div class="card-body">
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
            @if (session()->has('status'))
            <div class="alert alert-success">{{ session()->get('status') }}</div>
            @endif
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Current Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                  </div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>New Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Repeat New Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" class="form-control" id="new_confirm_password" name="new_confirm_password">
                  </div>
                </div>
              </div>

          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Change</button>
          </div>
        </form>        
        
    </div>
  </div>
</div>
@stop