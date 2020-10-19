<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account is not confirmed</title>
</head>
<body>
    <table>
        <tr><td>Dear {{$username}}</td></tr>
        <tr><td>&nbsp;</td></tr>
        
        <tr><td>Thank you for registering on TWT website
        </td></tr>
        <tr><td>Please click on below link to confirm your account:
        </td></tr>

        <tr><td>&nbsp;</td></tr>
        <tr><td><a href="{{url('/user/confirm/'.$code)}}" class="btn btn-info">confirm Account</a> </td></tr>
        <tr><td>&nbsp;</td></tr>
        <td>Wishing you all the best</td>
        <tr><td>&nbsp;</td></tr>
        <td>Yours Sincerely</td>         
        <tr><td>&nbsp;</td></tr>
        <tr><td>Thanks&regards</td></tr>
        <tr><td>TWT website</td></tr>

    </table>
</body>
</html>