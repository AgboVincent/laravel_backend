
@component('mail::message')
# Dear, {{ $data['name'] }}
  
{{ $data['msg'] }}. 
   
@component('mail::button', ['url' => $data['url']])
Continue
@endcomponent
   
Thanks,<br>
 Auto Claims
@endcomponent