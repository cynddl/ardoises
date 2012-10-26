<?php

HTML::macro('flash', function()
{
    $message_status = Session::get('message_status');
    $message        = Session::get('message');

    return ($message && $message_status) ? '<div class="alert alert-' . $message_status . '"><button type="button" class="close" data-dismiss="alert">×</button>' . $message . '</div>' : '';
});