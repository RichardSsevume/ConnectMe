@if($u->verification_status === 'verified')
    <span class="badge-verified"><i class="fa-solid fa-circle-check"></i> Verified</span>
@elseif($u->verification_status === 'rejected')
    <span class="badge text-bg-danger">Rejected</span>
@else
    <span class="badge text-bg-warning">Pending</span>
@endif
