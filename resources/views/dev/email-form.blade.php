@php
$time=\Carbon\Carbon::now()->timestamp;
$publicKey = config('app.api_key');
//this is not a perfect signature, but it will do just fine
$sigBase = $publicKey ."|/api/senator/email|" .  $time;
//This uses the default laravel encryption
$sig =  base64_encode(\Illuminate\Support\Facades\Crypt::encrypt($sigBase));
@endphp
<form action="/api/senator/email" method="POST">
    <fieldset>
        <legend>Email form tester:</legend>

        <label for="sentor_id">Senator ID</label>
        <input type="text" id="senator_id" name="senator_id"><br><br>

        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name"><br><br>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email"><br><br>

        <label for="message">Message:</label>
        <textarea id="message" name="message"></textarea><br><br>

        <label for="signature">Signature(written at time of load for simplicity)</label>
        <input type="text" id="signature" name="signature" value="{{$sig}}"><br><br>

        <input type="hidden" name="t" value="{{$time}}">


        <button type="submit">Send</button>
    </fieldset>
</form>
