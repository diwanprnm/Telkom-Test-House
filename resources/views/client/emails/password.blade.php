{{ trans('translate.password_reset') }} <a href="{{ $link = url('client/password/reset', $token).'?email='.urlencode($email) }}"> {{ $link }} </a>
