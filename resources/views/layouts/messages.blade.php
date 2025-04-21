@if($errors->any())
<x-messages.errors :message="Session::get('success')" :errors="$errors" />
@endif
@if(Session::has('success'))
<x-messages.success :message="Session::get('success')" :alertClass="Session::get('alert-class', 'alert-info')" />
@endif
@if(Session::has('info'))
<x-messages.info :message="Session::get('info')" :alertClass="Session::get('alert-class', 'alert-info')" />
@endif