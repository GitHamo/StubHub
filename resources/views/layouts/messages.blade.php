@if($errors->any())
    <div class="p-6">
        <x-messages.errors :message="Session::get('success')" :errors="$errors" />
    </div>
@endif

@if(Session::has('success'))
    <div class="p-6">
        <x-messages.success :message="Session::get('success')" :alertClass="Session::get('alert-class', 'alert-info')" />
    </div>
@endif

@if(Session::has('info'))
    <div class="p-6">
        <x-messages.info :message="Session::get('info')" :alertClass="Session::get('alert-class', 'alert-info')" />
    </div>
@endif
