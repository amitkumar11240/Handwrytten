@extends('shopify-app::layouts.default')

@section('content')
<div class="pb-5 mt-1">
    <div class="row m-0 p-0">
        <div class="col-12">
            <div class="card pt-3 pb-5">
                <div class="card-header bg-white">
                    <div class="float-left">
                        <h5>Create a New Trigger</h5>
                         <p>Create a new rule to trigger the sending of Handwytten notes</p>
                    </div>
                    <button type="button" class="btn btn-primary shadow float-right mt-3 mr-5" data-toggle="modal" data-target="#exampleModal">
                        Create Trigger
                    </button>
                </div>
                <div class="card-body">
                    {{-- @foreach($triggers as $trigger) --}}
                    <div class="card">
                        <div class="card-header bg-white">
                            <div class="float-left">
                            <p class="font-weight-bold">First Order Placed</p>
                            </div>
                            <div class="float-right">
                                <form action="" class="d-inline-block">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="trigger_status" class="custom-control-input" id="trigger_status" checked>
                                        <label class="custom-control-label" for="trigger_status">Enabled</label>
                                    </div>
                                </form>
                                <a href="" class="d-inline-block ml-4 mt-1"><i class="fa fa-trash-o fa-2x text-dark" aria-hidden="true"></i></a>

                            </div>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group row">
                                            <label for="trigger_card" class="col-sm-3 col-form-label">Card:</label>
                                            <div class="col-sm-9">
                                            <img src="{{ asset('img/download.png') }}" alt="" >
                                              <div class="text-center ml-4">
                                                <a href="#" style="text-decoration: underline">Change</a>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group row">
                                            <label for="trigger_message" class="col-sm-4 col-form-label">Message:</label>
                                            <div class="col-sm-8 pl-0">
                                              <textarea name="trigger_message" id="trigger_message" cols="24" rows="4" placeholder="Enter a message"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="trigger_signoff" class="col-sm-4 col-form-label">Sign Off:</label>
                                            <div class="col-sm-8">
                                              <textarea name="trigger_signoff" class="float-right mr-3" id="trigger_signoff" cols="15" rows="3" placeholder="Enter a message"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group row">
                                            <label for="trigger_handwriting_style" class="col-sm-4 col-form-label">Handwriting Style</label>
                                            <div class="col-sm-8">
                                                <select class="custom-select" name="trigger_handwriting_style" id="trigger_handwriting_style">
                                                    <option selected>Handwriting Style</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="trigger_insert" class="col-sm-4 col-form-label">Insert:</label>
                                            <div class="col-sm-5">
                                                <select class="custom-select" name="trigger_insert" id="trigger_insert">
                                                    <option selected>Sticker</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="trigger_gift_card" class="col-sm-4 col-form-label">Gift Card:</label>
                                            <div class="col-sm-7">
                                                <select class="custom-select" name="trigger_gift_card" id="trigger_gift_card">
                                                    <option selected>$5 Starbucks</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- @endforeach --}}
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                    <form action="" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add A Trigger</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body p-5">
                            <div class="container" style="padding-bottom: 150px">
                                <select class="custom-select" name="trigger_name">
                                    <option value="First Order Placed" selected>First Order Placed</option>
                                    <option value="New Registration">New Registration</option>
                                    <option value="$ Purchase Threshold (Single Order)">$ Purchase Threshold (Single Order)</option>
                                    <option value="Lifetime # Of Order Purchase Threshold">Lifetime # Of Order Purchase Threshold</option>
                                    <option value="Lifetime $ Purchase Threshold">Lifetime $ Purchase Threshold</option>
                                    <option value="Birthday">Birthday</option>
                                    <option value="Anniversary Of Purchase">Anniversary Of Purchase</option>
                                    <option value="Specific Item Purchased">Specific Item Purchased</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" disabled>Add Trigger</button>
                        </div>
                    </div>
                    </form>
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
            title: 'Triggers',
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);
    </script>
@endsection