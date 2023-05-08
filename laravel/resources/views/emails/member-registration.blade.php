<div style='width:600px;border:3px solid #1e3567;margin-top:20px;font-size:15px;padding:25px;border-radius:25px;'>
  <table align='center' >
    <tr>
      <td>
        <div style='margin:0 auto;background:#fff;color:#000; padding:10px;'>
          <center>
            <img style='margin:0 auto;width:500px;' src="{{URL::to('img/logo-h.png')}}" >
          </center>

          <h3>Welcome to {{ config("app.COMPANY_NAME") }}!</h3>

          <p>
            Congratulations {{ trim($FirstName) }}! We are happy to inform you that your application for membership has been accepted.
          </p>
          <p>
             You may now log in to your back office at https://www.sfimc.org/member-login using the account details below :
            <br><br>
            Entry No. : {{ trim($EntryCode) }}
            <br>
            Temporary Password : {{ trim($Password) }}
            <br><br>
            For accounts concerns, please call our customer service at {{ config('app.COMPANY_MOBILE1') }} or email us at {{ config('app.COMPANY_EMAIL') }}
            <br><br>
            Thank you!
          </p>

        </div>

      </td>
    </tr>
  </table>

</div>
