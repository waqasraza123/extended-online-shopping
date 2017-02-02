<script src="/dist/js/sb-schools-2.js"></script>
@if ($errors->has())

<script>
    //set the image when redirected back with errors
    $('#photo').attr('src',document.getElementById('school_invisible_image').value);

    //open modal when error is made
    //display errors in modal and hid them with animation slide up in 3 sec
    $('div.alert').delay(4000).slideUp(300);
    {{ $errors = null }}
</script>

@endif

@if (session()->has('success'))
<script>
    $('div.alert').delay(4000).slideUp(300);
</script>
@endif
<script>
    if(($('div.alert')).length > 0){
        $('div.alert').delay(4000).slideUp(300);
    }
</script>