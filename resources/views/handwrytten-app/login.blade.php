@extends('shopify-app::layouts.default')

@section('content')
    <div class="card">
        
        <div class="card-body">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Login to Handwrytten Account</h5>
                                <p>Handwrytten account information</p>
                            </div>
                            <div class="card-body">
                                @include('msg')
                                <form action="{{ route('handwrytten.login') }}" method="POST" class="form-sm">
                                    @csrf
                                    <div class="form-group">
                                        <label for="accountName">Account Name</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="accountName" value="{{ old('name') }}" aria-describedby="accountName" required>
                                    </div>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email address</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" value="{{ old('email') }}" aria-describedby="emailHelp" required>
                                    </div>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
                                    </div>
                            
                                    <button type="submit" class="btn btn-primary" id="submitLoginId" onclick="submitLogin()">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
        var AppBridge = window['app-bridge'];
        var actions = AppBridge.actions;
        var TitleBar = actions.TitleBar;
        var Button = actions.Button;
        var Redirect = actions.Redirect;
        var titleBarOptions = {
            title: 'Configuration',
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);
    </script>

<script>
    function submitLogin(){
        document.getElementById("submitLoginId").innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please wait";
    }
</script>
@endsection