
@extends('layouts.tickets')
@section('title', 'FAQ')
@section('content')
    <section id="category-one">
        <div class="category-one">
            <div class="container contact">
                <div class="submit-area">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">




                            <form action="{{route('departments.store')}}" class="defaultForm" method="post">
                            <div class="form-group">
                                <label class="control-label">Department Name:</label>
                                <input type="text" class="form-control" name="name"/>
                            </div>

                            <div class="submit-button">
                                <button type="submit" class="btn btn-default">SUBMIT</button>
                            </div>


                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

