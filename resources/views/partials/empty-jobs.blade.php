<div class="empty-state text-center">
    <img class="empty-img" src="{{ asset('assets/img/no-vacancies.svg') }}" alt="Illustration of an empty job search">
    @if(($variant ?? 'public') === 'dashboard')
        <h3>No Vacancies Available Right Now!</h3>
        <p>There are currently no vacancies matching your search. Don't give up! New opportunities may be posted soon. Please check again later.</p>
    @else
        <h3>No vacancies available at the moment!</h3>
        <p>Don't give up. New opportunities are added regularly. Please check again later.</p>
    @endif
</div>
