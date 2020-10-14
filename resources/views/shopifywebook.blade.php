@extends('shopify-app::layouts.default')
@section('content')
@include('msg')
<form action="{{ route('triggers.store') }}" method="POST">
                    @csrf
<select name="trigger_name" class="custom-select @error('trigger_name') is-invalid @enderror">
                                    <option value="orders/create" selected>Create first Oder</option>
                                    <option value="New Registration">New Registration</option>
                                    <option value="$ Purchase Threshold (Single Order)">$ Purchase Threshold (Single Order)</option>
                                    <option value="Lifetime # Of Order Purchase Threshold">Lifetime # Of Order Purchase Threshold</option>
                                    <option value="Lifetime $ Purchase Threshold">Lifetime $ Purchase Threshold</option>
                                    <option value="Birthday">Birthday</option>
                                    <option value="Anniversary Of Purchase">Anniversary Of Purchase</option>
                                    <option value="Specific Item Purchased">Specific Item Purchased</option>
                                </select>
                                @error('trigger_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            
                            <button type="submit" class="btn btn-primary" id="submitIconwebhook" onclick="submitTriggerwebhook()">Add Webhook</button>

                            </form>
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
            title: 'Webhook',
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);
    </script>



    <script>
        function submitTriggerwebhook(){
            document.getElementById("submitIconwebhook").innerHTML = "<i class='fa fa-spinner fa-spin'></i> Adding Webhook";
        }
    </script>

    <script>
        function updateTrigger(){
            document.getElementById("updateTriggerID").innerHTML = "<i class='fa fa-spinner fa-spin'></i> Updating Trigger";
        }
    </script>

    <script>
        function deleteTrigger(){
            document.getElementById("destroyTrigger").innerHTML = "<i class='fa fa-spinner fa-2x fa-spin text-danger'></i>";
        }
    </script>

@endsection