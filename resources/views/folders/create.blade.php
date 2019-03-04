@extends('admin::layouts.standard')

@section('content')
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Create new folder</h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary" role="button">Back</a>
                </div>
            </div>

        </div>

        <create-folder-form></create-folder-form>

    </main>
@endsection