  <div style='width:600px;border:3px solid #24c704;margin-top:20px;font-size:15px;padding:25px;border-radius:25px;'>
      <table align='center' >
        <tr>
          <td>
            <div style='margin:0 auto;background:#fff;padding:10px;'>
              <center>
                <img style='margin:0 auto;width:300px;' src="{{URL::to('img/logo-circle.jpg')}}" >
              </center>

              <div style='clear:both;'></div>
              <br>
              <h1 style='text-align:center;'>Password Reset</h1>
              <br>
              <p style='font-family:helvetica;line-height:150%;padding:15px;color:#000; text-align: justify;'>
                Dear <b>{{ $FirstName}}</b>,
                <br><br>
                Due to the encryption method used by our system, we issued a new and temporary password for you. Please change your password after logging in to your back office and delete this email immediately.
                <br><br>
                Please log in to your back office using your new password below :<br><br>
                <span style='color:#24c704'><b>Password: {{ $Password }}</b></span>
                <br><br>
                In any case that you didn't attempt this request, we suggest you to login, change your password and delete this email immediately for security purposes.
                <br><br>
                Thank you,
                <br><br>
                {{ config("app.CompanyName") }} Support
              </p>
            </div>
          </td>
        </tr>
      </table>
    </div>
