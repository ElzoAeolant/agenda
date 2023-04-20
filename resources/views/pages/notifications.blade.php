@extends('layouts.app', ['activePage' => 'notifications', 'titlePage' => __('Notifications')])

@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header card-header-primary">
        <h3 class="card-title">Notifications</h3>
        <p class="card-category">Handcrafted by our friend
          <a target="_blank" href="https://github.com/mouse0270">Robert McIntosh</a>. Please checkout the
          <a href="http://bootstrap-notify.remabledesigns.com/" target="_blank">full documentation.</a>
        </p>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h4 class="card-title">Notifications Style</h4>
            <div class="alert alert-info">
              <span>
              <textarea class="form-control{{ $errors->has('details') ? ' is-invalid' : '' }}" name="details" id="details" placeholder="{{ __('Ingrese el texto') }}" value="" rows="10" />
                      <p style="text-align: center; font-size: 15px;"><img title="TinyMCE Logo" src="//www.tiny.cloud/images/glyph-tinymce@2x.png" alt="TinyMCE Logo" width="110" height="97" />
  </p>
  <h2 style="text-align: center;">Welcome to the TinyMCE Cloud demo!</h2>
  <p>Please try out the features provided in this full featured example</p>
  <h2>Got questions or need help?</h2>
  <ul>
    <li>Our <a class="mceNonEditable" href="//www.tiny.cloud/docs/">documentation</a> is a great resource for learning how to configure TinyMCE.</li>
    <li>Have a specific question? Visit the <a class="mceNonEditable" href="https://community.tiny.cloud/forum/">Community Forum</a>.</li>
    <li>We also offer enterprise grade support as part of <a href="https://www.tiny.cloud/pricing">TinyMCE premium subscriptions</a>.</li>
  </ul>

  <h2>A simple table to play with</h2>
  <table style="text-align: center;border-collapse: collapse; width: 100%;">
    <thead>
      <tr>
        <th>Product</th>
        <th>Cost</th>
        <th>Really?</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>TinyMCE Cloud</td>
        <td>Get started for free</td>
        <td>YES!</td>
      </tr>
      <tr>
        <td>Plupload</td>
        <td>Free</td>
        <td>YES!</td>
      </tr>
    </tbody>
  </table>

  <h2>Found a bug?</h2>
  <p>If you think you have found a bug please create an issue on the <a href="https://github.com/tinymce/tinymce/issues">GitHub repo</a> to report it to the developers.</p>

  <h2>Finally ...</h2>
  <p>Don't forget to check out our other product <a href="http://www.plupload.com" target="_blank">Plupload</a>, your ultimate upload solution featuring HTML5 upload support.</p>
  <p>Thanks for supporting TinyMCE! We hope it helps you and your users create great content.<br>All the best from the TinyMCE team.</p>
