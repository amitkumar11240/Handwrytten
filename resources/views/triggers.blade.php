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
                    @include('msg')



                    @foreach($triggers as $trigger)

                    <div class="card">
                        <div class="card-header bg-white">
                            <div class="float-left">
                                <p class="font-weight-bold">{{ $trigger->trigger_name }}</p>
                            </div>
                            <div class="float-right">
                                <div class="d-inline-block">
                                    <form action="{{ route('triggers.destroy', $trigger->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link ml-4 mt-1" id="destroyTrigger" onclick="deleteTrigger()"> <i class="fa fa-trash-o fa-2x text-danger"></i> </button>
                                    </form>
                                </div>
                                <form action="{{ route('triggers.update', $trigger->id) }}" method="POST" enctype="multipart/form-data" class="d-inline-block float-left">
                                    @csrf
                                    @method('PUT')
                                    <div class="mt-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="trigger_status" class="custom-control-input" id="trigger_status" value="{{ $trigger->trigger_status}}" onclick="triggeerStatus()">
                                            <label class="custom-control-label" for="trigger_status" id="triggerStatus">Disabled</label>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group row">
                                        <label for="trigger_card" class="col-sm-3 col-form-label">Card:</label>
                                        <div class="col-sm-9">
                                            @if($trigger->trigger_card == null)
                                            <img src="{{ asset('img/download.png') }}" id="cardImg" alt="Card Image" width="150" height="150" style="object-fit: contain;">
                                            @else
                                            <img src="{{ $trigger->trigger_card }}" alt="Card Image" width="150" height="150" style="object-fit: contain;">
                                            @endif
                                            <div class="text-center ml-5">
                                                <input type="hidden" name="trigger_card" value="" id="cardInputFile">
                                                <input type="hidden" name="old_trigger_card" value="{{ $trigger->trigger_card }}" id="cardInputFile">
                                                {{-- <label for="files" class="text-primary" style="text-decoration:underline;cursor: pointer;">Change</label>
                                                    <input type="file"name="trigger_card" id="files" style="visibility:hidden;">
                                                    <input type="hidden"name="old_trigger_card" value="{{ $trigger->trigger_card}}"> --}}
                                                <button type="button" class="btn btn-link" data-toggle="modal" data-target="#triggerCard">
                                                    Change
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal-dialog modal-dialog-scrollable">
                                                    <div class="modal fade" id="triggerCard" tabindex="-1" aria-labelledby="triggerCardLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="triggerCardLabel">Select Category</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body pb-5">
                                                                    <div class="container pb-5">
                                                                        <select class="custom-select" name="category" id="category_id" onchange="categoryFun()">
                                                                            <option value="" selected>Please Select</option>
                                                                            @foreach($category->categories as $data)
                                                                            <option value="{{ $data->id}}">{{ $data->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div id="overlay">
                                                                            <div class="cv-spinner">
                                                                                <span class="spinner"></span>
                                                                            </div>
                                                                        </div>
                                                                        <div id="cardInfo" class="container-fluid mt-5">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Save Changes</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group row">
                                        <label for="trigger_message" class="col-sm-4 col-form-label">Message:</label>
                                        <div class="col-sm-8 pl-0">
                                            <textarea name="trigger_message" class="form-control" id="trigger_message" cols="24" rows="4" placeholder="Enter a message">{{ old('trigger_message') ?? $trigger->trigger_message }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="trigger_signoff" class="col-sm-4 col-form-label">Sign Off:</label>
                                        <div class="col-sm-8">
                                            <textarea name="trigger_signoff" class="float-right mr-3 form-control" id="trigger_signoff" cols="15" rows="3" placeholder="Enter a message">{{ old('trigger_signoff') ?? $trigger->trigger_signoff }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group row">
                                        <label for="trigger_handwriting_style" class="col-sm-4 col-form-label">Handwriting Style</label>
                                        <div class="col-sm-8">
                                            <select class="custom-select" name="trigger_handwriting_style" id="trigger_handwriting_style">
                                                <option value="">Please Select</option>
                                                @foreach($style->fonts as $data)
                                                <option value="{{  $data->label }}" @if($data->label == $trigger->trigger_handwriting_style) selected @endif>{{ $data->label}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="trigger_insert" class="col-sm-4 col-form-label">Insert:</label>
                                        <div class="col-sm-5">
                                            <select class="custom-select" name="trigger_insert" id="trigger_insert">
                                                <option value="">Please Select</option>
                                                <option value="Sticker" @if($trigger->trigger_insert == 'Sticker') selected @endif>Sticker</option>
                                                @foreach($insertData->inserts as $data)
                                                <option value="{{ $data->name }}" @if($data->name == $trigger->trigger_insert) selected @endif>{{ $data->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="trigger_gift_card" class="col-sm-4 col-form-label">Gift Card:</label>
                                        <div class="col-sm-7">
                                            <select class="custom-select" name="trigger_gift_card" id="trigger_gift_card">
                                                <option value="">Please Select</option>
                                                @foreach($giftCard->gcards as $data)
                                                <option value="{{ $data->name }}" @if($data->name == $trigger->trigger_gift_card) selected @endif>{{ $data->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary float-right" id="updateTriggerID" onclick="updateTrigger()">Update Trigger</button>
                        </div>
                        </form>
                    </div>

                    @endforeach
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('triggers.store') }}" method="POST">
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
                                        <select name="trigger_name" class="custom-select @error('trigger_name') is-invalid @enderror">
                                            <option value="First Order Placed" selected>First Order Placed</option>
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
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                    <button type="submit" class="btn btn-primary" id="submitIcon" onclick="submitTrigger()">Add Trigger</button>
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

<script>
    function triggeerStatus() {
        var newStatus = document.getElementById("trigger_status").value;
        if (newStatus == '1') {
            document.getElementById("trigger_status").value = '0';
            document.getElementById("triggerStatus").innerHTML = "Enabled";
        } else {
            document.getElementById("trigger_status").value = '1';
            document.getElementById("triggerStatus").innerHTML = "Disabled";
        }
    }
</script>

<script>
    function submitTrigger() {
        document.getElementById("submitIcon").innerHTML = "<i class='fa fa-spinner fa-spin'></i> Adding Trigger";
    }
</script>

<script>
    function updateTrigger() {
        document.getElementById("updateTriggerID").innerHTML = "<i class='fa fa-spinner fa-spin'></i> Updating Trigger";
    }
</script>

<script>
    function deleteTrigger() {
        document.getElementById("destroyTrigger").innerHTML = "<i class='fa fa-spinner fa-2x fa-spin text-danger'></i>";
    }
</script>

<script>
    function categoryFun() {
        // var newStatus = document.getElementById("category_id").value;
        var categoryID = $("#category_id :selected").val();
        // alert(categoryID);
        // alert(newStatus);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
       $("#cardInfo").empty();
        $("#overlay").fadeIn(300);
        $.ajax({
            type: 'POST',
            url: "{{ route('ajaxRequest.post') }}",
            data: {
                id: categoryID
            },
            success: function(data) {
                var newres = JSON.parse(data);
                console.log(newres.cards);
                var cardinfo = newres.cards;
                for (i = 0; i < cardinfo.length; i++) {
                    $('#cardInfo').append("<div class='row'><div class='col-md-4'><img src='" + cardinfo[i].cover + "' style='width:120px;height:120px'></div><div class='col-md-7'><div class='custom-control custom-radio'><input type='radio' class='custom-control-input' name='cardlist' value='" + cardinfo[i].cover + "' id='cardLabel" + cardinfo[i].id + "' onclick='cardFormClick()'><label class='custom-control-label' for='cardLabel" + cardinfo[i].id + "'>'" + cardinfo[i].name + "'</label></div></div></div><hr class='my-3'>");
                }
                $("#overlay").fadeOut(300);
            }
        });
    }
</script>

<script>
    function cardFormClick() {
        var Cardvalue = $('input[name=cardlist]:checked').val();
        document.getElementById("cardImg").src = Cardvalue;
        document.getElementById("cardInputFile").value = Cardvalue;
    }
</script>
@endsection