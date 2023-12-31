@extends('layouts.tickets')
@section('title', 'Add new Admin')
@section('content')
    <section id="category-one">
        <div class="category-one">
            <div class="container contact">
                <div class="submit-area">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            @if(count($errors->all()))
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>Alert!</strong> {{ $error }}
                                    </div>
                                @endforeach
                            @endif


                           <form action="{{route('admins.store')}}" method="post"  enctype="multipart/form-data">
                            @csrf
                            <div class="small-border"></div>
                            <h1>Add Admin</h1>
                            <hr>

                            <div class="form-group">
                                <label class="control-label">Name*:</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required/>
                            </div>

                            <div class="form-group">
                                <label class="control-label">User Name*:</label>
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}" required/>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Email*:</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required/>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Password*:</label>
                                <input type="password" class="form-control" name="password"  required/>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Avatar:</label>
                                <div class="custom-file-upload">
                                    <input type="file" id="file" name="file"/>
                                </div>
                            </div>


                            <div class="submit-button">
                                <button type="submit" class="btn btn-default">Submit</button>
                            </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('script')

@stop

