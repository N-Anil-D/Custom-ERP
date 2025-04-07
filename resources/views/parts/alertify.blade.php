@if(Session::has('error'))
<script>

    alertify.set('notifier','position','top-right',10);
    alertify.error("{{ Session::get('error') }}",10);

</script>
@endif