</textarea>
              </span>
            </div>
            <div class="alert alert-info">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span>This is a notification with close button.</span>
            </div>
            <div class="alert alert-info alert-with-icon" data-notify="container">
              <i class="material-icons" data-notify="icon">add_alert</i>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span data-notify="message">This is a notification with close button and icon.</span>
            </div>
            <div class="alert alert-info alert-with-icon" data-notify="container">
              <i class="material-icons" data-notify="icon">add_alert</i>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span data-notify="message">

              <textarea class="form-control{{ $errors->has('details') ? ' is-invalid' : '' }}" name="details" id="details" placeholder="{{ __('Ingrese el texto') }}" value="" rows="10" />
                      <p style="text-align: center; font-size: 15px;"><img title="TinyMCE Logo" src="//www.tiny.cloud/images/glyph-tinymce@2x.png" alt="TinyMCE Logo" width="110" height="97" />
  </p>
  <h2 style="text-align: center;">Welcome to the TinyMCE Cloud demo!</h2>
  <p>Please try out the features provided in this full featured example</p>
  <h2>Got questions or need help?</h2>
  <ul>
    <li>Our <a class="mceNonEditable" href="//www.tiny.cloud/docs/">documentation</a> is a great resource for learning how to configure TinyMCE.</li>
    <li>Have a specific question? Visit the <a class="mceNonEditable" href="https://community.tiny.cloud/forum/">Community Forum</a>.</li>
    <li>We also offer enterprise grade support as part of <a href="https://www.tiny.cloud/pricing">TinyMCE premium subscriptions</a>.</li>
  </ul>

  <h2>A simple table to play with</h2>
  <table style="text-align: center;border-collapse: collapse; width: 100%;">
    <thead>
      <tr>
        <th>Product</th>
        <th>Cost</th>
        <th>Really?</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>TinyMCE Cloud</td>
        <td>Get started for free</td>
        <td>YES!</td>
      </tr>
      <tr>
        <td>Plupload</td>
        <td>Free</td>
        <td>YES!</td>
      </tr>
    </tbody>
  </table>

  <h2>Found a bug?</h2>
  <p>If you think you have found a bug please create an issue on the <a href="https://github.com/tinymce/tinymce/issues">GitHub repo</a> to report it to the developers.</p>

  <h2>Finally ...</h2>
  <p>Don't forget to check out our other product <a href="http://www.plupload.com" target="_blank">Plupload</a>, your ultimate upload solution featuring HTML5 upload support.</p>
  <p>Thanks for supporting TinyMCE! We hope it helps you and your users create great content.<br>All the best from the TinyMCE team.</p>
</textarea>


              </span>
            </div>
          </div>
          <div class="col-md-6">
            <h4 class="card-title">Notification states</h4>
            <div class="alert alert-info">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span>
                <b> Info - </b> This is a regular notification made with ".alert-info"</span>
            </div>
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span>
                <b> Success - </b> This is a regular notification made with ".alert-success"</span>
            </div>
            <div class="alert alert-warning">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span>
                <b> Warning - </b> This is a regular notification made with ".alert-warning"</span>
            </div>
            <div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span>
                <b> Danger - </b> This is a regular notification made with ".alert-danger"</span>
            </div>
            <div class="alert alert-primary">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span>
                <b> Primary - </b> This is a regular notification made with ".alert-primary"</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="places-buttons">
          <div class="row">
            <div class="col-md-6 ml-auto mr-auto text-center">
              <h4 class="card-title">
                Notifications Places
                <p class="category">Click to view notifications</p>
              </h4>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-8 col-md-10 ml-auto mr-auto">
              <div class="row">
                <div class="col-md-4">
                  <button class="btn btn-primary btn-block" onclick="md.showNotification('top','left')">Top Left</button>
                </div>
                <div class="col-md-4">
                  <button class="btn btn-primary btn-block" onclick="md.showNotification('top','center')">Top Center</button>
                </div>
                <div class="col-md-4">
                  <button class="btn btn-primary btn-block" onclick="md.showNotification('top','right')">Top Right</button>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-8 col-md-10 ml-auto mr-auto">
              <div class="row">
                <div class="col-md-4">
                  <button class="btn btn-primary btn-block" onclick="md.showNotification('bottom','left')">Bottom Left</button>
                </div>
                <div class="col-md-4">
                  <button class="btn btn-primary btn-block" onclick="md.showNotification('bottom','center')">Bottom Center</button>
                </div>
                <div class="col-md-4">
                  <button class="btn btn-primary btn-block" onclick="md.showNotification('bottom','right')">Bottom Right</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('js')
  <script>
    $(document).ready(function() {

      
      tinymce.init({
        selector: 'textarea',
        height: 500,
        menubar: false,
        readonly : 1,
        plugins: [
        ],
        toolbar: false,
        content_css: [
          '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
          '//www.tiny.cloud/css/codepen.min.css'
        ]
      });

     
    });
  </script>
  @endpush
@endsection
