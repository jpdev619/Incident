
@component('mail::message')
<b>An Incident Report has been created.</b> 
@component('mail::table',['class'=>'tblappr'])
| <!-- -->           |  <!-- -->     
| :-------------     |:-------------
| Incident Number    | #{{$ir->incident_number}}   
| Incident Title     | {{$ir->incident_title}}    
| Incident Detected  | {{$ir->incident_detect}}    
| Type               | {{$ir->incident_type}}   
| Reported By        | {{$ir->userbasic->user_xfirstname}} {{$ir->userbasic->user_xlastname}} 
@endcomponent


@component('mail::button', ['url' => 'https://neoada.acccorp.com.ph/ir/'.$ir->incident_number])
OPEN IN NEOADA
@endcomponent


@endcomponent
